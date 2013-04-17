<?php

abstract class CImageHandlerDriver
{
	protected $imageHandler = null;
	
	public function __construct($imageHandler) {
		$this->imageHandler = $imageHandler;
	}

	abstract public function loadImage($file, $format);
	abstract public function initImage($image = false);
}