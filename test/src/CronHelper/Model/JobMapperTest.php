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
use CronHelper\Model\JobMapper;
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
		$mapper = new \CronHelper\Model\JobMapper($adapter);

		$this->assertInstanceOf('CronHelper\Model\JobMapper', $mapper);
	}
}