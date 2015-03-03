<?php
/**
 * zf2-cron-helper
 *
 * @link https://github.com/ondrejd/zf2-cron-helper for the canonical source repository
 * @copyright Copyright (c) 2015 Ondřej Doněk.
 * @license https://www.mozilla.org/MPL/2.0/ Mozilla Public License 2.0
 */

return array(
	'cron_helper' => array(
		'options' => array(
            'scheduleAhead' => 1440,
            'scheduleLifetime' => 15,
            'maxRunningTime' => 0,
            'successLogLifetime' => 1440,
            'failureLogLifetime' => 2880,
            'emitEvents' => false,
            'allowJsonApi' => false,
            'jsonApiSecurityHash' => 'YOUR_SECURITY_HASH',
        ),
		'db' => array(
			'driver' => 'Pdo_Sqlite',
			'database' => ':memory:',
		),
        'jobs' => array(
            'job1' => array(
                'code' => 'job1',
                'frequency' => '0 20 * * *',
                'task' => array(
                    'type' => 'CronHelper\Service\JobTask\RouteTask',
                    'options' => array(
                        'routeName' => 'cron_job1',
                    ),
                ),
                'args' => array(
                    'name' => 'value'
                ),
            ),
            'job2' => array(
                'frequency' => '0 0 1 * *',
                'task' => array(
                    'type' => 'CronHelper\Service\JobTask\CallbackTask',
                    'options' => array(
                        'className' => 'YourClass',
                        'methodName' => 'doAction',
                    ),
                ),
            ),
            'job3' => array(
                'task' => array(
                    'type' => 'CronHelper\Service\JobTask\ExternalTask',
                    'options' => array(
                        'command' => '/var/www/renbo/bin/export_dump.sh'
                    ),
                ),
            ),
        ),
	),
);
