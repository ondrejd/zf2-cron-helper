<?php
/**
 * zf2-cron-helper
 *
 * @link https://github.com/ondrejd/zf2-cron-helper for the canonical source repository
 * @copyright Copyright (c) 2015 Ondřej Doněk.
 * @license https://www.mozilla.org/MPL/2.0/ Mozilla Public License 2.0
 */

namespace CronHelper\Model;

/**
 * Interface for job mapper.
 *
 * @package CronHelper
 * @subpackage Model
 * @author Ondřej Doněk <ondrejd@gmail.com>
 */
interface JobTableInterface
{
	/**
	 * Create database table.
	 *
	 * @return void
	 */
	public function create();

	/**
	 * Drop database table.
	 *
	 * @return void
	 */
	public function drop();

	/**
	 * Truncate database table.
	 *
	 * @return void
	 */
	public function truncate();
}