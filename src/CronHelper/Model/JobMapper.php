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
use Zend\Db\Adapter\Driver\ResultInterface;
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
class JobMapper
{
	/**
	 * @var string
	 */
	const TABLE_NAME = 'cron_helper_job';

	/**
	 * @var string $tableName
	 */
	protected $tableName;

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
	 * @param string $tableName (Optional.)
	 * @return void
	 */
	public function __construct(AdapterInterface $dbAdapter, $tableName = self::TABLE_NAME)
	{
		$this->dbAdapter = $dbAdapter;
		$this->tableName = $tableName;

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
	 * Fetch all jobs.
	 *
	 * @return HydratingResultSet
	 */
	public function fetchAll()
	{
		return $this->fetchByWhere(new Where);
	}

	/**
	 * Fetch one job by where clause.
	 *
	 * If `$trimResultSetIfOneRow` is TRUE then if resulting resultset
	 * has just one row the `JobEntity` is returned instead.
	 *
	 * If `$trimResultSetIfOneRow` is TRUE and count of results is zero
	 * than NULL is returned.
	 *
	 * @param Where $where
	 * @param boolean $trimResultSetIfOneRow (Optional, default TRUE).
	 * @return HydratingResultSet|JobEntity|null
	 */
	public function fetchByWhere(Where $where, $trimResultSetIfOneRow = true)
	{
		$query = $this->sql->select();
		$query->where($where);

		$entity = new JobEntity();
		$hydrator = new ClassMethods();
		$resultSet = new HydratingResultSet($hydrator, $entity);

		$stmt = $this->sql->prepareStatementForSqlObject($query);

		$results = $stmt->execute();
		$resultSet->initialize($results);
		$resultSet->buffer();

		if ($resultSet->count() == 0 && $trimResultSetIfOneRow === true) {
			return null;
		}
		elseif ($resultSet->count() == 1 && $trimResultSetIfOneRow === true) {
			return $resultSet->current();
		}
		else {
			$resultSet->buffer();
		}

		return $resultSet;
	}

	/**
	 * Fetch one job by its id.
	 *
	 * @param integer $id
	 * @return JobEntity|null
	 */
	public function fetchOneById($id)
	{
		$where = new Where();
		$where->equalTo('id', $id);

		return $this->fetchByWhere($where, true);
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
			$query->set($job->getArrayCopy());
			$query->where(array('id' => $job->getId()));
		}
		else {
			$query = $this->sql->update();
			$query->values($job->getArrayCopy());
		}

		$stmt = $this->sql->prepareStatementForSqlObject($query);
		$stmt->execute();

		if ((int) $job->getId() == 0) {
			$id = $this->dbAdapter->getDriver()->getLastGeneratedValue();
			// Note: When PostgreSQL is used then ID is:
			//$id = $this->dbAdapter->getDriver()->getLastGeneratedValue($this->getTableName() . '_seq');
			$job->setId($id);
		}

		return $job;
	}

	/**
	 * Delete job by its ID.
	 *
	 * @param integer|JobEntity $job
	 * @return ResultInterface
	 */
	public function delete($job)
	{
		$jobId = ($job instanceof JobEntity) ? $job->getId() : $job;
		$where = new Where();
		$where->equalTo('id', $jobId);

		return $this->deleteByWhere($where, 1);
	}

	/**
	 * Delete job or jobs.
	 *
	 * @param Where $where
	 * @param integer|null $limit (Optional.)
	 * @return ResultInterface
	 */
	public function deleteByWhere(Where $where, $limit = null)
	{
		$query = $this->sql->delete();
		$query->where($where);

		if (!is_null($limit) && (int) $limit > 0) {
			$query->limit($limit);
		}

		$stmt = $this->sql->prepareStatementForSqlObject($query);

		return $stmt->execute();
	}
}
