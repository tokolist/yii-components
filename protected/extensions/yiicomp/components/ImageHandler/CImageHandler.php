<?php
/**
 * Image handler
 * @author Yaroslav Pelesh aka Tokolist http://tokolist.com
 * @author Kosenka https://github.com/kosenka
 * @link https://github.com/tokolist/yii-components
 * @version 2.0
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 */

class CImageHandler
{
	/**
	 * @var CImageHandlerDriver
	 */
	private $driver = null;
	private $originalImage = null;
	private $fileName = '';
	private $logCallback = false;

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
	
	const LOG_LEVEL_TRACE = 1;
	const LOG_LEVEL_WARNING = 2;
	const LOG_LEVEL_ERROR = 3;
	const LOG_LEVEL_INFO = 4;

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
	
	public function log($logMessage, $logLevel)
	{
		if($this->logCallback !== false)
		{
			call_user_func($this->logCallback, $logMessage, $logLevel);
		}
	}
	
	public static function getMethodStr($className = __CLASS__, $methodName = __METHOD__)
	{
		return $className . '::' . $methodName . '()';
	}

	public function __construct($driver, $driverOptions=array(), $logCallback = false) {
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
		
		$this->logCallback = $logCallback;
		
		return $this;
	}

	private function freeImage()
	{
		$this->driver->freeImage();
	}

	private function checkLoaded()
	{
		$this->log(self::getMethodStr(), self::LOG_LEVEL_TRACE);

		if(!$this->driver->checkLoaded())
		{
			throw new Exception('Load image first');
		}
	}

	private function loadImage($file)
	{
		$this->log(self::getMethodStr(), self::LOG_LEVEL_TRACE);

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
		$this->log(self::getMethodStr(), self::LOG_LEVEL_TRACE);
		
		if($image === false)
		{
			$image = $this->originalImage;
		}
		
		$this->driver->initImage($image);
	}

	public function load($file)
	{
		$this->log(self::getMethodStr(), self::LOG_LEVEL_TRACE);

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
		$this->log(self::getMethodStr(), self::LOG_LEVEL_TRACE);

		$this->checkLoaded();
		$this->initImage();

		return $this;
	}

	public function resize($toWidth, $toHeight, $proportional = true)
	{
		$this->log(self::getMethodStr(), self::LOG_LEVEL_TRACE);

		$this->checkLoaded();

		$toWidth = $toWidth !== false ? $toWidth : $this->getWidth();
		$toHeight = $toHeight !== false ? $toHeight : $this->getHeight();

		if($proportional)
		{
			$newHeight = $toHeight;
			$newWidth = round($newHeight / $this->getWidth() * $this->getWidth());
			if($newWidth > $toWidth)
			{
				$newWidth = $toWidth;
				$newHeight = round($newWidth / $this->getWidth() * $this->getWidth());
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
		$this->log(self::getMethodStr(), self::LOG_LEVEL_TRACE);

		$this->checkLoaded();

		if($toWidth !== false)
			$toWidth = min($toWidth, $this->getWidth());

		if($toHeight !== false)
			$toHeight = min($toHeight, $this->getHeight());

		
		$this->resize($toWidth, $toHeight, $proportional);

		return $this;
	}

	public function watermark($watermarkFile, $offsetX, $offsetY, $corner = self::CORNER_RIGHT_BOTTOM, $zoom = false)
	{
		$this->log(self::getMethodStr(), self::LOG_LEVEL_TRACE);

		$this->checkLoaded();

		if($wImg = $this->loadImage($watermarkFile))
		{
			$posX = 0;
			$posY = 0;

			$watermarkWidth = $wImg['width'];
			$watermarkHeight = $wImg['height'];

			if($zoom !== false)
			{
				$dimension = round(max($this->getWidth(), $this->getHeight()) * $zoom);

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
					$posX = $this->getWidth() - $watermarkWidth - $offsetX;
					$posY = $offsetY;
					break;
				case self::CORNER_LEFT_BOTTOM:
					$posX = $offsetX;
					$posY = $this->getHeight() - $watermarkHeight - $offsetY;
					break;
				case self::CORNER_RIGHT_BOTTOM:
					$posX = $this->getWidth() - $watermarkWidth - $offsetX;
					$posY = $this->getHeight() - $watermarkHeight - $offsetY;
					break;
				case self::CORNER_CENTER:
					$posX = floor(($this->getWidth() - $watermarkWidth) / 2);
					$posY = floor(($this->getHeight() - $watermarkHeight) / 2);
					break;
				case self::CORNER_CENTER_TOP:
					$posX = floor(($this->getWidth() - $watermarkWidth) / 2);
					$posY = $offsetY;
					break;
				case self::CORNER_CENTER_BOTTOM:
					$posX = floor(($this->getWidth() - $watermarkWidth) / 2);
					$posY = $this->getHeight() - $watermarkHeight - $offsetY;
					break;
				case self::CORNER_LEFT_CENTER:
					$posX = $offsetX;
					$posY = floor(($this->getHeight() - $watermarkHeight) / 2);
					break;
				case self::CORNER_RIGHT_CENTER:
					$posX = $this->getWidth() - $watermarkWidth - $offsetX;
					$posY = floor(($this->getHeight() - $watermarkHeight) / 2);
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
		$this->log(self::getMethodStr(), self::LOG_LEVEL_TRACE);

		$this->checkLoaded();
		$this->driver->flip($mode);
		
		return $this;
	}

	public function rotate($degrees)
	{
		$this->log(self::getMethodStr(), self::LOG_LEVEL_TRACE);

		$this->checkLoaded();
		$this->driver->rotate($degrees);
		
		return $this;
	}

	public function crop($width, $height, $startX = false, $startY = false)
	{
		$this->log(self::getMethodStr(), self::LOG_LEVEL_TRACE);

		$this->checkLoaded();

		$width = intval($width);
		$height = intval($height);

		//Centered crop
	 	$startX = $startX === false ? floor(($this->getWidth() - $width) / 2) : intval($startX);
		$startY = $startY === false ? floor(($this->getHeight() - $height) / 2) : intval($startY);
		
		//Check dimensions
		$startX = max(0, min($this->getWidth(), $startX));
		$startY = max(0, min($this->getHeight(), $startY));
		$width = min($width, $this->getWidth() - $startX);
		$height = min($height, $this->getHeight() - $startY);

		$this->driver->crop($width, $height, $startX, $startY);
		
		return $this;
	}

	public function text($text, $fontFile, $size=12, $color=array(0, 0, 0),
		$corner=self::CORNER_LEFT_TOP, $offsetX=0, $offsetY=0, $angle=0, $alpha = 0)
	{
		$this->log(self::getMethodStr(), self::LOG_LEVEL_TRACE);

		$this->checkLoaded();
		$this->driver->text($text, $fontFile, $size, $color, $angle, $alpha);
		
		return $this;
	}

	public function adaptiveThumb($width, $height, $backgroundColor=array(0, 0, 0))
	{
		$this->log(self::getMethodStr(), self::LOG_LEVEL_TRACE);

		$this->checkLoaded();
		
		$width = intval($width);
		$height = intval($height);
		
		$this->driver->adaptiveThumb($width, $height, $backgroundColor);

		return $this;
	}

	public function resizeCanvas($toWidth, $toHeight, $backgroundColor = array(255, 255, 255))
	{
		$this->log(self::getMethodStr(), self::LOG_LEVEL_TRACE);

		$this->checkLoaded();
		$this->driver->resizeCanvas($toWidth, $toHeight, $backgroundColor);

		return $this;
	}

	public function grayscale()
	{
		$this->log(self::getMethodStr(), self::LOG_LEVEL_TRACE);

		$this->driver->grayscale();
		
		return $this;
	}

	public function show($inFormat = false, $jpegQuality = 75)
	{
		$this->log(self::getMethodStr(), self::LOG_LEVEL_TRACE);
		
		$this->checkLoaded();

		if(!$inFormat)
		{
			$inFormat = $this->getFormat();
		}
		
		switch($inFormat)
		{
			case self::IMG_GIF:
				header('Content-type: image/gif');
				break;
			case self::IMG_JPEG:
				header('Content-type: image/jpeg');
				break;
			case self::IMG_PNG:
				header('Content-type: image/png');
				break;
			default:
				throw new Exception('Invalid image format for output');
		}

		$this->driver->show($inFormat, $jpegQuality);

		return $this;
	}

	public function save($file = false, $toFormat = false, $jpegQuality = 75, $touch = false)
	{
		$this->log(self::getMethodStr(), self::LOG_LEVEL_TRACE);

		if(empty($file))
		{
			$file = $this->fileName;
		}

		$this->checkLoaded();
		
		if(!$toFormat)
		{
			$toFormat = $this->getFormat();
		}
		
		$this->driver->save($file, $toFormat, $jpegQuality);
		
		if ($touch && $file != $this->fileName)
		{
			touch($file, filemtime($this->fileName));
		}

		return $this;
	}


}
