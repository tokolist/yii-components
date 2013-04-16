<?php
require '..\CImageHandlerDriver.php';

class CIMImageHandlerDriver extends CImageHandlerDriver
{
	public function loadImage($file, $format)
	{
		if(!file_exists($file))
		{
			throw new Exception("File {$file} not found");
		}
		
		return null;
	}
}