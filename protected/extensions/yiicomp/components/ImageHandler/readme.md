CImageHandler
=============

`CImageHandler` - component for image handling.

Features
--------

* handles JPG, PNG and GIF formats
* transparency preserving for PNG and GIF formats
* applying of few actions to one image at once
* loading from and saving to any supported file formats

Installation
------------

1. Copy ImageHandler directory to Yii `components` directory

2. Add import path

~~~php
'import'=>array(

    ...

    'application.components.ImageHandler.CImageHandler',

    ...

)
~~~

3. Add following lines to `config/main.php`

~~~php
'components'=>array(

    ...

    'ih'=>array(
        'class'=>'CImageHandler',
    ),

    ...

)
~~~

or you can use it as simle class

~~~php
$ih = new CImageHandler();
~~~

Image loading
-------------

~~~php
public function load($file);
~~~

`$file` - image file path or URL

Image reloading
---------------

If there is need to reprocess image from scratch, use following method

~~~php
public function reload();
~~~

Processed image saving
----------------------

~~~php
public function save($file = false, $toFormat = false, $jpegQuality = 75, $touch = false);
~~~

`$file` - file path. If value is `false`, then it will be replaced with original file path. Default value is `false`

`$toFormat` - image format. If value is `false`, then file will be saved in the same as original file format. Possible options:

* `IMG_GIF`
* `IMG_JPEG`
* `IMG_PNG`

`$jpegQuality` - image quality (for JPEG only). Default value is `75`

`$touch` - set for saved file modification timestamp which is equal to original file one. Default value is `false`


Direct output
-------------

~~~php
public function show($inFormat = false, $jpegQuality = 75);
~~~

`$inFormat` - image format. If value is `false`, then file will be saved in the same as original file format. Possible options:

* `IMG_GIF`
* `IMG_JPEG`
* `IMG_PNG`

`$jpegQuality` - image quality (for JPEG only). Default value is `75`

~~~php
Yii::app()->ih
    ->load($_SERVER['DOCUMENT_ROOT'] . '/upload/image.jpg')
    ->crop(20, 20, 200, 200)
    ->show();
~~~

Thumbs
------

~~~php
public function thumb($toWidth, $toHeight, $proportional = true);
~~~

`$toWidth` - fit to width. Can be `false`, which means that this parameter is ignored

`$toHeight` - fit to height. Can be `false`, which means that this parameter is ignored

`$proportional` - proportional scaling. Default value is `true`

~~~php
Yii::app()->ih
    ->load($_SERVER['DOCUMENT_ROOT'] . '/upload/image.jpg')
    ->thumb(200, 200)
    ->save($_SERVER['DOCUMENT_ROOT'] . '/upload/image2.jpg');
~~~

Image resizing (proportional or non-proportional)
---------------------------------------------------

~~~php
public function resize($toWidth, $toHeight, $proportional = true);
~~~

`$toWidth` - resize to width. Can be `false`, which means that this parameter is ignored

`$toHeight` - resize to height. Can be `false`, which means that this parameter is ignored

`$proportional` - proportional scaling. Default value is `true`

~~~php
Yii::app()->ih
    ->load($_SERVER['DOCUMENT_ROOT'] . '/upload/image.jpg')
    ->resize(80, 80, false)
    ->save($_SERVER['DOCUMENT_ROOT'] . '/upload/80x80.jpg')
    ->reload()
    ->resize(24, 24, false)
    ->save($_SERVER['DOCUMENT_ROOT'] . '/upload/24x24.jpg');
~~~

Watermark
---------

~~~php
public function watermark($watermarkFile, $offsetX, $offsetY, $corner = self::CORNER_RIGHT_BOTTOM, $zoom = false);
~~~

`$watermarkFile` - watermark file path

`$offsetX` - horizontal offset

`$offsetY` - vertical offset

`$corner` - image part to which watermark position is relative. Default value is `self::CORNER_RIGHT_BOTTOM`. Possible options:

`$zoom` - zoom watermark image relatively to the image dimensions (e.g. 0.3). Default value is `false`, which means that watermark image keeps original scale.

* `CORNER_LEFT_TOP` - left top corner
* `CORNER_RIGHT_TOP` - right top corner
* `CORNER_LEFT_BOTTOM` - left bottom corner
* `CORNER_RIGHT_BOTTOM` - right bottom corner
* `CORNER_CENTER` - image middle point

~~~php
Yii::app()->ih
    ->load($_SERVER['DOCUMENT_ROOT'] . '/upload/image.jpg')
    ->watermark($_SERVER['DOCUMENT_ROOT'] . '/upload/watermark.png', 10, 20, CImageHandler::CORNER_LEFT_BOTTOM, 0.3)
    ->thumb(200, 200)
    ->save($_SERVER['DOCUMENT_ROOT'] . '/upload/image2.jpg');
~~~

Flipping
--------

~~~php
public function flip($mode);
~~~

`$mode` - flipping direction. Possible options:

* `FLIP_HORIZONTAL` - horizontal
* `FLIP_VERTICAL` - vertical
* `FLIP_BOTH` - first two options simultaneously

~~~php
Yii::app()->ih
    ->load($_SERVER['DOCUMENT_ROOT'] . '/upload/image.jpg')
    ->flip(CImageHandler::FLIP_BOTH)
    ->save($_SERVER['DOCUMENT_ROOT'] . '/upload/flipped.jpg');
~~~

Rotating
--------

~~~php
public function rotate($degrees);
~~~

`$degrees` - degrees. Value can be negative; in that case image will be rotated anticlockwise

~~~php
Yii::app()->ih
    ->load($_SERVER['DOCUMENT_ROOT'] . '/upload/image.jpg')
    ->rotate(-90)
    ->save($_SERVER['DOCUMENT_ROOT'] . '/upload/rotated.jpg');
~~~

Cropping
--------

~~~php
public function crop($width, $height, $startX = false, $startY = false);
~~~

`$width` - target width

`$height` - target height

`$startX` - horizontal offset. Value can be `false`, in that case target area will be horizontally cantered. Default value is `false`

`$startY` - vertical offset. Value can be `false,` in that case target area will be vertically cantered. Default value is `false`

~~~php
Yii::app()->ih
    ->load($_SERVER['DOCUMENT_ROOT'] . '/upload/test.png')
    ->crop(100, 100, 10, 10)
    ->save($_SERVER['DOCUMENT_ROOT'] . '/upload/test2.png');
~~~

Text adding
-----------

~~~php
public function text($text, $fontFile, $size=12, $color=array(0, 0, 0), $corner=self::CORNER_LEFT_TOP, $offsetX=0, $offsetY=0, $angle=0, $alpha = 0);
~~~

`$text` - text

`$fontFile` - font file path

`$size` - font size. Default value is `12`

`$color` - text color in RGB format `array(red, green, blue)`. Default value is black color `array(0, 0, 0)`

`$corner` - text position. Default value is `self::CORNER_LEFT_TOP`. Possible options:

* `CORNER_LEFT_TOP` - left top corner
* `CORNER_RIGHT_TOP` - right top corner
* `CORNER_LEFT_BOTTOM` - left bottom corner
* `CORNER_RIGHT_BOTTOM` - right bottom corner
* `CORNER_CENTER` - image middle point
* `CORNER_CENTER_TOP` - center top point
* `CORNER_CENTER_BOTTOM` - center bottom point
* `CORNER_LEFT_CENTER` - left center point
* `CORNER_RIGHT_CENTER` - right center point

`$offsetX` - horizontal offset

`$offsetY` - vertical offset

`$angle` - angle degrees

`$alpha` - text transparency. Possible value is between 0 and 127. Default value is 0, i.e. completely opaque text

~~~php
Yii::app()->ih
    ->load($_SERVER['DOCUMENT_ROOT'] . '/upload/image.jpg')
    ->text('Hello!', $_SERVER['DOCUMENT_ROOT'] . '/upload/georgia.ttf',
        20, array(255,0,0), CImageHandler::CORNER_LEFT_BOTTOM, 10, 10)
    ->save($_SERVER['DOCUMENT_ROOT'] . '/upload/text.jpg');
~~~

Thumbs with cropping (adaptive thumbs)
--------------------------------------

~~~php
public function adaptiveThumb($width, $height);
~~~

`$width` - target width

`$height` - target height

~~~php
Yii::app()->ih
    ->load($_SERVER['DOCUMENT_ROOT'] . '/upload/test.jpg')
    ->adaptiveThumb(50, 50)
    ->save($_SERVER['DOCUMENT_ROOT'] . '/upload/test2.jpg');
~~~

Thumbs with background filling
------------------------------

~~~php
public function resizeCanvas($toWidth, $toHeight, $backgroundColor = array(255, 255, 255));
~~~

`$toWidth` - if original image is wider then resize it to this width

`$toHeight` - if original image is higher then resize it to this height

`$backgroundColor` - text color in RGB format `array(red, green, blue)`. Default value is white color `array(255, 255, 255)`

~~~php
Yii::app()->ih
    ->load($_SERVER['DOCUMENT_ROOT'] . '/upload/test.jpg')
    ->resizeCanvas(300,300, array(0,255,0))
    ->save($_SERVER['DOCUMENT_ROOT'] . '/upload/test2.jpg');
~~~

If you put original width and height as parameters, than only background will be filled:

~~~php
Yii::app()->ih->load($_SERVER['DOCUMENT_ROOT'] . '/upload/test.png')
    ->resizeCanvas(Yii::app()->ih->width,Yii::app()->ih->height,array(0, 255, 0))
    ->show();
~~~

Grayscale
---------

~~~php
public function grayscale();
~~~

No parameters.

~~~php
Yii::app()->ih->load($_SERVER['DOCUMENT_ROOT'] . '/upload/test.jpg')
    ->grayscale()
    ->show();
~~~