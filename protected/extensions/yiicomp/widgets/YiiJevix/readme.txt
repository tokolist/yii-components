Config example:

array(
	'allowTags'=>array('a', 'img', 'i', 'b', 'u', 'em', 'strong', 'nobr', 'li', 'ol', 'ul', 'sup', 'abbr', 'pre', 'acronym', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'adabracut', 'br', 'code'),
	'tagShort'=>array('br','img'),
	'tagPreformatted'=>array('pre'),
	'tagCutWithContent'=>array('script', 'object', 'iframe', 'style'),
	'allowTagParams'=>array(
		'a'=>array('title', 'href', 'rel'),
		'img'=>array('src', 'alt' => '#text', 'title', 'align' => array('right', 'left', 'center'), 'width' => '#int', 'height' => '#int', 'hspace' => '#int', 'vspace' => '#int'),
	),
	'tagParamsRequired'=>array(
		'img'=>'src',
		'a'=>'href',
	),

	'tagChilds'=>array(
		'ul'=>array('childs'=>'li', 'isContainerOnly'=>true, 'isChildOnly'=>false),
	),

	'tagParamDefault'=>array(
		array(
			'tag'=>'a',
			'param'=>'rel',
			'value'=>'test',
			'isRewrite'=>true,
		),

		array(
			'tag'=>'img',
			'param'=>'width',
			'value'=>'300',
		),

		array(
			'tag'=>'img',
			'param'=>'height',
			'value'=>'300',
		),
	),


	'autoReplace'=>array(
		'+/-'=>'±',
		'(c)'=>'©',
		'(r)'=>'®',
	),

	'XHTMLMode'=>true,
	'autoBrMode'=>true,
	'autoLinkMode'=>true,
	'tagNoTypography'=>'code',
),