# zf2-cron-helper

[![Build Status](https://travis-ci.org/ondrejd/zf2-cron-helper.svg)](https://travis-ci.org/ondrejd/zf2-cron-helper)

Module that simplify dealing with CRON jobs in your PHP projects based on [Zend Framework 2](http://framework.zend.com/).

> This module is heavily inspired by [zf2-cron](https://github.com/heartsentwined/zf2-cron) module. Formely I wanted to use this module instead of creating my own but I dislike using of YAML and dependency on Doctrine - beside it's look like the code is not maintained anymore... But still it was the start point and good learning source.

## Features

1. No other dependencies than [Zend Framework 2](http://framework.zend.com/)
2. Entry-point for all CRON jobs of your application
3. Pre-configured CRON jobs can be modified or triggered outside the regular timeplan
4. Simple registering one-time CRON jobs directly from the code
5. Advanced logging features with optional background - defaultly SQLite database but you can provide your own database adapter
6. `EventManager` is used so you can easilly append new actions
6. All code is well documented and tested

## Installation

__CronHelper__ was created especially for pure [Zend Framework 2](http://framework.zend.com/) applications and description below presumes that you want to add this module into such application.

In short, you need do this:

1. register new dependency for the [Composer](https://getcomposer.org/)
2. add __zf2-cron-helper__ module to modules listed in your `config/application.config.php`
3. create `config/autoload/cronhelper.config.php` configuration file to set up the module
4. create the database table for logging if needed
5. and that's all

Here is detailed description:

### Composer.json file

Below is example `composer.json` file for a simple application:

```json
{
  "name": "My application",
  "description": "Application using zf2-cron-helper module",
  "version": "1.0.0",
  "type": "project",
  "keywords": ["commerce","website"],
  "homepage": "http://my.project.com/",
  "license": "MPL-2.0",
  "authors": [{
    "name": "Ondřej Doněk",
    "email": "ondrejd@gmail.com",
    "homepage": "http://ondrejdonek.blogspot.com/",
    "role": "Developer"
  }],
  "repositories": [
    {
      "type": "composer",
      "url": "https://packages.zendframework.com/"
    }, {
      "type": "git",
      "url": "https://github.com/ondrejd/zf2-cron-helper"
    }
  ],
  "require": {
    "php": ">=5.4",
    "zendframework/zendframework": "2.2.*",
    "ondrejd/zf2-cron-helper": "dev-master"
  }
}
```

### Application config

Find the main configuration file of your application (usually `config/application.config.php`) and modify this file. Here is the very simple version how it can look like:

```php
<?php
return array(
  'modules' => array(
    'Application',
		'CronHelper',
	),
	'module_listener_options' => array(
		'config_glob_paths' => array(
			'config/autoload/{,*.}{global,local}.php',
		),
		'module_paths' => array(
			'.',
			'./vendor',
		),
	),
	'service_manager' => array(
	  'factories' => array(),
	),
);
```

### Service config

Now you need to configure __CronHelper__ self. Firstly copy the pre-prepared configuration file into the `config/autoload` folder:

```sh
cp vendor/ondrejd/zf2-cron-helper/config/cronhelper.config.php.dist config/autoload/cronhelper.config.php
```

And now open it and edit it according to notes there:

```php
<?php
/**
 * zf2-cron-helper
 *
 * @link https://github.com/ondrejd/zf2-cron-helper for the canonical source repository
 * @copyright Copyright (c) 2015 Ondřej Doněk.
 * @license https://www.mozilla.org/MPL/2.0/ Mozilla Public License 2.0
 */

return array(
    // Example configuration for the `zf2-cron-helper` module
    'cron_helper' => array(
        // Main options
        'options' => array(
            // Time in minutes for how long ahead CRON jobs have to be scheduled.
            // This means for how long before the scheduled execution time should
            // be job inserted into the database (e.g. scheduled).
            'scheduleAhead' => 1440, // one day before
            // Time in minutes for how long it takes before the scheduled job
            // is considered missed.
            'scheduleLifetime' => 15,
            // Maximal running time (in minutes) for the each CRON job.
            // If `0` is set than the set (in `php.ini`) `max_execution_time` is used.
            'maxRunningTime' => 0,
            // Time in minutes for how long to keep records about successfully
            // completed CRON jobs.
            'successLogLifetime' => 1440, // one day
            // Time in minutes for how long to keep records about failed CRON jobs.
            'failureLogLifetime' => 2880, // two days
            // If `TRUE` then events are emited during processing CRON jobs.
            // This can be useful if you need to perform other actions related
            // to executed CRON jobs.
            'emitEvents' => false,
            // If `TRUE` then you can access info about current status by simple
            // JSON API.
            // This can be useful when you want to provide some sort of UI
            // to watch or manage CRON jobs.
            'allowJsonApi' => false,
            // If JSON API is allowed the security hash MUST BE SET to achive
            // the full functionality. Otherwise will be available only status
            // informations but all managment functions will be disabled.
            'jsonApiSecurityHash' => 'YOUR_SECURITY_HASH',
        ),
        // Optionaly you can define CronHelper own database adapter.
        // If you omit to do that adapter will be searched using
        // ServiceManager by commonly used alias "dbAdapter".
        //'db' => array(
        //	'driver' => 'Pdo_Sqlite',
        //	'database' => 'cronhelper.sqlite'
        //),
        // Here are defined CRON jobs of our application. Keys of these jobs
        // can be used for triggering them directly from the application
        // beside the scheduled timeplan.
        'jobs' => array(
            // The first example job
            'job1' => array(
                // Name/identifier of the job. Can be omitted if is same
                // as the key of its array.
                'code' => 'job1',
                // Frequency of executing. If is omitted than the job will be
                // executed only on demand.
                'frequency' => '0 20 * * *',
                // `RouteTask` defines task that is using existing application's
                // route as a target job's action.
                'task' => array(
                    'type' => 'CronHelper\Service\JobTask\RouteTask',
                    'options' => array(
                        'routeName' => 'cron_job1',
                    ),
                ),
                // Optional callback arguments.
                'args' => array(
                    'name' => 'value'
                ),
            ),
            // The second example job
            'job2' => array(
                'frequency' => '0 0 1 * *',
                // `CallbackTask` is task that executes regular PHP code.
                'task' => array(
                    'type' => 'CronHelper\Service\JobTask\CallbackTask',
                    'options' => array(
                        'className' => 'YourClass',
                        'methodName' => 'doAction',
                    ),
                ),
            ),
            // The third example job - this job has no frequency defined
            // so can be executed only on direct demand from the code.
            'job3' => array(
                // `ExternalTask` is used for executing external scripts.
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
```

At this moment is the most important to configure database driver properly.

### Prepare database

If we have correctly set database adapter we can create table for the logging - open console in your application's folder and execute `create storage` command:

```sh
php public/index.php db create
```

You should got message _Storage was successfully created!_.

### Crontab

The final step is obvious - we need to add our service to your `crontab` file in order to get all this working.

__TODO ... Finish this (example)! ...__

At this point is installation process over and you can start using __CronHelper__ service.

## Using

__CronHelper__ commands are accessible __only__ via CLI interface (even if your application doesn't support it yet).

Here is list of available commands:

- main command (used from `crontab`): `cron`
- logging database-related commands: `db create`, `db clear`, `db destroy`

__TODO ... Finish this! ...__

## Developers

Here are few notes for developers:

1. _CI_ service is running on [Travis CI](https://travis-ci.org/ondrejd/zf2-cron-helper) and the tests are running on __PHP 5.4__ as well as on __PHP 5.5__
2. Other tools used for development: [Composer](https://getcomposer.org/), [phpDocumentor](http://www.phpdoc.org/), [PHPUnit](https://phpunit.de/),  [NetBeans IDE](https://netbeans.org/)

### Running tests

```sh
phpunit -c test/phpunit.xml
```

Test code-coverage reports can be found in folder `test/log/report`.

### Generating documentation

```sh
phpdoc run -d "src" -t "docs/generated" --title "CronHelper Module" --defaultpackagename "CronHelper" -q
```
