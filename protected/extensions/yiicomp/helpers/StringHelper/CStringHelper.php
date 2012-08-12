<?php
/**
 * CStringHelper
 * @author Yaroslav Pelesh aka Tokolist http://tokolist.com
 * @link https://github.com/tokolist/yii-components
 * @version 1.0
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 */

class CStringHelper
{
	public static $unicode = true;
	public static $subjectChanged = false;

	public static function breakLongWords($subject, $maxLength=20, $break="\r\n")
	{
		$pattern = '/([^\s]{'.$maxLength.'})(?=[^\s])/m';
		if(self::$unicode)
			$pattern .= 'u';
		
		return preg_replace($pattern, "$1$break", $subject);
	}

	public static function arrayEncode($subject)
	{
		if(!is_array($subject))
			return htmlspecialchars($subject);

		return array_map(array('self','arrayEncode'), $subject);
	}

	public static function arrayTrim($subject)
	{
		if(!is_array($subject))
			return trim($subject);

		return array_map(array('self','arrayTrim'), $subject);
	}

	public static function cutLongString($subject, $maxChars, $trimChars=" \t\n\r\0\x0B")
	{
		self::$subjectChanged = false;

		if(strlen($subject) > $maxChars)
		{
			$subject = substr($subject, 0, $maxChars);
			$subject = rtrim($subject, $trimChars);

			self::$subjectChanged = true;
		}

		return $subject;
	}

	public static function cutStringToWords($subject, $maxWordsCount, $trimChars=" \t\n\r\0\x0B")
	{
		self::$subjectChanged = false;

		$pattern = '/([^\s\n\r]+[\s\n\r]+){' . $maxWordsCount . '}/s';
		if(self::$unicode)
			$pattern .= 'u';
		
		if(preg_match($pattern, $subject, $match))
		{
			$subject = rtrim($match[0], $trimChars);

			self::$subjectChanged = true;
		}

		return $subject;
	}

	public static function stringFormat($subject, $maxChars, $maxWordLength, $encode=true, $ellipsis='&hellip;', $wordBreak='&shy;')
	{
		$subject = self::cutLongString($subject, $maxChars, $trimChars=" \t\n\r\0\x0B.,");
		$stringCut = self::$subjectChanged;

		$subject = self::breakLongWords($subject, $maxWordLength);

		if($encode)
			$subject = htmlspecialchars($subject);
		
		$subject = str_replace("\r\n", $wordBreak, $subject);

		if($stringCut)
			$subject .= $ellipsis;

		return $subject;
	}

	public static function simpleTextFormat($subject)
	{
		$subject = trim($subject);
		$subject = preg_replace('~([^\r\n]+?)([\r\n]+|$)~', '<p>$1</p>', $subject);
		return $subject;
	}

	public static function randomString($length=8, $chars=false)
	{
		if($chars === false)
			$chars = "0123456789AaBbCcDdEeFfGgHhIiJjKkLlMmNnOoPpQqRrSsTtUuVvWwXxYyZz";
		
		srand((double)microtime()*1000000);
		$i = 1;
		$result = '';
		while($i <= $length)
		{
			$num = rand() % strlen($chars);
			$tmp = substr($chars, $num, 1);
			$result = $result . $tmp;
			$i++;
		}
		return $result;
	}

	public static function wordwrap($str, $width=75, $break="\n", $cut=false)
	{
		//Fix multiple spaces
		$str = preg_replace('/ +/', ' ', $str);

		//Standardize line breaks
		$str = str_replace(array("\r\n", "\r"), "\n", $str);

		
		$lines=explode($break, $str);
		$result = array();
		if($cut)
		{
			$pattern = '/.{'.$width.'}/';

			if(self::$unicode)
				$pattern .= 'u';

			foreach($lines as $line)
			{
				if(strlen($line) > $width)
					$result[]=preg_replace($pattern, "$0$break", $line);
			}
		}
		else
		{
			foreach($lines as $line)
			{
				if(strlen($line) > $width)
				{
					$line=self::breakLongWords($line, $width, ' ');

					

					$currentLine='';
					foreach(explode(' ', $line) as $word)
					{
						if(strlen($currentLine.$word) > $width)
						{
							$result[]=rtrim($currentLine);
							$currentLine='';
						}

						$currentLine .= $word . ' ';
					}

					if($currentLine!='')
						$result[]=rtrim($currentLine);

					
				}
				else
				{
					$result[]=$line;
				}
			}
		}

		return implode($break, $result);
	}

	public static function smartReSubject($subject)
	{
		$subject=trim($subject);

		//Has Re
		$pattern='/^Re(\[(\d+)\])?:/';

		if(self::$unicode)
			$pattern .= 'u';

		if(preg_match($pattern, $subject, $matches))
		{
			$number=intval($matches[2]);

			if(empty($number))
				$number=1;

			$number++;

			return preg_replace($pattern, "Re[$number]:", $subject);
		}

		//No Re's
		return 'Re: '.$subject;
	}

	public static function preserveTags()
	{
		//TODO
	}

	public static function restoreTags()
	{
		//TODO
	}

	public static function closeTags()
	{
		//TODO
	}
}