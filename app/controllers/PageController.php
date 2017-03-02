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

	public function showPage($id, $volume = 0, $page = 0, $highlight = null)
	{
		$id = (int) $id;
		$volume = (preg_match('#[^0-9]#', $volume)) ? $volume : ((int) $volume);
		$page = (int) $page;
		
		if (Request::server('REQUEST_METHOD') == 'POST')
		{
			return Redirect::to(sprintf('%s/%s/%s', (int) Input::get('id', $id), ((preg_match('#[^0-9]#', Input::get('volume', $volume))) ? Input::get('volume', $volume) : ((int) Input::get('volume', $volume))), (int) Input::get('page', $page)));  // url should be updated too...
		}
		
		// دانشنامه جهان اسلام
		if ($id == 23019 and $volume > 1)
		{
			return Redirect::to(sprintf('%s/%s/%s', 23019, 1, $page));
		}
		
		$xpath = new DOMXpath($this->_xmlObject);
		
		// Redirect by ID and Vol
		$xpathQuery = sprintf('//%s[@%s=\'%s\'][@%s=\'%s\']', REDIRECT_NODE, REDIRECT_FROM_ID_NODE, $id, REDIRECT_FROM_VOL_NODE, $volume);
		$redirect = $xpath->query($xpathQuery, $this->_xmlObject);
		
		if ($redirect->length)
		{
			if ($redirect[0]->hasAttribute(REDIRECT_TO_ID_NODE))
			{
				$redirectToId = $redirect[0]->getAttribute(REDIRECT_TO_ID_NODE);
				$redirectToVol = $volume;
				if ($redirect[0]->hasAttribute(REDIRECT_TO_VOL_NODE))
				{
					$redirectToVol = $redirect[0]->getAttribute(REDIRECT_TO_VOL_NODE);
				}
				
				return Redirect::to(sprintf('%s/%s/%s/%s', $redirectToId, $redirectToVol, $page, $highlight));
			}
		}
		
		// Redirect by just ID
		$xpathQuery = sprintf('//%s[@%s=\'%s\']', REDIRECT_NODE, REDIRECT_FROM_ID_NODE, $id);
		$redirect = $xpath->query($xpathQuery, $this->_xmlObject);
		
		if ($redirect->length)
		{
			if ($redirect[0]->hasAttribute(REDIRECT_TO_ID_NODE))
			{
				$redirectToId = $redirect[0]->getAttribute(REDIRECT_TO_ID_NODE);
				$redirectToVol = $volume;
				if ($redirect[0]->hasAttribute(REDIRECT_TO_VOL_NODE))
				{
					$redirectToVol = $redirect[0]->getAttribute(REDIRECT_TO_VOL_NODE);
				}
				
				return Redirect::to(sprintf('%s/%s/%s/%s', $redirectToId, $redirectToVol, $page, $highlight));
			}
		}
		
		$xpathQuery = sprintf('//%s[@%s=\'%s\']/%s/%s', BOOK_NODE, BOOK_ATTR_NAME, $id, VOLUMES_NODE, VOL_NODE);
		
		$vols = $xpath->query($xpathQuery, $this->_xmlObject);
		
		if ($vols->length == 0)
		{
			Log::error(sprintf('Book with id "%s" has no volume or doesn\'t exist.', $id));
			Helpers::setExceptionErrorMessage(Lang::get(sprintf('%s/app.query_search_result_not_found', Config::get('app_settings.theme')), array('query' => sprintf('"%s"', Request::segment(1)))));
			
			App::abort(404);
		}
		
		$volumes = array();
		$volumeOptions = array();
		
		foreach ($vols as $volNode)
		{
			if ($volNode->hasAttribute(VOL_ATTR_ID))
			{
				$volID = $volNode->getAttribute(VOL_ATTR_ID);
				$volID = (preg_match('#[^0-9]#', $volID)) ? $volID : ((int) $volID);

				$volumes[$volID]['indexpage'] = (int) $volNode->getAttribute(VOL_ATTR_INDEX);
				$volumes[$volID]['base']      = (int) $volNode->getAttribute(VOL_ATTR_BASE);
				$volumes[$volID]['pages']     = (int) $volNode->getAttribute(VOL_ATTR_PAGES);
			}
		}
		
		ksort($volumes);
		
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
			$volumeOptions[$key] = ($key === 0 ? Lang::get(sprintf('%s/app.index_volume', Config::get('app_settings.theme'))) : $key);
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
		
		$booksPath = Config::get('app_settings.books_path');
		
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
			$_utf8Content = @iconv('UTF-8', 'UTF-8//IGNORE', file_get_contents($pagePath));
			If ( ! $_utf8Content)
			{
				Log::error(sprintf('Detected an incomplete multibyte character in book id="%s", volume="%s", page="%s".', $id, $volume, $page));
				Helpers::setExceptionErrorMessage(Lang::get(sprintf('%s/app.page_display_error', Config::get('app_settings.theme'))));
				
				App::abort('404');
			}
			
			$content =  preg_replace('#\p{Cf}+#u', pack('H*', 'e2808c'),
							str_replace([pack('H*', 'c2a0'), '&#160;'], ' ',
								str_replace([pack('H*', 'efbbbf'), '&#65279;'], ' ',
									str_replace(pack('H*', '00'), '',
										$_utf8Content
									)
								)
							)
						);
			
			if ($highlight)
			{
				if ( ! (preg_match('#\/search\/(\d+\/)?[^\/]+#', Request::server('HTTP_REFERER')) or preg_match('#[\=\!\"\*\/\(\)\[\]\~\<\>\^\$\:\|\@\-]#', $highlight)))
				{
					$highlight = preg_replace('#([\p{N}\p{L}][\p{N}\p{L}\p{M}]*)#iu', '=$1', $highlight);
				}
				
				$_content =  with(new \Sphinx\SphinxClient)->buildExcerpts(compact('content'), Config::get('app_settings.search_index_main_name', 'lib_eshia_ir_main'), $highlight,
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
				
				$content =  is_array($_content) ? (($_content = array_values($_content)[0]) != '' ? $_content : $content) : $content;
			}
		}
		else
		{
			Log::error(sprintf('File "%s" doesn\'t exist.', $pagePath));
			
			$content = Lang::get(sprintf('%s/app.book_page_not_found_message', Config::get('app_settings.theme')));
		}
		
		//return View::make('page')->with(compact('id', 'volume', 'page', 'volumeOptions', 'bookName', 'authorName', 'indexPage', 'firstPage', 'lastPage', 'prevPage', 'nextPage', 'content'));
		$this->layout->content = View::make(sprintf('%s/page', Config::get('app_settings.theme')), compact('id', 'volume', 'page', 'volumeOptions', 'bookName', 'authorName', 'indexPage', 'firstPage', 'lastPage', 'prevPage', 'nextPage', 'content'));
	}
}