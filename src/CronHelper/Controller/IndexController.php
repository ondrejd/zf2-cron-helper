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
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Console\Request as ConsoleRequest;

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
	 * @var Zend\Console\Adapter\AdapterInterface $console
	 */
	private $console;

	/**
	 * @return Zend\Console\Adapter\AdapterInterface
	 */
	protected function getConsole()
	{
		if (!$this->console instanceof Zend\Console\Adapter\AdapterInterface) {
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
		$console->writeLine('TODO Create storage', ConsoleColor::LIGHT_RED);

		// ...
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
		$console->writeLine('TODO Clear storage', ConsoleColor::LIGHT_RED);

		// ...
	}

	/**
	 * Destroy storage.
	 * @throws \RuntimeException Whenever is action accessed not via console request.
	 *
	 * @return void
	 */
	public function storageDestroyAction()
	{
        if (!$this->isConsoleRequest()) {
            throw new \RuntimeException('You can only use this action from a console!');
        }

		$console = $this->getConsole();
		$console->writeLine('TODO Destroy storage', ConsoleColor::LIGHT_RED);

		// ...
	}
}
