<?php
/**
 * zf2-cron-helper
 *
 * @link https://github.com/ondrejd/zf2-cron-helper for the canonical source repository
 * @copyright Copyright (c) 2015 Ondřej Doněk.
 * @license https://www.mozilla.org/MPL/2.0/ Mozilla Public License 2.0
 */

namespace CronHelper\Service;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;

/**
 * `CronHelper` service self.
 *
 * @package CronHelper
 * @subpackage Service
 * @author Ondřej Doněk <ondrejd@gmail.com>
 */
class CronService implements CronServiceInterface, ServiceManagerAwareInterface, EventManagerAwareInterface
{
    /**
     * @var array $options
     */
    protected $options;

    /**
    * @var EventManagerInterface $eventManager
    */
    protected $eventManager;

    /**
    * @var ServiceManager $serviceManager
    */
    protected $serviceManager;

    /**
     * Constructor.
     *
     * @param array $options
     * @return void
     */
    public function __construct(array $options = array())
    {
        $this->setOptions($options);
    }

    /**
     * @return EventManagerInterface
     */
    public function getEventManager()
    {
        if (!($this->eventManager instanceof EventManagerInterface)) {
            $this->setEventManager(new EventManager());
        }

        return $this->eventManager;
    }

    /**
     * @param EventManagerInterface $eventManager
     * @return void
     */
    public function getEventManager(EventManagerInterface $eventManager)
    {
        $eventManager->addIdentifiers(array(
            get_called_class()
        ));

        $this->eventManager = $eventManager;
    }

    /**
     * Get service manager.
     *
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    /**
     * Set service manager.
     *
     * @param ServiceManager $serviceManager
     * @return void
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

    /**
     * Get default options.
     *
     * Options array (names of keys correspond with methods of this class
     * so look there for detailed description of a single option):
     * <ul>
     *   <li><code>scheduleAhead</code> <i><u>integer</u></i> time in minutes</li>
     *   <li><code>scheduleLifetime</code> <i><u>integer</u></i> time in minutes</li>
     *   <li><code>maxRunningTime</code> <i><u>integer</u></i> time in minutes</li>
     *   <li><code>successLogLifetime</code> <i><u>integer</u></i> time in minutes</li>
     *   <li><code>failureLogLifetime</code> <i><u>integer</u></i> time in minutes</li>
     *   <li><code>emitEvents</code> <i><u>boolean</u></i> emit process events?</li>
     *   <li><code>allowJsonApi</code> <i><u>boolean</u></i> allow JSON API?</li>
     *   <li><code>jsonApiSecurityHash</code> <i><u>string</u></i> security hash for accessing JSON API</li>
     * </ul>
     *
     * @return array
     */
    public function getDefaultOptions()
    {
        return array(
            'scheduleAhead' => 1440, // one day before
            'scheduleLifetime' => 15,
            'maxRunningTime' => 0,
            'successLogLifetime' => 1440, // one day
            'failureLogLifetime' => 2880, // two days
            'emitEvents' => false,/* @todo Should be TRUE */
            'allowJsonApi' => false,/* @todo Should be TRUE */
            'jsonApiSecurityHash' => 'SECURITY_HASH'
        );
    }

    /**
     * Get options.
     *
     * @return array
     * @see CronService::getDefaultOptions() There is a detailed options array description.
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Set options.
     *
     * @param array $options
     * @return CronServiceInterface
     * @see CronService::getDefaultOptions() There is a detailed options array description.
     */
    public function setOptions(array $options)
    {
        $this->options = array_merge($this->getDefaultOptions(), $options);
    }

    /**
     * Get time in minutes for how long ahead CRON jobs have to be scheduled.
     *
     * @return integer
     */
    public function getScheduleAhead()
    {
        return (int) $this->options['scheduleAhead'];
    }

    /**
     * Set time in minutes for how long ahead CRON jobs have to be scheduled.
     *.
     * @param integer $time
     * @return CronServiceInterface
     */
    public function setScheduleAhead($time)
    {
        if (!is_numeric($time)) {
            throw new \InvalidArgumentException('`scheduleAhead` expects integer value!');
        }

        $this->options['scheduleAhead'] = (int) $time;
        return $this;
    }

    /**
     * Get time in minutes for how long it takes before the scheduled job
     * is considered missed.
     *
     * @return integer
     */
    public function getScheduleLifetime()
    {
        return (int) $this->options['scheduleLifetime'];
    }

    /**
     * Set time in minutes for how long it takes before the scheduled job
     * is considered missed.
     *
     * @param integer $time
     * @return CronServiceInterface
     */
    public function setScheduleLifetime($time)
    {
        if (!is_numeric($time)) {
            throw new \InvalidArgumentException('`scheduleLifetime` expects integer value!');
        }

        $this->options['scheduleLifetime'] = (int) $time;
        return $this;
    }

    /**
     * Get maximal running time (in minutes) for the each CRON job.
     *
     * If 0 than no maximal limit is set or the system is used.
     *
     * @return integer
     */
    public function getMaxRunningTime()
    {
        return (int) $this->options['maxRunningTime'];
    }

    /**
     * Set maximal running time (in minutes) for the each CRON job.
     *
     * If 0 than no maximal limit is set or the system is used.
     *
     * @param integer $time
     * @return CronServiceInterface
     */
    public function setMaxRunningTime($time)
    {
        if (!is_numeric($time)) {
            throw new \InvalidArgumentException('`maxRunningTime` expects integer value!');
        }

        $this->options['maxRunningTime'] = (int) $time;
        return $this;
    }

    /**
     * Get time in minutes for how long to keep records about successfully
     * completed CRON jobs.
     *
     * @return integer
     */
    public function getSuccessLogLifetime()
    {
        return (int) $this->options['sucessLogLifetime'];
    }

    /**
     * Set time in minutes for how long to keep records about successfully
     * completed CRON jobs.
     *
     * @param integer $time
     * @return CronServiceInterface
     */
    public function setSuccessLogLifetime($time)
    {
        if (!is_numeric($time)) {
            throw new \InvalidArgumentException('`successLogLifetime` expects integer value!');
        }

        $this->options['successLogLifetime'] = (int) $time;
        return $this;
    }

    /**
     * Get time in minutes for how long to keep records about failed CRON jobs.
     *
     * @return integer
     */
    public function getFailureLogLifetime()
    {
        return (int) $this->options['failureLogLifetime'];
    }

    /**
     * Set time in minutes for how long to keep records about failed CRON jobs.
     *
     * @param integer $time
     * @return CronServiceInterface
     * @throws \InvalidArgumentException Whenever `$time` is not a numeric value.
     */
    public function setFailureLogLifetime($time)
    {
        if (!is_numeric($time)) {
            throw new \InvalidArgumentException('`failureLogLifetime` expects integer value!');
        }

        $this->options['failureLogLifetime'] = (int) $time;
        return $this;
    }

    /**
     * Get TRUE if events are emitted during job processing.
     *
     * @return boolean
     */
    public function getEmitEvents()
    {
        return (bool) $this->options['emitEvents'];
    }

    /**
     * Set TRUE if events are emitted during job processing.
     *
     * @param boolean $emitEvents
     * @return CronServiceInterface
     * @throws \InvalidArgumentException Whenever `$emitEvents` is not a boolean value.
     */
    public function setEmitEvents($emitEvents)
    {
        if (!is_bool($emitEvents)) {
            throw new \InvalidArgumentException('`emitEvents` expects boolean value!');
        }

        $this->options['emitEvents'] = (bool) $emitEvents;
        return $this;
    }

    /**
     * Returns pending jobs.
     *
     * @return /Traversable
     */
    public function getPending()
    {
        // ...
    }

    /**
     * Reset (clear) all pending jobs.
     *
     * @return CronServiceInterface
     */
    public function resetPending()
    {
        // ...
    }

    /**
     * Main action - run scheduled jobs and prepare next run.
     *
     * @return CronServiceInterface
     */
    public function run()
    {
        // ...

        // Trigger event
        //if ($this->getEmitEvents() === true) {
        //    $this->getEventManager()->trigger('run', null, array(/* ... */));
        //}
    }

    /**
     * Run sheduled CRON jobs.
     *
     * @return CronServiceInterface
     */
    public function process()
    {
        // ...

        // Trigger event
        //if ($this->getEmitEvents() === true) {
        //    $this->getEventManager()->trigger('process', null, array(/* ... */));
        //}
    }

    /**
     * Shedule CRON jobs.
     *
     * Read configuration and insert `CronHelper` database records according to it.
     *
     * @return CronServiceInterface
     */
    public function schedule()
    {
        // ...

        // Trigger event
        //if ($this->getEmitEvents() === true) {
        //    $this->getEventManager()->trigger('schedule', null, array(/* ... */));
        //}
    }

    /**
     * Cleanup `CronHelper` database according to set timeout options.
     *
     * @return CronServiceInterface
     */
    public function cleanup()
    {
        // ...

        // Trigger event
        //if ($this->getEmitEvents() === true) {
        //    $this->getEventManager()->trigger('cleanup', null, array(/* ... */));
        //}
    }

    /**
     * Recover CRON jobs that exceeded `max_execution_time` st in system's `php.ini`.
     *
     * @return CronServiceInterface
     */
    public function recoverRunning()
    {
        // ...
    }

    /**
     * Register CRON job.
     *
     * This method is used for creating CRON jobs directly from application's code.
     *
     * @param string $code
     * @param int|string $frequency
     * @param callback $callback
     * @param array $options (Optional.)
     * @return CronServiceInterface
     */
    public function register($code, $frequency, $callback, array $options = array())
    {
        // ...

        // Trigger event
        //if ($this->getEmitEvents() === true) {
        //    $this->getEventManager()->trigger('register', null, array(/* ... */));
        //}
    }
}
