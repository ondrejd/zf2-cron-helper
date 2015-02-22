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
 * Entity that represents job entity.
 *
 * @package CronHelper
 * @subpackage Model
 * @author Ondřej Doněk <ondrejd@gmail.com>
 */
class JobEntity
{
	/**
	 * @var string
	 */
	const STATUS_SUCCESS = 'success';

	/**
	 * @var string
	 */
	const STATUS_RUNNING = 'success';

	/**
	 * @var string
	 */
	const STATUS_MISSED = 'missed';

	/**
	 * @var string
	 */
	const STATUS_ERROR = 'error';

	/**
	 * @var integer $id
	 */
	private $id;

	/**
	 * @var string $code
	 */
	private $code;

	/**
	 * @var string $status
	 */
	private $status;

	/**
	 * @var string $errorMsg
	 */
	private $errorMsg;

	/**
	 * @var string $stackTrace
	 */
	private $stackTrace;

	/**
	 * @var string $created
	 */
	private $created;

	/**
	 * @var string $scheduled
	 */
	private $scheduled;

	/**
	 * @var string $executed
	 */
	private $executed;

	/**
	 * @var string $finished
	 */
	private $finished;

	/**
	 * @param array $data (Optional). Data to set.
	 * @return void
	 */
	public function __construct($data = array())
	{
		$this->exchangeArray($data);
	}

	/**
	 * @param array $data (Optional). Data to set.
	 * @return void
	 */
	public function exchangeArray($data = array())
	{
		$this->setId(array_key_exists('id', $data) ? $data['id'] : null);
		$this->setCode(array_key_exists('code', $data) ? $data['code'] : null);
		$this->setStatus(array_key_exists('status', $data) ? $data['status'] : null);
		$this->setErrorMsg(array_key_exists('error_msg', $data) ? $data['error_msg'] : null);
		$this->setStackTrace(array_key_exists('stack_trace', $data) ? $data['stack_trace'] : null);
		$this->setCreated(array_key_exists('created', $data) ? $data['created'] : null);
		$this->setScheduled(array_key_exists('scheduled', $data) ? $data['scheduled'] : null);
		$this->setExecuted(array_key_exists('executed', $data) ? $data['executed'] : null);
		$this->setFinished(array_key_exists('finished', $data) ? $data['finished'] : null);
	}

	/**
	 * @return array Returns array with entity's data.
	 */
	public function getArrayCopy()
	{
		return array(
			'id' => $this->getId(),
			'code' => $this->getCode(),
			'status' => $this->getStatus(),
			'error_msg' => $this->getErrorMsg(),
			'stack_trace' => $this->getStackTrace(),
			'created' => $this->getCreated(),
			'scheduled' => $this->getScheduled(),
			'executed' => $this->getExecuted(),
			'finished' => $this->getFinished(),
		);
	}

	/**
	 * @param integer $val
	 * @return JobEntity
	 */
	public function setId($val)
	{
		$this->id = is_int($val) ? (int) $val : null;
		return $this;
	}

	/**
	 * @return integer|null
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @param string $val
	 * @return JobEntity
	 */
	public function setCode($val)
	{
		$this->code = is_string($val) ? $val : null;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getCode()
	{
		return $this->code;
	}

	/**
	 * @param string|null $val
	 * @return JobEntity
	 * @throws \InvalidArgumentException Whenever bad status given.
	 */
	public function setStatus($val)
	{
		$status = is_string($val) ? $val : null;

		switch ($status) {
			case self::STATUS_SUCCESS:
			case self::STATUS_RUNNING:
			case self::STATUS_MISSED:
			case self::STATUS_ERROR:
			case null:
				$this->status = $status;
				return $this;
		}

		throw new \InvalidArgumentException('Bad status given!');
	}

	/**
	 * @return string|null
	 */
	public function getStatus()
	{
		return $this->status;
	}

	/**
	 * @param string $val
	 * @return JobEntity
	 */
	public function setErrorMsg($val)
	{
		$this->errorMsg = is_string($val) ? $val : null;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getErrorMsg()
	{
		return $this->errorMsg;
	}

	/**
	 * @param string $val
	 * @return JobEntity
	 */
	public function setStackTrace($val)
	{
		$this->stackTrace = is_string($val) ? $val : null;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getStackTrace()
	{
		return $this->stackTrace;
	}

	/**
	 * @param string $val
	 * @return JobEntity
	 */
	public function setCreated($val)
	{
		$this->created = is_string($val) ? $val : null;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getCreated()
	{
		return $this->created;
	}

	/**
	 * @param string $val
	 * @return JobEntity
	 */
	public function setScheduled($val)
	{
		$this->scheduled = is_string($val) ? $val : null;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getScheduled()
	{
		return $this->scheduled;
	}

	/**
	 * @param string $val
	 * @return JobEntity
	 */
	public function setExecuted($val)
	{
		$this->executed = is_string($val) ? $val : null;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getExecuted()
	{
		return $this->executed;
	}

	/**
	 * @param string $val
	 * @return JobEntity
	 */
	public function setFinished($val)
	{
		$this->finished = is_string($val) ? $val : null;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getFinished()
	{
		return $this->finished;
	}

	/**
	 * Returns duration of the job's execution.
	 *
	 * @return integer Time in miliseconds.
	 * @todo Implement this!
	 */
	public function getDuration()
	{
		return 0;
	}
}
