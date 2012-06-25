<?php if($canPostComments): ?>
	<h3>
		<?php
			echo CHtml::link(Yii::t('comments', 'Post new comment'), '#', array(
				'class'=>'reply',
				'rel'=>'comment_0',
			));
		?>
	</h3>

	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'add_comment_form',
	)); ?>

	<?php echo $form->errorSummary($model); ?>
	<?php echo $form->hiddenField($model,'parent',array('id'=>'comment_parent_id')); ?>
	<p><?php echo $form->textArea($model,'comment',array('rows'=>6, 'cols'=>50)); ?></p>
	<p><?php echo CHtml::submitButton(Yii::t('comments', 'Add comment')); ?></p>

	<?php $this->endWidget(); ?>
<?php else: ?>
	<p><?php echo Yii::t('comments', 'Only authorized users can post comments. Please <a href="{loginLink}">login</a> or <a href="{registerLink}">register</a>.'); ?></p>
<?php endif; ?>
