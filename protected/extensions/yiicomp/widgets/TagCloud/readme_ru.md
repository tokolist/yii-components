CTagCloud
=========

`CTagCloud` - простое облако тегов.

Installation
------------

1. Скопируйте директорию `TagCloud` в директорию `components`

2. Добавьте импорт

~~~php
'import'=>array(

    ...

    'application.components.TagCloud.CTagCloud',

    ...

)
~~~

3. Используйте виджет следующим образом

~~~php
<?php $this->widget('CTagCloud', array(
	'maxTags'=>false,
	'urlRoute'=>'tagCloud/item'
)); ?>
~~~

Properties
----------

`maxTags` - максимальное количество тегов для отображения

`distribution` - тип распределения. Значение по умолчанию `self::DISTRIBUTION_LOGARITHMIC`.
Возможные значения:

* `DISTRIBUTION_LINEAR`

* `DISTRIBUTION_LOGARITHMIC` - рекомендуется этот вариант, поскольку облако выглядит более плавным

`tagTable` - таблица, использующаяся для хранения тегов

`tagTableName` - название поля, соответствующего названию тега в таблице `tagTable`

`tagTableCount` - название поля, соответствующего количеству тегов в таблице `tagTable`

`urlRoute` - роут, используемый для генерации URL

`urlParamName` - URL GET параметр, который соответствует названию тега

`tagClasses` - названия классов тегов в возрастающем порядке

`linkOptions` - свойства ссылки тега

`itemTemplate` - шаблон тега. Значение по умолчанию `{link}\n`