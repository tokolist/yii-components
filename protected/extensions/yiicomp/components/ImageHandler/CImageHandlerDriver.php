<?php
/**
 * Image handler
 * @author Yaroslav Pelesh aka Tokolist http://tokolist.com
 * @author Kosenka https://github.com/kosenka
 * @link https://github.com/tokolist/yii-components
 * @version 2.0
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 */

abstract class CImageHandlerDriver
{
	protected $imageHandler = null;
	
	public function __construct($imageHandler) {
		$this->imageHandler = $imageHandler;
	}

	abstract public function loadImage($file, $format);
	abstract public function initImage($image = false);
	abstract public function freeImage();
	abstract public function checkLoaded();
	abstract public function resize($toWidth, $toHeight);
	abstract public function watermark($wImg, $posX, $posY, $watermarkWidth, $watermarkHeight, $corner);
}