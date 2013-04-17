<?php
require '..\CImageHandlerDriver.php';

class CIMImageHandlerDriver extends CImageHandlerDriver
{
	public $convertPath = '/usr/bin/convert';
	public $compositePath = '/usr/bin/composite';
	protected $commandString;
	
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
	
	public function freeImage()
	{
		//do nothing
	}
	
	public function checkLoaded()
	{
		//TODO
	}
	
	public function resize($toWidth, $toHeight)
	{
		$this->commandString = $this->convertPath . " -quiet -strip -resize " . $newWidth . "x" . $newHeight . " " . $this->fileName . " %dest%";
	}
	
	public function watermark() {
		if($corner==self::CORNER_TILE)
			$this->engineExec=$this->engineIMComposite." -quiet -dissolve 25 -tile ".$watermarkFile." ".$this->fileName." %dest%";
		else
			$this->engineExec=$this->engineIMConvert." -quiet ".$this->fileName." ".$watermarkFile." -geometry +".$posX."+".$posY." -composite %dest%";
	}
}