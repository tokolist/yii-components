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

	public function setConfig($config)
	{
		if($this->jevix !== null)
			throw new Exception('Config has been already set. Please create another widget instance.');
		
		$this->jevix = new CJevixEx();
		$this->jevix->loadConfig($config);
	}
	
	public function getErrors()
	{
		return $this->errors;
	}

	public function processOutput($output)
	{
		parent::processOutput($this->parse($output));
	}

	public function parse($content)
	{
		if($this->jevix === null)
			throw new Exception('Please set config before text parsing');

		$this->errors = null;
		return $this->jevix->parse($content, $this->errors);
	}
}