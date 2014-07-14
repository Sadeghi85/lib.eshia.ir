<?php

class SearchController extends BaseController {

	protected $_persianizedXMLObject = null;
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

	public function showPage($id, $query = null)
	{
		if (is_null($query))
		{
			$query = $id;
			$id = null;
		}
		
		$page = Input::get('page', 1);
		$perPage = 10;  //number of results per page
		
		$xpath = new DOMXpath($this->_xmlObject);
		
		$sphinx = new \Sphinx\SphinxClient;
		
		$sphinx->setServer('127.0.0.1', 9312);
		
		$sphinx->resetFilters();
		
		$sphinx->setMatchMode(\Sphinx\SphinxClient::SPH_MATCH_EXTENDED);
		//$sphinx->setRankingMode(\Sphinx\SphinxClient::SPH_RANK_PROXIMITY_BM25);
		$sphinx->setRankingMode(\Sphinx\SphinxClient::SPH_RANK_EXPR, 'sum((lcs*(1+exact_order+(1/(1+min_gaps))*(word_count>1))+wlccs)*user_weight)*1000+bm25');
		$sphinx->setSortMode(\Sphinx\SphinxClient::SPH_SORT_EXTENDED, '@relevance DESC, modified_at ASC, @id ASC');
		
		if ( ! is_null($id))
		{
			$sphinx->setFilter('bookid', array($id));
		}
		else
		{
			$sphinx->setFilter('bookid', Helpers::getBookIdArray());
		}
		
		$sphinx->setLimits(($page - 1) * $perPage, $perPage, 1000);
		
		$sphinx->setMaxQueryTime(2000); // in mili-seconds
		
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
			return View::make('search')->with(array('results' => $paginator, 'time' => $results['time'], 'resultCount' => $results['total'], 'page' => $page, 'perPage' => $perPage, 'query' => urlencode($query)));
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
}