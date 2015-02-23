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
		'db' => array(
			'driver' => 'Pdo_Sqlite',
			'database' => 'test/data/cronhelper.sqlite'
		),
	),
);