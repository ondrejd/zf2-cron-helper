<?php
/**
 * zf2-cron-helper
 *
 * @link https://github.com/ondrejd/zf2-cron-helper for the canonical source repository
 * @copyright Copyright (c) 2015 Ondřej Doněk.
 * @license https://www.mozilla.org/MPL/2.0/ Mozilla Public License 2.0
 */

namespace CronHelperTest\Controller;

use Zend\Test\PHPUnit\Controller\AbstractConsoleControllerTestCase;

class IndexControllerTest extends AbstractConsoleControllerTestCase
{
	public function setUp()
	{
		$this->setApplicationConfig(
			include 'test/config/test.config.php'
		);
		parent::setUp();
	}

	public function testStorageCreateActionCanBeAccessed()
	{
		$this->dispatch('db create');
		$this->assertResponseStatusCode(0);
		$this->assertControllerName('CronHelper\Controller\Index');
		$this->assertControllerClass('IndexController');
		$this->assertMatchedRouteName('cronhelper_storage_create');
	}

	public function testStorageClearActionCanBeAccessed()
	{
		$this->dispatch('db clear');
		$this->assertResponseStatusCode(0);
		$this->assertControllerName('CronHelper\Controller\Index');
		$this->assertControllerClass('IndexController');
		$this->assertMatchedRouteName('cronhelper_storage_clear');
	}

	public function testStorageDestroyActionCanBeAccessed()
	{
		$this->dispatch('db destroy');
		$this->assertResponseStatusCode(0);
		$this->assertControllerName('CronHelper\Controller\Index');
		$this->assertControllerClass('IndexController');
		$this->assertMatchedRouteName('cronhelper_storage_destroy');
	}
}