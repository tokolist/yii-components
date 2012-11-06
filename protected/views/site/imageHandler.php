<h1>ImageHandler</h1>
<h2>resizeCanvas</h2>

<table>
	<tr>
		<td><?php echo CHtml::image(Yii::app()->createUrl('imageHandler/resize1')); ?></td>
		<td><?php echo CHtml::image(Yii::app()->createUrl('imageHandler/resize2')); ?></td>
	</tr>
</table>


<h2>grayscale</h2>

<table>
	<tr>
		<td><?php echo CHtml::image(Yii::app()->createUrl('imageHandler/grayscale')); ?></td>
	</tr>
</table>

<h2>reload</h2>

<table>
	<tr>
		<td><?php echo CHtml::image('/images/image_handler/reload_test/img_thumb50x50.jpg'); ?></td>
		<td><?php echo CHtml::image('/images/image_handler/reload_test/img_thumb100x100_rotate.jpg'); ?></td>
		<td><?php echo CHtml::image('/images/image_handler/reload_test/img_thumb100x100_bg.jpg'); ?></td>
		<td><?php echo CHtml::image('/images/image_handler/reload_test/img_thumb100x100.jpg'); ?></td>
	</tr>
</table>

<h2>PNG with alpha chanel</h2>

<table>
	<tr>
		<td><?php echo CHtml::image(Yii::app()->createUrl('imageHandler/pngAlpha')); ?></td>
	</tr>
</table>

<h2>Fill background</h2>

<table>
	<tr>
		<td><?php echo CHtml::image(Yii::app()->createUrl('imageHandler/fillBg')); ?></td>
	</tr>
</table>

<h2>Watermark</h2>

<table>
	<tr>
		<td><?php echo CHtml::image(Yii::app()->createUrl('imageHandler/watermark')); ?></td>
	</tr>
</table>