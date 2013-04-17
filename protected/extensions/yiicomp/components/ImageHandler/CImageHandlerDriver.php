<?php

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
	abstract public function watermark();
}