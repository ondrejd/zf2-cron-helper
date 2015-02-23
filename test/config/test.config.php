<?php
/**
 * zf2-cron-helper
 *
 * @link https://github.com/ondrejd/zf2-cron-helper for the canonical source repository
 * @copyright Copyright (c) 2015 Ondřej Doněk.
 * @license https://www.mozilla.org/MPL/2.0/ Mozilla Public License 2.0
 */

return array(
    'modules' => array(
		'CronHelper'
	),
	'module_listener_options' => array(
		'module_paths' => array(
			'module',
			'vendor',
		),
		'config_glob_paths' => array(
			'config/module.config.php',
			'test/config/cronhelper.config.php',
		),
	),
	'service_manager' => array(
		'factories' => array(
		),
	),
);

