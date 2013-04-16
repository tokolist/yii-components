CLangUrlManager
=============

`CLangUrlManager` - this extension allows you to pass the application language 
via the URL and then use it for all further generated URLs.

Installation
------------

1. Copy LangUrlManager directory to Yii `components` directory

2. Add import path

~~~php
'import'=>array(

    ...

    'application.components.LangUrlManager.CLangUrlManager',

    ...

)
~~~

3. Add following code to `config/main.php` or merge with your current URL Manager
settings

~~~php
'components'=>array(
    ...

	'urlManager'=>array(
		'class'=>'application.components.LangUrlManager.CLangUrlManager', //specify component class
		'languages'=>array('en','ru'), //available languages
		'langParam'=>'lang', //GET parameter name used for language definition
		'langCookieName'=>'yii_lang', //cookie name used for storing language

		'urlFormat'=>'path',
		'showScriptName'=>false,

		//add lang parameter to the rules if necessary
		'rules'=>array(
			...
			
			...
		),
	),

    ...
)
~~~

4. Add the following code to your protected/components/Controller.php init method

~~~php
public function init()
{
	parent::init();
	Yii::app()->urlManager->setAppLanguage();
}
~~~