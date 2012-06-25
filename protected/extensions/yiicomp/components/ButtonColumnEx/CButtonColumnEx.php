<?php
/**
 * Extended CButtonColumn, that can be used with dynamic templates
 * @author Pelesh Yaroslav aka Tokolist http://tokolist.com
 * @link http://code.google.com/p/yii-components/
 * @version 1.0
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 */

class CButtonColumnEx extends CButtonColumn
{
	public $templateExpression;

	protected function renderDataCellContent($row,$data)
	{
		$tmp = $this->template;

		if($this->templateExpression !== null) {
			$this->template = $this->evaluateExpression($this->templateExpression,array('data'=>$data,'row'=>$row));
		}

		parent::renderDataCellContent($row,$data);

		$this->template = $tmp;
	}

}