<?php

abstract class CImageHandlerDriver
{
	abstract public function loadImage($file, $format);
}