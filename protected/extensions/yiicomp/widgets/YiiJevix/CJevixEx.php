<?php
/**
 * Jevix wrapper for Yii framework
 * @author Yaroslav Pelesh aka Tokolist http://tokolist.com
 * @link https://github.com/tokolist/yii-components
 * @version 1.0
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 */

require_once(dirname(__FILE__).'/jevix/jevix.class.php');


class CJevixEx extends Jevix
{
	/*
	 * Loads Jevix config as an array
	 * @param array $config Config array
	 */
	public function loadConfig($config)
	{
		if(!is_array($config))
			throw new Exception('Config must be an array');

		foreach($config as $paramName=>$paramValue)
		{
			switch($paramName)
			{
				case 'allowTags':
					$this->cfgAllowTags($paramValue);
					break;

				case 'tagShort':
					$this->cfgSetTagShort($paramValue);
					break;

				case 'tagPreformatted':
					$this->cfgSetTagPreformatted($paramValue);
					break;

				case 'tagNoTypography':
					$this->cfgSetTagNoTypography($paramValue);
					break;

				case 'tagIsEmpty':
					$this->cfgSetTagIsEmpty($paramValue);
					break;

				case 'tagNoAutoBr':
					$this->cfgSetTagNoAutoBr($paramValue);
					break;

				case 'tagCutWithContent':
					$this->cfgSetTagCutWithContent($paramValue);
					break;

				case 'allowTagParams':
					foreach($paramValue as $tag=>$attributes)
					{
						$this->cfgAllowTagParams($tag, $attributes);
					}
					break;

				case 'tagParamsRequired':
					foreach($paramValue as $tag=>$attributes)
					{
						$this->cfgSetTagParamsRequired($tag, $attributes);
					}
					break;

				case 'tagChilds':
					foreach($paramValue as $tag=>$funcParams)
					{
						$this->cfgSetTagChilds(
							$tag, 
							$funcParams['childs'], 
							isset($funcParams['isContainerOnly']) ? $funcParams['isContainerOnly'] : false,
							isset($funcParams['isChildOnly']) ? $funcParams['isChildOnly'] : false
						);
					}
					break;

				case 'tagParamDefault':
					foreach($paramValue as $funcParams)
					{
						$this->cfgSetTagParamDefault(
							$funcParams['tag'],
							$funcParams['param'],
							$funcParams['value'],
							isset($funcParams['isRewrite']) ? $funcParams['isRewrite'] : false
						);
					}
					break;

				case 'autoReplace':
					$this->cfgSetAutoReplace(array_keys($paramValue), array_values($paramValue));
					break;

				case 'XHTMLMode':
					$this->cfgSetXHTMLMode($paramValue);
					break;

				case 'autoBrMode':
					$this->cfgSetAutoBrMode($paramValue);
					break;

				case 'autoLinkMode':
					$this->cfgSetAutoLinkMode($paramValue);
					break;
			}
		}
	}
}