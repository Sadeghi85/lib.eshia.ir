<?php

class SearchController extends BaseController {
	
	protected $_xmlObject = null;
	
	/**
	 * Initializer.
	 */
	public function __construct()
	{
		parent::__construct();
		
		$this->_xmlObject = Helpers::getXMLObject();
		
	}
	
	public function showPage($id, $query = null)
	{
		if (is_null($query))
		{
			$query = $id;
			$id = null;
		}
		
		$page = Input::get('page', 1);
		$perPage = Config::get('app_settings.results_per_page', 10);  //number of results per page
		
		$xpath = new DOMXpath($this->_xmlObject);
		
		$sphinx = new \Sphinx\SphinxClient;
		
		$sphinx->setServer('127.0.0.1', 9312);
		
		$sphinx->resetFilters();
		
		$sphinx->setMatchMode(\Sphinx\SphinxClient::SPH_MATCH_EXTENDED);
		//$sphinx->setRankingMode(\Sphinx\SphinxClient::SPH_RANK_PROXIMITY_BM25);
		$sphinx->setRankingMode(\Sphinx\SphinxClient::SPH_RANK_EXPR, 'sum((lcs*(1+exact_order+(1/(1+min_gaps))*(word_count>1))+wlccs)*user_weight)*1000+bm25');
		$sphinx->setSortMode(\Sphinx\SphinxClient::SPH_SORT_EXTENDED, '@relevance DESC, modified_at ASC, @id ASC');
		
		( ! is_null($id)) ? $sphinx->setFilter('bookid', array($id)) : $sphinx->setFilter('bookid', Helpers::getBookIdArray());
		
		$sphinx->setLimits(($page - 1) * $perPage, $perPage, 1000);
		
		$sphinx->setMaxQueryTime(3000); // in mili-seconds
		
		$sphinx->setArrayResult(true);
		
		$results = $sphinx->query($query, 'lib_eshia_ir');
		
		if (isset($results['matches']))
		{
			$thisPage = $results['matches'];
			
			$docs = array();
			foreach ($thisPage as $_page)
			{
				$docs[] = $_page['attrs']['path'];
			}
			
			$excerpts = $sphinx->buildExcerpts($docs, 'lib_eshia_ir_main', $query,
							array
							(
								'query_mode' => true,
								'exact_phrase' => true,
								'weight_order' => false,
								'load_files' => true,
								'allow_empty' => false,
								'before_match' => '<span class="hilight">',
								'after_match' => '</span>'
							));

			for ($i = count($thisPage) - 1; $i >= 0; --$i)
			{
				$xpathQuery = sprintf('//%s[@%s=\'%s\']', BOOK_NODE, BOOK_ATTR_NAME, $thisPage[$i]['attrs']['bookid']);
				$bookNode = $xpath->query($xpathQuery, $this->_xmlObject);
				
				$thisPage[$i]['attrs']['bookName'] = $bookNode->item(0)->getAttribute(BOOK_ATTR_DISPLAYNAME);
				$thisPage[$i]['attrs']['excerpt'] = $excerpts[$i];
			}

			$paginator = Paginator::make($thisPage, min($results['total'], 1000), $perPage);
			return View::make('search')->with(array('results' => $paginator, 'time' => $results['time'], 'resultCount' => $results['total'], 'page' => $page, 'perPage' => $perPage, 'query' => urlencode($query), 'id' => $id));
		}
		
		if ($page > 1)
		{
			return Redirect::to('/search/'.str_replace(array(' ', '%20'), '_', $query));
		}
		
		if (isset($results['warning']) and $results['warning'] == 'query time exceeded max_query_time')
		{
			Session::put('exception.error.message', Lang::get('app.query_timed_out'));
		}
		else
		{
			Session::put('exception.error.message', Lang::get('app.query_search_result_not_found', array('query' => sprintf('"%s"', $query))));
		}
		
		App::abort('404');
	}
	
	public function showAdvancedPage()
	{
		$xpath = new DOMXpath($this->_xmlObject);
		
		$xpathQuery = sprintf('//%s', GROUP_NODE);
		$groupNode = $xpath->query($xpathQuery, $this->_xmlObject);
		
		$groupArray = array();
		
		foreach ($groupNode as $group)
		{
			$depth = 0;
			$groupKey = sprintf('/%s', BOOK_NODE);
			$node = $group;
			
			do
			{
				if (($node instanceof DOMElement) and ($node->hasAttribute(GROUP_ATTR_NAME)))
				{
					++$depth;
					$groupKey = sprintf('%s[@%s=\'%s\']/%s', GROUP_NODE, GROUP_ATTR_NAME, $node->getAttribute(GROUP_ATTR_NAME), $groupKey);
					
				}
			}
			while ($node = $node->parentNode);
			
			$groupKey = Crypt::encrypt(sprintf('/%s/%s', MAIN_NODE, $groupKey));
			
			$groupArray[$groupKey] = sprintf('%s&nbsp;%s', str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $depth).str_repeat('-', $depth), $group->getAttribute(GROUP_ATTR_NAME));
		}
		
		return View::make('advanced_search')->with('groupArray', $groupArray);
	}
	
	public function processAdvancedPage()
	{
		if (Input::get('groupWhere', '') == 'groupOne')
		{
			$groupKey = Input::get('groupKey', '');
		}
		
		$and = Input::get('and', '');
		$and = trim(preg_replace('#[[:space:]]+#', ' ', $and));
		
		$or = Input::get('or', '');
		$or = trim(preg_replace('#[[:space:]]+#', ' ', $or));
		if ($or) { $or = sprintf('(%s)', preg_replace('# #', ' | ', $or)); }
		
		$not = Input::get('not', '');
		$not = trim(preg_replace('#[[:space:]]+#', ' ', $not));
		if ($not) { $not = preg_replace('#(?:^|(?<= ))([^[:space:]]+)#', '!$1', $not); }
		
		$phrase = Input::get('phrase', '');
		if ($phrase) { $phrase = sprintf('"%s"', trim(preg_replace('#[[:space:]]+#', ' ', $phrase))); }
		
		$query = trim(preg_replace('#[[:space:]]+#', ' ', sprintf('%s %s %s %s', $phrase, $or, $not, $and)));
		
		return (isset($groupKey) ? Helpers::redirect(sprintf('/search/%s?groupKey=%s', urlencode($query), urlencode($groupKey))) : Helpers::redirect(sprintf('/search/%s', urlencode($query))));
	}
	
	
}