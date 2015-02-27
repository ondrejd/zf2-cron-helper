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
	'console' => array(
		'router' => array(
			'routes' => array(
				'cronhelper_cron' => array(
					'options' => array(
						'route' => 'cron',
						'defaults' => array(
							'controller' => 'CronHelper\Controller\Index',
							'action' => 'index',
						),
					),
				),
				'cronhelper_info' => array(
					'options' => array(
						'route' => '[info|status]',
						'defaults' => array(
							'controller' => 'CronHelper\Controller\Index',
							'action' => 'info',
						),
					),
				),
				'cronhelper_storage_create' => array(
					'options' => array(
						'route' => 'db create',
						'defaults' => array(
							'controller' => 'CronHelper\Controller\Index',
							'action' => 'storage-create',
						),
					),
				),
				'cronhelper_storage_clear' => array(
					'options' => array(
						'route' => 'db clear',
						'defaults' => array(
							'controller' => 'CronHelper\Controller\Index',
							'action' => 'storage-clear',
						),
					),
				),
				'cronhelper_storage_destroy' => array(
					'options' => array(
						'route' => 'db destroy',
						'defaults' => array(
							'controller' => 'CronHelper\Controller\Index',
							'action' => 'storage-destroy',
						),
					),
				),
			),
		),
	),
/*	'db' => array(
		'driver' => 'Pdo_Sqlite',
		'database' => '/home/ondrejd/.odtimetracker/db.sqlite'
	),
*/
);
