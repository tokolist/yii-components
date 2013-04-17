<?php
/**
 * Image handler
 * @author Yaroslav Pelesh aka Tokolist http://tokolist.com
 * @author Kosenka https://github.com/kosenka
 * @link https://github.com/tokolist/yii-components
 * @version 2.0
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 */

require '..\CImageHandlerDriver.php';

class CGDImageHandlerDriver extends CImageHandlerDriver
{
	private $image = null;
	public $transparencyColor = array(0, 0, 0);

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
	
	public function freeImage()
	{
		if(is_resource($this->image))
		{
			imagedestroy($this->image);
		}

		if($this->originalImage !== null)
		{
			if(is_resource($this->originalImage['image']))
			{
				imagedestroy($this->originalImage['image']);
			}
			$this->originalImage = null;
		}
	}
	
	public function checkLoaded()
	{
		return is_resource($this->image);
	}
	
	public function resize($toWidth, $toHeight)
	{
		$newImage = imagecreatetruecolor($newWidth, $newHeight);
		$this->preserveTransparency($newImage);
		imagecopyresampled($newImage, $this->image, 0, 0, 0, 0, $newWidth, $newHeight, $this->width, $this->height);
		imagedestroy($this->image);
		$this->image = $newImage;
		$this->width = $newWidth;
		$this->height = $newHeight;
	}
	
	public function watermark()
	{
		imagecopyresampled(
			$this->image,
			$wImg['image'],
			$posX,
			$posY,
			0,
			0,
			$watermarkWidth,
			$watermarkHeight,
			$wImg['width'],
			$wImg['height']
		);
		
		imagedestroy($wImg['image']);
	}
}
