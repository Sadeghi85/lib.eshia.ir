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
	switch (preg_replace('#^([^.]+).+$#', '$1', strtolower(Request::server('HTTP_HOST'))))
	{
		case 'ar':
			App::setLocale('ar');
			Config::set('app.locale', 'ar');
		break;
		case 'en':
			App::setLocale('en');
			Config::set('app.locale', 'en');
		break;
		default:
			App::setLocale('fa');
			Config::set('app.locale', 'fa');
	}
	
	define('REDIRECT_NODE', Config::get('xml_settings.redirect_node'));
	define('REDIRECT_FROM_NODE', Config::get('xml_settings.redirect_from_node'));
	define('REDIRECT_TO_NODE', Config::get('xml_settings.redirect_to_node'));
	
	define('MAIN_NODE', Config::get('xml_settings.main_node'));
	define('GROUP_NODE', Config::get('xml_settings.group_node'));
	define('GROUP_ATTR_NAME', Config::get('xml_settings.group_attr_name'));
	define('GROUP_ATTR_ORDER', Config::get('xml_settings.group_attr_order'));
	define('BOOK_NODE', Config::get('xml_settings.book_node'));
	define('BOOK_ATTR_NAME', Config::get('xml_settings.book_attr_name'));
	define('BOOK_ATTR_DISPLAYNAME', Config::get('xml_settings.book_attr_displayname'));
	define('BOOK_ATTR_AUTHOR', Config::get('xml_settings.book_attr_author'));
	define('VOLUMES_NODE', Config::get('xml_settings.volumes_node'));
	define('VOL_NODE', Config::get('xml_settings.vol_node'));
	define('VOL_ATTR_ID', Config::get('xml_settings.vol_attr_id'));
	define('VOL_ATTR_INDEX', Config::get('xml_settings.vol_attr_index'));
	define('VOL_ATTR_BASE', Config::get('xml_settings.vol_attr_base'));
	define('VOL_ATTR_PAGES', Config::get('xml_settings.vol_attr_pages'));

	Session::put('REMOTE_ADDR', Request::server('REMOTE_ADDR'));
	Session::put('HTTP_USER_AGENT', Request::server('HTTP_USER_AGENT'));
	Session::put('HTTP_REFERER', Request::server('HTTP_REFERER'));
	
	if (Request::server('REQUEST_METHOD') == 'GET' and Helpers::getModifiedDateHash(Helpers::getCacheableFiles()) == Cache::tags(strtolower(Request::server('HTTP_HOST')))->get('cache.files.date.hash', 0) and Cache::tags(strtolower(Request::server('HTTP_HOST')))->has(Helpers::getEncodedRequestUri()))
	{
		$response = Response::make(Cache::tags(strtolower(Request::server('HTTP_HOST')))->get(Helpers::getEncodedRequestUri()), 200);
		$response->header('X-Cache', 'HIT');
		return $response;
	}
});

App::after(function($request, $response)
{
	if (defined('PAGE_IS_CACHEABLE') and $response->isOk() and strlen($response->getContent()) > 0)
	{
		Cache::tags(strtolower(Request::server('HTTP_HOST')))->put(Helpers::getEncodedRequestUri(), $response->getContent(), 24 * 60);
	}
	
	Cache::tags(strtolower(Request::server('HTTP_HOST')))->put('cache.files.date.hash', Helpers::getModifiedDateHash(Helpers::getCacheableFiles()), 24 * 60);
	
	if(App::Environment() != 'local')
    {
        if( ! defined('PAGE_IS_CACHEABLE') and $response instanceof \Illuminate\Http\Response)
        {
            $output = $response->getOriginalContent();
			
			$output = str_replace("\r\n", '<crlf>', $output);
			$output = preg_replace('#[[:space:]]+#u', ' ', $output);
			$output = str_replace('<crlf>', "\r\n", $output);
			$output = preg_replace('#[\r\n]+\s*[\r\n]*#u', "\r\n", $output);
			
            $response->setContent($output);
        }
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
	
	$navigation = Helpers::renderNavigation();
	
});