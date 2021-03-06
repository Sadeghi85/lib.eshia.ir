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
	app_path().'/libraries',
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
		$message = Helpers::getExceptionErrorMessage();
		//return Response::view('error.error', compact('message'), 500);
		return (Request::ajax() ? Response::make('', 200) : Response::view(sprintf('%s/error.error', Config::get('app_settings.theme')), compact('message'), 200));
	}
});

App::error(function(Exception $exception, $code)
{
	Log::error($exception);
	
	if ( ! Config::get('app.debug'))
	{
		$message = Helpers::getExceptionErrorMessage();
		
		switch ($code)
		{
			case 403:
				//return Response::view('error.error', compact('message'), 403);
				
			case 405:
				//return Response::view('error.error', compact('message'), 405);

			case 500:
				//return Response::view('error.error', compact('message'), 500);
				
			case 503:
				//return Response::view('error.error', compact('message'), 503);

			default:
				//return Response::view('error.error', compact('message'), 404);
				return (Request::ajax() ? Response::make('', 200) : Response::view(sprintf('%s/error.error', Config::get('app_settings.theme')), compact('message'), 200));
		}
	}
});

// App::error(function(Exception $exception, $code)
// {
	// Log::error($exception);
// });

App::error(function(Illuminate\Database\Eloquent\ModelNotFoundException $exception, $code)
{
	$message = Helpers::getExceptionErrorMessage();
    //return Response::view('error.error', compact('message'), 404);
	return (Request::ajax() ? Response::make('', 200) : Response::view(sprintf('%s/error.error', Config::get('app_settings.theme')), compact('message'), 200));
});

App::missing(function($exception)
{
    $message = Helpers::getExceptionErrorMessage();
    //return Response::view('error.error', compact('message'), 404);
	return (Request::ajax() ? Response::make('', 200) : Response::view(sprintf('%s/error.error', Config::get('app_settings.theme')), compact('message'), 200));
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
	
	$message = Helpers::getExceptionErrorMessage();
    //return Response::view('error.error', compact('message'), 503);
	return (Request::ajax() ? Response::make('', 200) : Response::view(sprintf('%s/error.error', Config::get('app_settings.theme')), compact('message'), 200));
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

// PageController page must be latin
if (Request::server('REQUEST_METHOD') == 'POST') {
	Input::merge(array('page' => abs(Helpers::latinizeNumber(Input::get('page', 0)))));
}

// Paginator page must be positive
if (Request::server('REQUEST_METHOD') == 'GET') {
	Input::merge(array(Paginator::getPageName() => abs(Input::get(Paginator::getPageName(), 1))));
}

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
	$queryString = array_except(Input::query(), array(Paginator::getPageName()));
	$view->paginator->appends($queryString);
});

View::composer(sprintf('%s/partials/navigation', Config::get('app_settings.theme')), function($view)
{
	$view->with('tabs', Helpers::getNavigationTabs());
});

// enable caching for near static contents
View::composer(sprintf('%s/advanced_search', Config::get('app_settings.theme')), function($view) { define('PAGE_IS_CACHEABLE', true); });
View::composer(sprintf('%s/index', Config::get('app_settings.theme')), function($view) { define('PAGE_IS_CACHEABLE', true); });
View::composer(sprintf('%s/author_booklist', Config::get('app_settings.theme')), function($view) { define('PAGE_IS_CACHEABLE', true); });
View::composer(sprintf('%s/authorlist', Config::get('app_settings.theme')), function($view) { define('PAGE_IS_CACHEABLE', true); });
View::composer(sprintf('%s/booklist', Config::get('app_settings.theme')), function($view) { define('PAGE_IS_CACHEABLE', true); });
View::composer(sprintf('%s/help/help-*', Config::get('app_settings.theme')), function($view) { define('PAGE_IS_CACHEABLE', true); });

/*
|--------------------------------------------------------------------------
| Global Constants
|--------------------------------------------------------------------------
|
*/

// To check if Views are run from inside the framework
define('VIEW_IS_ALLOWED', true);
