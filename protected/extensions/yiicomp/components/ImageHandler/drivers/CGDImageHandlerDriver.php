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
				if(($result = imagecreatefromgif($file)))
				{
					return $result;
				}
				else
				{
					throw new Exception('Invalid image gif format');
				}
				break;
			case self::IMG_JPEG:
				if(($result = imagecreatefromjpeg($file)))
				{
					return $result;
				}
				else
				{
					throw new Exception('Invalid image jpeg format');
				}
				break;
			case self::IMG_PNG:
				if(($result = imagecreatefrompng($file)))
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
	
	public function initImage($image)
	{
		parent::initImage($image);
		
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
	
	public function watermark($wImg, $posX, $posY, $watermarkWidth, $watermarkHeight, $corner)
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
	
	public function flip($mode)
	{
		$srcX = 0;
		$srcY = 0;
		
		$width = $this->width;
		$height = $this->height;
		
		$srcWidth = $width;
		$srcHeight = $height;

		switch ($mode)
		{
			case self::FLIP_HORIZONTAL:
				$srcX = $width - 1;
				$srcWidth = -$width;
				break;
			case self::FLIP_VERTICAL:
				$srcY = $height - 1;
				$srcHeight = -$height;
				break;
			case self::FLIP_BOTH:
				$srcX = $width - 1;
				$srcY = $height - 1;
				$srcWidth = -$width;
				$srcHeight = -$height;
				break;
			default:
				throw new Exception('Invalid $mode value');
		}

		$newImage = imagecreatetruecolor($width, $height);
		$this->preserveTransparency($newImage);
		imagecopyresampled($newImage, $this->image, 0, 0, $srcX, $srcY, $width, $height, $srcWidth, $srcHeight);
		imagedestroy($this->image);
		$this->image = $newImage;
	}
	
	public function rotate($degrees)
	{
		$degrees = intval($degrees);
		$this->image = imagerotate($this->image, $degrees, 0);

		$this->width = imagesx($this->image);
		$this->height = imagesy($this->image);
	}
	
	public function crop($width, $height, $startX, $startY)
	{
		$newImage = imagecreatetruecolor($width, $height);

		$this->preserveTransparency($newImage);

		imagecopyresampled($newImage, $this->image, 0, 0, $startX, $startY, $width, $height, $width, $height);

		imagedestroy($this->image);
		$this->image = $newImage;
		$this->width = $width;
		$this->height = $height;
	}
	
	public function text($text, $fontFile, $size, $color, $corner, $offsetX, $offsetY, $angle, $alpha)
	{
		$bBox = imagettfbbox($size, $angle, $fontFile, $text);
		$textHeight = $bBox[1] - $bBox[7];
		$textWidth = $bBox[2] - $bBox[0];

		switch($corner)
		{
			case self::CORNER_LEFT_TOP:
				$posX = $offsetX;
				$posY = $offsetY;
				break;
			case self::CORNER_RIGHT_TOP:
				$posX = $this->width - $textWidth - $offsetX;
				$posY = $offsetY;
				break;
			case self::CORNER_LEFT_BOTTOM:
				$posX = $offsetX;
				$posY = $this->height - $textHeight - $offsetY;
				break;
			case self::CORNER_RIGHT_BOTTOM:
				$posX = $this->width - $textWidth - $offsetX;
				$posY = $this->height - $textHeight - $offsetY;
				break;
			case self::CORNER_CENTER:
				$posX = floor(($this->width - $textWidth) / 2);
				$posY = floor(($this->height - $textHeight) / 2);
				break;
			case self::CORNER_CENTER_TOP:
				$posX = floor(($this->width - $textWidth) / 2);
				$posY = $offsetY;
				break;
			case self::CORNER_CENTER_BOTTOM:
				$posX = floor(($this->width - $textWidth) / 2);
				$posY = $this->height - $textHeight - $offsetY;
				break;
			case self::CORNER_LEFT_CENTER:
				$posX = $offsetX;
				$posY = floor(($this->height - $textHeight) / 2);
				break;
			case self::CORNER_RIGHT_CENTER:
				$posX = $this->width - $textWidth - $offsetX;
				$posY = floor(($this->height - $textHeight) / 2);
				break;
			default:
				throw new Exception('Invalid $corner value');
		}
		
		if($alpha > 0)
		{
			$color = imagecolorallocatealpha($this->image, $color[0], $color[1], $color[2], $alpha);
		}
		else
		{
			$color = imagecolorallocate($this->image, $color[0], $color[1], $color[2]);
		}

		imagettftext($this->image, $size, $angle, $posX, $posY + $textHeight, $color, $fontFile, $text);
	}
	
	public function adaptiveThumb($width, $height, $backgroundColor)
	{
		$widthProportion = $width / $this->width;
		$heightProportion = $height / $this->height;

		if ($widthProportion > $heightProportion)
		{
			$newWidth = $width;
			$newHeight = round($newWidth / $this->width * $this->height);
		}
		else
		{
			$newHeight = $height;
			$newWidth = round($newHeight / $this->height * $this->width);
		}
		$this->resize($newWidth, $newHeight);
		$this->crop($width, $height);
	}
}
