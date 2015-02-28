<?php
/*
 * zf2-cron-helper
 *
 * @link https://github.com/ondrejd/zf2-cron-helper for the canonical source repository
 * @copyright Copyright (c) 2015 Ondřej Doněk.
 * @license https://www.mozilla.org/MPL/2.0/ Mozilla Public License 2.0
 */

defined('CRONHELPER_APPNAME') || define('CRONHELPER_APPNAME', 'CronHelper');
defined('CRONHELPER_VERSION') || define('CRONHELPER_VERSION', '0.1');

/**
 * @var string $basePath
 */
$basePath = dirname(__DIR__);

// Set our application as a current `user agent`
ini_set('user_agent', sprintf('%s %s', CRONHELPER_APPNAME, CRONHELPER_VERSION));

// Load autoloader
if (file_exists("$basePath/vendor/autoload.php")) {
	require_once "$basePath/vendor/autoload.php";
} elseif (file_exists("$basePath/init_autoload.php")) {
	require_once "$basePath/init_autoload.php";
} elseif (\Phar::running()) {
	require_once __DIR__ . '/vendor/autoload.php';
} else {
	echo 'Error: I cannot find the autoloader of the application.' . PHP_EOL;
	echo "Check if $basePath contains a valid ZF2 application." . PHP_EOL;
	exit(2);
}

/**
 * @var array $appConfig
 */
$appConfig = array(
	'modules' => array(
		'CronHelper',
	),
	'module_listener_options' => array(
		'config_glob_paths' => array(
			"$basePath/config/module.config.php",
			"$basePath/config/cronhelper.config.php",
		),
		'module_paths' => array(
			"$basePath",
			"$basePath/vendor",
		),
	),
);

// Start application
Zend\Mvc\Application::init($appConfig)->run();
