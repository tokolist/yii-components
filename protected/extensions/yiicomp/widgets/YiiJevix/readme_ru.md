CYiiJevix
=========

Обертка над системой автоматического типографирования и фильтрации текста с 
HTML/XHTML разметкой Jevix.

http://code.google.com/p/jevix/

Подключение
-----------

1. Скопируйте папку `YiiJevix` в папку `components`

2. Добавьте путь в импорт

~~~php
'import'=>array(

    ...

    'application.components.YiiJevix.CYiiJevix',

    ...

)
~~~

3. Используйте виджет следующим образом

~~~php
<?php $this->beginWidget('CYiiJevix', array(
	'config'=>require_once(Yii::getPathOfAlias('application.config') . '/jevix.php'),
)); ?>

...

<?php $this->endWidget(); ?>
~~~

Ошибки фильтрации
-----------------

Если в процессе парсинга произошли ошибки, то их можно получить с помощью свойства
`errors`

~~~php
$jevix = $this->beginWidget('CYiiJevix' ... );

...

print_r($jevix->errors);
~~~

Пример конфигурации
-------------------

Пример конфигурации можно найти в файле `config.sample.php`. Также смотрите файл
`jevix/tests/jevixtest.php` с описаниями и примерами методов.