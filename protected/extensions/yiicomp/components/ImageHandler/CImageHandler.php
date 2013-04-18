<?php
/**
 * Image handler
 * @author Yaroslav Pelesh aka Tokolist http://tokolist.com
 * @author Kosenka https://github.com/kosenka
 * @link https://github.com/tokolist/yii-components
 * @version 2.0
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 */

class CImageHandler extends CApplicationComponent
{
	/**
	 * @var CImageHandlerDriver
	 */
	private $driver = null;
	private $originalImage = null;
	private $fileName = '';

	const IMG_GIF = 1;
	const IMG_JPEG = 2;
	const IMG_PNG = 3;

	const CORNER_LEFT_TOP = 1;
	const CORNER_RIGHT_TOP = 2;
	const CORNER_LEFT_BOTTOM = 3;
	const CORNER_RIGHT_BOTTOM = 4;
	const CORNER_CENTER = 5;
	const CORNER_CENTER_TOP = 6;
	const CORNER_CENTER_BOTTOM = 7;
	const CORNER_LEFT_CENTER = 8;
	const CORNER_RIGHT_CENTER = 9;
	const CORNER_TILE = 10;

	const FLIP_HORIZONTAL = 1;
	const FLIP_VERTICAL = 2;
	const FLIP_BOTH = 3;


	public function getImage()
	{
		return $this->driver->getImage();
	}

	public function getFormat()
	{
		return $this->driver->getFormat();
	}

	public function getWidth()
	{
		return $this->driver->getWidth();
	}

	public function getHeight()
	{
		return $this->driver->getHeight();
	}

	public function getMimeType()
	{
		return $this->driver->getMimeType();
	}

	public function __destruct()
	{
		$this->freeImage();
	}
	
	public function __construct($driver, $driverOptions=array()) {
		if(empty($driver))
		{
			throw new Exception('Invalid driver name');
		}
		
		$driverClassName = "C{$driver}ImageHandlerDriver";
		require "drivers\\$driverClassName.php";
		$this->driver = new $driverClassName($this);
		
		foreach($driverOptions as $option => $value)
		{
			$this->driver->{$option} = $value;
		}
		
		return $this;
	}

	private function freeImage()
	{
		$this->driver->freeImage();
	}

	private function checkLoaded()
	{
		Yii::log('CImageHandler::checkLoaded: ', "trace", "system.*");

		if(!$this->driver->checkLoaded())
		{
			throw new Exception('Load image first');
		}
	}

	private function loadImage($file)
	{
		Yii::log('CImageHandler::loadImage: '.$file, "trace", "system.*");

		$result = array();

		if(($imageInfo = @getimagesize($file)))
		{
			$result['width'] = $imageInfo[0];
			$result['height'] = $imageInfo[1];
			$result['mimeType'] = $imageInfo['mime'];
			$result['format'] = $imageInfo[2];
			$result['image'] = $this->driver->loadImage($file, $result['format']);
			
			return $result;
		}
		else
		{
			throw new Exception('Invalid image file');
		}
	}

	protected function initImage($image = false)
	{
		Yii::log('CImageHandler::initImage: ', "trace", "system.*");
		
		if($image === false)
		{
			$image = $this->originalImage;
		}
		
		$this->driver->initImage($image);
	}

	public function load($file)
	{
		Yii::log('CImageHandler::load: '.$file, "trace", "system.*");

		$this->freeImage();

		if(($this->originalImage = $this->loadImage($file)))
		{
			$this->initImage();
			$this->fileName = $file;
			return $this;
		}
		else
		{
			return false;
		}
	}

	public function reload()
	{
		Yii::log('CImageHandler::reload: ', "trace", "system.*");

		$this->checkLoaded();
		$this->initImage();

		return $this;
	}

	public function resize($toWidth, $toHeight, $proportional = true)
	{
		Yii::log('CImageHandler::resize: ', "trace", "system.*");

		$this->checkLoaded();

		$toWidth = $toWidth !== false ? $toWidth : $this->width;
		$toHeight = $toHeight !== false ? $toHeight : $this->height;

		if($proportional)
		{
			$newHeight = $toHeight;
			$newWidth = round($newHeight / $this->height * $this->width);
			if($newWidth > $toWidth)
			{
				$newWidth = $toWidth;
				$newHeight = round($newWidth / $this->width * $this->height);
			}
		}
		else
		{
			$newWidth = $toWidth;
			$newHeight = $toHeight;
		}
		
		$this->driver->resize($toWidth, $toHeight);
		
		return $this;
	}

	public function thumb($toWidth, $toHeight, $proportional = true)
	{
		Yii::log('CImageHandler::thumb: ', "trace", "system.*");

		$this->checkLoaded();

		if($toWidth !== false)
			$toWidth = min($toWidth, $this->width);

		if($toHeight !== false)
			$toHeight = min($toHeight, $this->height);


		$this->resize($toWidth, $toHeight, $proportional);

		return $this;
	}

	public function watermark($watermarkFile, $offsetX, $offsetY, $corner = self::CORNER_RIGHT_BOTTOM, $zoom = false)
	{
		Yii::log('CImageHandler::watermark: ', "trace", "system.*");

		$this->checkLoaded();

		if($wImg = $this->loadImage($watermarkFile))
		{
			$posX = 0;
			$posY = 0;

			$watermarkWidth = $wImg['width'];
			$watermarkHeight = $wImg['height'];

			if($zoom !== false)
			{
				$dimension = round(max($this->width, $this->height) * $zoom);

				$watermarkHeight = $dimension;
				$watermarkWidth = round($watermarkHeight / $wImg['height'] * $wImg['width']);

				if($watermarkWidth > $dimension)
				{
					$watermarkWidth = $dimension;
					$watermarkHeight = round($watermarkWidth / $wImg['width'] * $wImg['height']);
				}
			}


			switch ($corner)
			{
				case self::CORNER_LEFT_TOP:
					$posX = $offsetX;
					$posY = $offsetY;
					break;
				case self::CORNER_RIGHT_TOP:
					$posX = $this->width - $watermarkWidth - $offsetX;
					$posY = $offsetY;
					break;
				case self::CORNER_LEFT_BOTTOM:
					$posX = $offsetX;
					$posY = $this->height - $watermarkHeight - $offsetY;
					break;
				case self::CORNER_RIGHT_BOTTOM:
					$posX = $this->width - $watermarkWidth - $offsetX;
					$posY = $this->height - $watermarkHeight - $offsetY;
					break;
				case self::CORNER_CENTER:
					$posX = floor(($this->width - $watermarkWidth) / 2);
					$posY = floor(($this->height - $watermarkHeight) / 2);
					break;
				case self::CORNER_CENTER_TOP:
					$posX = floor(($this->width - $watermarkWidth) / 2);
					$posY = $offsetY;
					break;
				case self::CORNER_CENTER_BOTTOM:
					$posX = floor(($this->width - $watermarkWidth) / 2);
					$posY = $this->height - $watermarkHeight - $offsetY;
					break;
				case self::CORNER_LEFT_CENTER:
					$posX = $offsetX;
					$posY = floor(($this->height - $watermarkHeight) / 2);
					break;
				case self::CORNER_RIGHT_CENTER:
					$posX = $this->width - $watermarkWidth - $offsetX;
					$posY = floor(($this->height - $watermarkHeight) / 2);
					break;
				case self::CORNER_TILE:
					break;
				default:
					throw new Exception('Invalid $corner value');
			}
			
			$this->driver->watermark($wImg, $posX, $posY, $watermarkWidth, $watermarkHeight, $corner);
			
			return $this;
		}
		else
		{
			return false;
		}
	}


	public function flip($mode)
	{
		Yii::log('CImageHandler::flip: ', "trace", "system.*");

		$this->checkLoaded();
		$this->driver->flip($mode);
		
		return $this;
	}

	public function rotate($degrees)
	{
		Yii::log('CImageHandler::rotate: ', "trace", "system.*");

		$this->checkLoaded();
		$this->driver->rotate($degrees);
		
		return $this;
	}

	public function crop($width, $height, $startX = false, $startY = false)
	{
		Yii::log('CImageHandler::crop: ', "trace", "system.*");

		$this->checkLoaded();

		$width = intval($width);
		$height = intval($height);

		//Centered crop
	 	$startX = $startX === false ? floor(($this->driver->getWidth() - $width) / 2) : intval($startX);
		$startY = $startY === false ? floor(($this->driver->getHeight() - $height) / 2) : intval($startY);
		
		//Check dimensions
		$startX = max(0, min($this->driver->getWidth(), $startX));
		$startY = max(0, min($this->driver->getHeight(), $startY));
		$width = min($width, $this->driver->getWidth() - $startX);
		$height = min($height, $this->driver->getHeight() - $startY);

		$this->driver->crop($width, $height, $startX, $startY);
		
		return $this;
	}

	public function text($text, $fontFile, $size=12, $color=array(0, 0, 0),
		$corner=self::CORNER_LEFT_TOP, $offsetX=0, $offsetY=0, $angle=0, $alpha = 0)
	{
		Yii::log('CImageHandler::text: ', "trace", "system.*");

		$this->checkLoaded();
		$this->driver->text($text, $fontFile, $size, $color, $angle, $alpha);
		
		return $this;
	}

	public function adaptiveThumb($width, $height, $backgroundColor=array(0, 0, 0))
	{
		Yii::log('CImageHandler::adaptiveThumb: ', "trace", "system.*");

		$this->checkLoaded();
		
		$width = intval($width);
		$height = intval($height);
		
		$this->driver->adaptiveThumb($width, $height, $backgroundColor);

		return $this;
	}

	public function resizeCanvas($toWidth, $toHeight, $backgroundColor = array(255, 255, 255))
	{
		Yii::log('CImageHandler::resizeCanvas: ', "trace", "system.*");

		$this->checkLoaded();
		$this->driver->resizeCanvas($toWidth, $toHeight, $backgroundColor);

		return $this;
	}

	public function grayscale()
	{
                Yii::log('CImageHandler::grayscale: ', "trace", "system.*");

                if($this->engine=='GD')
                {
        		$newImage = imagecreatetruecolor($this->width, $this->height);

        		imagecopy($newImage, $this->image, 0, 0, 0, 0, $this->width, $this->height);
        		imagecopymergegray($newImage, $newImage, 0, 0, 0, 0, $this->width, $this->height, 0);

        		imagedestroy($this->image);

        		$this->image = $newImage;
                }
                else
                {
                        $this->engineExec=$this->engineIMConvert." -colorspace Gray ".$this->fileName." %dest%";
                }

		return $this;
	}

	public function show($inFormat = false, $jpegQuality = 75)
	{
		$this->checkLoaded();

		if (!$inFormat)
		{
			$inFormat = $this->format;
		}

		switch ($inFormat)
		{
			case self::IMG_GIF:
				header('Content-type: image/gif');
				imagegif($this->image);
				break;
			case self::IMG_JPEG:
				header('Content-type: image/jpeg');
				imagejpeg($this->image, null, $jpegQuality);
				break;
			case self::IMG_PNG:
				header('Content-type: image/png');
				imagepng($this->image);
				break;
			default:
				throw new Exception('Invalid image format for putput');
		}

		return $this;
	}

	public function save($file = false, $toFormat = false, $jpegQuality = 75, $touch = false)
	{
                Yii::log('CImageHandler::save: ', "trace", "system.*");

		if (empty($file))
		{
			$file = $this->fileName;
		}

		$this->checkLoaded();

                if($this->engine=='GD')
                {
        		if (!$toFormat)
        		{
        			$toFormat = $this->format;
        		}

        		switch ($toFormat)
        		{
        			case self::IMG_GIF:
        				if (!imagegif($this->image, $file))
        				{
        					throw new Exception('Can\'t save gif file');
        				}
        				break;
        			case self::IMG_JPEG:
        				if (!imagejpeg($this->image, $file, $jpegQuality))
        				{
        					throw new Exception('Can\'t save jpeg file');
        				}
        				break;
        			case self::IMG_PNG:
        				if (!imagepng($this->image, $file))
        				{
        					throw new Exception('Can\'t save png file');
        				}
        				break;
        			default:
        				throw new Exception('Invalid image format for save');
        		}
                }
                else
                {
        		if (!$toFormat)
        		{
        			$toFormat = $this->format;
        		}

        		switch ($toFormat)
        		{
        			case self::IMG_GIF:
                                        $format="GIF";
        				break;
        			case self::IMG_JPEG:
                                        $format="JPG";
        				break;
        			case self::IMG_PNG:
                                        $format="PNG";
        				break;
        			default:
        				throw new Exception('Invalid image format for save');
        		}
                        $path_parts = pathinfo($file);
                        $file=$path_parts['dirname'].DIRECTORY_SEPARATOR.$path_parts['filename'].'.'.strtolower($format);
                        $this->engineExec=str_replace('%dest%',' -quality '.$jpegQuality.' '.$format.':'.$file,$this->engineExec);
                        Yii::log('CImageHandler: '.$this->engineExec, "trace", "system.*");
                        exec($this->engineExec);
                        $this->fileName=$file;
                }

		if ($touch && $file != $this->fileName)
		{
			touch($file, filemtime($this->fileName));
		}

		return $this;
	}


}
