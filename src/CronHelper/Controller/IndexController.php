<?php
/**
 * zf2-cron-helper
 *
 * @link https://github.com/ondrejd/zf2-cron-helper for the canonical source repository
 * @copyright Copyright (c) 2015 Ondřej Doněk.
 * @license https://www.mozilla.org/MPL/2.0/ Mozilla Public License 2.0
 */

namespace CronHelper\Controller;

use Zend\Console\ColorInterface as ConsoleColor;
use Zend\Console\Request as ConsoleRequest;
use Zend\Db\Sql\Ddl;
use Zend\Db\Sql\Ddl\Column;
use Zend\Db\Sql\Ddl\Constraint;
use Zend\Db\Sql\Sql;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * Main CronHelper controller.
 *
 * @package CronHelper
 * @subpackage Controller
 * @author Ondřej Doněk <ondrejd@gmail.com>
 */
class IndexController extends AbstractActionController
{
	/**
	 * @var \Zend\Console\Adapter\AdapterInterface $console
	 */
	private $console;

	/**
	 * @return \Zend\Console\Adapter\AdapterInterface
	 */
	protected function getConsole()
	{
		if (!$this->console instanceof \Zend\Console\Adapter\AdapterInterface) {
			$this->console = $this->getServiceLocator()->get('console');
		}

		return $this->console;
	}

	/**
	 * @return boolean
	 */
	protected function isConsoleRequest()
	{
		return 	($this->getRequest() instanceof ConsoleRequest);
	}

	/**
	 * Create storage.
	 *
	 * @return void
	 * @throws \RuntimeException Whenever is action accessed not via console request.
	 */
	public function storageCreateAction()
	{
        if (!$this->isConsoleRequest()) {
            throw new \RuntimeException('You can only use this action from a console!');
        }

		$console = $this->getConsole();

		try {
			$this->createDatabaseTable();
		} catch (\Exception $exception) {
			$console->writeLine('Creating database table failed!', ConsoleColor::LIGHT_RED);
			return;
		}

		$console->writeLine('Storage was successfully created!', ConsoleColor::LIGHT_GREEN);
	}

	/**
	 * Clear storage.
	 *
	 * @return void
	 * @throws \RuntimeException Whenever is action accessed not via console request.
	 */
	public function storageClearAction()
	{
        if (!$this->isConsoleRequest()) {
            throw new \RuntimeException('You can only use this action from a console!');
        }

		$console = $this->getConsole();

		try {
			$this->truncateDatabaseTable();
		} catch (\Exception $exception) {
			$console->writeLine('Truncating database table failed!', ConsoleColor::LIGHT_RED);
			return;
		}

		$console->writeLine('Storage was successfully cleared!', ConsoleColor::LIGHT_GREEN);
	}

	/**
	 * Destroy storage.
	 *
	 * @return void
	 * @throws \RuntimeException Whenever is action accessed not via console request.
	 */
	public function storageDestroyAction()
	{
        if (!$this->isConsoleRequest()) {
            throw new \RuntimeException('You can only use this action from a console!');
        }

		$console = $this->getConsole();

		try {
			$this->dropDatabaseTable();
		} catch (\Exception $exception) {
			$console->writeLine('Dropping database table failed!', ConsoleColor::LIGHT_RED);
			return;
		}

		$console->writeLine('Storage was successfully destroyed!', ConsoleColor::LIGHT_GREEN);
	}

	/**
	 * Create database table.
	 *
	 * @return void
	 */
	private function createDatabaseTable()
	{
		$adapter = $this->getServiceLocator()->get('dbAdapter');
		$mapper = new \CronHelper\Model\JobMapper($adapter);

		$ddl = new Ddl\CreateTable();
		$ddl->setTable($mapper->getTableName());
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
	private function dropDatabaseTable()
	{
		$adapter = $this->getServiceLocator()->get('dbAdapter');
		$mapper = new \CronHelper\Model\JobMapper($adapter);

		$ddl = new Ddl\DropTable($mapper->getTableName());
		$sql = (new Sql($adapter))->getSqlStringForSqlObject($ddl);

		$adapter->query($sql, $adapter::QUERY_MODE_EXECUTE);
	}

	/**
	 * Truncate database table.
	 *
	 * @return void
	 */
	private function truncateDatabaseTable()
	{
		$adapter = $this->getServiceLocator()->get('dbAdapter');
		$mapper = new \CronHelper\Model\JobMapper($adapter);
		$where = new \Zend\Db\Sql\Where();

		$mapper->deleteByWhere($where);
	}
}