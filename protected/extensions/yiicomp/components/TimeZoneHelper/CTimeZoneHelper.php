<?php
/**
 * CTimeZoneHelper
 * @author Yaroslav Pelesh aka Tokolist http://tokolist.com
 * @link https://github.com/tokolist/yii-components
 * @version 1.0
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 */

class CTimeZoneHelper extends CApplicationComponent
{

	private $_serverTimeZoneString;
	private $_serverTimeZone;

	private $_userTimeZoneString;
	private $_userTimeZone;

	public function init() {
		parent::init();

		$systemTimeZone=Yii::app()->getTimeZone();

		if($this->_serverTimeZoneString===null)
			$this->_serverTimeZoneString=$systemTimeZone;

		if($this->_userTimeZoneString===null)
			$this->_userTimeZoneString=$systemTimeZone;
	}

	private function getPreparedDate($date)
	{
		if(is_int($date))
			$date="@$date";

		return $date;
	}


	public function setServerTimeZoneString($serverTimeZoneString)
	{
		$this->_serverTimeZoneString=$serverTimeZoneString;
		$this->_serverTimeZone=null;
	}

	public function getServerTimeZone()
	{
		if($this->_serverTimeZone===null)
			$this->_serverTimeZone=new DateTimeZone($this->_serverTimeZoneString);

		return $this->_serverTimeZone;
	}

	public function setUserTimeZoneString($userTimeZoneString)
	{
		$this->_userTimeZoneString=$userTimeZoneString;
		$this->_userTimeZone=null;
	}

	public function getUserTimeZone()
	{
		if($this->_userTimeZone===null)
			$this->_userTimeZone=new DateTimeZone($this->_userTimeZoneString);

		return $this->_userTimeZone;
	}

	public function toUserTime($date, $format='U')
	{
		$dateTime = new DateTime(
			$this->getPreparedDate($date),
			$this->getServerTimeZone()
		);
		
		$dateTime->setTimezone($this->getUserTimeZone());

		return $dateTime->format($format);
	}

	public function toServerTime($date, $format='U')
	{
		$dateTime = new DateTime(
			$this->getPreparedDate($date),
			$this->getUserTimeZone()
		);

		$dateTime->setTimezone($this->getServerTimeZone());

		return $dateTime->format($format);
	}
}