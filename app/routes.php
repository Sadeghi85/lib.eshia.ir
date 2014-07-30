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

// Ajax Search
Route::post('/ajax/search/{rnd?}', array('uses' => 'AjaxController@showAjax'));

Route::group(array('before' => 'needs.xml.navigation'), function()
{
	// Search
	Route::get('/search/{id}/{query}', array('uses' => 'SearchController@showPage'))
	->where(array('id' => '\d+', 'query' => '.+'));
	Route::get('/search/{query}', array('uses' => 'SearchController@showPage'))
	->where('query', '.+');
	// Advanced Search
	Route::get('/advanced-search', array('uses' => 'SearchController@showAdvancedPage'));
	Route::post('/advanced-search', array('uses' => 'SearchController@processAdvancedPage'));
	
	// Page
	Route::match(array('GET', 'POST'), '/{id}/{volume?}/{page?}/{highlight?}', array('uses' => 'PageController@showPage'))
	->where(array('id' => '\d+', 'volume' => '\d+', 'page' => '\d+', 'highlight' => '.+'));

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

