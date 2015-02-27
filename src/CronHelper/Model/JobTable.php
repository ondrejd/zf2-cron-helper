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
use Zend\Db\Sql\Ddl;
use Zend\Db\Sql\Ddl\Column;
use Zend\Db\Sql\Ddl\Constraint;
use Zend\Db\Sql\Sql;

/**
 * Class for creating/dropping/truncating job table.
 *
 * @package CronHelper
 * @subpackage Model
 * @author Ondřej Doněk <ondrejd@gmail.com>
 */
class JobTable implements JobTableInterface
{
	/**
	 * @var string
	 */
	const TABLE_NAME = 'cron_helper_job';

	/**
	 * @var AdapterInterface $dbAdapter
	 */
	protected $dbAdapter;

	/**
	 * Constructor.
	 *
	 * @param AdapterInterface $dbAdapter
	 * @param string $tableName (Optional.)
	 * @return void
	 */
	public function __construct(AdapterInterface $dbAdapter)
	{
		$this->dbAdapter = $dbAdapter;
	}

	/**
	 * Create database table.
	 *
	 * @return void
	 */
	public function create()
	{
		$adapter = $this->dbAdapter;

		$ddl = new Ddl\CreateTable();
		$ddl->setTable(self::TABLE_NAME)
			->addColumn(new Column\Integer('id', false, null, array('autoincrement' => true)))
			->addColumn(new Column\Varchar('code', 55))
			->addColumn(new Column\Varchar('status', 55))
			->addColumn(new Column\Text('error_msg'))
			->addColumn(new Column\Text('stack_trace'))
			->addColumn(new Column\Varchar('created', 255))
			->addColumn(new Column\Varchar('scheduled', 255))
			->addColumn(new Column\Varchar('executed', 255))
			->addColumn(new Column\Varchar('finished', 255))
			->addConstraint(new Constraint\PrimaryKey('id'));

		$sql = (new Sql($adapter))->getSqlStringForSqlObject($ddl);

		$adapter->query($sql, $adapter::QUERY_MODE_EXECUTE);
	}

	/**
	 * Drop database table.
	 *
	 * @return void
	 */
	public function drop()
	{
		$adapter = $this->dbAdapter;

		$ddl = new Ddl\DropTable(self::TABLE_NAME);
		$sql = (new Sql($adapter))->getSqlStringForSqlObject($ddl);

		$adapter->query($sql, $adapter::QUERY_MODE_EXECUTE);
	}

	/**
	 * Truncate database table.
	 *
	 * @return void
	 */
	public function truncate()
	{
		$adapter = $this->dbAdapter;

		$mapper = new \CronHelper\Model\JobMapper($adapter);
		$where = new \Zend\Db\Sql\Where();

		$mapper->deleteByWhere($where);
	}
}
