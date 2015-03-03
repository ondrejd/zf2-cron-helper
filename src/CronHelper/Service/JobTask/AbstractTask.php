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
 * Simple abstract class for classes describing CRON job task.
 *
 * @package CronHelper
 * @subpackage Service
 * @author Ondřej Doněk <ondrejd@gmail.com>
 */
abstract class AbstractTask implements TaskInterface
{
    /**
     * @var array $options
     */
    protected $options = array();

    /**
     * Constructor.
     *
     * @param array $options (Optional.)
     * @return void
     */
    public function __construct(array $options = array())
    {
        $this->setOptions($options);
    }

    /**
     * Get task options.
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->getOptions();
    }

    /**
     * Set task options.
     *
     * @param array $options
     * @return void
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
    }

    /**
     * Execute task.
     *
     * @return TaskResultInterface
     */
    abstract public function execute();
}
