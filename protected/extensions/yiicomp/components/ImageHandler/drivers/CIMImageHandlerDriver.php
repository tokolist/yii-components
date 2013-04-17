<?php
require '..\CImageHandlerDriver.php';

class CIMImageHandlerDriver extends CImageHandlerDriver
{
	private $convertPath = '/usr/bin/convert';
	private $compositePath = '/usr/bin/composite';
	private $commandString;
	
	public function __construct($imageHandler) {
		parent::__construct();
		
		if(empty($this->convertPath))
		{
			throw new Exception('ImageMagick::convert not set');
		}
		
		if(empty($this->compositePath))
		{
			throw new Exception('ImageMagick::composite not set');
		}
	}
	
	private function colorToHex($color)
	{
		return str_pad(dechex($color), 2, '0', STR_PAD_LEFT);
	}
	
	private function rgbToHex($r, $g, $b)
	{
		$result = '';

		foreach(array($r, $g, $b) as $color)
		{
			$result .= colorToHex($color);
		}		
		
		return "#" . $result;
	}
	
	public function loadImage($file, $format)
	{
		if(!file_exists($file))
		{
			throw new Exception("File {$file} not found");
		}
		
		return null;
	}
	
	public function initImage($image = false)
	{
		//do nothing
	}
}