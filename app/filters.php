<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request)
{
	//app('request')->server->set('REQUEST_URI', str_replace('_', '%20', Request::server('REQUEST_URI')));

	Session::forget('page.is.cacheable');
	Session::forget('exception.error.message');
	Session::forget('navigation.tabs');
	Session::forget('navigation.segments');
	
	if
	(
			true  === Config::get('app_settings.cache_enable')
		and
			false === Input::get(Config::get('app_settings.cache_bypass'), false)
		and
			Cache::has(Helpers::getEncodedRequestUri())
	)
	{
		$response = Response::make(Cache::get(Helpers::getEncodedRequestUri()), 200);
		$response->header('X-Cache', 'HIT');
		return $response;
	}

});


App::after(function($request, $response)
{
	if
	(
			true  === Config::get('app_settings.cache_enable')
		and
			false === Input::get(Config::get('app_settings.cache_bypass'), false)
		and
			$response->getStatusCode() == 200
		and
			Session::get('page.is.cacheable', false)
	)
	{
		Cache::put(Helpers::getEncodedRequestUri(), $response->getContent(), Config::get('app_settings.cache_timeout'));
	}
	
	
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function()
{
	if (Auth::guest())
	{
		if (Request::ajax())
		{
			return Response::make('Unauthorized', 401);
		}
		else
		{
			return Redirect::guest('login');
		}
	}
});


Route::filter('auth.basic', function()
{
	return Auth::basic();
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
	if (Auth::check()) return Redirect::to('/');
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
	if (Session::token() != Input::get('_token'))
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});


Route::filter('needs.xml.navigation', function()
{
	header('Content-Type: text/html; charset=utf-8');
	header('X-UA-Compatible: IE=edge');
	
	Helpers::loadXML();
	
	Helpers::renderNavigation();
	
	
});