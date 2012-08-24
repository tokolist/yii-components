CDateTimeBehavior
=================

`CDateTimeBehavior` - flexible behavior, that automatically converts date attribute values
into specified formats between the client and database.

Installation
------------

1. Copy `DateTimeBehavior` directory to Yii `components` directory

2. Add an import path

~~~php
'import'=>array(

	...

	'application.components.DateTimeBehavior.CDateTimeBehavior',

	...

)
~~~

3. Attach the behavior to your model

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

Date/time format patterns
-------------------------

Please see http://www.unicode.org/reports/tr35/#Date_Format_Patterns for details 
about the recognized pattern characters.

Properties
----------

`dbFormat` - default DB format for each processed attribute. Default value is `yyyy-MM-dd`.

`clientFormat` - default client format for each processed attribute. Default value is `dd.MM.yyyy`.

`attributes` - which attributes should be processed, their formats etc. (please see samples below)

Methods
-------

~~~php
public function getDateTimeAttr($attribute, $format=false);
~~~

Retrieves date/time attribute value in unmodified or in specified in the second parameter format.
If an attribute is not processed by behavior, than model unmodified attribute value is
returned (the second method parameter is ignored).

`$attribute` - name of an attribute

`$format` - date/time format, in which attribute value will be returned. Function returns unmodified
attribute value if this attribute is unspecified or equal to `false`.


Usage samples
-------------

Simple usage:

~~~php
'dateTimeBehavior' => array(
	'class'=>'CDateTimeBehavior',
	'attributes'=>'date_of_birth, passport_expiration',
),
~~~

In the above sample behavior converts attributes with default date formats.

You can change default formats:

~~~php
'dateTimeBehavior' => array(
	'class'=>'CDateTimeBehavior',
	'dbFormat'=>'dd-MM-yyyy',
	'clientFormat'=>'MM/dd/yyyy',
	'attributes'=>'date_of_birth, passport_expiration',
),
~~~

Advanced sample:

~~~php
'dateTimeBehavior' => array(
	'class'=>'CDateTimeBehavior',
	'attributes' => array(
		'date_start, date_end', //default formats

		//Attribute with formats, that differ from default ones
		array(
			'date_created',
			'dbFormat' => 'yyyy-MM-dd HH:mm:ss',
			'clientFormat' => 'dd.MM.yyyy HH:mm:ss'
		),

		array(
			'date_scenario',
			'dbFormat' => 'yyyy-MM-dd',
			'clientFormat' => 'dd.MM.yyyy'
			'on'=>'scenario1, scenario2', //scenarios can be specified
		),

		array(
			'date_scenario',
			'dbFormat' => 'dd-MM-yyyy',
			'clientFormat' => 'MM/dd/yyyy'
			'on'=>'scenario3', //scenarios can be specified
		),
	),
),
~~~