CImageHandler
=============

`CImageHandler` - компонент для обработки изображений.

Возможности
-----------

* работает с JPG, PNG, GIF
* сохранение прозрачности при работе с PNG и GIF
* комбинирование нескольких операций над одной картинкой
* загрузка из любого и сохранение в любой из поддерживаемых форматов

Подключение
-----------

1. Скопируйте папку ImageHandler в папку `components`

2. Добавте путь в импорт

~~~php
'import'=>array(

    ...

    'application.components.ImageHandler.CImageHandler',

    ...

)
~~~

3. в `config/main.php`

~~~php
'components'=>array(

    ...

    'ih'=>array(
        'class'=>'CImageHandler',
    ),

    ...

)
~~~

или можно просто создавать экземпляр класса

~~~php
$ih = new CImageHandler();
~~~

Загрузка изображения
--------------------

~~~php
public function load($file);
~~~

`$file` - файл или урл изображения, которое будем обрабатывать

Повторная загрузка изображения
------------------------------

Если необходимо обработать ту же картинку, но с нуля, используйте метод

~~~php
public function reload();
~~~

Сохранение обработанного изображения
------------------------------------

~~~php
public function save($file = false, $toFormat = false, $jpegQuality = 75, $touch = false);
~~~

`$file` - в какой файл сохраняем, если `false`, то в тот же из которого была произведена загрузка. По умолчанию `false`

`$toFormat` - в каком формате сохраняем. Если `false`, то в том же что исходный файл. Возможные значения:

* `IMG_GIF`
* `IMG_JPEG`
* `IMG_PNG`

`$jpegQuality` - если сохраняем в JPEG формате, то можно задать качество. По умолчанию `75`

`$touch` - задать файлу ту же дату модификации что и у оригинала, если буду развивать класс, то необходимость в данном параметре отпадет. По умолчанию `false`


Вывод картинки без сохранения в файл
------------------------------------

~~~php
public function show($inFormat = false, $jpegQuality = 75);
~~~

`$inFormat` - в каком формате выводим. Если `false`, то в том же что исходный файл. Возможные значения:

* `IMG_GIF`
* `IMG_JPEG`
* `IMG_PNG`

`$jpegQuality` - если выводим в JPEG формате, то можно задать качество. По умолчанию `75`

~~~php
Yii::app()->ih
    ->load($_SERVER['DOCUMENT_ROOT'] . '/upload/image.jpg')
    ->crop(20, 20, 200, 200)
    ->show();
~~~

Создание превюшек
-----------------

~~~php
public function thumb($toWidth, $toHeight, $proportional = true);
~~~

`$toWidth` - до какой ширины уменьшать изображение, если у оригинала она больше. Можно задать `false`, тогда этот параметр не будет учитываться

`$toHeight` - до какой высоты уменьшать изображение, если у оригинала она больше. Можно задать `false`, тогда этот параметр не будет учитываться

`$proportional` - пропорционально ли масштабировать изображение. По умолчанию `true`

~~~php
Yii::app()->ih
    ->load($_SERVER['DOCUMENT_ROOT'] . '/upload/image.jpg')
    ->thumb(200, 200)
    ->save($_SERVER['DOCUMENT_ROOT'] . '/upload/image2.jpg');
~~~

Ресайз картинок (пропорционально/непропорционально)
---------------------------------------------------

~~~php
public function resize($toWidth, $toHeight, $proportional = true);
~~~

`$toWidth` - до какой ширины масштабировать изображение. Можно задать `false`, тогда этот параметр не будет учитываться

`$toHeight` - до какой высоты масштабировать изображение. Можно задать `false`, тогда этот параметр не будет учитываться

`$proportional` - пропорционально ли масштабировать изображение. По умолчанию `true`

~~~php
Yii::app()->ih
    ->load($_SERVER['DOCUMENT_ROOT'] . '/upload/image.jpg')
    ->resize(80, 80, false)
    ->save($_SERVER['DOCUMENT_ROOT'] . '/upload/80x80.jpg')
    ->reload()
    ->resize(24, 24, false)
    ->save($_SERVER['DOCUMENT_ROOT'] . '/upload/24x24.jpg');
~~~

Водяные знаки
-------------

~~~php
public function watermark($watermarkFile, $offsetX, $offsetY, $corner = self::CORNER_RIGHT_BOTTOM, $zoom = false);
~~~

`$watermarkFile` - изображение водяного знака, которое будет накладываться

`$offsetX` - отступ по горизонтали

`$offsetY` - отступ по вертикали

`$corner` - от какой части изображения позиционируется водяной знак. По умолчанию `self::CORNER_RIGHT_BOTTOM`. Возможные значения:

`$zoom` - изменить размер вотермарка относительно картинки (например, 0.3). Значение по умолчанию `false`, т.е. картинка сохраняет оригинальные размеры.

* `CORNER_LEFT_TOP` - левый верхний угол
* `CORNER_RIGHT_TOP` - правый верхний угол
* `CORNER_LEFT_BOTTOM` - левый нижний угол
* `CORNER_RIGHT_BOTTOM` - правый нижний угол
* `CORNER_CENTER` - центр изображения

~~~php
Yii::app()->ih
    ->load($_SERVER['DOCUMENT_ROOT'] . '/upload/image.jpg')
    ->watermark($_SERVER['DOCUMENT_ROOT'] . '/upload/watermark.png', 10, 20, CImageHandler::CORNER_LEFT_BOTTOM, 0.3)
    ->thumb(200, 200)
    ->save($_SERVER['DOCUMENT_ROOT'] . '/upload/image2.jpg');
~~~

Переворачивание
---------------

~~~php
public function flip($mode);
~~~

`$mode` - как поворачиваем. Возможные значения:

* `FLIP_HORIZONTAL` - горизонтально
* `FLIP_VERTICAL` - вертикально
* `FLIP_BOTH` - горизонтально и вертикально одновременно

~~~php
Yii::app()->ih
    ->load($_SERVER['DOCUMENT_ROOT'] . '/upload/image.jpg')
    ->flip(CImageHandler::FLIP_BOTH)
    ->save($_SERVER['DOCUMENT_ROOT'] . '/upload/flipped.jpg');
~~~

Поворот
-------

~~~php
public function rotate($degrees);
~~~

`$degrees` - угол в градусах. Если отрицательное значение, то поворот осуществляется против часовой стрелки.

~~~php
Yii::app()->ih
    ->load($_SERVER['DOCUMENT_ROOT'] . '/upload/image.jpg')
    ->rotate(-90)
    ->save($_SERVER['DOCUMENT_ROOT'] . '/upload/rotated.jpg');
~~~

Обрезка
-------

~~~php
public function crop($width, $height, $startX = false, $startY = false);
~~~

`$width` - ширина области, которая останется

`$height` - высота области, которая останется

`$startX` - отступ по горизонтали области, которая останется. Если задать `false`, то область будет центироваться по горизонтали. По умолчанию `false`

`$startY` - отступ по вертикали области, которая останется. Если задать `false,` то область будет центрироваться по вертикали. По умолчанию `false`

~~~php
Yii::app()->ih
    ->load($_SERVER['DOCUMENT_ROOT'] . '/upload/test.png')
    ->crop(100, 100, 10, 10)
    ->save($_SERVER['DOCUMENT_ROOT'] . '/upload/test2.png');
~~~

Добавление текста
-----------------

~~~php
public function text($text, $fontFile, $size=12, $color=array(0, 0, 0), $corner=self::CORNER_LEFT_TOP, $offsetX=0, $offsetY=0, $angle=0, $alpha = 0);
~~~

`$text` - собственно текст

`$fontFile` - путь к файлу шрифта

`$size` - размер текста. По умолчанию `12`

`$color` - цвет текста в RGB формате массивом `array(красный, зеленый, синий)`. По умолчанию черный `array(0, 0, 0)`

`$corner` - положение текста. По умолчанию `self::CORNER_LEFT_TOP`. Возможные значения:

* `CORNER_LEFT_TOP` - левый верхний угол
* `CORNER_RIGHT_TOP` - правый верхний угол
* `CORNER_LEFT_BOTTOM` - левый нижний угол
* `CORNER_RIGHT_BOTTOM` - правый нижний угол
* `CORNER_CENTER` - центр изображения
* `CORNER_CENTER_TOP` - центр верхней граници
* `CORNER_CENTER_BOTTOM` - центр нижней граници
* `CORNER_LEFT_CENTER` - центр левой граници
* `CORNER_RIGHT_CENTER` - центр правой граници

`$offsetX` - отступ по горизонтали

`$offsetY` - отступ по вертикали

`$angle` - угол поворота текста

`$alpha` - прозрачность текста. Возможные значения 0-127. Значение по умолчанию 0, т.е. полностью непрозрачный текст

~~~php
Yii::app()->ih
    ->load($_SERVER['DOCUMENT_ROOT'] . '/upload/image.jpg')
    ->text('Hello!', $_SERVER['DOCUMENT_ROOT'] . '/upload/georgia.ttf',
        20, array(255,0,0), CImageHandler::CORNER_LEFT_BOTTOM, 10, 10)
    ->save($_SERVER['DOCUMENT_ROOT'] . '/upload/text.jpg');
~~~

Превюшка с подгоном размера и обрезкой лишнего
----------------------------------------------

~~~php
public function adaptiveThumb($width, $height);
~~~

`$width` - ширина под которую подогнать

`$height` - высота под которую подогнать

~~~php
Yii::app()->ih
    ->load($_SERVER['DOCUMENT_ROOT'] . '/upload/test.jpg')
    ->adaptiveThumb(50, 50)
    ->save($_SERVER['DOCUMENT_ROOT'] . '/upload/test2.jpg');
~~~

Превюшка с заливкой бекграунда
------------------------------

~~~php
public function resizeCanvas($toWidth, $toHeight, $backgroundColor = array(255, 255, 255));
~~~

`$toWidth` - до какой ширины уменьшать изображение, если у оригинала она больше.

`$toHeight` - до какой высоты уменьшать изображение, если у оригинала она больше.

`$backgroundColor` - цвет фона в RGB формате массивом `array(красный, зеленый, синий)`. По умолчанию белый `array(255, 255, 255)`

~~~php
Yii::app()->ih
    ->load($_SERVER['DOCUMENT_ROOT'] . '/upload/test.jpg')
    ->resizeCanvas(300,300, array(0,255,0))
    ->save($_SERVER['DOCUMENT_ROOT'] . '/upload/test2.jpg');
~~~

Если указать в качестве размеров изображения исходные ширину и высоту, то получим просто заливку бекграунда:

~~~php
Yii::app()->ih->load($_SERVER['DOCUMENT_ROOT'] . '/upload/test.png')
    ->resizeCanvas(Yii::app()->ih->width,Yii::app()->ih->height,array(0, 255, 0))
    ->show();
~~~

Конвертация в черно-белое изображение
-------------------------------------

~~~php
public function grayscale();
~~~

Без параметров.

~~~php
Yii::app()->ih->load($_SERVER['DOCUMENT_ROOT'] . '/upload/test.jpg')
    ->grayscale()
    ->show();
~~~