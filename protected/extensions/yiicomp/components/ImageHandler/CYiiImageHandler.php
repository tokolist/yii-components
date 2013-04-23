<?php

require 'CImageHandler.php';

class CYiiImageHandler extends CApplicationComponent
{
	private $imageHandler;
	public $driver = 'GD';
	public $driverOptions = array();


	public function init() {
		parent::init();
		
		$this->imageHandler = new CImageHandler($this->driver, $this->driverOptions);
	}
	
	public function load($file)
	{
		return $this->imageHandler->load($file);
	}
}