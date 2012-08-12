<?php
/**
 * Jevix wrapper for Yii framework
 * @author Yaroslav Pelesh aka Tokolist http://tokolist.com
 * @link https://github.com/tokolist/yii-components
 * @version 1.0
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 */

require_once(dirname(__FILE__).'/CJevixEx.php');


class CYiiJevix extends COutputProcessor
{
	/*
	 * CJevixEx instance
	 * @var CJevixEx
	 */
	protected $jevix=null;

	/*
	 * Parse errors (read only)
	 * @var array
	 */
	protected $errors=null;

	public function __set($name, $value) {
		if($name == 'config')
			$this->setConfig($value);
	}

	public function __get($name) {
		if($name == 'errors')
			return $this->errors;
	}

	public function setConfig($config)
	{
		$this->jevix = new CJevixEx();
		$this->jevix->loadConfig($config);
	}

	public function processOutput($output)
	{
		$output=$this->parse($output);
		parent::processOutput($output);
	}

	public function parse($content)
	{
		if($this->jevix === null)
			throw new Exception('Before parse text set config');

		$this->errors = null;
		return $this->jevix->parse($content, $this->errors);
	}
}