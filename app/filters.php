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
	Session::forget('page.is.cacheable');
	Session::forget('exception.error.message');
	Session::forget('navigation.tabs');
	Session::forget('navigation.segments');
	
	if (Helpers::getModifiedDateHash(Helpers::getCacheableFiles()) == Cache::get('cache.files.date.hash', 0) and Cache::has(Helpers::getEncodedRequestUri()))
	{
		$response = Response::make(Cache::get(Helpers::getEncodedRequestUri()), 200);
		$response->header('X-Cache', 'HIT');
		return $response;
	}
});

App::after(function($request, $response)
{
	if ($response->getStatusCode() == 200 and Session::get('page.is.cacheable', false))
	{
		Cache::forever(Helpers::getEncodedRequestUri(), $response->getContent());
	}
	
	Cache::forever('cache.files.date.hash', Helpers::getModifiedDateHash(Helpers::getCacheableFiles()));
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
	
	//Helpers::loadXML();
	
	Helpers::renderNavigation();
	
	
});