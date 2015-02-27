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
		$ddl->setTable(self::TABLE_NAME);
		$ddl->addColumn(new Column\Integer('id'));
		$ddl->addColumn(new Column\Varchar('code', 55));
		$ddl->addColumn(new Column\Varchar('status', 55));
		$ddl->addColumn(new Column\Text('error_msg'));
		$ddl->addColumn(new Column\Text('stack_trace'));
		$ddl->addColumn(new Column\Varchar('created', 256));
		$ddl->addColumn(new Column\Varchar('scheduled', 256));
		$ddl->addColumn(new Column\Varchar('executed', 256));
		$ddl->addColumn(new Column\Varchar('finished', 256));
		$ddl->addConstraint(new Constraint\PrimaryKey('id'));

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
