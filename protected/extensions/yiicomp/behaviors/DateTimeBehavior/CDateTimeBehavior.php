<?php
/**
 * Behaviour for converting date formats between the DB and model
 * @author Yaroslav Pelesh aka Tokolist http://tokolist.com
 * @link https://github.com/tokolist/yii-components
 * @version 1.2
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 */

class CDateTimeBehavior extends CActiveRecordBehavior
{
	const DIRECTION_DB = 0;
	const DIRECTION_CLIENT = 1;
	
	public $dbFormat = 'yyyy-MM-dd';
	public $clientFormat = 'dd.MM.yyyy';
	public $attributes = array();
	
	protected $originalValues = array();


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
			
			$attributeNames = array_map('trim', explode(',', $attribute[0]));
			
			$scenarios = false;
			if(isset($attribute['on']) && is_string($attribute['on']))
			{
				$scenarios = array_map('trim', explode(',', $attribute['on']));
			}
			
			if($scenarios === false || in_array($this->owner->scenario, $scenarios))
			{
				foreach($attributeNames as $attributeName)
				{
					if(empty($model->$attributeName))
						continue;

					$fromFormat = isset($attribute['dbFormat']) ? $attribute['dbFormat'] : $this->dbFormat;
					$toFormat = isset($attribute['clientFormat']) ? $attribute['clientFormat'] : $this->clientFormat;

					if($direction == self::DIRECTION_CLIENT)
					{
						$this->originalValues[$attributeName] = array(
							'value'=>$model->$attributeName,
							'format'=>$fromFormat,
						);
					}

					if($direction == self::DIRECTION_DB)
					{
						list($fromFormat, $toFormat) = array($toFormat, $fromFormat);
					}

					$model->$attributeName = $this->convertDateTimeFormat(
						$model->$attributeName, $fromFormat, $toFormat);
				}
			}
		}
	}

	public function beforeSave($event)
	{
		parent::beforeSave($event);
		$this->processAttributes($event->sender, self::DIRECTION_DB);
	}
	
	public function afterSave($event)
	{
		parent::afterSave($event);
		$this->processAttributes($event->sender, self::DIRECTION_CLIENT);
	}
	
	public function afterFind($event)
	{
		parent::afterFind($event);
		$this->processAttributes($event->sender, self::DIRECTION_CLIENT);
	}
	
	public function getDateTimeAttr($attribute, $format=false)
	{
		if(!isset($this->originalValues[$attribute]['value']))
			return $this->owner->$attribute;
			
		$result = $this->originalValues[$attribute]['value'];
		
		if($format !== false)
		{
			$result = $this->convertDateTimeFormat($result,	
				$this->originalValues[$attribute]['format'], $format);
		}
		
		return $result;
	}
}