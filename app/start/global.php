<?php

/*
|--------------------------------------------------------------------------
| Register The Laravel Class Loader
|--------------------------------------------------------------------------
|
| In addition to using Composer, you may use the Laravel class loader to
| load your controllers and models. This is useful for keeping all of
| your classes in the "global" namespace without Composer updating.
|
*/

ClassLoader::addDirectories(array(

	app_path().'/commands',
	app_path().'/controllers',
	app_path().'/models',
	app_path().'/database/seeds',

	app_path().'/helpers',
	app_path().'/view_composers',
));

/*
|--------------------------------------------------------------------------
| Application Error Logger
|--------------------------------------------------------------------------
|
| Here we will configure the error logger setup for the application which
| is built on top of the wonderful Monolog library. By default we will
| build a basic log file setup which creates a single file for logs.
|
*/

Log::useFiles(storage_path().'/logs/laravel.log');

/*
|--------------------------------------------------------------------------
| Application Error Handler
|--------------------------------------------------------------------------
|
| Here you may handle any errors that occur in your application, including
| logging them or displaying custom views for specific errors. You may
| even register several error handlers to handle different types of
| exceptions. If nothing is returned, the default error view is
| shown, which includes a detailed stack trace during debug.
|
*/

App::fatal(function($exception)
{
	Log::error($exception);
	
	if ( ! Config::get('app.debug'))
	{
		$message = Session::pull('exception.error.message', Lang::get('app.page_display_error'));
		return Response::view('error.error', compact('message'), 500);
	}
});

App::error(function(Exception $exception, $code)
{
	Log::error($exception);
	
	if ( ! Config::get('app.debug'))
	{
		$message = Session::pull('exception.error.message', Lang::get('app.page_display_error'));
		
		switch ($code)
		{
			case 403:
				return Response::view('error.error', compact('message'), 403);

			case 500:
				return Response::view('error.error', compact('message'), 500);
				
			case 503:
				return Response::view('error.error', compact('message'), 503);

			default:
				return Response::view('error.error', compact('message'), 404);
		}
	}
});

// App::error(function(Exception $exception, $code)
// {
	// Log::error($exception);
// });

App::error(function(Illuminate\Database\Eloquent\ModelNotFoundException $exception, $code)
{
	$message = Session::pull('exception.error.message', Lang::get('app.page_display_error'));
    return Response::view('error.error', compact('message'), 404);
});

App::missing(function($exception)
{
    $message = Session::pull('exception.error.message', Lang::get('app.page_display_error'));
    return Response::view('error.error', compact('message'), 404);
});

/*
|--------------------------------------------------------------------------
| Maintenance Mode Handler
|--------------------------------------------------------------------------
|
| The "down" Artisan command gives you the ability to put an application
| into maintenance mode. Here, you will define what is displayed back
| to the user if maintenance mode is in effect for the application.
|
*/

App::down(function()
{
	//return Response::make("Be right back!", 503);
	
	$message = Session::pull('exception.error.message', Lang::get('app.page_display_error'));
    return Response::view('error.error', compact('message'), 503);
});

/*
|--------------------------------------------------------------------------
| Require The Filters File
|--------------------------------------------------------------------------
|
| Next we will load the filters file for the application. This gives us
| a nice separate location to store our route and application filter
| definitions instead of putting them all in the main routes file.
|
*/

require app_path().'/filters.php';

/*
|--------------------------------------------------------------------------
| Validator Extends
|--------------------------------------------------------------------------
|
*/


/*
|--------------------------------------------------------------------------
| Blade Extends
|--------------------------------------------------------------------------
|
*/

Blade::extend(function($value)
{
	return preg_replace('/@php((.|\s)*?)@endphp/', '<?php $1 ?>', $value);
});

Blade::extend(function($value)
{
	return preg_replace_callback('/@comment((.|\s)*?)@endcomment/',
              function ($matches) {
                    return '<?php /* ' . preg_replace('/@|\{/', '\\\\$0\\\\', $matches[1]) . ' */ ?>';
              },
              $value
			);
});


/*
|--------------------------
| View Composers
|--------------------------
*/

View::composer(Paginator::getViewName(), function($view)
{
	$queryString = array_except(Input::query(), array(Paginator::getPageName(), 'cache_key'));
	$view->paginator->appends($queryString);
});

View::composer('partials/navigation', function($view)
{
	$tabs = Session::pull('navigation.tabs', array(array(array('selected' => false, 'path' => '', 'group' => ''))));
	$view->with('tabs', $tabs);
});

/*
|--------------------------------------------------------------------------
| Global Constants
|--------------------------------------------------------------------------
|
*/

// To check if Views are run from inside the framework
define('VIEW_IS_ALLOWED', true);

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

