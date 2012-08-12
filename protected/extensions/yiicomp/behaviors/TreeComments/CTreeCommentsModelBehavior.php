<?php
/**
 * Active Record behaviour for tree comments functionality
 * @author Yaroslav Pelesh aka Tokolist http://tokolist.com
 * @link https://github.com/tokolist/yii-components
 * @version 1.0
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 */

class CTreeCommentsModelBehavior extends CActiveRecordBehavior
{
	public $commentsPkField = 'id';
	public $commentsTargetField = 'target_id';
	public $commentsTargetTypeField = 'target_type';
	public $commentsParentField = 'parent';
	public $commentsOrderField = 'order';
	public $commentsLevelField = 'level';



    function saveComment()
	{
		$owner = $this->getOwner();
		

		if ($owner->isNewRecord)
		{
			$tableName = $owner->tableName();
			$targetId = intval($owner->getAttribute($this->commentsTargetField));
			$parentId = intval($owner->getAttribute($this->commentsParentField));

			
			$sql = "SELECT COUNT(`{$this->commentsPkField}`) FROM `{$tableName}` WHERE "
				. "`{$this->commentsTargetField}`='{$targetId}' "
				. "AND `{$this->commentsParentField}`='{$parentId}'";

			$count = Yii::app()->db->createCommand($sql)->queryScalar();

			$parentOrder = '';
			if ($parentId > 0)
			{
				$sql = "SELECT `{$this->commentsOrderField}` FROM `{$tableName}` WHERE "
					. "`{$this->commentsPkField}`='{$parentId}'";
					
				$parentOrder = Yii::app()->db->createCommand($sql)->queryScalar();
			}

			$order = $parentOrder . str_pad($count+1, 5, '0', STR_PAD_LEFT);
			$owner->setAttribute($this->commentsOrderField, $order);
			$owner->setAttribute($this->commentsLevelField, strlen($order)/5 - 1);
		}


		return $owner->save();
    }


    function getComments($targetType, $targetId)
	{
		$owner = $this->getOwner();

		$criteria = new CDbCriteria();
		$criteria->compare($this->commentsTargetField, $targetId);
		$criteria->compare($this->commentsTargetTypeField, $targetType);
		$criteria->order = "`{$this->commentsOrderField}` ASC";

		return $owner->findAll($criteria);
    }

	function getCommentsCount($targetType, $targetId)
	{
		//TODO comments count
	}

}