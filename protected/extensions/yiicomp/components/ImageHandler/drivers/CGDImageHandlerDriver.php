<?php
require '..\CImageHandlerDriver.php';

class CGDImageHandlerDriver extends CImageHandlerDriver
{
	public function loadImage($file, $format)
	{
		switch ($format)
		{
			case self::IMG_GIF:
				if ($result = imagecreatefromgif($file))
				{
					return $result;
				}
				else
				{
					throw new Exception('Invalid image gif format');
				}
				break;
			case self::IMG_JPEG:
				if ($result = imagecreatefromjpeg($file))
				{
					return $result;
				}
				else
				{
					throw new Exception('Invalid image jpeg format');
				}
				break;
			case self::IMG_PNG:
				if ($result = imagecreatefrompng($file))
				{
					return $result;
				}
				else
				{
					throw new Exception('Invalid image png format');
				}
				break;
			default:
				throw new Exception('Not supported image format');
		}
	}
}
