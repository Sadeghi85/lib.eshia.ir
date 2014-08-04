<?php

class PageController extends BaseController {
	
	protected $_xmlObject = null;
	
	/**
	 * Initializer.
	 */
	public function __construct()
	{
		parent::__construct();
		
		$this->_xmlObject = Helpers::getXMLObject();
		// $this->_persianizedXMLObject = Helpers::getPersianizedXMLObject();
		
	}

	public function showPage($id, $volume = -1, $page = -1, $highlight = null)
	{
		if (Request::server('REQUEST_METHOD') == 'POST')
		{
			return Redirect::to(sprintf('%s/%s/%s', (int) Input::get('id', $id), (int) Input::get('volume', $volume), (int) Input::get('page', $page)));  // url should be updated too...
		}
		
		$xpath = new DOMXpath($this->_xmlObject);
		
		$booksPath = Config::get('app_settings.books_path');
		
		$xpathQuery = sprintf('//%s[@%s=\'%s\']/%s/%s', BOOK_NODE, BOOK_ATTR_NAME, $id, VOLUMES_NODE, VOL_NODE);
		
		$vols = $xpath->query($xpathQuery, $this->_xmlObject);
		
		if ($vols->length == 0)
		{
			Log::error("Book entry '{$id}' has no volume or it doesn't exist. ( ". __FILE__ .' on line '. __LINE__ .' )');
			Session::put('exception.error.message', Lang::get('app.query_search_result_not_found', array('query' => sprintf('"%s"', Request::segment(1)))));
			App::abort(404);
		}
		
		$volumes = array();
		$volumeOptions = array();
		
		foreach ($vols as $volNode)
		{
			if ($volNode->hasAttribute(VOL_ATTR_ID))
			{
				$volumes[(int) $volNode->getAttribute(VOL_ATTR_ID)]['indexpage'] = (int) $volNode->getAttribute(VOL_ATTR_INDEX);
				$volumes[(int) $volNode->getAttribute(VOL_ATTR_ID)]['base']      = (int) $volNode->getAttribute(VOL_ATTR_BASE);
				$volumes[(int) $volNode->getAttribute(VOL_ATTR_ID)]['pages']     = (int) $volNode->getAttribute(VOL_ATTR_PAGES);
			}
		}
		
		if (isset($volumes[$volume]))
		{
			if ($page > $volumes[$volume]['base'] + $volumes[$volume]['pages'] - 1)
			{
				$page = $volumes[$volume]['base'] + $volumes[$volume]['pages'] - 1;
				
				return Redirect::to(sprintf('%s/%s/%s', $id, $volume, $page));  // url should be updated too...
			}
			elseif ($page < $volumes[$volume]['base'])
			{
				$page = $volumes[$volume]['base'];
				
				return Redirect::to(sprintf('%s/%s/%s', $id, $volume, $page));  // url should be updated too...
			}
		}
		else
		{
			$volume = array_keys($volumes)[0];
			$page = $volumes[$volume]['base'];
			
			return Redirect::to(sprintf('%s/%s/%s', $id, $volume, $page));  // url should be updated too...
		}
		
		foreach (array_keys($volumes) as $key)
		{
			$volumeOptions[$key] = $key;
		}
		
		$indexPage = $volumes[$volume]['indexpage'];
		$firstPage = $volumes[$volume]['base'];
		$prevPage  = $page - 1;
		$nextPage  = $page + 1;
		$lastPage  = $volumes[$volume]['base'] + $volumes[$volume]['pages'] - 1;
		
		if ($page == $volumes[$volume]['base'])
		{
			$firstPage = null;
			$prevPage  = null;
		}
		
		if ($page == ($volumes[$volume]['base'] + $volumes[$volume]['pages'] - 1))
		{
			$nextPage = null;
			$lastPage = null;
		}

		$bookName   = $vols->item(0)->parentNode->parentNode->getAttribute(BOOK_ATTR_DISPLAYNAME);
		$authorName = $vols->item(0)->parentNode->parentNode->getAttribute(BOOK_ATTR_AUTHOR);
		
		if ($vols->length >= 100)
		{
			$pagePath = sprintf('%s\\%s\\%s\\%s.html', $booksPath, $id,
				($volume >= 100 ? $volume : ($volume >= 10 ? '0' . $volume : '00' . $volume)),
				($page   >= 100 ? $page   : ($page   >= 10 ? '0' . $page   : '00' .   $page))
			);
		}
		else
		{
			$pagePath = sprintf('%s\\%s\\%s\\%s.html', $booksPath, $id,
				($volume >= 10  ? $volume : '0' . $volume),
				($page   >= 100 ? $page   : ($page   >= 10 ? '0' . $page   : '00' .   $page))
			);
		}
		
		if (is_readable($pagePath))
		{
			$content =  preg_replace('#[[:space:]]+#', ' ',
							preg_replace('#\p{Cf}+#u', pack('H*', 'e2808c'),
								str_replace(pack('H*', 'c2a0'), '',
									str_replace(pack('H*', 'efbbbf'), '',
										iconv('UTF-8', 'UTF-8//IGNORE',
											file_get_contents($pagePath)
										)
									)
								)
							)
						);
			
			if ($highlight)
			{
				$content =  with(new \Sphinx\SphinxClient)->buildExcerpts(compact('content'), 'lib_eshia_ir_main', $highlight,
								array
								(
									'query_mode' => true,
									'limit' => 0,
									'chunk_separator' => '',
									'exact_phrase' => true,
									'html_strip_mode' => 'retain',
									'load_files' => false,
									'allow_empty' => false,
									'before_match' => '<span class="hilight">',
									'after_match' => '</span>'
								)
							);
				
				$content =  array_values($content)[0];
			}
		}
		else
		{
			Log::error("File '{$pagePath}' doesn't exist. ( ". __FILE__ .' on line '. __LINE__ .' )');
			
			$content = Lang::get('app.book_page_not_found_message');
		}
		
		return View::make('page')->with(compact('id', 'volume', 'page', 'volumeOptions', 'bookName', 'authorName', 'indexPage', 'firstPage', 'lastPage', 'prevPage', 'nextPage', 'content'));
	}
}