<?php
/**
 * Image handler
 * @author Yaroslav Pelesh aka Tokolist http://tokolist.com
 * @author Kosenka https://github.com/kosenka
 * @link https://github.com/tokolist/image-handler
 * @version 2.0 beta
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 */

abstract class CImageHandlerDriver
{
	/**
	 * @var CImageHandler
	 */
	protected $imageHandler = null;
	
	protected $image = null;
	protected $format = 0;
	protected $width = 0;
	protected $height = 0;
	protected $mimeType = '';
	
	protected $originalImage = null;

	public function getImage()
	{
		return $this->image;
	}

	public function getFormat()
	{
		return $this->format;
	}
	
	public function getWidth()
	{
		return $this->width;
	}
	
	public function getHeight()
	{
		return $this->height;
	}
	
	public function getMimeType()
	{
		return $this->mimeType;
	}

	public function __construct($imageHandler) {
		$this->imageHandler = $imageHandler;
	}
	
	public function __destruct()
	{
		$this->freeImage();
	}

	public function initImage()
	{
		$this->format = $this->originalImage['format'];
		$this->width = $this->originalImage['width'];
		$this->height = $this->originalImage['height'];
		$this->mimeType = $this->originalImage['mimeType'];
	}
	
	public function getImageInfo($file)
	{
		//getimagesize does not require the GD image library.
		if(($imageInfo = @getimagesize($file)))
		{
			return array(
				'fileName' => $file,
				'format' => $imageInfo[2],
				'width' => $imageInfo[0],
				'height' => $imageInfo[1],
				'mimeType' => $imageInfo['mime'],
			);
		}
		else
		{
			throw new Exception('Invalid image file');
		}
	}

	abstract public function createImage($imageInfo);
	abstract public function loadImage($image, $imageInfo);
	abstract public function freeImage();
	abstract public function checkLoaded();
	abstract public function resize($toWidth, $toHeight);
	abstract public function watermark($wImg, $posX, $posY, $watermarkWidth, $watermarkHeight, $corner);
	abstract public function flip($mode);
	abstract public function rotate($degrees);
	abstract public function crop($width, $height, $startX, $startY);
	abstract public function text($text, $fontFile, $size, $color, $corner, $offsetX, $offsetY, $angle, $alpha);
	abstract public function adaptiveThumb($width, $height, $backgroundColor);
	abstract public function resizeCanvas($toWidth, $toHeight, $backgroundColor);
	abstract public function grayscale();
	abstract public function show($inFormat, $jpegQuality);
	abstract public function save($file, $toFormat, $jpegQuality);
}