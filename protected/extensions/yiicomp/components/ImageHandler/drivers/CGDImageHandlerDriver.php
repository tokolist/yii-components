<?php
require '..\CImageHandlerDriver.php';

class CGDImageHandlerDriver extends CImageHandlerDriver
{
	private $image = null;

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
	
	public function initImage($image = false)
	{
		if(is_resource($this->image))
		{
			imagedestroy($this->image);
		}

		$this->image = imagecreatetruecolor($image['width'], $image['height']);
		$this->preserveTransparency($this->image);
		imagecopy($this->image, $image['image'], 0, 0, 0, 0, $image['width'], $image['height']);
	}
	
	private function preserveTransparency($newImage)
	{
		switch($this->format)
		{
			case self::IMG_GIF:
				$color = imagecolorallocate(
					$newImage,
					$this->transparencyColor[0],
					$this->transparencyColor[1],
					$this->transparencyColor[2]
				);

				imagecolortransparent($newImage, $color);
				imagetruecolortopalette($newImage, false, 256);
				break;
			case self::IMG_PNG:
    			imagealphablending($newImage, false);

				$color = imagecolorallocatealpha (
					$newImage,
					$this->transparencyColor[0],
					$this->transparencyColor[1],
					$this->transparencyColor[2],
					0
    			);

				imagefill($newImage, 0, 0, $color);
    			imagesavealpha($newImage, true);
				break;
		}
	}
}
