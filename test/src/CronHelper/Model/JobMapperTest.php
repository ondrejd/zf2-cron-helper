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
	private $dbAdapter;

	protected function getAdapter()
	{
		if (!($this->dbAdapter instanceof DbAdapter)) {
			$config = array(
				'driver' => 'Pdo_Sqlite',
				'database' => ':memory:',
			);

			$this->dbAdapter = new DbAdapter($config);
		}

		return $this->dbAdapter;
	}

	public function testConstructor()
	{
		$adapter = $this->getAdapter();

		$mapper1 = new \CronHelper\Model\JobMapper($adapter);
		$this->assertInstanceOf('CronHelper\Model\JobMapper', $mapper1);
		$this->assertSame(\CronHelper\Model\JobMapper::TABLE_NAME, $mapper1->getTableName());

		$mapper2 = new \CronHelper\Model\JobMapper($adapter, 'test');
		$this->assertInstanceOf('CronHelper\Model\JobMapper', $mapper2);
		$this->assertSame('test', $mapper2->getTableName());
	}

	/**
	 * @depends testConstructor
	 */
	public function testDataOperations()
	{
		$adapter = $this->getAdapter();
		$mapper = new \CronHelper\Model\JobMapper($adapter);

		// 1) Now we have empty table
		$resultSet1 = $mapper->fetchAll();
		$this->assertInstanceOf('Zend\Db\ResultSet\HydratingResultSet', $resultSet1);
		$this->assertSame(0, $resultSet1->count());

		// ...
	}
}