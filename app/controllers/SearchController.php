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
		
		$xpath = new DOMXpath($this->_xmlObject);
		
		$sphinx = new \Sphinx\SphinxClient;
		
		$sphinx->setServer('127.0.0.1', 9312);
		
		$sphinx->resetFilters();
		
		$sphinx->setMatchMode(\Sphinx\SphinxClient::SPH_MATCH_EXTENDED);
		$sphinx->setRankingMode(\Sphinx\SphinxClient::SPH_RANK_SPH04);
		$sphinx->setSortMode(\Sphinx\SphinxClient::SPH_SORT_EXTENDED, '@relevance DESC, modified_at DESC, @id DESC');
		
		if ( ! is_null($id))
		{
			$sphinx->setFilter('bookid', array($id));
		}
		else
		{
			$sphinx->setFilter('bookid', Helpers::getBookIdArray());
		}
		
		$sphinx->setLimits(0, 1000, 1000, 1000);
		
		$results = $sphinx->query($query, 'lib_eshia_ir');
		#dd($results);
		#//die(print_r($results,true));
		
		$page = Input::get('page', 1);
		$perPage = 10;  //number of results per page
		
		if ($results['total'])
		{
			$pages = array_chunk($results['matches'], $perPage);
			
			if (isset($pages[$page - 1]))
			{
				$thisPage = $pages[$page - 1];
			} else {
				#//dd(count($pages));
				#//return View::make('404');
				//Session::put('exception.error.message', Lang::get('app.query_search_result_not_found', array('query' => sprintf('"%s"', $query))));
				//App::abort('404');
				return Redirect::to('/search/'.$query);
			}
			
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
		
		#//return View::make('search')->with(array('result_count' => 0, 'query' => $query));
		Session::put('exception.error.message', Lang::get('app.query_search_result_not_found', array('query' => sprintf('"%s"', $query))));
		App::abort('404');
		
		
		
		
		
		
		
		
		
		// $xpath = new DOMXpath($this->_xmlObject);
		
		// $booksPath = Config::get('app_settings.books_path');
		
		// $xpathQuery = sprintf('//%s[@%s=\'%s\']/%s/%s', BOOK_NODE, BOOK_ATTR_NAME, $id, VOLUMES_NODE, VOL_NODE);
		
		// $vols = $xpath->query($xpathQuery, $this->_xmlObject);
		
		// if ($vols->length == 0)
		// {
			// Log::error("Book entry '{$id}' has no volume or it doesn't exist. ( ". __FILE__ .' on line '. __LINE__ .' )');
			// Session::put('exception.error.message', Lang::get('app.query_search_result_not_found', array('query' => sprintf('"%s"', Request::segment(1)))));
			// App::abort(404);
		// }
		
		
		
		// return View::make('page')->with(compact('id', 'volume', 'page', 'volumeOptions', 'bookName', 'authorName', 'indexPage', 'firstPage', 'lastPage', 'prevPage', 'nextPage', 'content'));
	}
}