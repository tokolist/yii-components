<div id="comments">
	<h3><?php echo Yii::t('comments', 'Comments ({count})', array(
		'{count}'=>'<span class="comments_count">' . $commentsCount . '</span>',
	)); ?></h3>

	<div id="comment_0">
		<?php if($commentsCount > 0): ?>
			<?php echo $comments; ?>
		<?php else: ?>
			<p class="no_comments"><?php echo Yii::t('comments', 'No comments yet'); ?></p>
		<?php endif; ?>
		
		<?php echo $form; ?>
	</div>
</div>