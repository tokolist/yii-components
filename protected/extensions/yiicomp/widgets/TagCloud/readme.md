CTagCloud
=========

`CTagCloud` - simple tag cloud widget.

Installation
------------

1. Copy `TagCloud` directory to Yii `components` directory

2. Add import path

~~~php
'import'=>array(

    ...

    'application.components.TagCloud.CTagCloud',

    ...

)
~~~

3. Use widget in the following way

~~~php
<?php $this->widget('CTagCloud', array(
	'maxTags'=>false,
	'urlRoute'=>'tagCloud/item'
)); ?>
~~~

Properties
----------

`maxTags` - maximum tags number to display

`distribution` - distribution type. Default value is `self::DISTRIBUTION_LOGARITHMIC`.
Possible values:

* `DISTRIBUTION_LINEAR`

* `DISTRIBUTION_LOGARITHMIC` - this option is recommended, since tag cloud looks more smooth

`tagTable` - database table used for storing tags

`tagTableName` - tag name field in specified `tagTable` table

`tagTableCount` - tags count field in specified `tagTable` table

`urlRoute` - route used for generating URLs

`urlParamName` - URL GET parameter, which corresponds to tag name

`tagClasses` - tag class names in ascending order

`linkOptions` - tag link options

`itemTemplate` - item template. Default value is `{link}\n`