<?php
/**
 * zf2-cron-helper
 *
 * @link https://github.com/ondrejd/zf2-cron-helper for the canonical source repository
 * @copyright Copyright (c) 2015 Ondřej Doněk.
 * @license https://www.mozilla.org/MPL/2.0/ Mozilla Public License 2.0
 */

namespace CronHelper;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\Mvc\ModuleRouteListener;

/**
 * @package CronHelper
 */
class Module implements AutoloaderProviderInterface, ConfigProviderInterface, ServiceProviderInterface
{
	/**
	 * @var string The module's namespace
	 */
	const NS = __NAMESPACE__;

	/**
	 * @var string The module's base path
	 */
	const BASE_PATH = __DIR__;

	/**
	 * Retrieve autoloader configuration for this module
	 *
	 * @see AutoloaderProviderInterface::getAutoloaderConfig()
	 * @return array
	 */
	public function getAutoloaderConfig()
	{
		return array(
			'Zend\Loader\ClassMapAutoloader' => array(
				self::BASE_PATH . '/autoload_classmap.php',
			),
			'Zend\Loader\StandardAutoloader' => array(
				'namespaces' => array(
					self::NS => self::BASE_PATH . '/src/' . self::NS,
				)
			),
		);
	}

	/**
	 * Retrieve configuration for this module
	 *
	 * @see ConfigProviderInterface::getConfig()
	 * @return array
	 */
	public function getConfig()
	{
		return include self::BASE_PATH . '/config/module.config.php';
	}

	/**
	 * Provide console usage messages for console endpoints
	 *
	 * @return array
	 */
	public function getConsoleUsage()
	{
		return array(
			'db create' => 'Create storage for cron-helper',
			'db clear' => 'Clear all data in cron-helper storage',
			'db destroy' => 'Destroy cron-helper storage'
		);
	}

	/**
	 * Retrieve configuration for the service manager
	 *
	 * @see ServiceProviderInterface::getServiceConfig()
	 * @return array
	 */
	public function getServiceConfig()
	{
		return array(
			'service_manager' => array(
				'factories' => array(/*
					'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
					'dbAdapter' => function ($sm) {
						$config = $sm->get('config');
						$config = $config['db'];
						$dbAdapter = new Zend\Db\Adapter\Adapter($config);
						return $dbAdapter;
					},
				*/),
				'invocables' => array(),
			),
		);
	}

	/**
	 * Listen to the application bootstrap event
	 *
	 * @param \Zend\Mvc\MvcEvent $event
	 * @return void
	 */
	public function onBootstrap($event)
	{
		$eventManager = $event->getApplication()->getEventManager();
		$moduleRouteListener = new ModuleRouteListener();
		$moduleRouteListener->attach($eventManager);

		$dbAdapter = $event->getApplication()->getServiceManager()->get('dbAdapter');
		\Zend\Db\TableGateway\Feature\GlobalAdapterFeature::setStaticAdapter($dbAdapter);
	}
}
