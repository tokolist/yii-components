<?php

class ImageHandlerController extends Controller
{
	private $basePath;

	public function init() {
		$this->basePath = Yii::app()->basePath . '/../images/image_handler/';
		parent::init();
	}

	public function actionResize1()
	{
		Yii::app()->ih->load($this->basePath . 'wm361812tt.jpg')
			->resizeCanvas(100,100,array(0, 255, 0))
			->show();
	}
	
	public function actionResize2()
	{
		Yii::app()->ih->load($this->basePath . 'wm370124tt.jpg')
			->resizeCanvas(100,100,array(0, 255, 0))
			->show();
	}
	
	public function actionGrayscale()
	{
		Yii::app()->ih->load($this->basePath . 'wm370124tt.jpg')
			->grayscale()
			->show();
	}
	
	public function actionPngAlpha()
	{
		Yii::app()->ih->load($this->basePath . 'paypal_512.png')
			->thumb(100,100)
			->show();
	}
	
	public function actionFillBg()
	{
		Yii::app()->ih->load($this->basePath . 'paypal_512.png')
			->resizeCanvas(Yii::app()->ih->width,Yii::app()->ih->height,array(0, 255, 0))
			->show();
	}
	
	
	public function actionWatermark()
	{
		Yii::app()->ih->load($this->basePath . 'wm370124tt.jpg')
			->watermark($this->basePath . 'paypal_512_trimmed.png', 10, 10, CImageHandler::CORNER_RIGHT_TOP, 0.2)
			->show();
	}
	

}