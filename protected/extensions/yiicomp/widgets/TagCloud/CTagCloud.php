<?php
/**
 * Simple tag cloud widget
 * @author Yaroslav Pelesh aka Tokolist http://tokolist.com
 * @link https://github.com/tokolist/yii-components
 * @version 1.1
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 */

class CTagCloud extends CWidget
{
    const DISTRIBUTION_LINEAR = 0;
	const DISTRIBUTION_LOGARITHMIC = 1;
	
	public $maxTags = 30;

	public $distribution = self::DISTRIBUTION_LOGARITHMIC;

	public $tagTable = 'tags';
	public $tagTableName = 'tag';
	public $tagTableCount = 'count';

	public $urlRoute = 'site/index';
	public $urlParamName = 'tag';

	public $tagClasses = array('tag12', 'tag13', 'tag14', 'tag15', 'tag16', 'tag17', 'tag18');

	public $linkOptions = array();

	public $itemTemplate = "{link}\n";


	protected function getLinearDistribution($rows, $maxCount, $minCount, $classesCount)
	{
		$tags = array();
		$countDiff = $maxCount - $minCount;

		$minCount--; $countDiff++; //Prevent zero division

		$classesCount--;

		foreach($rows as $row)
		{
			$tags[$row[$this->tagTableName]] = $this->tagClasses[
				floor(($row[$this->tagTableCount] - $minCount) / $countDiff * $classesCount)
			];
		}

		return $tags;
	}

	protected function getLogarithmicDistribution($rows, $maxCount, $minCount, $classesCount)
	{
		$tags = array();

		$minCount++;
		$maxCount++;

		$countDiff = log($maxCount) - log($minCount);

		$classesCount--;

		$minCount = log($minCount);

		foreach($rows as $row)
		{
			$tags[$row[$this->tagTableName]] = $this->tagClasses[
				floor((log($row[$this->tagTableCount] + 1) - $minCount) / $countDiff * $classesCount)
			];
		}

		return $tags;
	}

	private function ciStringCompare($a, $b)
	{
		return strtolower($a) > strtolower($b);
	}

	public function run()
    {
        $sql = "SELECT * FROM {$this->tagTable} ORDER BY {$this->tagTableCount} DESC";

		if($this->maxTags !== false)
			$sql .= " LIMIT {$this->maxTags}";

		$command = Yii::app()->db->createCommand($sql);
		$rows = $command->queryAll();

		if(!empty($rows))
		{
			$minCount = $rows[count($rows) - 1][$this->tagTableCount];
			$maxCount = $rows[0][$this->tagTableCount];

			$classesCount = count($this->tagClasses);


			switch($this->distribution)
			{
				case self::DISTRIBUTION_LINEAR:
					$tags = $this->getLinearDistribution($rows, $maxCount, $minCount, $classesCount);
					break;
				default: //DISTRIBUTION_LOGARITHMIC
					$tags = $this->getLogarithmicDistribution($rows, $maxCount, $minCount, $classesCount);
					break;
			}

			//ksort($tags, SORT_LOCALE_STRING);
			uksort($tags, array($this, 'ciStringCompare'));

			foreach($tags as $tag => $class)
			{
				$link = CHtml::link(
					CHtml::encode($tag),
					array($this->urlRoute, $this->urlParamName=>$tag),
					array_merge($this->linkOptions, array('class'=>$class))
				);

				echo strtr($this->itemTemplate, array('{link}'=>$link));
			}
		}
	}
}
