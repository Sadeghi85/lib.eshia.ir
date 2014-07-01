<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/






Route::group(array('before' => 'needs.xml.navigation'), function()
{
	// search
	Route::get('/search/{term}', function($term)
	{
		$sphinx = new \Sphinx\SphinxClient;
		
		$sphinx->setServer('127.0.0.1', 9312);
		
		$sphinx->resetFilters();
		
		$sphinx->setMatchMode(\Sphinx\SphinxClient::SPH_MATCH_EXTENDED);
		$sphinx->setRankingMode(\Sphinx\SphinxClient::SPH_RANK_SPH04);
		$sphinx->setSortMode(\Sphinx\SphinxClient::SPH_SORT_EXTENDED, '@weight DESC, modified_at DESC, path ASC');
		$sphinx->setLimits(0, 1000, 1000, 1000);
		
		$results = $sphinx->query($term, 'lib_eshia_ir');
		//dd($results);
		//die(print_r($results,true));
		
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
				//return View::make('404');
				App::abort('404');
			}
			
			$docs = array();
			foreach ($thisPage as $_page)
			{
				$docs[] = $_page['attrs']['path'];
			}
			
			$excerpts = $sphinx->buildExcerpts($docs, 'lib_eshia_ir_main', $term, array('query_mode' => true, 'load_files' => true, 'allow_empty' => true, 'before_match' => '<span class="hilight">', 'after_match' => '</span>'));

			for ($i = count($thisPage) - 1; $i >= 0; --$i)
			{
				$thisPage[$i]['attrs']['excerpt'] = $excerpts[$i];
			}

			$paginator = Paginator::make($thisPage, min($results['total'], 1000), $perPage);
			return View::make('search')->with(array('results' => $paginator, 'time' => $results['time'], 'result_count' => $results['total'], 'page' => $page, 'per_page' => $perPage));
		}
		
		//return View::make('search')->with(array('result_count' => 0, 'term' => $term));
		App::abort('404');
		
		

	});


	// Help
	Route::get('/help/{id?}', function ($id = 0)
	{
		if ($id == 0) $id = '01';
		return View::make('help/help-'.$id);
	})->where('id', '\d+');

	// Shenasnameh
	Route::get('/shenasnameh/{id}', function ($id)
	{
		if (($shenasnameh = file_get_contents(Config::get('app_settings.shenasnameh_url').'/'.$id)) && preg_replace('#[[:space:]\x{A0}]+#', '', preg_replace('#<[^<>]+>#', '', $shenasnameh))) {
			$content = $shenasnameh;
		} else {
			$content = Lang::get('app.shenasnameh_not_found');
		}
		return View::make('shenasnameh')->with(compact('content'));
	})->where('id', '\d+');
	
	// PDF
	Route::get('/pdf/{id}/{vol}', array('uses' => 'PdfController@showPage'))
	->where('id', '\d+')->where('vol', '\d+');
	
	// Page
	Route::get('/{id}/{segments?}', function($bookId)
	{
		return 'page';
	})->where('id', '\d+')->where('segments', '.*');

    // Catch all
	// |
	// ----query_not_found
	// ----index
	// ----author_booklist
	// ----authorlist
	// ----booklist
	Route::get('{all}', array('uses' => 'IndexController@showPage'))
	->where('all', '.*');
	
});




#######################
# 1. tabs and navigation creator in form of "before filter" for routes that need it, will be two arrays that will be put in session
# 2. view composer for "navigation partial" that will get the tabs array from session
# 3. navigation array is the "correct" url segments; routes that need it will retrieve it from the session
# 4. remove tabs and navigation arrays from the session at the end of application
#######################


// $route['(:num)/?(:num)?/?(:num)?'] = ($_SERVER['REQUEST_METHOD'] == 'GET') ? 'page/index/$1/$2/$3' : 'page/get_page';
// $route['(:num)/(:num)/(:num)/(:any)'] = ($_SERVER['REQUEST_METHOD'] == 'GET') ? 'page/index/$1/$2/$3/$4' : 'page/get_page';
// $route['search/(:num)/(:any)'] = 'page/search_page/$1/$2';
// $route['search/(:num)/(:any)/(:num)'] = 'page/search_page/$1/$2/$3';
// $route['search/(:any)'] = 'search/index/$1';
// $route['search/(:any)/(:num)'] = 'search/index/$1/$2';




// Catch all
// one of these:
// 1. segment[1] => author => list author's books
// 2. chain of groups => list books in last child group
// 3. 404 not found
// Route::get('{all}', function($uri)
// {
    // return View::make('hello');
// })->where('all', '.*');