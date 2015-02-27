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
use Zend\Mvc\Controller\AbstractActionController;

use CronHelper\Model\JobEntity;
use CronHelper\Model\JobMapper;
use CronHelper\Model\JobTable;

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
		$console->writeLine('TODO ... !', ConsoleColor::LIGHT_RED);
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
		$adapter = $this->getServiceLocator()->get('dbAdapter');

		try {
			$table = new JobTable($adapter);
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

		$console = $this->getConsole();
		$adapter = $this->getServiceLocator()->get('dbAdapter');

		try {
			$table = new JobTable($adapter);
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

		$console = $this->getConsole();
		$adapter = $this->getServiceLocator()->get('dbAdapter');

		try {
			$table = new JobTable($adapter);
			$table->drop();
		} catch (\Exception $exception) {
			$console->writeLine('Dropping database table failed!', ConsoleColor::LIGHT_RED);
			return;
		}

		$console->writeLine('Storage was successfully destroyed!', ConsoleColor::LIGHT_GREEN);
	}
}