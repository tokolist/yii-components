CButtonColumnEx
===============

`CButtonColumnEx` - потомок CButtonColumn, в котором свойство template и свойства options 
кнопок могут быть php-выражениями.

Подключение
-----------

1. Скопируйте папку ButtonColumnEx в папку `components`

2. Добавте путь в импорт

~~~php
'import'=>array(

    ...

    'application.components.ButtonColumnEx.CButtonColumnEx',

    ...

)
~~~

3. Используйте компонент в конфигурации CGridView

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