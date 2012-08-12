<?php
/**
 * Controller behaviour for tree comments functionality
 * @author Yaroslav Pelesh aka Tokolist http://tokolist.com
 * @link https://github.com/tokolist/yii-components
 * @version 1.0
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 */

class CTreeCommentsControllerBehavior extends CBehavior
{

	public $maxLevel = 20;

	public $hEntryBegin = '<ul class="hentry">';
	public $hEntryEnd = '</ul>';

	public $commentBegin = '<li class="comment" id="comment_{commentId}">';
	public $commentEnd = '</li>';

	public $commentsView = 'application.components.TreeComments.views.comments';
	public $commentView = 'application.components.TreeComments.views.comment';
	public $formView = 'application.components.TreeComments.views.form';
	public $errorsView = 'application.components.TreeComments.views.errors';

	public $scriptFile;

	public $jsAjaxUrl;

	public $jsCommentsSelector = '#comments';
	public $jsReplyLinksSelector = 'a.reply';
	public $jsAddCommentFormSelector = '#add_comment_form';
	public $jsParentIdInputSelector = '#comment_parent_id';
	public $jsHEntrySelector = '.hentry:first';
	public $jsErrorsSelector = '.errorSummary';
	public $jsNoCommentsSelector = '#comment_0 .no_comments';
	public $jsCommentsCountSelector = '.comments_count';

	private function jsonEncode($value)
	{
		return function_exists('json_encode') ? json_encode($value) : CJSON::encode($value);
	}

	public function registerClientScripts()
	{

		Yii::app()->clientScript->registerCoreScript('jquery');
		
		if($this->scriptFile != null)
			$scriptFile = $this->scriptFile;
		else
			$scriptFile = Yii::app()->assetManager->publish(dirname(__FILE__).'/js/comments_tree.js');

		Yii::app()->clientScript->registerScriptFile($scriptFile);

		if($this->jsAjaxUrl != null)
			$ajaxUrl = $this->jsAjaxUrl;
		else
			$ajaxUrl = Yii::app()->request->requestUri;

		$jsOptions = array(
			'commentsSelector' => $this->jsCommentsSelector,
			'replyLinksSelector' => $this->jsReplyLinksSelector,
			'addCommentFormSelector' => $this->jsAddCommentFormSelector,
			'parentIdInputSelector' => $this->jsParentIdInputSelector,
			'hEntrySelector' => $this->jsHEntrySelector,
			'errorsSelector' => $this->jsErrorsSelector,
			'ajaxUrl' => $ajaxUrl,
			'hEntryBegin' => $this->hEntryBegin,
			'hEntryEnd' => $this->hEntryEnd,
			'noCommentsSelector' => $this->jsNoCommentsSelector,
			'commentsCountSelector' => $this->jsCommentsCountSelector,
		);

		$script = '$().treeComments(' . $this->jsonEncode($jsOptions) . ');';

		Yii::app()->clientScript->registerScript('TreeComments', $script, CClientScript::POS_READY);
	}

	public function renderComments($model, $targetType, $targetId, $viewData = array(), &$commentsCount=null)
	{
		$html = '';

		$behaviorOwner = $this->getOwner();

		$comments = $model->getComments($targetType, $targetId);
		$commentsCount = count($comments);

		if($commentsCount > 0)
		{
			$html .= $this->hEntryBegin;

			$previousLevel = 0;

			foreach($comments as $index => $comment)
			{
				$level = min($comment->getAttribute($model->commentsLevelField), $this->maxLevel);


				if($index > 0)
				{
					if($level > $previousLevel)
					{
						$html .= $this->hEntryBegin;
					}
					elseif($level == $previousLevel)
					{
						$html .= $this->commentEnd;
					}
					else // $level < $previousLevel
					{
						$html .= str_repeat($this->commentEnd . $this->hEntryEnd, $previousLevel - $level);
						$html .= $this->commentEnd;
					}
				}

				$html .= strtr($this->commentBegin, array('{commentId}'=>$comment->id));

				$html .= $behaviorOwner->renderPartial(
					$this->commentView,
					array_merge(array('model' => $comment),	$viewData),
					true
				);

				$previousLevel = $level;
			}

			$html .= str_repeat($this->commentEnd . $this->hEntryEnd, $level + 1);
		}

		return $html;
	}

	public function renderCommentsForm($model, $viewData = array())
	{
		$html = '';

		$behaviorOwner = $this->getOwner();

		$html .= $behaviorOwner->renderPartial(
			$this->formView,
			array_merge(array('model' => $model), $viewData),
			true
		);

		return $html;
	}

	public function renderCommentsFull($model, $targetType, $targetId, $viewData = array())
	{
		$html = '';

		$behaviorOwner = $this->getOwner();

		$this->registerClientScripts();

		//$commentsCount

		$comments = $this->renderComments($model, $targetType, $targetId, $viewData, $commentsCount);
		$form = $this->renderCommentsForm($model, $viewData);
		
		$html .= $behaviorOwner->renderPartial(
			$this->commentsView,
			array_merge(
				array(
					'model' => $model, 
					'comments'=>$comments,
					'form'=>$form,
					'commentsCount'=>$commentsCount,
				), 
				$viewData
			),
			true
		);

		return $html;
	}

	public function renderAjaxAddCommentResponse($model, $viewData = array())
	{
		$behaviorOwner = $this->getOwner();

		$response = array();
		$response['validated'] = !$model->hasErrors();

		if($response['validated'])
		{
			$commentHtml = $behaviorOwner->renderPartial(
				$this->commentView,
				array_merge(array('model' => $model), $viewData),
				true
			);

			$response['html'] = strtr($this->commentBegin, array('{commentId}'=>$model->id))
				. $commentHtml . $this->commentEnd;
		}
		else
		{
			$response['html'] = $behaviorOwner->renderPartial(
				$this->errorsView,
				array_merge(array('model' => $model), $viewData),
				true
			);
		}

		return $this->jsonEncode($response);
	}

}