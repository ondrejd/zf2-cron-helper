<?php
/**
* zf2-cron-helper
*
* @link https://github.com/ondrejd/zf2-cron-helper for the canonical source repository
* @copyright Copyright (c) 2015 Ondřej Doněk.
* @license https://www.mozilla.org/MPL/2.0/ Mozilla Public License 2.0
*/

namespace CronHelperTest\Model;

use PHPUnit_Framework_TestCase;
use CronHelper\Service\CronService;

class CronServiceTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var array $usedConfig
     */
    protected $usedConfig;
    /**
     * @var CronService $cronService
     */
    protected $cronService;

    public function setUp()
    {
        $config = require(TEST_DIR . '/config/cronhelper.config.php');
        $this->usedConfig = $config['cron_helper'];
        $this->cronService = new CronService($this->usedConfig);
    }

    public function testEventManager()
    {
        $this->assertInstanceOf('Zend\EventManager\EventManager', $this->cronService->getEventManager());
    }

    public function testGetDefaultOptions()
    {
        $defaultOptions = $this->cronService->getDefaultOptions();

        $this->assertArrayHasKey('scheduleAhead', $defaultOptions);
        $this->assertArrayHasKey('scheduleLifetime', $defaultOptions);
        $this->assertArrayHasKey('maxRunningTime', $defaultOptions);
        $this->assertArrayHasKey('successLogLifetime', $defaultOptions);
        $this->assertArrayHasKey('failureLogLifetime', $defaultOptions);
        $this->assertArrayHasKey('emitEvents', $defaultOptions);
        $this->assertArrayHasKey('allowJsonApi', $defaultOptions);
        $this->assertArrayHasKey('jsonApiSecurityHash', $defaultOptions);
    }

    public function testGetOptions()
    {
        $options = $this->cronService->getOptions();

        $this->assertArrayHasKey('scheduleAhead', $options);
        $this->assertArrayHasKey('scheduleLifetime', $options);
        $this->assertArrayHasKey('maxRunningTime', $options);
        $this->assertArrayHasKey('successLogLifetime', $options);
        $this->assertArrayHasKey('failureLogLifetime', $options);
        $this->assertArrayHasKey('emitEvents', $options);
        $this->assertArrayHasKey('allowJsonApi', $options);
        $this->assertArrayHasKey('jsonApiSecurityHash', $options);

        $this->assertSame($options['scheduleAhead'], $this->usedConfig['options']['scheduleAhead']);
        $this->assertSame($options['scheduleLifetime'], $this->usedConfig['options']['scheduleLifetime']);
        $this->assertSame($options['maxRunningTime'], $this->usedConfig['options']['maxRunningTime']);
        $this->assertSame($options['successLogLifetime'], $this->usedConfig['options']['successLogLifetime']);
        $this->assertSame($options['failureLogLifetime'], $this->usedConfig['options']['failureLogLifetime']);
        $this->assertSame($options['emitEvents'], $this->usedConfig['options']['emitEvents']);
        $this->assertSame($options['allowJsonApi'], $this->usedConfig['options']['allowJsonApi']);
        $this->assertSame($options['jsonApiSecurityHash'], $this->usedConfig['options']['jsonApiSecurityHash']);

        $this->assertSame($this->cronService->getScheduleAhead(), $this->usedConfig['options']['scheduleAhead']);
        $this->assertSame($this->cronService->getScheduleLifetime(), $this->usedConfig['options']['scheduleLifetime']);
        $this->assertSame($this->cronService->getMaxRunningTime(), $this->usedConfig['options']['maxRunningTime']);
        $this->assertSame($this->cronService->getSuccessLogLifetime(), $this->usedConfig['options']['successLogLifetime']);
        $this->assertSame($this->cronService->getFailureLogLifetime(), $this->usedConfig['options']['failureLogLifetime']);
        $this->assertSame($this->cronService->getEmitEvents(), $this->usedConfig['options']['emitEvents']);
        $this->assertSame($this->cronService->getAllowJsonApi(), $this->usedConfig['options']['allowJsonApi']);
        $this->assertSame($this->cronService->getJsonApiSecurityHash(), $this->usedConfig['options']['jsonApiSecurityHash']);
    }

    public function testSetOptions()
    {
        $this->cronService->setOptions(array()); // default options should be used
        $options = $this->cronService->getOptions();

        $this->assertArrayHasKey('scheduleAhead', $options);
        $this->assertArrayHasKey('scheduleLifetime', $options);
        $this->assertArrayHasKey('maxRunningTime', $options);
        $this->assertArrayHasKey('successLogLifetime', $options);
        $this->assertArrayHasKey('failureLogLifetime', $options);
        $this->assertArrayHasKey('emitEvents', $options);
        $this->assertArrayHasKey('allowJsonApi', $options);
        $this->assertArrayHasKey('jsonApiSecurityHash', $options);

        // see CronService::getDefaultOptions(), ln. ~120
        $this->assertSame($options['scheduleAhead'], 1440);
        $this->assertSame($options['scheduleLifetime'], 15);
        $this->assertSame($options['maxRunningTime'], 0);
        $this->assertSame($options['successLogLifetime'], 1440);
        $this->assertSame($options['failureLogLifetime'], 2880);
        $this->assertSame($options['emitEvents'], false);
        $this->assertSame($options['allowJsonApi'], false);
        $this->assertSame($options['jsonApiSecurityHash'], 'SECURITY_HASH');

        $this->cronService->setOptions(array('options' => array(
            'scheduleAhead' => 2880,
            'scheduleLifetime' => 1440,
            'maxRunningTime' => 0,
            'successLogLifetime' => 2880,
            'failureLogLifetime' => 5760,
            'emitEvents' => true,
            'allowJsonApi' => true,
            'jsonApiSecurityHash' => 'test'
        )));
        $options = $this->cronService->getOptions();

        $this->assertArrayHasKey('scheduleAhead', $options);
        $this->assertArrayHasKey('scheduleLifetime', $options);
        $this->assertArrayHasKey('maxRunningTime', $options);
        $this->assertArrayHasKey('successLogLifetime', $options);
        $this->assertArrayHasKey('failureLogLifetime', $options);
        $this->assertArrayHasKey('emitEvents', $options);
        $this->assertArrayHasKey('allowJsonApi', $options);
        $this->assertArrayHasKey('jsonApiSecurityHash', $options);

        $this->assertSame($options['scheduleAhead'], 2880);
        $this->assertSame($options['scheduleLifetime'], 1440);
        $this->assertSame($options['maxRunningTime'], 0);
        $this->assertSame($options['successLogLifetime'], 2880);
        $this->assertSame($options['failureLogLifetime'], 5760);
        $this->assertSame($options['emitEvents'], true);
        $this->assertSame($options['allowJsonApi'], true);
        $this->assertSame($options['jsonApiSecurityHash'], 'test');
    }

    public function testOptionsSetters()
    {
        $this->assertSame($this->cronService->setScheduleAhead(1000)->getScheduleAhead(), 1000);
        $this->assertSame($this->cronService->setScheduleLifetime(1000)->getScheduleLifetime(), 1000);
        $this->assertSame($this->cronService->setMaxRunningTime(5)->getMaxRunningTime(), 5);
        $this->assertSame($this->cronService->setSuccessLogLifetime(2000)->getSuccessLogLifetime(), 2000);
        $this->assertSame($this->cronService->setFailureLogLifetime(4000)->getFailureLogLifetime(), 4000);
        $this->assertSame($this->cronService->setEmitEvents(false)->getEmitEvents(), false);
        $this->assertSame($this->cronService->setAllowJsonApi(false)->getAllowJsonApi(), false);
        $this->assertSame($this->cronService->setJsonApiSecurityHash('security')->getJsonApiSecurityHash(), 'security');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage `scheduleAhead` expects integer value!
     */
    public function testSetScheduleAhead()
    {
        $this->cronService->setScheduleAhead('bad value');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage `scheduleLifetime` expects integer value!
     */
    public function testSetScheduleLifetime()
    {
        $this->cronService->setScheduleLifetime('bad value');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage `maxRunningTime` expects integer value!
     */
    public function testSetMaxRunningTime()
    {
        $this->cronService->setMaxRunningTime('bad value');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage `successLogLifetime` expects integer value!
     */
    public function testSetSuccessLogLifetime()
    {
        $this->cronService->setSuccessLogLifetime('bad value');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage `failureLogLifetime` expects integer value!
     */
    public function testSetFailureLogLifetime()
    {
        $this->cronService->setFailureLogLifetime('bad value');
    }

    /**
    * @expectedException \InvalidArgumentException
    * @expectedExceptionMessage `emitEvents` expects boolean value!
    */
    public function testSetEmitEvents()
    {
        $this->cronService->setEmitEvents('bad value');
    }

    /**
    * @expectedException \InvalidArgumentException
    * @expectedExceptionMessage `allowJsonApi` expects boolean value!
    */
    public function testSetAllowJsonApi()
    {
        $this->cronService->setAllowJsonApi('bad value');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage `jsonApiSecurityHash` expects string value!
     */
    public function testSetJsonApiSecurityHash()
    {
        $this->cronService->setJsonApiSecurityHash(null);
    }

    public function testGetPending()
    {
        // ...
    }

    public function testResetPending()
    {
        // ...
    }

    public function testRun()
    {
        // ...
    }

    public function testProcess()
    {
        // ...
    }

    public function testSchedule()
    {
        // ...
    }

    public function testCleanup()
    {
        // ...
    }

    public function testRecoverRunning()
    {
        // ...
    }

    public function testRegister()
    {
        // ...
    }
}
