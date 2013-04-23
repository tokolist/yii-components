<?php
/**
 * Yii wrapper for ImageHandler
 * @author Yaroslav Pelesh aka Tokolist http://tokolist.com
 * @link https://github.com/tokolist/yii-components
 * @version 1.0
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 */

require 'CImageHandler.php';

class CYiiImageHandler extends CApplicationComponent
{
	private $imageHandler;
	public $driver = 'GD';
	public $driverOptions = array();


	public function init()
	{
		parent::init();
		
		$this->imageHandler = new CImageHandler(
			$this->driver,
			$this->driverOptions,
			array($this, 'log')
		);
	}
	
	public function load($file)
	{
		return $this->imageHandler->load($file);
	}
	
	public function log($logMessage, $logLevel)
	{
		$yiiLogLevels = array(
			CImageHandler::LOG_LEVEL_TRACE => CLogger::LEVEL_TRACE,
			CImageHandler::LOG_LEVEL_WARNING => CLogger::LEVEL_WARNING,
			CImageHandler::LOG_LEVEL_ERROR => CLogger::LEVEL_ERROR,
			CImageHandler::LOG_LEVEL_INFO => CLogger::LEVEL_INFO,
		);
		
		Yii::log($logMessage, $yiiLogLevels[$logLevel], "system.*");
	}
}