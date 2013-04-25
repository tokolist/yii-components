YiiImageHandler
=============

YiiImageHandler обертка над ImageHandler для yii

Подключение
-----------

1. Скопируйте папку YiiImageHandler в папку `components`

2. Добавьте путь в импорт

~~~php
'import'=>array(

    ...

    'application.components.YiiImageHandler.CYiiImageHandler',

    ...

)
~~~

3. в `config/main.php`

~~~php
'components'=>array(

    ...

    'ih'=>array(
        'class'=>'CYiiImageHandler',

		'driver'=>'GD', //драйвер который будет использован (может быть GD или IM)
						//GD - библиотека GD
						//IM - ImageMagic

		//Можно указать опции драйвера
		'driverOptions'=>array(
		),
    ),

    ...

)
~~~

теперь можно использовать компонент в Вашем проекте

~~~php
Yii::app()->ih->load($_SERVER['DOCUMENT_ROOT'] . '/upload/image.jpg')
    ->crop(20, 20, 200, 200)
    ->show();
~~~

или можно просто создавать экземпляр класса

~~~php
$ih = new CImageHandler();
~~~

Использование
-------------

Пожалуйста смотрите документацию ImageHandler.