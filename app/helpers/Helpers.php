<?php

class Helpers {

	private static $_xmlObject = null;
	private static $_persianizedXMLObject = null;
	
	private static $_bookIdArray = array();
	
	public static function getCacheableFiles()
	{
		$Directory = new RecursiveDirectoryIterator(public_path() . '/views/');
		$Iterator = new RecursiveIteratorIterator($Directory);
		$Views = new RegexIterator($Iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);
		
		$files = array_flatten(iterator_to_array($Views));
		$files[] = base_path() . Config::get('app_settings.xml_path');
		
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
		$string = preg_replace('# +#iu', ' ', $string);
		$string = preg_replace('#\p{M}+#iu', '', $string);
		$string = preg_replace('#(ي|ى|ئ)#iu', 'ی', $string);
		$string = preg_replace('#(إ|أ)#iu', 'ا', $string);
		$string = preg_replace('#ة#iu', 'ه', $string);
		$string = preg_replace('#ؤ#iu', 'و', $string);
		$string = preg_replace('#ك#iu', 'ک', $string);
		$string = preg_replace('#\p{Cf}+#iu', ' ', $string); # zwnj, etc.;
		$string = preg_replace('#ـ#iu', '', $string); # مـزمل
		
		return trim($string);
	}
	
	public static function persianizeUrl($string)
	{
		$string = urldecode($string);
		$string = preg_replace('#_+#iu', ' ', $string);
		$string = preg_replace('#[\'"\\\0\\\\]#iu', '', $string);
		
		$string = self::persianizeString($string);
		
		return $string;
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
			'~A~' => '۰',	'~B~' => '۱',	'~C~' => '۲',
			'~D~' => '۳',	'~E~' => '۴',	'~F~' => '۵',
			'~G~' => '۶',	'~H~' => '۷',	'~I~' => '۸',
			'~J~' => '۹',
			'~A0~' => '0',	'~B1~' => '1',	'~C2~' => '2',
			'~D3~' => '3',	'~E4~' => '4',	'~F5~' => '5',
			'~G6~' => '6',	'~H7~' => '7',	'~I8~' => '8',
			'~J9~' => '9',
			'~K~' => 'آ',	'~L~' => 'ا',
			'~M~' => 'أ',	'~N~' => 'إ',	'~O~' => 'ؤ',
			'~P~' => 'ئ',	'~Q~' => 'ء',	'~R~' => 'ب',
			'~S~' => 'پ',	'~T~' => 'ت',	'~U~' => 'ث',
			'~V~' => 'ج',	'~W~' => 'چ',	'~X~' => 'ح',
			'~Y~' => 'خ',	'~Z~' => 'د',	'~a~' => 'ذ',
			'~b~' => 'ر',	'~c~' => 'ز',	'~d~' => 'ژ',
			'~e~' => 'س',	'~f~' => 'ش',	'~g~' => 'ص',
			'~h~' => 'ض',	'~i~' => 'ط',	'~j~' => 'ظ',
			'~k~' => 'ع',	'~l~' => 'غ',	'~m~' => 'ف',
			'~n~' => 'ق',	'~o~' => 'ک',	'~p~' => 'ك',	'~q~' => 'گ',
			'~r~' => 'ل',	'~s~' => 'م',	'~t~' => 'ن',
			'~u~' => 'و',	'~v~' => 'ه',	'~w~' => 'ی',
			'~x~' => 'ي',	'~y~' => 'ۀ',	'~z~' => 'ة'
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
			'~A~' => '۰',	'~B~' => '۱',	'~C~' => '۲',
			'~D~' => '۳',	'~E~' => '۴',	'~F~' => '۵',
			'~G~' => '۶',	'~H~' => '۷',	'~I~' => '۸',
			'~J~' => '۹',
			'~A0~' => '0',	'~B1~' => '1',	'~C2~' => '2',
			'~D3~' => '3',	'~E4~' => '4',	'~F5~' => '5',
			'~G6~' => '6',	'~H7~' => '7',	'~I8~' => '8',
			'~J9~' => '9',
			'~K~' => 'آ',	'~L~' => 'ا',
			'~M~' => 'أ',	'~N~' => 'إ',	'~O~' => 'ؤ',
			'~P~' => 'ئ',	'~Q~' => 'ء',	'~R~' => 'ب',
			'~S~' => 'پ',	'~T~' => 'ت',	'~U~' => 'ث',
			'~V~' => 'ج',	'~W~' => 'چ',	'~X~' => 'ح',
			'~Y~' => 'خ',	'~Z~' => 'د',	'~a~' => 'ذ',
			'~b~' => 'ر',	'~c~' => 'ز',	'~d~' => 'ژ',
			'~e~' => 'س',	'~f~' => 'ش',	'~g~' => 'ص',
			'~h~' => 'ض',	'~i~' => 'ط',	'~j~' => 'ظ',
			'~k~' => 'ع',	'~l~' => 'غ',	'~m~' => 'ف',
			'~n~' => 'ق',	'~o~' => 'ک',	'~p~' => 'ك',	'~q~' => 'گ',
			'~r~' => 'ل',	'~s~' => 'م',	'~t~' => 'ن',
			'~u~' => 'و',	'~v~' => 'ه',	'~w~' => 'ی',
			'~x~' => 'ي',	'~y~' => 'ۀ',	'~z~' => 'ة'
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
		
		$xml = new Sadeghi85\Extensions\DomDocument;
		
		try	{
			$xml->loadXML($xmlContent, LIBXML_NOBLANKS);
		}
		catch (\Exception $e) {
			Log::error('Error loading xml. ( '. __FILE__ .' on line '. __LINE__ .' )');
			Session::put('exception.error.message', Lang::get('app.page_display_error'));
			App::abort(500);
		}
		
		return $xml;
	}
	
	public static function loadPersianizedXML()
	{
		$persianizedXML = self::getXMLObject();
		
		$persianizedXMLContent = $persianizedXML->saveXML();
		
		$persianizedXMLContent = preg_replace_callback(sprintf('#(%s="([^"]+)")#iu', BOOK_ATTR_DISPLAYNAME), function ($matches)
		{
			return $matches[1] . ' tmpdisplayname="' . self::persianizeString($matches[2]) . '"';
		},
		$persianizedXMLContent);
		
		$persianizedXMLContent = preg_replace_callback(sprintf('#(%s="([^"]+)")#iu', BOOK_ATTR_AUTHOR), function ($matches)
		{
			return $matches[1] . ' tmpauthor="' . self::persianizeString($matches[2]) . '"';
		},
		$persianizedXMLContent);
		
		try	{
			$persianizedXML->loadXML($persianizedXMLContent, LIBXML_NOBLANKS);
		}
		catch (\Exception $e) {
			Log::error('Error loading persianized xml. ( '. __FILE__ .' on line '. __LINE__ .' )');
			Session::put('exception.error.message', Lang::get('app.page_display_error'));
			App::abort(500);
		}
		
		return $persianizedXML;
	}
	
	public static function getXMLObject()
	{
		if ( ! is_object(self::$_xmlObject))
		{
			if (self::getModifiedDateHash(self::getCacheableFiles()) == Cache::get('cache.files.date.hash', 0) and Cache::has('xml.object'))
			{
				self::$_xmlObject = unserialize(Cache::get('xml.object'));
			}
			else
			{
				self::$_xmlObject = self::loadXML();
				Cache::forever('xml.object', serialize(self::$_xmlObject));
			}
		}
		
		return self::$_xmlObject;
	}
	
	public static function getPersianizedXMLObject()
	{
		if ( ! is_object(self::$_persianizedXMLObject))
		{
			if (self::getModifiedDateHash(self::getCacheableFiles()) == Cache::get('cache.files.date.hash', 0) and Cache::has('persianized.xml.object'))
			{
				self::$_persianizedXMLObject = unserialize(Cache::get('persianized.xml.object'));
			}
			else
			{
				self::$_persianizedXMLObject = self::loadPersianizedXML();
				Cache::forever('persianized.xml.object', serialize(self::$_persianizedXMLObject));
			}
		}
		
		return self::$_persianizedXMLObject;
	}
	
	public static function getSuggestions($query, $limit = 10)
	{
		$xml = self::getPersianizedXMLObject();
		$xpath = new DOMXpath($xml);
		
		$query = self::persianizeString($query);
		
		$xpathQuery = sprintf('//%s[starts-with(@%s, \'%s\')]', BOOK_NODE, 'tmpdisplayname', $query);
		
		$set1 = $xpath->query($xpathQuery, $xml);
		$return1 = array();
		$output = array();
		
		// foreach ($set1 as $node)
		// {
			// $return1[$node->getAttribute(BOOK_ATTR_NAME)] = $node->getAttribute(BOOK_ATTR_DISPLAYNAME).' '.'('.$node->getAttribute(BOOK_ATTR_AUTHOR).')';
		// }
		for ($i=0, $j=0; ($i<$set1->length and $j<$limit); $i++, $j++)
		{
			$return1[$set1->item($i)->getAttribute(BOOK_ATTR_NAME)] = $set1->item($i)->getAttribute(BOOK_ATTR_DISPLAYNAME).' '.'('.$set1->item($i)->getAttribute(BOOK_ATTR_AUTHOR).')';
		}
		
		$return1 = self::psort($return1);
		
		foreach ($return1 as $key => $value)
		{
			$output[] = array('label' => $value, 'value' => $key);
		}
		
		
		return $output;
		
	}
	
	public static function getBookIdArray()
	{
		$_xmlObject = self::getXMLObject();
		
		$xpath = new DOMXpath($_xmlObject);
		
		$xpathQuery = sprintf('//%s', BOOK_NODE);
		
		$books = $xpath->query($xpathQuery, $_xmlObject);
		
		$_bookIdArray = array();
		
		foreach ($books as $bookNode)
		{
			$_bookIdArray[] = (int) $bookNode->getAttribute(BOOK_ATTR_NAME);
		}
		
		self::$_bookIdArray = $_bookIdArray;
		
		return self::$_bookIdArray;
	}
	
	public static function getEncodedRequestUri()
	{
		return base64_encode(Request::Url());
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
		
		//dd($segments);
		
		//$xml = unserialize(Session::get('xml.object'));
		$xml = self::getXMLObject();
		
		$xpath = new DOMXpath($xml);
		
		############################
		$xpath_query = sprintf('//%s', GROUP_NODE);

		$group_nodes = $xpath->query($xpath_query, $xml);

		$depth = 0;

		if ($group_nodes->length != 0)
		{
			foreach ($group_nodes as $group)
			{
				
				if(($current_depth = $xpath->evaluate('count(ancestor::*)', $group)) > $depth)
				{
					$depth = $current_depth;
				}
			}
		}
		###########################
		
		$group_array = array();
		$navigation = array();
		
		$base_path = '';
		$partial_path = '';
		$base_xpath = sprintf('/%s/%s', MAIN_NODE, GROUP_NODE);
		$have_selected = FALSE;
		
		if (empty($segments))
		{
			$group_array[0][] = array('path' => '/', 'group' => Lang::get('app.index_page'), 'selected' => TRUE);
			
			$nodes = $xpath->query($base_xpath, $xml);
			
			foreach($nodes as $key => $node)
			{
				############################
				if (isset($group_array[0][$node->getAttribute(GROUP_ATTR_ORDER)]))
				{
					end($group_array[0]);
					$last_key = key($group_array[0]);
					
					for ($j = $last_key; $j > $node->getAttribute(GROUP_ATTR_ORDER); --$j)
					{
						if (isset($group_array[0][$j]))
						{
							$group_array[0][$j + 1] = $group_array[0][$j];
							unset($group_array[0][$j]);
						}
					}
					
					if (strcasecmp($group_array[0][$node->getAttribute(GROUP_ATTR_ORDER)]['group'], $node->getAttribute(GROUP_ATTR_NAME)) <= 0)
					{
						$group_array[0][$node->getAttribute(GROUP_ATTR_ORDER) + 1] = array('path' => $base_path.'/'.$node->getAttribute(GROUP_ATTR_NAME), 'group' => $node->getAttribute(GROUP_ATTR_NAME), 'selected' => FALSE);
					}
					else
					{
						$group_array[0][$node->getAttribute(GROUP_ATTR_ORDER) + 1] = $group_array[0][$node->getAttribute(GROUP_ATTR_ORDER)];
						$group_array[0][$node->getAttribute(GROUP_ATTR_ORDER)] = array('path' => $base_path.'/'.$node->getAttribute(GROUP_ATTR_NAME), 'group' => $node->getAttribute(GROUP_ATTR_NAME), 'selected' => FALSE);
					}
				}
				else
				{
					$group_array[0][$node->getAttribute(GROUP_ATTR_ORDER)] = array('path' => $base_path.'/'.$node->getAttribute(GROUP_ATTR_NAME), 'group' => $node->getAttribute(GROUP_ATTR_NAME), 'selected' => FALSE);
				}
				
				ksort($group_array[0]);
				################################
			}
			
			$group_array[0][] = array('path' => $partial_path.'/'.'all', 'group' => Lang::get('app.all_groups'), 'selected' => FALSE);
			$group_array[0][] = array('path' => $partial_path.'/'.'authors', 'group' => Lang::get('app.authors'), 'selected' => FALSE);
			$navigation[0] = '';
			
			//dd($group_array);
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
						$book_depth = $xpath->evaluate('count(ancestor::*)', $node) - 1;
						
						unset($segments);
						
						$current_node = $node;
						
						for ($k = $book_depth; $k > 0; --$k)
						{
							$current_node = $current_node->parentNode;
							
							$segments[$k] = $current_node->getAttribute(GROUP_ATTR_NAME);
						}
					}
				}
			}
			
			for($i = 1; $i <= $depth; $i++)
			{
				if ($i == 1)
				{
					$group_array[$i][] = array('path' => '/', 'group' => Lang::get('app.index_page'), 'selected' => FALSE);
				}
				
				$nodes = $xpath->query($base_xpath, $xml);
				
				$partial_path = $base_path;
				
				foreach($nodes as $key => $node)
				{
					if (isset($segments[$i]) && $node->getAttribute(GROUP_ATTR_NAME) == $segments[$i])
					{
						############################
						if (isset($group_array[$i][$node->getAttribute(GROUP_ATTR_ORDER)]))
						{
							end($group_array[$i]);
							$last_key = key($group_array[$i]);
							
							for ($j = $last_key; $j > $node->getAttribute(GROUP_ATTR_ORDER); --$j)
							{
								if (isset($group_array[$i][$j]))
								{
									$group_array[$i][$j + 1] = $group_array[$i][$j];
									unset($group_array[$i][$j]);
								}
							}
							
							if (strcasecmp($group_array[$i][$node->getAttribute(GROUP_ATTR_ORDER)]['group'], $node->getAttribute(GROUP_ATTR_NAME)) <= 0)
							{
								$group_array[$i][$node->getAttribute(GROUP_ATTR_ORDER) + 1] = array('path' => $partial_path.'/'.$node->getAttribute(GROUP_ATTR_NAME), 'group' => $node->getAttribute(GROUP_ATTR_NAME), 'selected' => TRUE);
							}
							else
							{
								$group_array[$i][$node->getAttribute(GROUP_ATTR_ORDER) + 1] = $group_array[$i][$node->getAttribute(GROUP_ATTR_ORDER)];
								$group_array[$i][$node->getAttribute(GROUP_ATTR_ORDER)] = array('path' => $partial_path.'/'.$node->getAttribute(GROUP_ATTR_NAME), 'group' => $node->getAttribute(GROUP_ATTR_NAME), 'selected' => TRUE);
							}
						}
						else
						{
							$group_array[$i][$node->getAttribute(GROUP_ATTR_ORDER)] = array('path' => $partial_path.'/'.$node->getAttribute(GROUP_ATTR_NAME), 'group' => $node->getAttribute(GROUP_ATTR_NAME), 'selected' => TRUE);
						}
						
						ksort($group_array[$i]);
						############################
						
						$base_path .= '/'.$node->getAttribute(GROUP_ATTR_NAME);
						
						$base_xpath .= sprintf('[@%s=\'%s\']/%s', GROUP_ATTR_NAME, $node->getAttribute(GROUP_ATTR_NAME), GROUP_NODE);
						
						$navigation[] = $node->getAttribute(GROUP_ATTR_NAME);
						
						$have_selected = TRUE;
					}
					else
					{
						############################
						if (isset($group_array[$i][$node->getAttribute(GROUP_ATTR_ORDER)]))
						{
							end($group_array[$i]);
							$last_key = key($group_array[$i]);
							
							for ($j = $last_key; $j > $node->getAttribute(GROUP_ATTR_ORDER); --$j)
							{
								if (isset($group_array[$i][$j]))
								{
									$group_array[$i][$j + 1] = $group_array[$i][$j];
									unset($group_array[$i][$j]);
								}
							}
							
							if (strcasecmp($group_array[$i][$node->getAttribute(GROUP_ATTR_ORDER)]['group'], $node->getAttribute(GROUP_ATTR_NAME)) <= 0)
							{
								$group_array[$i][$node->getAttribute(GROUP_ATTR_ORDER) + 1] = array('path' => $partial_path.'/'.$node->getAttribute(GROUP_ATTR_NAME), 'group' => $node->getAttribute(GROUP_ATTR_NAME), 'selected' => FALSE);
							}
							else
							{
								$group_array[$i][$node->getAttribute(GROUP_ATTR_ORDER) + 1] = $group_array[$i][$node->getAttribute(GROUP_ATTR_ORDER)];
								$group_array[$i][$node->getAttribute(GROUP_ATTR_ORDER)] = array('path' => $partial_path.'/'.$node->getAttribute(GROUP_ATTR_NAME), 'group' => $node->getAttribute(GROUP_ATTR_NAME), 'selected' => FALSE);
							}
						}
						else
						{
							$group_array[$i][$node->getAttribute(GROUP_ATTR_ORDER)] = array('path' => $partial_path.'/'.$node->getAttribute(GROUP_ATTR_NAME), 'group' => $node->getAttribute(GROUP_ATTR_NAME), 'selected' => FALSE);
						}
						
						ksort($group_array[$i]);
						############################
					}
				}

				switch ((isset($segments[$i]) ? $segments[$i] : ''))
				{
					case 'authors':
						$group_array[$i][] = array('path' => $partial_path.'/'.'all', 'group' => Lang::get('app.all_groups'), 'selected' => FALSE);
						$group_array[$i][] = array('path' => $partial_path.'/'.'authors', 'group' => Lang::get('app.authors'), 'selected' => TRUE);
						$navigation[] = 'authors';
					break;
						
					case 'all':
						$group_array[$i][] = array('path' => $partial_path.'/'.'all', 'group' => Lang::get('app.all_groups'), 'selected' => TRUE);
						$group_array[$i][] = array('path' => $partial_path.'/'.'authors', 'group' => Lang::get('app.authors'), 'selected' => FALSE);
						$navigation[] = 'all';
					break;
						
					default:
						
						if ($have_selected === FALSE)
						{
							if (isset($segments[$i]))
							{
								$nodes2 = $xpath->query(sprintf('//%s[@%s=\'%s\']', BOOK_NODE, BOOK_ATTR_AUTHOR, $segments[$i]), $xml);
								
								if ($nodes2->length > 0)
								{
									$group_array[$i][] = array('path' => $partial_path.'/'.'all', 'group' => Lang::get('app.all_groups'), 'selected' => FALSE);
									$group_array[$i][] = array('path' => $partial_path.'/'.'authors', 'group' => Lang::get('app.authors'), 'selected' => TRUE);
									$navigation[] = $segments[$i];
								}
								else
								{
									reset($group_array[$i]);
									$first_key = key($group_array[$i]);
									
									if ($nodes->length > 0 && $group_array[$i][$first_key]['group'] != Lang::get('app.index_page'))
									{
										$group_array[$i][$first_key]['selected'] = TRUE;
										
										$base_path .= '/'.$group_array[$i][$first_key]['group'];
								
										$base_xpath .= sprintf('[@%s=\'%s\']/%s', GROUP_ATTR_NAME, $group_array[$i][$first_key]['group'], GROUP_NODE);
										
										$navigation[] = $group_array[$i][$first_key]['group'];
										
										$have_selected = TRUE;
										
										$group_array[$i][] = array('path' => $partial_path.'/'.'all', 'group' => Lang::get('app.all_groups'), 'selected' => FALSE);
										$group_array[$i][] = array('path' => $partial_path.'/'.'authors', 'group' => Lang::get('app.authors'), 'selected' => FALSE);
									}
									
									if (isset($group_array[$i][0]) && $have_selected === FALSE)
									{
										$group_array[$i][] = array('path' => $partial_path.'/'.'all', 'group' => Lang::get('app.all_groups'), 'selected' => TRUE);
										$group_array[$i][] = array('path' => $partial_path.'/'.'authors', 'group' => Lang::get('app.authors'), 'selected' => FALSE);
										$navigation[] = $segments[$i];
									}
								}
							}
							else
							{
								if ($nodes->length > 0)
								{
									reset($group_array[$i]);
									$first_key = key($group_array[$i]);
									
									$group_array[$i][$first_key]['selected'] = TRUE;
									
									$base_path .= '/'.$group_array[$i][$first_key]['group'];
									
									$base_xpath .= sprintf('[@%s=\'%s\']/%s', GROUP_ATTR_NAME, $group_array[$i][$first_key]['group'], GROUP_NODE);
									
									$navigation[] = $group_array[$i][$first_key]['group'];
									
									$have_selected = TRUE;
									
									$group_array[$i][] = array('path' => $partial_path.'/'.'all', 'group' => Lang::get('app.all_groups'), 'selected' => FALSE);
									$group_array[$i][] = array('path' => $partial_path.'/'.'authors', 'group' => Lang::get('app.authors'), 'selected' => FALSE);
								}
									
								if (isset($group_array[$i][0]) && $have_selected === FALSE)
								{
									$group_array[$i][] = array('path' => $partial_path.'/'.'all', 'group' => Lang::get('app.all_groups'), 'selected' => TRUE);
									$group_array[$i][] = array('path' => $partial_path.'/'.'authors', 'group' => Lang::get('app.authors'), 'selected' => FALSE);
									$navigation[] = '-';
								}
							}
						}
						else
						{
							$group_array[$i][] = array('path' => $partial_path.'/'.'all', 'group' => Lang::get('app.all_groups'), 'selected' => FALSE);
							$group_array[$i][] = array('path' => $partial_path.'/'.'authors', 'group' => Lang::get('app.authors'), 'selected' => FALSE);
						}
						
				}
				
				if ($have_selected === TRUE)
				{
					$have_selected = FALSE;
				}
				else
				{
					break;
				}
			}
		}

		//print_r($group_array);
		//print_r($navigation);
		//return array('tabs' => $group_array, 'navigation' => $navigation);
		
		Session::put('navigation.tabs', $group_array);
		Session::put('navigation.segments', $navigation);
	}
	
}