CButtonColumnEx
===============

`CButtonColumnEx` - extended CButtonColumn, in which template and button options 
can be php expressions.

Installation
------------

1. Copy ButtonColumnEx directory to Yii `components` directory

2. Add import path

~~~php
'import'=>array(

    ...

    'application.components.ButtonColumnEx.CButtonColumnEx',

    ...

)
~~~

3. Use component in CGridView columns configuration

~~~php
'columns'=>array(

	...

	array(
		'class' => 'CButtonColumnEx',
		'template' => '"{view}" . ($data->isCancelable() ? " / {cancel}" : "")',
		'buttons' => array(
			'cancel' => array(

				...

				'options' => array(
					'data-title'=>'$data->title',
				),

				...

			),
		),
	),

	...

),
~~~