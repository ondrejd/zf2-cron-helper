<?php
/**
 * zf2-cron-helper
 *
 * @link https://github.com/ondrejd/zf2-cron-helper for the canonical source repository
 * @copyright Copyright (c) 2015 Ondřej Doněk.
 * @license https://www.mozilla.org/MPL/2.0/ Mozilla Public License 2.0
 */

namespace CronHelper\Model;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\Pdo\Result;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;
use Zend\Stdlib\Hydrator\ClassMethods;

/**
 * Mapper to database table with CRON jobs.
 *
 * @package CronHelper
 * @subpackage Model
 * @author Ondřej Doněk <ondrejd@gmail.com>
 */
class JobMapper implements JobMapperInterface
{
	/**
	 * @var string $tableName
	 */
	protected $tableName = JobTable::TABLE_NAME;

	/**
	 * @var AdapterInterface $dbAdapter
	 */
	protected $dbAdapter;

	/**
	 * @var Sql $sql
	 */
	protected $sql;

	/**
	 * Constructor.
	 *
	 * @param AdapterInterface $dbAdapter
	 * @return void
	 */
	public function __construct(AdapterInterface $dbAdapter)
	{
		$this->dbAdapter = $dbAdapter;

		$this->sql = new Sql($dbAdapter);
		$this->sql->setTable($this->tableName);
	}

	/**
	 * Return name of database table.
	 *
	 * @return string
	 */
	public function getTableName()
	{
		return $this->tableName;
	}

	/**
	 * @param Result $result
	 * @return HydratingResultSet
	 */
	private function hydrateResult(Result $result)
	{
		$entity = new JobEntity();
		$hydrator = new ClassMethods();
		$resultSet = new HydratingResultSet($hydrator, $entity);
		$resultSet->initialize($result);
		$resultSet->buffer();

		return $resultSet;
	}

	/**
	 * Find jobs.
	 *
	 * @param Where|string|null $where
	 * @param array $options (Optional.)
	 * @return Result|HydratingResultSet
	 */
	public function fetchByWhere($where = null, array $options = array())
	{
		$select = $this->sql->select();

		if ($where instanceof Where) {
			$select->where($where);
		} elseif (is_string($where) && !empty($where)) {
			$select->where($where);
		}

		// Options: limit
		$limit = array_key_exists('limit', $options) ? (int) $limit : null;

		if (!is_null($limit) && (int) $limit > 0) {
			$select->limit($limit);
		}

		$stmt = $this->sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		// Option: hydrate
		$hydrate = array_key_exists('hydrate', $options) ? (bool) $options['hydrate'] : true;

		if ($hydrate !== true) {
			return $result;
		}

		return $this->hydrateResult($result);
	}

	/**
	 * Find jobs(s) by its/their ID(s).
	 *
	 * @param integer|array $id
	 * @param array $options (Optional.)
	 * @return Result|HydratingResultSet|JobEntity|null
	 */
	public function fetchById($id, array $options = array())
	{
		$select = $this->sql->select();
		$where = new Where();

		if (is_array($id)) {
			if (count($id) == 0) {
				return null;
			}
			$where->in('id', $id);
		} else {
			$where->equalTo('id', $id);
		}

		$select->where($where);

		$stmt = $this->sql->prepareStatementForSqlObject($select);
		$result = $stmt->execute();

		// Options: hydrate
		$hydrate = array_key_exists('hydrate', $options) ? (bool) $options['hydrate'] : true;

		if (is_array($id)) {
			if ($hydrate !== true) {
				return $result;
			}

			return $this->hydrateResult($result);
		}

		if ($result->count() == 0) {
			return null;
		}

		if ($hydrate !== true) {
			return $result->current();
		}

		$hydrator = new ClassMethods();
		$entity = new JobEntity();
		$hydrator->hydrate($result->current(), $entity);

		return $entity;
	}

	/**
	 * Save job.
	 *
	 * @param JobEntity $job
	 * @return JobEntity
	 */
	public function save(JobEntity $job)
	{
		$query = null;

		if ((int) $job->getId() == 0) {
			$query = $this->sql->insert();
			$query->values($job->getArrayCopy());
		} else {
			$query = $this->sql->update();
			$query->set($job->getArrayCopy());
			$query->where(array('id' => $job->getId()));
		}

		$stmt = $this->sql->prepareStatementForSqlObject($query);
		$res = $stmt->execute();

		if ((int) $job->getId() == 0) {
			$job->setId((int) $res->getGeneratedValue());
		}

		return $job;
	}

	/**
	 * Delete jobs.
	 *
	 * @param Where|string|null $where
	 * @param array $options (Optional.)
	 * @return Result
	 */
	public function deleteByWhere($where = null, array $options = array())
	{
		$delete = $this->sql->delete();

		if ($where instanceof Where) {
			$delete->where($where);
		} elseif (is_string($where) && !empty($where)) {
			$delete->where($where);
		}

		$delete->where($where);

		// Options: limit
		$limit = array_key_exists('limit', $options) ? (int) $limit : null;

		if (!is_null($limit) && (int) $limit > 0) {
			$delete->limit($limit);
		}

		$stmt = $this->sql->prepareStatementForSqlObject($delete);

		return $stmt->execute();
	}

	/**
	 * Delete jobs(s) by its/their ID(s).
	 *
	 * @param integer|array $id
	 * @return Result|HydratingResultSet|JobEntity|null
	 */
	public function deleteById($id)
	{
		$where = new Where();

		if (is_array($id)) {
			if (count($id) == 0) {
				return null;
			}
			$where->in('id', $id);
		} else {
			$where->equalTo('id', $id);
		}

		return $this->fetchByWhere($where);
	}

    /**
     * Get pending cron jobs.
     *
	 * @param array $options (Optional.)
     * @return HydratingResultSet
     */
    public function getPending(array $options = array())
	{
		$where = new Where();
		$where->equalTo("{$this->tableName}.status", JobEntity::STATUS_PENDING);

		return $this->fetchByWhere($where, $options);
	}

    /**
     * Get running cron jobs.
     *
	 * @param array $options (Optional.)
     * @return HydratingResultSet
     */
    public function getRunning(array $options = array())
	{
		$where = new Where();
		$where->equalTo("{$this->tableName}.status", JobEntity::STATUS_RUNNING);

		return $this->fetchByWhere($where, $options);
	}

    /**
     * Get completed cron jobs (not pending or runnig).
     *
	 * @param array $options (Optional.)
	 * @return HydratingResultSet
     */
    public function getHistory(array $options = array())
	{
		$where = new Where();
		$where->notIn(
			"{$this->tableName}.status",
			array(JobEntity::STATUS_PENDING, JobEntity::STATUS_RUNNING)
		);

		return $this->fetchByWhere($where, $options);
	}
}
