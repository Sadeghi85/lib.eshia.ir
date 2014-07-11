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
	
	// Search
	Route::get('/search/{query}', array('uses' => 'SearchController@showPage'))
	->where('query', '.+');
	Route::get('/search/{id}/{query}', array('uses' => 'SearchController@showPage'))
	->where(array('id' => '\d+', 'query' => '.+'));
	
	// Page
	Route::match(array('GET', 'POST'), '/{id}/{volume?}/{page?}/{highlight?}', array('uses' => 'PageController@showPage'))
	->where(array('id' => '\d+', 'volume' => '\d+', 'page' => '\d+', 'highlight' => '[^/]+'));

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
	Route::get('/pdf/{id}/{volume}', array('uses' => 'PdfController@showPage'))
	->where('id', '\d+')->where('volume', '\d+');

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