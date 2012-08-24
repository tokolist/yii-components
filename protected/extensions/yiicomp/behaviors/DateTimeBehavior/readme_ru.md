CDateTimeBehavior
=================

`CDateTimeBehavior` - гибко настраиваемое поведение, позволяющее конвертировать
форматы дат/времени между базой и клиентом.

Installation
------------

1. Скопируйте директорию DateTimeBehavior в директорию `components`

2. Добавьте импорт

~~~php
'import'=>array(

	...

	'application.components.DateTimeBehavior.CDateTimeBehavior',

	...

)
~~~

3. Привяжите поведение к модели

~~~php
public function behaviors()
{
	return array(
		'dateTimeBehavior' => array(
			'class'=>'CDateTimeBehavior',
			'attributes'=>'date_of_birth, passport_expiration',
		),
	);
}
~~~

Паттерны даты/времени
---------------------

Пожалуйста, смотрите по нижеследующей ссылке возможные значения паттернов
http://www.unicode.org/reports/tr35/#Date_Format_Patterns

Свойства
--------

`dbFormat` - формат дат в БД по умолчанию для всех атрибутов. Значение по умолчанию равно `yyyy-MM-dd`.

`clientFormat` - формат дат клиента по умолчанию для всех атрибутов. Значение по умолчанию равно `dd.MM.yyyy`.

`attributes` - какие атрибуты должны обрабатываться, их значения и т.п. (см. примеры ниже)

Методы
------

~~~php
public function getDateTimeAttr($attribute, $format=false);
~~~

Получает значение даты/времени не модифицированным или в указанном во втором параметре формате.
Если атрибут не обрабатывается поведением, то будет возвращено не модифицированное
значение атрибута модели (второй параметр в таком случае игнорируется).

`$attribute` - название атрибута

`$format` - формат, в котором будет возвращена дата/время. Если параметр не указан
или его значение равно `false`, будет возвращено не модифицированное значение.


Примеры использования
---------------------

Простое использование:

~~~php
'dateTimeBehavior' => array(
	'class'=>'CDateTimeBehavior',
	'attributes'=>'date_of_birth, passport_expiration',
),
~~~

В примере выше атрибуты будут конвертированы в форматы по умолчанию.

Можно изменить форматы по умолчанию:

~~~php
'dateTimeBehavior' => array(
	'class'=>'CDateTimeBehavior',
	'dbFormat'=>'dd-MM-yyyy',
	'clientFormat'=>'MM/dd/yyyy',
	'attributes'=>'date_of_birth, passport_expiration',
),
~~~

Сложный пример:

~~~php
'dateTimeBehavior' => array(
	'class'=>'CDateTimeBehavior',
	'attributes' => array(
		'date_start, date_end', //в форматах по умолчанию

		//Атрибут с форматами отличными от форматов по умолчанию
		array(
			'date_created',
			'dbFormat' => 'yyyy-MM-dd HH:mm:ss',
			'clientFormat' => 'dd.MM.yyyy HH:mm:ss'
		),

		array(
			'date_scenario',
			'dbFormat' => 'yyyy-MM-dd',
			'clientFormat' => 'dd.MM.yyyy'
			'on'=>'scenario1, scenario2', //можно указывать сценарии
		),

		array(
			'date_scenario',
			'dbFormat' => 'dd-MM-yyyy',
			'clientFormat' => 'MM/dd/yyyy'
			'on'=>'scenario3', //можно указывать сценарии
		),
	),
),
~~~