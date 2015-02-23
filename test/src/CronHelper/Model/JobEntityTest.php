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
use CronHelper\Model\JobEntity;

class JobEntityTest extends PHPUnit_Framework_TestCase
{
	private $data = array(
		'id' => 1,
		'code' => 'foo',
		'status' => JobEntity::STATUS_SUCCESS,
		'error_msg' => null,
		'stack_trace' => null,
		'created' => '2015-02-22 21:47:00',
		'scheduled' => '2015-02-23 00:00:00',
		'executed' => '2015-02-23 00:00:02',
		'finished' => '2015-02-23 00:00:30',
		//duration => 28 sec
	);

	public function testConstructWithNulls()
	{
		$entity = new JobEntity();
		$this->assertNull($entity->getId(), '"id" should initially be null');
		$this->assertNull($entity->getCode(), '"code" should initially be null');
		$this->assertNull($entity->getStatus(), '"status" should initially be null');
		$this->assertNull($entity->getErrorMsg(), '"error_msg" should initially be null');
		$this->assertNull($entity->getStackTrace(), '"stack_trace" should initially be null');
		$this->assertNull($entity->getCreated(), '"created" should initially be null');
		$this->assertNull($entity->getScheduled(), '"scheduled" should initially be null');
		$this->assertNull($entity->getExecuted(), '"executed" should initially be null');
		$this->assertNull($entity->getFinished(), '"finished" should initially be null');
		$this->assertSame($entity->getDuration(), 0, 'duration should be 0 ms');
	}

	public function testConstructWithValues()
	{
		$entity = new JobEntity($this->data);
		$this->assertSame($this->data['id'], $entity->getId(), '"id" was not set correctly');
		$this->assertSame($this->data['code'], $entity->getCode(), '"code" was not set correctly');
		$this->assertSame($this->data['status'], $entity->getStatus(), '"status" was not set correctly');
		$this->assertSame($this->data['error_msg'], $entity->getErrorMsg(), '"error_msg" was not set correctly');
		$this->assertSame($this->data['stack_trace'], $entity->getStackTrace(), '"stack_trace" was not set correctly');
		$this->assertSame($this->data['created'], $entity->getCreated(), '"created" was not set correctly');
		$this->assertSame($this->data['scheduled'], $entity->getScheduled(), '"scheduled" was not set correctly');
		$this->assertSame($this->data['executed'], $entity->getExecuted(), '"executed" was not set correctly');
		$this->assertSame($this->data['finished'], $entity->getFinished(), '"finished" was not set correctly');
		$this->assertSame($entity->getDuration(), 28, 'duration should be 28 ms');
	}

	public function testExchangeArrayWithNulls()
	{
		$entity = new JobEntity();
		$entity->exchangeArray($this->data);
		$entity->exchangeArray(array());
		$this->assertNull($entity->getId(), '"id" should be null');
		$this->assertNull($entity->getCode(), '"code" should be null');
		$this->assertNull($entity->getStatus(), '"status" should be null');
		$this->assertNull($entity->getErrorMsg(), '"error_msg" should be null');
		$this->assertNull($entity->getStackTrace(), '"stack_trace" should be null');
		$this->assertNull($entity->getCreated(), '"created" should be null');
		$this->assertNull($entity->getScheduled(), '"scheduled" should be null');
		$this->assertNull($entity->getExecuted(), '"executed" should be null');
		$this->assertNull($entity->getFinished(), '"finished" should be null');
		$this->assertSame($entity->getDuration(), 0, 'duration should be 0 ms');
	}

	public function testExchangeArrayWithValues()
	{
		$entity = new JobEntity();
		$entity->exchangeArray($this->data);
		$this->assertSame($this->data['id'], $entity->getId(), '"id" was not set correctly');
		$this->assertSame($this->data['code'], $entity->getCode(), '"code" was not set correctly');
		$this->assertSame($this->data['status'], $entity->getStatus(), '"status" was not set correctly');
		$this->assertSame($this->data['error_msg'], $entity->getErrorMsg(), '"error_msg" was not set correctly');
		$this->assertSame($this->data['stack_trace'], $entity->getStackTrace(), '"stack_trace" was not set correctly');
		$this->assertSame($this->data['created'], $entity->getCreated(), '"created" was not set correctly');
		$this->assertSame($this->data['scheduled'], $entity->getScheduled(), '"scheduled" was not set correctly');
		$this->assertSame($this->data['executed'], $entity->getExecuted(), '"executed" was not set correctly');
		$this->assertSame($this->data['finished'], $entity->getFinished(), '"finished" was not set correctly');
		$this->assertSame($entity->getDuration(), 28, 'duration should be 28 ms');
	}

	public function testGetArrayCopyWithNulls()
	{
		$entity = new JobEntity();
		$copy = $entity->getArrayCopy();
		$this->assertNull($copy['id'], '"id" should initially be null');
		$this->assertNull($copy['code'], '"category_id" should initially be null');
		$this->assertNull($copy['status'], '"status" should initially be null');
		$this->assertNull($copy['error_msg'], '"error_msg" should initially be null');
		$this->assertNull($copy['stack_trace'], '"stack_trace" should initially be null');
		$this->assertNull($copy['created'], '"created" should initially be null');
		$this->assertNull($copy['scheduled'], '"scheduled" should initially be null');
		$this->assertNull($copy['executed'], '"executed" should initially be null');
		$this->assertNull($copy['finished'], '"finished" should initially be null');
	}

	public function testGetArrayCopyWithValues()
	{
		$entity = new JobEntity($this->data);
		$copy = $entity->getArrayCopy();
		$this->assertSame($this->data['id'], $copy['id'], '"id" was not set correctly');
		$this->assertSame($this->data['code'], $copy['code'], '"code" was not set correctly');
		$this->assertSame($this->data['status'], $copy['status'], '"status" was not set correctly');
		$this->assertSame($this->data['error_msg'], $copy['error_msg'], '"error_msg" was not set correctly');
		$this->assertSame($this->data['stack_trace'], $copy['stack_trace'], '"stack_trace" was not set correctly');
		$this->assertSame($this->data['created'], $copy['created'], '"created" was not set correctly');
		$this->assertSame($this->data['scheduled'], $copy['scheduled'], '"scheduled" was not set correctly');
		$this->assertSame($this->data['executed'], $copy['executed'], '"executed" was not set correctly');
		$this->assertSame($this->data['finished'], $copy['finished'], '"finished" was not set correctly');
	}

	public function testBadStatus()
	{
		try {
			$entity = new JobEntity(array('status' => 'BAD STATUS'));
			$this->fail('Expected exception was not thrown!');
		} catch (\InvalidArgumentException $e) {
			$this->assertEquals('Bad status given!', $e->getMessage());
		}
	}
}
