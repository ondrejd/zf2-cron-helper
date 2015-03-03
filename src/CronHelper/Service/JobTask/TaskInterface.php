<?php
/**
 * zf2-cron-helper
 *
 * @link https://github.com/ondrejd/zf2-cron-helper for the canonical source repository
 * @copyright Copyright (c) 2015 Ondřej Doněk.
 * @license https://www.mozilla.org/MPL/2.0/ Mozilla Public License 2.0
 */

namespace CronHelper\Service\JobTask;

/**
 * Interface for classes describing CRON job task.
 *
 * @package CronHelper
 * @subpackage Service
 * @author Ondřej Doněk <ondrejd@gmail.com>
 */
interface TaskInterface
{
    /**
     * Execute task.
     *
     * @return TaskResultInterface
     */
    public function execute();

    /**
     * Get task options.
     *
     * @return array
     */
    public function getOptions();

    /**
     * Set task options.
     *
     * @param array $options
     * @return void
     */
    public function setOptions(array $options = array());
}
