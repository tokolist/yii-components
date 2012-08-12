<?php
/**
 * Extended CButtonColumn, in which template and button options can be php expressions.
 * @author Yaroslav Pelesh aka Tokolist http://tokolist.com
 * @link https://github.com/tokolist/yii-components
 * @version 1.2
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 */

class CButtonColumnEx extends CButtonColumn
{
	protected function renderDataCellContent($row,$data)
	{
		$tmpTemplate = $this->template;
		$tmpButtons = $this->buttons;

		$this->template = $this->evaluateExpression($this->template,array('data'=>$data,'row'=>$row));
		
		foreach($this->buttons as $buttonIndex => $button)
		{
			foreach($button['options'] as $optionIndex => $option)
			{
				$this->buttons[$buttonIndex]['options'][$optionIndex] = $this->evaluateExpression(
					$this->buttons[$buttonIndex]['options'][$optionIndex],
					array('data'=>$data,'row'=>$row)
				);
			}
		}

		parent::renderDataCellContent($row,$data);

		$this->template = $tmpTemplate;
		$this->buttons = $tmpButtons;
	}

}