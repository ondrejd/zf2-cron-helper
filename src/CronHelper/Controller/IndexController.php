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
use Zend\Db\Adapter\Adapter as DbAdapter;
use Zend\Mvc\Controller\AbstractActionController;

use CronHelper\Model\JobEntity;
use CronHelper\Model\JobMapper;
use CronHelper\Model\JobTable;
use CronHelper\Service\CronService;

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
	 * @var DbAdapter $dbAdapter
	 */
	private $dbAdapter;

	/**
	 * @var CronService $cronService
	 */
	private $cronService;

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
	 * @return DbAdapter
	 */
	protected function getDbAdapter()
	{
		$serviceLocator = $this->getServiceLocator();

		if ($serviceLocator->has('Zend\Db\Adapter\Adapter')) {
			$this->dbAdapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
		} else {
			$config = $serviceLocator->get('config');

			if (array_key_exists('cron_helper', $config)) {
				$config = $config['cron_helper'];

				if (array_key_exists('db', $config)) {
					$this->dbAdapter = new DbAdapter($config['db']);
				}
			}
		}

		if (!($this->dbAdapter instanceof DbAdapter)) {
			throw new \RuntimeException(
				'Module "cron_helper" has no database adapter. Probably is ' .
				'missing a proper configuration!'
			);
		}

		return $this->dbAdapter;
	}

	/**
	 * @return CronService
	 */
	protected function getCronService()
	{
		if (!($this->cronService instanceof CronService)) {
			$this->cronService = $this->getServiceLocator()->get('CronHelper\Service\CronService');
		}

		return $this->cronService;
	}

	/**
	 * return JobMapper
	 */
	protected function getJobMapper()
	{
		$dbAdapter = $this->getDbAdapter();
		$mapper = new JobMapper($dbAdapter);

		return $mapper;
	}

	/**
	 * @return boolean
	 */
	protected function isConsoleRequest()
	{
		return 	($this->getRequest() instanceof ConsoleRequest);
	}

	/**
	 * @param Console $console
	 * @return string
	 */
	private function printConsoleBanner($console)
	{
		$console->writeLine(\CronHelper\Module::CONSOLE_BANNER, ConsoleColor::BLUE);
		$console->writeLine();
	}

	/**
	 * Main action.
	 *
	 * @return void
	 * @throws \RuntimeException Whenever is action accessed not via console request.
	 */
	public function indexAction()
	{
		if (!$this->isConsoleRequest()) {
			throw new \RuntimeException('You can only use this action from a console!');
		}

		$console = $this->getConsole();
		$this->printConsoleBanner($console);

		$console->writeLine('TODO Finish indexAction!', ConsoleColor::LIGHT_RED);
	}

	/**
	 * Print info about current status.
	 *
	 * @return void
	 * @throws \RuntimeException Whenever is action accessed not via console request.
	 */
	public function infoAction()
	{
		if (!$this->isConsoleRequest()) {
			throw new \RuntimeException('You can only use this action from a console!');
		}

		$console = $this->getConsole();
		$this->printConsoleBanner($console);

		$mapper = $this->getJobMapper();

		try {
			$pendingJobs = $mapper->getPending()->count();
			$runningJobs = $mapper->getRunning()->count();
			$finishedJobs = $mapper->getHistory()->count();

			$console->writeLine(sprintf('Pending jobs: %s', $pendingJobs));
			$console->writeLine(sprintf('Running jobs: %s', $runningJobs));
			$console->writeLine(sprintf('Finished jobs: %s', $finishedJobs));
		} catch (\PDOException $exception) {
			// Note: It's look like that either database adapter is not properly
			// defined or table database doesn't exist.
			$console->writeLine(
				'Something is bad with your database - either database ' .
				'adapter is not properly configured or database table is ' .
				'not created.', ConsoleColor::LIGHT_RED
			);
		}
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

		$dbAdapter = $this->getDbAdapter();
		$console = $this->getConsole();
		$this->printConsoleBanner($console);

		try {
			$table = new JobTable($dbAdapter);
			$table->create();
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

		$dbAdapter = $this->getDbAdapter();
		$console = $this->getConsole();
		$this->printConsoleBanner($console);

		try {
			$table = new JobTable($dbAdapter);
			$table->truncate();
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

		$dbAdapter = $this->getDbAdapter();
		$console = $this->getConsole();
		$this->printConsoleBanner($console);

		try {
			$table = new JobTable($dbAdapter);
			$table->drop();
		} catch (\Exception $exception) {
			$console->writeLine('Dropping database table failed!', ConsoleColor::LIGHT_RED);
			return;
		}

		$console->writeLine('Storage was successfully destroyed!', ConsoleColor::LIGHT_GREEN);
	}
}
