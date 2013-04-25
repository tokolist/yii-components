YiiImageHandler
=============

YiiImageHandler is an ImageHandler wrapper for yii

Installation
------------

1. Copy YiiImageHandler directory into Yii `components` directory

2. Add the import paths

~~~php
'import'=>array(

    ...

    'application.components.YiiImageHandler.CYiiImageHandler',

    ...

)
~~~

3. Add the following lines to the `config/main.php` file

~~~php
'components'=>array(

    ...

    'ih'=>array(
        'class'=>'CYiiImageHandler',

		'driver'=>'GD', //driver which you are going to use (can be either GD or IM)
						//GD is GD library
						//IM is ImageMagic

		//You can specify chosen driver options if needed
		'driverOptions'=>array(
		),
    ),

    ...

)
~~~

now you can use it in your application

~~~php
Yii::app()->ih->load($_SERVER['DOCUMENT_ROOT'] . '/upload/image.jpg')
    ->crop(20, 20, 200, 200)
    ->show();
~~~

or you can use it as a standalone class

~~~php
$ih = new CYiiImageHandler();
~~~

Usage
-----

Please refer to the ImageHandler documentation.