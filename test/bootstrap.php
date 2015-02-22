<?php
/**
 * zf2-cron-helper
 *
 * @link https://github.com/ondrejd/zf2-cron-helper for the canonical source repository
 * @copyright Copyright (c) 2015 Ondřej Doněk.
 * @license https://www.mozilla.org/MPL/2.0/ Mozilla Public License 2.0
 */

namespace CronHelperTest;

use CronHelperTest\Model\JobEntity;
use Zend\Loader\AutoloaderFactory;
use Zend\Mvc\Service\ServiceManagerConfig;
use Zend\ServiceManager\ServiceManager;
use Zend\Db\Adapter\Adapter as DbAdapter;

error_reporting(E_ALL | E_STRICT);
chdir(__DIR__);

/**
 * Test bootstrap, for setting up autoloading
 */
class Bootstrap
{
	protected static $databaseAdapter;
	protected static $serviceManager;

	public static function init()
	{
		$zf2ModulePaths = array(dirname(__DIR__));
		if (($path = static::findParentPath('vendor'))) {
			$zf2ModulePaths[] = $path;
		}

		static::initAutoloader();

		// use ModuleManager to load this module and it's dependencies
		$config = array(
			'module_listener_options' => array(
				'module_paths' => $zf2ModulePaths,
			),
			'modules' => array(
				'CronHelper'
			)
		);

		$serviceManager = new ServiceManager(new ServiceManagerConfig());
		$serviceManager->setService('ApplicationConfig', $config);
		$serviceManager->get('ModuleManager')->loadModules();

		static::$serviceManager = $serviceManager;

		static::initDatabaseAdapter();
	}

	public static function chroot()
	{
		$rootPath = dirname(__DIR__);
		chdir($rootPath);
	}

	public static function getServiceManager()
	{
		return static::$serviceManager;
	}

	protected static function initAutoloader()
	{
		$vendorPath = static::findParentPath('vendor');

		if (file_exists($vendorPath . '/autoload.php')) {
			include $vendorPath . '/autoload.php';
		}

		AutoloaderFactory::factory(array(
			'Zend\Loader\StandardAutoloader' => array(
				'autoregister_zf' => false,
				'namespaces' => array(
					__NAMESPACE__ => __DIR__ . '/' . __NAMESPACE__,
				),
			),
		));
	}

	protected static function initDatabaseAdapter()
	{
		/*
		static::$databaseAdapter = new DbAdapter(array(
			'driver' => 'Pdo_Sqlite',
			'database' => '/home/ondrejd/.odtimetracker/db.sqlite'
		));

		\Zend\Db\TableGateway\Feature\GlobalAdapterFeature::setStaticAdapter(static::$databaseAdapter);
		static::$serviceManager->setService('db', static::$databaseAdapter);
		*/
	}

	protected static function findParentPath($path)
	{
		$dir = __DIR__;
		$previousDir = '.';
		while (!is_dir($dir . '/' . $path)) {
			$dir = dirname($dir);
			if ($previousDir === $dir) {
				return false;
			}
			$previousDir = $dir;
		}
		return $dir . '/' . $path;
	}
}

Bootstrap::init();
Bootstrap::chroot();