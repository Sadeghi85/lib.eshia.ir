<?php

class Helpers {

	private static $_xmlObject = null;
	private static $_persianizedXMLObject = null;
	
	private static $_navigationTabs = array(array(array('selected' => false, 'path' => '', 'group' => '')));
	private static $_navigationSegments = null;
	
	private static $_exceptionErrorMessage = null;
	
	public static function getExceptionErrorMessage()
	{
		return self::$_exceptionErrorMessage;
	}
	
	public static function setExceptionErrorMessage($message)
	{
		self::$_exceptionErrorMessage = $message;
	}
	
	public static function getCacheableFiles()
	{
		$files = array(
			base_path() . Config::get('app_settings.xml_path'),
		);
		
		$pathes = array(
			public_path() . '/views/',
			app_path() . '/lang/',
		);
		
		foreach ($pathes as $path)
		{
			$Directory = new RecursiveDirectoryIterator($path);
			$Iterator = new RecursiveIteratorIterator($Directory);
			$Items = new RegexIterator($Iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);
			
			$files = array_merge($files, array_flatten(iterator_to_array($Items)));
		}
		
		return $files;
	}
	
	public static function getModifiedDateHash(array $files)
	{
		$solidString = '';
		
		clearstatcache();
		
		foreach ($files as $file)
		{
			$solidString .= filemtime($file);
		}
		
		return sha1($solidString);
	}
	
	public static function persianizeString($string)
	{
		$string = str_replace(pack('H*', 'efbbbf'), '', $string);
		$string = str_replace(pack('H*', 'c2a0'), '', $string);
		$string = str_replace(pack('H*', 'd980'), '', $string); # مـزمل
		$string = str_replace(array(pack('H*', 'd98a'), pack('H*', 'd989'), pack('H*', 'd8a6')), pack('H*', 'db8c'), $string); # ی
		$string = str_replace(array(pack('H*', 'd8a5'), pack('H*', 'd8a3'), pack('H*', 'd8a2')), pack('H*', 'd8a7'), $string); # ا
		$string = str_replace(array(pack('H*', 'd8a9'), pack('H*', 'db80')), pack('H*', 'd987'), $string); # ه
		$string = str_replace(pack('H*', 'd8a4'), pack('H*', 'd988'), $string); # و
		$string = str_replace(pack('H*', 'd983'), pack('H*', 'daa9'), $string); # ک
		
		$string = preg_replace('#[[:space:]]+#iu', ' ', $string);
		$string = preg_replace('#\p{Cf}+#iu', ' ', $string); # zwnj, etc.;
		$string = preg_replace('#\p{M}+#iu', '', $string);
		
		return trim($string);
	}
	
	/**
	 * psort : Persian array sorting function
	 * 
	 * 
	 * Author : AHHP(Amir Hossein Hodjaty Pour) ~ Boplo@Boplo.ir
	 * Modified by : Sadeghi85
	 * License : GPL
	 * Version : 1
	 * Created on        : 1388/02/29     03:09am
	 * Modified on       : 1391/11/05     02:53pm
	 * Modified again on : 1393/04/09     05:15pm
	 * 
	 * @param array $inputArray Input array for Persian sorting.
	 * @param string $function Function name for sorting pattern.
	 * @return array Sorted array.
	 */
	public static function psort($inputArray, $function = 'asort')
	{
		$converted = $result = array();
		
		$alphabet = array(
			'~A~' => pack('H*', 'd9a0'),	'~B~' => pack('H*', 'd9a1'),	'~C~' => pack('H*', 'd9a2'),
			'~D~' => pack('H*', 'd9a3'),	'~E~' => pack('H*', 'd9a4'),	'~F~' => pack('H*', 'd9a5'),
			'~G~' => pack('H*', 'd9a6'),	'~H~' => pack('H*', 'd9a7'),	'~I~' => pack('H*', 'd9a8'),
			'~J~' => pack('H*', 'd9a9'),
			'~A0~' => '0',	'~B1~' => '1',	'~C2~' => '2',
			'~D3~' => '3',	'~E4~' => '4',	'~F5~' => '5',
			'~G6~' => '6',	'~H7~' => '7',	'~I8~' => '8',
			'~J9~' => '9',
			'~K~' => pack('H*', 'd8a2'),	'~L~' => pack('H*', 'd8a7'),
			'~M~' => pack('H*', 'd8a3'),	'~N~' => pack('H*', 'd8a5'),	'~O~' => pack('H*', 'd8a4'),
			'~P~' => pack('H*', 'd8a6'),	'~Q~' => pack('H*', 'd8a1'),	'~R~' => pack('H*', 'd8a8'),
			'~S~' => pack('H*', 'd9be'),	'~T~' => pack('H*', 'd8aa'),	'~U~' => pack('H*', 'd8ab'),
			'~V~' => pack('H*', 'd8ac'),	'~W~' => pack('H*', 'da86'),	'~X~' => pack('H*', 'd8ad'),
			'~Y~' => pack('H*', 'd8ae'),	'~Z~' => pack('H*', 'd8af'),	'~a~' => pack('H*', 'd8b0'),
			'~b~' => pack('H*', 'd8b1'),	'~c~' => pack('H*', 'd8b2'),	'~d~' => pack('H*', 'da98'),
			'~e~' => pack('H*', 'd8b3'),	'~f~' => pack('H*', 'd8b4'),	'~g~' => pack('H*', 'd8b5'),
			'~h~' => pack('H*', 'd8b6'),	'~i~' => pack('H*', 'd8b7'),	'~j~' => pack('H*', 'd8b8'),
			'~k~' => pack('H*', 'd8b9'),	'~l~' => pack('H*', 'd8ba'),	'~m~' => pack('H*', 'd981'),
			'~n~' => pack('H*', 'd982'),	'~o~' => pack('H*', 'daa9'),	'~p~' => pack('H*', 'd983'),	'~q~' => pack('H*', 'daaf'),
			'~r~' => pack('H*', 'd984'),	'~s~' => pack('H*', 'd985'),	'~t~' => pack('H*', 'd986'),
			'~u~' => pack('H*', 'd988'),	'~v~' => pack('H*', 'd987'),	'~w~' => pack('H*', 'db8c'),
			'~x~' => pack('H*', 'd98a'),	'~y~' => pack('H*', 'db80'),	'~z~' => pack('H*', 'd8a9')
		);
		
		$searchArray = array_values($alphabet);
		$replacementArray = array_keys($alphabet);
		
		foreach ($inputArray as $inputKey => $inputStr)
		{
			if (is_string($inputStr))
			{
				$inputStr = str_replace($searchArray, $replacementArray, $inputStr);
			}
			
			if (is_array($inputStr))
			{
				$inputStr = self::psort($inputStr, $function);
			}
			
			$converted[$inputKey] = $inputStr;
		}
		
		$ret = $function($converted);	// Run function
		$converted = is_array($ret) ? $ret : $converted;	// Check for function output. Some functions affect input itself and return bool...
		
		foreach ($converted as $convertedKey => $convertedStr)
		{
			if (is_string($convertedStr))
			{
				$convertedStr = str_replace($replacementArray, $searchArray, $convertedStr);
			}
			
			if (is_array($convertedStr))
			{
				$convertedStr = self::psort($convertedStr, $function);
			}
			
			$result[$convertedKey] = $convertedStr;
		}
		
		return $result;
	}
	
	public static function kpsort($inputArray, $function = 'ksort')
	{
		$converted = $result = array();
		
		$alphabet = array(
			'~A~' => pack('H*', 'd9a0'),	'~B~' => pack('H*', 'd9a1'),	'~C~' => pack('H*', 'd9a2'),
			'~D~' => pack('H*', 'd9a3'),	'~E~' => pack('H*', 'd9a4'),	'~F~' => pack('H*', 'd9a5'),
			'~G~' => pack('H*', 'd9a6'),	'~H~' => pack('H*', 'd9a7'),	'~I~' => pack('H*', 'd9a8'),
			'~J~' => pack('H*', 'd9a9'),
			'~A0~' => '0',	'~B1~' => '1',	'~C2~' => '2',
			'~D3~' => '3',	'~E4~' => '4',	'~F5~' => '5',
			'~G6~' => '6',	'~H7~' => '7',	'~I8~' => '8',
			'~J9~' => '9',
			'~K~' => pack('H*', 'd8a2'),	'~L~' => pack('H*', 'd8a7'),
			'~M~' => pack('H*', 'd8a3'),	'~N~' => pack('H*', 'd8a5'),	'~O~' => pack('H*', 'd8a4'),
			'~P~' => pack('H*', 'd8a6'),	'~Q~' => pack('H*', 'd8a1'),	'~R~' => pack('H*', 'd8a8'),
			'~S~' => pack('H*', 'd9be'),	'~T~' => pack('H*', 'd8aa'),	'~U~' => pack('H*', 'd8ab'),
			'~V~' => pack('H*', 'd8ac'),	'~W~' => pack('H*', 'da86'),	'~X~' => pack('H*', 'd8ad'),
			'~Y~' => pack('H*', 'd8ae'),	'~Z~' => pack('H*', 'd8af'),	'~a~' => pack('H*', 'd8b0'),
			'~b~' => pack('H*', 'd8b1'),	'~c~' => pack('H*', 'd8b2'),	'~d~' => pack('H*', 'da98'),
			'~e~' => pack('H*', 'd8b3'),	'~f~' => pack('H*', 'd8b4'),	'~g~' => pack('H*', 'd8b5'),
			'~h~' => pack('H*', 'd8b6'),	'~i~' => pack('H*', 'd8b7'),	'~j~' => pack('H*', 'd8b8'),
			'~k~' => pack('H*', 'd8b9'),	'~l~' => pack('H*', 'd8ba'),	'~m~' => pack('H*', 'd981'),
			'~n~' => pack('H*', 'd982'),	'~o~' => pack('H*', 'daa9'),	'~p~' => pack('H*', 'd983'),	'~q~' => pack('H*', 'daaf'),
			'~r~' => pack('H*', 'd984'),	'~s~' => pack('H*', 'd985'),	'~t~' => pack('H*', 'd986'),
			'~u~' => pack('H*', 'd988'),	'~v~' => pack('H*', 'd987'),	'~w~' => pack('H*', 'db8c'),
			'~x~' => pack('H*', 'd98a'),	'~y~' => pack('H*', 'db80'),	'~z~' => pack('H*', 'd8a9')
		);
		
		$searchArray = array_values($alphabet);
		$replacementArray = array_keys($alphabet);
		
		foreach ($inputArray as $inputKey => $inputStr)
		{
			$inputKey = str_replace($searchArray, $replacementArray, $inputKey);
			
			$converted[$inputKey] = $inputStr;
		}
		
		$ret = $function($converted);	// Run function
		$converted = is_array($ret) ? $ret : $converted;	// Check for function output. Some functions affect input itself and return bool...
		
		foreach ($converted as $convertedKey => $convertedStr)
		{
			$convertedKey = str_replace($replacementArray, $searchArray, $convertedKey);

			$result[$convertedKey] = $convertedStr;
		}
		
		return $result;
	}
	
	public static function loadXML()
	{
		$xml_path = base_path() . Config::get('app_settings.xml_path');
		
		$xmlContent = file_get_contents($xml_path);
		
		$xmlContent = str_replace(pack('H*', 'efbbbf'), '', $xmlContent);
		$xmlContent = str_replace(pack('H*', 'c2a0'), '', $xmlContent);
		$xmlContent = preg_replace('#[[:space:]]+#iu', ' ', $xmlContent);
		$xmlContent = preg_replace_callback('#[\'"]([^\r\n\'"]*)[\'"]#', function ($matches)
		{
			return '"'.preg_replace('#[[:space:]\p{Cf}]+$#iu', '', preg_replace('#^[[:space:]\p{Cf}]+#iu', '', $matches[1])).'"';
		}, $xmlContent);
		$xmlContent = preg_replace('#\p{Cf}+#iu', pack('H*', 'e2808c'), $xmlContent);
		if (strpos($xmlContent, '<?xml') === FALSE)
		{
			$xmlContent = '<?xml version="1.0" encoding="UTF-8"?>' . $xmlContent;
		}
		
		$xml = new Sadeghi85\Extensions\DomDocument;
		
		try	{
			$xml->loadXML($xmlContent, LIBXML_NOBLANKS);
		}
		catch (\Exception $e) {
			Log::error('Error loading xml. ( '. __FILE__ .' on line '. __LINE__ .' )');
			self::setExceptionErrorMessage(Lang::get('app.page_display_error'));
			
			App::abort(500);
		}
		
		return $xml;
	}
	
	public static function loadPersianizedXML()
	{
		$persianizedXML = self::getXMLObject();
		
		$persianizedXMLContent = $persianizedXML->saveXML();
		
		$persianizedXMLContent = preg_replace_callback(sprintf('#<%s [^>]+#', BOOK_NODE), function ($matches)
		{
			preg_match(sprintf('#%s[[:space:]]*=[[:space:]]*"([^"]+)"#iu', BOOK_ATTR_DISPLAYNAME), $matches[0], $bookName);
			preg_match(sprintf('#%s[[:space:]]*=[[:space:]]*"([^"]+)"#iu', BOOK_ATTR_AUTHOR), $matches[0], $authorName);
			
			return sprintf('%s tmpSuggest="%s"', $matches[0], self::persianizeString(sprintf('%s (%s)', $bookName[1], $authorName[1])));
		},
		$persianizedXMLContent);
		
		try	{
			$persianizedXML->loadXML($persianizedXMLContent, LIBXML_NOBLANKS);
		}
		catch (\Exception $e) {
			Log::error('Error loading persianized xml. ( '. __FILE__ .' on line '. __LINE__ .' )');
			self::setExceptionErrorMessage(Lang::get('app.page_display_error'));
			App::abort(500);
		}
		
		return $persianizedXML;
	}
	
	public static function getXMLObject($persianized = false)
	{
		if ( ! is_object(self::$_xmlObject) or ($persianized and ! is_object(self::$_persianizedXMLObject)))
		{
			if (self::getModifiedDateHash(self::getCacheableFiles()) == Cache::tags(Request::server('HTTP_HOST'))->get('cache.files.date.hash', 0) and Cache::tags(Request::server('HTTP_HOST'))->has('xml.object') and Cache::tags(Request::server('HTTP_HOST'))->has('persianized.xml.object'))
			{
				if ($persianized)
				{
					self::$_persianizedXMLObject = unserialize(Cache::tags(Request::server('HTTP_HOST'))->get('persianized.xml.object'));
				}
				else
				{
					self::$_xmlObject = unserialize(Cache::tags(Request::server('HTTP_HOST'))->get('xml.object'));
				}
			}
			else
			{
				Cache::tags(Request::server('HTTP_HOST'))->flush();
				
				self::$_xmlObject = self::loadXML();
				Cache::tags(Request::server('HTTP_HOST'))->put('xml.object', serialize(self::$_xmlObject), 24 * 60);
				
				self::$_persianizedXMLObject = self::loadPersianizedXML();
				Cache::tags(Request::server('HTTP_HOST'))->put('persianized.xml.object', serialize(self::$_persianizedXMLObject), 24 * 60);
			}
		}
		
		return $persianized ? self::$_persianizedXMLObject : self::$_xmlObject;
	}
	
	public static function getPersianizedXMLObject()
	{
		return self::getXMLObject($persianized = true);
	}
	
	public static function getSuggestions($query, $limit = 10)
	{
		$xml = self::getPersianizedXMLObject();
		$xpath = new DOMXpath($xml);
		
		$suggestArray = array();
		$finalArray = array();
		
		$query = self::persianizeString($query);
		if ( ! $query) return array();
		$queries = explode(' ', $query);
		
		$xpathQuery = sprintf('//%s[starts-with(@%s, \'%s\')]', BOOK_NODE, 'tmpSuggest', $query);
		$nodes = $xpath->query($xpathQuery, $xml);
		
		for ($i=0, $j=0, $k=$nodes->length; ($i<$k and $j<(2*$limit)); $i++, $j++)
		{
			$suggestArray[$nodes->item($i)->getAttribute(BOOK_ATTR_NAME)] = sprintf('%s (%s)', $nodes->item($i)->getAttribute(BOOK_ATTR_DISPLAYNAME), $nodes->item($i)->getAttribute(BOOK_ATTR_AUTHOR));
		}
		
		$xpathQuery = sprintf('//%s[', BOOK_NODE);
		foreach ($queries as $q)
		{
			$xpathQuery .= sprintf('contains(@%s, \'%s\') and ', 'tmpSuggest', $q);
		}
		$xpathQuery .= '1]';
		$nodes = $xpath->query($xpathQuery, $xml);
		
		for ($i=0, $j=0, $k=$nodes->length; ($i<$k and $j<(2*$limit)); $i++, $j++)
		{
			$suggestArray[$nodes->item($i)->getAttribute(BOOK_ATTR_NAME)] = sprintf('%s (%s)', $nodes->item($i)->getAttribute(BOOK_ATTR_DISPLAYNAME), $nodes->item($i)->getAttribute(BOOK_ATTR_AUTHOR));
		}
		
		$suggestArray = array_first(array_chunk($suggestArray, (2*$limit), true), function($key, $value)
		{
			return $key == 0;
		}, array());
		
		foreach ($suggestArray as $key => $value)
		{
			foreach ($queries as $q)
			{
				$q = preg_quote($q);
				$q = str_replace(pack('H*', 'db8c'), sprintf('(?:%s|%s|%s|%s)', pack('H*', 'db8c'), pack('H*', 'd98a'), pack('H*', 'd989'), pack('H*', 'd8a6')), $q); # ی
				$q = str_replace(pack('H*', 'd8a7'), sprintf('(?:%s|%s|%s|%s)', pack('H*', 'd8a7'), pack('H*', 'd8a5'), pack('H*', 'd8a3'), pack('H*', 'd8a2')), $q); # ا
				$q = str_replace(pack('H*', 'd987'), sprintf('(?:%s|%s|%s)', pack('H*', 'd987'), pack('H*', 'd8a9'), pack('H*', 'db80')), $q); # ه
				$q = str_replace(pack('H*', 'd988'), sprintf('(?:%s|%s)', pack('H*', 'd988'), pack('H*', 'd8a4')), $q); # و
				$q = str_replace(pack('H*', 'daa9'), sprintf('(?:%s|%s)', pack('H*', 'daa9'), pack('H*', 'd983')), $q); # ک
			
				$value = preg_replace(sprintf('#[\p{L}\p{M}\p{Cf}]*%s[\p{L}\p{M}\p{Cf}]*#iu', $q), sprintf('<span class="hilight_suggestion">%s</span>', '$0'), $value);
			}
			
			$finalArray[] = array('label' => $value, 'value' => $key);
		}
		
		return $finalArray;
	}
	
	public static function getBookIdArray()
	{
		$_xmlObject = self::getXMLObject();
		
		$xpath = new DOMXpath($_xmlObject);
		
		$groupKey = Input::get('groupKey', '');
		
		$xpathQuery = $groupKey ? Crypt::decrypt(urldecode($groupKey)) : sprintf('//%s', BOOK_NODE);
		
		$books = $xpath->query($xpathQuery, $_xmlObject);
		
		$_bookIdArray = array();
		
		foreach ($books as $bookNode)
		{
			$_bookIdArray[] = (int) $bookNode->getAttribute(BOOK_ATTR_NAME);
		}
		
		return (empty($_bookIdArray) ? array(0) : $_bookIdArray);
	}
	
	public static function getEncodedRequestUri()
	{
		return base64_encode(Request::Url());
	}
	
	public static function redirect($path, $status = 302, $headers = array(), $secure = null)
	{
		$path = preg_replace('#\++|(%20)+| +#', '_', $path);
		$path = str_replace('"', '%22', $path);
		
		return Redirect::to($path, $status, $headers, $secure);
	}
	
	public static function to($path = null, $parameters = array(), $secure = null)
	{
		$path = preg_replace('#\++|(%20)+| +#', '_', $path);
		
		return url($path, $parameters, $secure);
	}
	
	public static function link_to($url, $title = null, $attributes = array(), $secure = null)
	{
		$url = preg_replace('#\++|(%20)+| +#', '_', $url);
		
		return link_to($url, $title, $attributes, $secure);
	}
	
	public static function renderNavigation()
	{
		// one based array
		$segments = Request::segments();
		
		$xml = self::getXMLObject();
		
		$xpath = new DOMXpath($xml);
		
		############################
		$xpathQuery = sprintf('//%s', GROUP_NODE);

		$groupNodes = $xpath->query($xpathQuery, $xml);

		$depth = 0;

		if ($groupNodes->length != 0)
		{
			foreach ($groupNodes as $group)
			{
				
				if(($currentDepth = $xpath->evaluate('count(ancestor::*)', $group)) > $depth)
				{
					$depth = $currentDepth;
				}
			}
		}
		###########################
		
		$groupArray = array();
		$navigation = array();
		
		$basePath = '';
		$partialPath = '';
		$baseXpath = sprintf('/%s/%s', MAIN_NODE, GROUP_NODE);
		$haveSelected = FALSE;
		
		if (empty($segments))
		{
			$groupArray[0][] = array('path' => '/', 'group' => Lang::get('app.index_page'), 'selected' => TRUE);
			
			$nodes = $xpath->query($baseXpath, $xml);
			
			foreach($nodes as $key => $node)
			{
				############################
				if (isset($groupArray[0][$node->getAttribute(GROUP_ATTR_ORDER)]))
				{
					end($groupArray[0]);
					$lastKey = key($groupArray[0]);
					
					for ($j = $lastKey; $j > $node->getAttribute(GROUP_ATTR_ORDER); --$j)
					{
						if (isset($groupArray[0][$j]))
						{
							$groupArray[0][$j + 1] = $groupArray[0][$j];
							unset($groupArray[0][$j]);
						}
					}
					
					if (strcasecmp($groupArray[0][$node->getAttribute(GROUP_ATTR_ORDER)]['group'], $node->getAttribute(GROUP_ATTR_NAME)) <= 0)
					{
						$groupArray[0][$node->getAttribute(GROUP_ATTR_ORDER) + 1] = array('path' => $basePath.'/'.$node->getAttribute(GROUP_ATTR_NAME), 'group' => $node->getAttribute(GROUP_ATTR_NAME), 'selected' => FALSE);
					}
					else
					{
						$groupArray[0][$node->getAttribute(GROUP_ATTR_ORDER) + 1] = $groupArray[0][$node->getAttribute(GROUP_ATTR_ORDER)];
						$groupArray[0][$node->getAttribute(GROUP_ATTR_ORDER)] = array('path' => $basePath.'/'.$node->getAttribute(GROUP_ATTR_NAME), 'group' => $node->getAttribute(GROUP_ATTR_NAME), 'selected' => FALSE);
					}
				}
				else
				{
					$groupArray[0][$node->getAttribute(GROUP_ATTR_ORDER)] = array('path' => $basePath.'/'.$node->getAttribute(GROUP_ATTR_NAME), 'group' => $node->getAttribute(GROUP_ATTR_NAME), 'selected' => FALSE);
				}
				
				ksort($groupArray[0]);
				################################
			}
			
			$groupArray[0][] = array('path' => $partialPath.'/'.'all', 'group' => Lang::get('app.all_groups'), 'selected' => FALSE);
			$groupArray[0][] = array('path' => $partialPath.'/'.'authors', 'group' => Lang::get('app.authors'), 'selected' => FALSE);
			$navigation[0] = '';
		}
		else
		{
			if (is_numeric($segments[1]))
			{
				$nodes3 = $xpath->query(sprintf('//%s[@%s=\'%s\']', BOOK_NODE, BOOK_ATTR_NAME, $segments[1]), $xml);
				
				if ($nodes3->length > 1)
				{
					Log::warning('Duplicate book entry: "'. $segments[1] .'". ( '. __FILE__ .' on line '. __LINE__ .' )');
				}
				elseif ($nodes3->length == 1)
				{
					foreach ($nodes3 as $node)
					{
						$bookDepth = $xpath->evaluate('count(ancestor::*)', $node) - 1;
						
						unset($segments);
						
						$currentNode = $node;
						
						for ($k = $bookDepth; $k > 0; --$k)
						{
							$currentNode = $currentNode->parentNode;
							
							$segments[$k] = $currentNode->getAttribute(GROUP_ATTR_NAME);
						}
					}
				}
			}
			
			for($i = 1; $i <= $depth; $i++)
			{
				if ($i == 1)
				{
					$groupArray[$i][] = array('path' => '/', 'group' => Lang::get('app.index_page'), 'selected' => FALSE);
				}
				
				$nodes = $xpath->query($baseXpath, $xml);
				
				$partialPath = $basePath;
				
				foreach($nodes as $key => $node)
				{
					if (isset($segments[$i]) && $node->getAttribute(GROUP_ATTR_NAME) == $segments[$i])
					{
						############################
						if (isset($groupArray[$i][$node->getAttribute(GROUP_ATTR_ORDER)]))
						{
							end($groupArray[$i]);
							$lastKey = key($groupArray[$i]);
							
							for ($j = $lastKey; $j > $node->getAttribute(GROUP_ATTR_ORDER); --$j)
							{
								if (isset($groupArray[$i][$j]))
								{
									$groupArray[$i][$j + 1] = $groupArray[$i][$j];
									unset($groupArray[$i][$j]);
								}
							}
							
							if (strcasecmp($groupArray[$i][$node->getAttribute(GROUP_ATTR_ORDER)]['group'], $node->getAttribute(GROUP_ATTR_NAME)) <= 0)
							{
								$groupArray[$i][$node->getAttribute(GROUP_ATTR_ORDER) + 1] = array('path' => $partialPath.'/'.$node->getAttribute(GROUP_ATTR_NAME), 'group' => $node->getAttribute(GROUP_ATTR_NAME), 'selected' => TRUE);
							}
							else
							{
								$groupArray[$i][$node->getAttribute(GROUP_ATTR_ORDER) + 1] = $groupArray[$i][$node->getAttribute(GROUP_ATTR_ORDER)];
								$groupArray[$i][$node->getAttribute(GROUP_ATTR_ORDER)] = array('path' => $partialPath.'/'.$node->getAttribute(GROUP_ATTR_NAME), 'group' => $node->getAttribute(GROUP_ATTR_NAME), 'selected' => TRUE);
							}
						}
						else
						{
							$groupArray[$i][$node->getAttribute(GROUP_ATTR_ORDER)] = array('path' => $partialPath.'/'.$node->getAttribute(GROUP_ATTR_NAME), 'group' => $node->getAttribute(GROUP_ATTR_NAME), 'selected' => TRUE);
						}
						
						ksort($groupArray[$i]);
						############################
						
						$basePath .= '/'.$node->getAttribute(GROUP_ATTR_NAME);
						
						$baseXpath .= sprintf('[@%s=\'%s\']/%s', GROUP_ATTR_NAME, $node->getAttribute(GROUP_ATTR_NAME), GROUP_NODE);
						
						$navigation[] = $node->getAttribute(GROUP_ATTR_NAME);
						
						$haveSelected = TRUE;
					}
					else
					{
						############################
						if (isset($groupArray[$i][$node->getAttribute(GROUP_ATTR_ORDER)]))
						{
							end($groupArray[$i]);
							$lastKey = key($groupArray[$i]);
							
							for ($j = $lastKey; $j > $node->getAttribute(GROUP_ATTR_ORDER); --$j)
							{
								if (isset($groupArray[$i][$j]))
								{
									$groupArray[$i][$j + 1] = $groupArray[$i][$j];
									unset($groupArray[$i][$j]);
								}
							}
							
							if (strcasecmp($groupArray[$i][$node->getAttribute(GROUP_ATTR_ORDER)]['group'], $node->getAttribute(GROUP_ATTR_NAME)) <= 0)
							{
								$groupArray[$i][$node->getAttribute(GROUP_ATTR_ORDER) + 1] = array('path' => $partialPath.'/'.$node->getAttribute(GROUP_ATTR_NAME), 'group' => $node->getAttribute(GROUP_ATTR_NAME), 'selected' => FALSE);
							}
							else
							{
								$groupArray[$i][$node->getAttribute(GROUP_ATTR_ORDER) + 1] = $groupArray[$i][$node->getAttribute(GROUP_ATTR_ORDER)];
								$groupArray[$i][$node->getAttribute(GROUP_ATTR_ORDER)] = array('path' => $partialPath.'/'.$node->getAttribute(GROUP_ATTR_NAME), 'group' => $node->getAttribute(GROUP_ATTR_NAME), 'selected' => FALSE);
							}
						}
						else
						{
							$groupArray[$i][$node->getAttribute(GROUP_ATTR_ORDER)] = array('path' => $partialPath.'/'.$node->getAttribute(GROUP_ATTR_NAME), 'group' => $node->getAttribute(GROUP_ATTR_NAME), 'selected' => FALSE);
						}
						
						ksort($groupArray[$i]);
						############################
					}
				}
				
				switch ((isset($segments[$i]) ? $segments[$i] : ''))
				{
					case 'authors':
						$groupArray[$i][] = array('path' => $partialPath.'/'.'all', 'group' => Lang::get('app.all_groups'), 'selected' => FALSE);
						$groupArray[$i][] = array('path' => $partialPath.'/'.'authors', 'group' => Lang::get('app.authors'), 'selected' => TRUE);
						$navigation[] = 'authors';
					break;
						
					case 'all':
						$groupArray[$i][] = array('path' => $partialPath.'/'.'all', 'group' => Lang::get('app.all_groups'), 'selected' => TRUE);
						$groupArray[$i][] = array('path' => $partialPath.'/'.'authors', 'group' => Lang::get('app.authors'), 'selected' => FALSE);
						$navigation[] = 'all';
					break;
						
					default:
						
						if ($haveSelected === FALSE)
						{
							if (isset($segments[$i]))
							{
								$nodes2 = $xpath->query(sprintf('//%s[@%s=\'%s\']', BOOK_NODE, BOOK_ATTR_AUTHOR, $segments[$i]), $xml);
								
								if ($nodes2->length > 0)
								{
									$groupArray[$i][] = array('path' => $partialPath.'/'.'all', 'group' => Lang::get('app.all_groups'), 'selected' => FALSE);
									$groupArray[$i][] = array('path' => $partialPath.'/'.'authors', 'group' => Lang::get('app.authors'), 'selected' => TRUE);
									$navigation[] = $segments[$i];
								}
								else
								{
									reset($groupArray[$i]);
									$firstKey = key($groupArray[$i]);
									
									if ($nodes->length > 0 && $groupArray[$i][$firstKey]['group'] != Lang::get('app.index_page'))
									{
										$groupArray[$i][$firstKey]['selected'] = TRUE;
										
										$basePath .= '/'.$groupArray[$i][$firstKey]['group'];
								
										$baseXpath .= sprintf('[@%s=\'%s\']/%s', GROUP_ATTR_NAME, $groupArray[$i][$firstKey]['group'], GROUP_NODE);
										
										$navigation[] = $groupArray[$i][$firstKey]['group'];
										
										$haveSelected = TRUE;
										
										$groupArray[$i][] = array('path' => $partialPath.'/'.'all', 'group' => Lang::get('app.all_groups'), 'selected' => FALSE);
										$groupArray[$i][] = array('path' => $partialPath.'/'.'authors', 'group' => Lang::get('app.authors'), 'selected' => FALSE);
									}
									
									if (isset($groupArray[$i][0]) && $haveSelected === FALSE)
									{
										$groupArray[$i][] = array('path' => $partialPath.'/'.'all', 'group' => Lang::get('app.all_groups'), 'selected' => TRUE);
										$groupArray[$i][] = array('path' => $partialPath.'/'.'authors', 'group' => Lang::get('app.authors'), 'selected' => FALSE);
										$navigation[] = $segments[$i];
									}
								}
							}
							else
							{
								if ($nodes->length > 0)
								{
									reset($groupArray[$i]);
									$firstKey = key($groupArray[$i]);
									
									$groupArray[$i][$firstKey]['selected'] = TRUE;
									
									$basePath .= '/'.$groupArray[$i][$firstKey]['group'];
									
									$baseXpath .= sprintf('[@%s=\'%s\']/%s', GROUP_ATTR_NAME, $groupArray[$i][$firstKey]['group'], GROUP_NODE);
									
									$navigation[] = $groupArray[$i][$firstKey]['group'];
									
									$haveSelected = TRUE;
									
									$groupArray[$i][] = array('path' => $partialPath.'/'.'all', 'group' => Lang::get('app.all_groups'), 'selected' => FALSE);
									$groupArray[$i][] = array('path' => $partialPath.'/'.'authors', 'group' => Lang::get('app.authors'), 'selected' => FALSE);
								}
									
								if (isset($groupArray[$i][0]) && $haveSelected === FALSE)
								{
									$groupArray[$i][] = array('path' => $partialPath.'/'.'all', 'group' => Lang::get('app.all_groups'), 'selected' => TRUE);
									$groupArray[$i][] = array('path' => $partialPath.'/'.'authors', 'group' => Lang::get('app.authors'), 'selected' => FALSE);
									$navigation[] = '-';
								}
							}
						}
						else
						{
							$groupArray[$i][] = array('path' => $partialPath.'/'.'all', 'group' => Lang::get('app.all_groups'), 'selected' => FALSE);
							$groupArray[$i][] = array('path' => $partialPath.'/'.'authors', 'group' => Lang::get('app.authors'), 'selected' => FALSE);
						}
					break;
				}
				
				if ($haveSelected === TRUE)
				{
					$haveSelected = FALSE;
				}
				else
				{
					break;
				}
			}
		}
		
		self::$_navigationSegments = $navigation;
		self::$_navigationTabs = $groupArray;
	}
	
	public static function getNavigationTabs()
	{
		return self::$_navigationTabs;
	}
	
	public static function getNavigationSegments()
	{
		return self::$_navigationSegments;
	}
}
