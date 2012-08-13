CYiiJevix
=========

Jevix wrapper. Jevix is autotypographing and HTML/XHTML markup text filtering
system.

http://code.google.com/p/jevix/

Installation
------------

1. Copy YiiJevix directory to Yii `components` directory

2. Add import path

~~~php
'import'=>array(

    ...

    'application.components.YiiJevix.CYiiJevix',

    ...

)
~~~

3. Use widget in following way

~~~php
<?php $this->beginWidget('CYiiJevix', array(
	'config'=>require_once(Yii::getPathOfAlias('application.config') . '/jevix.php'),
)); ?>

...

<?php $this->endWidget(); ?>
~~~

Parsing errors
--------------

If there are some parsing errors, you can get them from `errors` property

~~~php
$jevix = $this->beginWidget('CYiiJevix' ... );

...

print_r($jevix->errors);
~~~

Configuration sample
--------------------

You can find configuration sample in `config.sample.php` file. Also please see
`jevix/tests/jevixtest.php` file with method descriptions and samples (ru).