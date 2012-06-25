<?php if(!$model->deleted): ?>
	<ul class="comment_meta">
		<li class="avatar"><?php echo CHtml::image($model->user->getAvatarUrl()); ?></li>
		<li class="user_name"><a href="#"><?php echo $model->user->login; ?></a></li>
		<li class="date"><?php echo date('d.m.Y H:i:s', strtotime($model->date)); ?></li>
		<li class="anchor"><?php echo CHtml::link('#', '#comment_' . $model->id); ?></li>
	</ul>

	<div class="comment_text"><?php echo CHtml::encode($model->comment); ?></div>

	<?php if($canPostComments): ?>
		<ul class="comment_control">
			<li class="reply">
				<?php echo CHtml::link(Yii::t('comments', 'Reply'), '#', array(
					'class'=>'reply',
					'rel'=>'comment_' . $model->id)
				); ?>
			</li>
		</ul>
	<?php endif; ?>
<?php else: ?>
	<p class="comment_deleted"><?php echo Yii::t('comments', 'This comment was deleted'); ?></p>
<?php endif; ?>