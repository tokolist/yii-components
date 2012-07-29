<?php
/**
 * Behaviour for converting date formats between the DB and model
 * @author Pelesh Yaroslav aka Tokolist http://tokolist.com
 * @link https://github.com/tokolist/yii-components
 * @version 1.0
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 */

class CDateTimeBehavior extends CActiveRecordBehavior
{
	const DIRECTION_DB = 0;
	const DIRECTION_CLIENT = 1;
	
	public $dbFormat = 'yyyy-MM-dd';
	public $clientFormat = 'dd.MM.yyyy';
	public $attributes = array();
	
	protected function convertDateTimeFormat($date, $fromFormat, $toFormat)
	{
		return Yii::app()->dateFormatter->format(
			$toFormat,
			CDateTimeParser::parse($date, $fromFormat)
		);
	}
	
	protected function processAttributes($model, $direction)
	{
		$attributes = $this->attributes;
		
		if(!is_array($attributes))
			$attributes = array($attributes);
		
		foreach($attributes as $attribute)
		{
			if(!is_array($attribute))
				$attribute = array($attribute);
			
			$attributeNames = split(',', $attribute[0]);
			
			foreach($attributeNames as $attributeName)
			{
				$attributeName = trim($attributeName);
				
				if(empty($model->$attributeName))
					continue;

				$fromFormat = isset($attribute['dbFormat']) ? $attribute['dbFormat'] : $this->dbFormat;
				$toFormat = isset($attribute['clientFormat']) ? $attribute['clientFormat'] : $this->clientFormat;

				if($direction == self::DIRECTION_DB)
					list($fromFormat, $toFormat) = array($toFormat, $fromFormat);

				$model->$attributeName = $this->convertDateTimeFormat(
					$model->$attributeName, $fromFormat, $toFormat);
			}
		}
	}

	public function beforeSave($event)
	{
		$this->processAttributes($event->sender, self::DIRECTION_DB);
	}
	
	public function afterFind($event)
	{
		$this->processAttributes($event->sender, self::DIRECTION_CLIENT);
	}
}