<?php $jevix = $this->beginWidget('ext.yiicomp.widgets.YiiJevix.CYiiJevix', array(
	'config'=>require_once(Yii::getPathOfAlias('application.config') . '/jevix.php'),
)); ?>

Some <script>alert(1)</script> test code: <strong>strong</strong>
<img src="http://hhh=&quot;onclick=&quot;alert(document.cookie)" width="300px" height="300px"/>

<?php $this->endWidget(); ?>

<hr />

<?php print_r($jevix->getErrors()); ?>