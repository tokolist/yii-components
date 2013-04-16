<?php
/**
 * This extension allows you to pass the application language via the URL
 * and then use it for all further generated URLs
 * @author Ekstazi http://yii-blog.blogspot.com
 * @author Yaroslav Pelesh aka Tokolist http://tokolist.com
 * @link https://github.com/tokolist/yii-components
 * @version 1.0
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 */

class CLangUrlManager extends CUrlManager
{
    public $languages=array('en');
    public $langParam='lang';
	public $langCookieName=false;
	public $langCookieDomain=false;

	protected function getCookieLang()
	{
		$cookieLang = false;

		if(!empty($this->langCookieName))
		{
			$cookies = Yii::app()->getRequest()->getCookies();
			
			if(isset($cookies[$this->langCookieName]))
			{
				$cookieLang = $cookies[$this->langCookieName]->value;
			}
		}
		
		return $cookieLang;
	}

	protected function setCookieLang()
	{
		if(!empty($this->langCookieName))
		{
			$cookies = Yii::app()->getRequest()->getCookies();

			if(!isset($cookies[$this->langCookieName]) 
				|| $cookies[$this->langCookieName]->value != Yii::app()->language)
			{
				$cookie = new CHttpCookie($this->langCookieName,Yii::app()->language);
				$cookie->expire = time() + 60*60*24*365; //1 year

				if(!empty($this->langCookieDomain))
					$cookie->domain = $this->langCookieDomain;

				$cookies[$this->langCookieName] = $cookie;
			}
		}
	}

	protected function getPreferredLang()
	{
		return Yii::app()->getRequest()->getPreferredLanguage();
	}

	public function setAppLanguage()
    {
        //If language pass via url use it
        if(isset($_GET[$this->langParam]) && in_array($_GET[$this->langParam],$this->languages))
		{
            Yii::app()->language = $_GET[$this->langParam];
        }
		//Else if lang cookie is setted
		elseif(in_array($cookieLang=$this->getCookieLang(),$this->languages))
		{
			Yii::app()->language = $cookieLang;
		}
		//Else if preffered language is allowed
		elseif(in_array($prefferedLang=$this->getPreferredLang(),$this->languages))
		{
            Yii::app()->language = $prefferedLang;
        }
		//Else use the first language from the list
		else
		{
			Yii::app()->language = $this->languages[0];
		}

		//Remember lang in cookies
		$this->setCookieLang();
    }

    public function createUrl($route, $params=array(), $ampersand='&')
    {
        if(!isset($params[$this->langParam]))
        {
        	$params[$this->langParam]=Yii::app()->language;
        }

        return parent::createUrl($route,$params,$ampersand);
    }

    public function createLangUrl($language, $ampersand='&')
    {
    	$route = Yii::app()->controller->route;
    	$params = $_GET;
    	unset($params[$this->routeVar]);
        $params[$this->langParam] = $language;
    	return parent::createUrl($route,$params,$ampersand);
    }

}