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

class JobMapperTest extends PHPUnit_Framework_TestCase
{
	private $data = array(
		'id' => 1,
		'code' => 'foo',
		'status' => \CronHelper\Model\JobEntity::STATUS_SUCCESS,
		'error_msg' => null,
		'stack_trace' => null,
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

		$mapper = new \CronHelper\Model\JobMapper($adapter);
		$this->assertInstanceOf('CronHelper\Model\JobMapper', $mapper);
		$this->assertSame(\CronHelper\Model\JobTable::TABLE_NAME, $mapper->getTableName());
	}

	/**
	 * @depends testConstructor
	 */
	public function testDataOperations()
	{
		$adapter = new DbAdapter($this->getAdapterConfig());
		$table = new \CronHelper\Model\JobTable($adapter);
		$mapper = new \CronHelper\Model\JobMapper($adapter);

		// 1) We need to create table
		$table->create();

		// 2) Now we have empty table
		$resultSet1 = $mapper->fetchAll();
		$this->assertInstanceOf('Zend\Db\ResultSet\HydratingResultSet', $resultSet1);
		$this->assertSame(0, $resultSet1->count());

		// 3) Insert new job
		$job = new \CronHelper\Model\JobEntity($this->data);
		$this->assertInstanceOf('CronHelper\Model\JobEntity', $job);
		$newJob = $mapper->save($job);
		$this->assertNotSame(null, $newJob->getId());

		// 4) Now we should have table with one row
		$resultSet2 = $mapper->fetchAll();
		$this->assertInstanceOf('Zend\Db\ResultSet\HydratingResultSet', $resultSet2);
		//$this->assertSame(1, $resultSet2->count());
	}
}