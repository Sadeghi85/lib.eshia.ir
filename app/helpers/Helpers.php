<?php

class Helpers {

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
	
	public static function loadXML()
	{
		if ( ! Cache::has('xml.object'))
		{
			$xml_path = base_path() . Config::get('app_settings.xml_path');
			
			$xml_content = file_get_contents($xml_path);
			
			$xml = new SerializableDomDocument;
			//$xml->formatOutput = true;
			
			try	{
				$xml->loadxml($xml_content, LIBXML_NOBLANKS);
			}
			catch (\Exception $e) {
				Log::error('Error loading xml. ( '. __FILE__ .' on line '. __LINE__ .' )');
				Session::put('exception.error.message', Lang::get('app.page_display_error'));
				App::abort(500);
			}

			// Persianize
			$persianized_xml = new SerializableDomDocument;
			//$persianized_xml->formatOutput = true;
			
			$xml_content = preg_replace('#\x{EF}\x{BB}\x{BF}#', '', $xml_content);
			$xml_content = preg_replace('#\p{Cf}+#iu', pack('H*', 'e2808c'), $xml_content);
			
			$xml_content = preg_replace_callback('#[\'"]([^\r\n\'"]*)[\'"]#',
					function ($matches)
					{
						return '"'.trim($matches[1]).'"';
					}, $xml_content);
					
			$xml_content = self::persianizeString($xml_content);
			
			try	{
				$persianized_xml->loadxml($xml_content, LIBXML_NOBLANKS);
			}
			catch (\Exception $e) {
				Log::error('Error loading persianized xml. ( '. __FILE__ .' on line '. __LINE__ .' )');
				Session::put('exception.error.message', Lang::get('app.page_display_error'));
				App::abort(500);
			}
			
			Cache::put('xml.object', serialize($xml), \Carbon\Carbon::now()->addMinutes(60));
			Cache::put('persianized.xml.object', serialize($persianized_xml), \Carbon\Carbon::now()->addMinutes(60));
			
			$xml = null;
			$persianized_xml = null;
		}
	}
	
	public static function renderNavigation()
	{
		// one based array
		$segments = array_map('urldecode', Request::segments());
		array_unshift($segments, '');
		unset($segments[0]);
		
		//dd($segments);
		
		$xml = unserialize(Cache::get('xml.object'));
		
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