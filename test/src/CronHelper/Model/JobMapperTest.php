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
use Zend\Db\Adapter\Adapter as DbAdapter;
use CronHelper\Model\JobEntity;
use CronHelper\Model\JobMapper;
use CronHelper\Model\JobTable;

class JobMapperTest extends PHPUnit_Framework_TestCase
{
	private $testData = array(
		'code' => 'foo',
		'status' => JobEntity::STATUS_PENDING,
		'error_msg' => '',
		'stack_trace' => '',
		'created' => '2015-02-22 21:47:00',
		'scheduled' => '2015-02-23 00:00:00',
		'executed' => '2015-02-23 00:00:02',
		'finished' => '2015-02-23 00:00:30',
		//duration => 28 sec
	);

	private function getAdapterConfig()
	{
		return array(
			'driver' => 'Pdo_Sqlite',
			'database' => ':memory:',
		);
	}

	public function testConstructor()
	{
		$adapter = new DbAdapter($this->getAdapterConfig());
		$mapper = new JobMapper($adapter);
		$this->assertInstanceOf('CronHelper\Model\JobMapper', $mapper);
		$this->assertSame(JobTable::TABLE_NAME, $mapper->getTableName());
	}

	/**
	 * @depends testConstructor
	 */
	public function testDataOperations()
	{
		$adapter = new DbAdapter($this->getAdapterConfig());
		$table = new JobTable($adapter);
		$mapper = new JobMapper($adapter);

		// 1) We need to create table
		$table->create();

		// 2) Now we have empty table
		$resultSet1 = $mapper->fetchByWhere();
		$this->assertInstanceOf('Zend\Db\ResultSet\HydratingResultSet', $resultSet1);
		$this->assertSame(0, $resultSet1->count());

		$resultSet2 = $mapper->getPending();
		$this->assertInstanceOf('Zend\Db\ResultSet\HydratingResultSet', $resultSet2);
		$this->assertSame(0, $resultSet2->count());

		$resultSet3 = $mapper->getRunning();
		$this->assertInstanceOf('Zend\Db\ResultSet\HydratingResultSet', $resultSet3);
		$this->assertSame(0, $resultSet3->count());

		$resultSet4 = $mapper->getHistory();
		$this->assertInstanceOf('Zend\Db\ResultSet\HydratingResultSet', $resultSet4);
		$this->assertSame(0, $resultSet4->count());

		// 3) Insert new job
		$job = new JobEntity($this->testData);
		$this->assertInstanceOf('CronHelper\Model\JobEntity', $job);
		$newJob = $mapper->save($job);
		$this->assertNotSame(null, $newJob->getId());

		// 4) Now we should have table with one row
		$resultSet5 = $mapper->fetchByWhere();
		$this->assertInstanceOf('Zend\Db\ResultSet\HydratingResultSet', $resultSet5);
		$this->assertSame(1, $resultSet5->count());

		$resultSet6 = $mapper->getPending();
		$this->assertInstanceOf('Zend\Db\ResultSet\HydratingResultSet', $resultSet6);
		$this->assertSame(1, $resultSet6->count());

		$resultSet7 = $mapper->getRunning();
		$this->assertInstanceOf('Zend\Db\ResultSet\HydratingResultSet', $resultSet7);
		$this->assertSame(0, $resultSet7->count());

		$resultSet8 = $mapper->getHistory();
		$this->assertInstanceOf('Zend\Db\ResultSet\HydratingResultSet', $resultSet8);
		$this->assertSame(0, $resultSet8->count());
	}
}