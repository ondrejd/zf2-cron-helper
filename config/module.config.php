<?php
/**
 * zf2-cron-helper
 *
 * @link https://github.com/ondrejd/zf2-cron-helper for the canonical source repository
 * @copyright Copyright (c) 2015 Ondřej Doněk.
 * @license https://www.mozilla.org/MPL/2.0/ Mozilla Public License 2.0
 */

return array(
	'controllers' => array(
		'invokables' => array(
			'CronHelper\Controller\Index' => 'CronHelper\Controller\IndexController',
		),
	),
	'service_manager' => array(
		'factories' => array(),
		'invokables' => array(),
	),
	'console' => array('router' => array('routes' => array(
		'cron_db_create' => array(
			'options' => array(
				'route' => 'db create',
				'defaults' => array(
					'controller' => 'CronHelper\Controller\Index',
					'action' => 'storage_create',
				),
			),
		),
		'cron_db_clear' => array(
			'options' => array(
				'route' => 'db clear',
				'defaults' => array(
					'controller' => 'CronHelper\Controller\Index',
					'action' => 'storage_clear',
				),
			),
		),
		'cron_db_destroy' => array(
			'options' => array(
				'route' => 'db destroy',
				'defaults' => array(
					'controller' => 'CronHelper\Controller\Index',
					'action' => 'storage_destroy',
				),
			),
		),
	))),
/*	'db' => array(
		'driver' => 'Pdo_Sqlite',
		'database' => '/home/ondrejd/.odtimetracker/db.sqlite'
	),
*/
);
