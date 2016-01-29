<!DOCTYPE html>
<html lang="{{ Config::get('app.locale') }}">
<head>
  <meta charset="utf-8">
  <!--<meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">-->
  <meta name="description" content="@lang(sprintf('%s/app.title_meta', Config::get('app_settings.theme')))">

  <link rel="shortcut icon" href="{{ asset(sprintf('/assets/%s/icon/favicon.ico', Config::get('app_settings.theme'))) }}">

  <title>
    @section('title')
          @lang(sprintf('%s/app.title_meta', Config::get('app_settings.theme')))
    @show
  </title>

    <!-- eShiaLibrary -->
    <link href="{{ asset(sprintf('/assets/%s/css/eShiaLibrary.css', Config::get('app_settings.theme'))) }}" rel="stylesheet" media="screen">
 
@section('style') 
   <style type="text/css">
   
   </style>
@show

</head>

<body>

@section('container')

<table id="all_contents" cellpadding="0" cellspacing="0" align="right">
	<tr>
		<td colspan="2" class="topheader"></td>
		<td rowspan="2" class="rightsection">
			<a href="/"><img class="vertical-logo" src="{{ asset(sprintf('/assets/%s/images/vertical-logo-%s.png', Config::get('app_settings.theme'), Config::get('app.locale'))) }}" alt="logo"></a>

			@include(sprintf('%s/partials/links', Config::get('app_settings.theme')))
		</td>		
	</tr>
	<tr>
		<td class="leftSection" colspan="2">
			<div id="header">
				<table  class="header-table">
					<tr>
						<td class="searchp1">
							<form action="#" id="mainSearchPanel" class="searchPanel" onsubmit="do_search(document.getElementById('search_input').value);return false;">
								<div>
									<label for="search_input" ></label>
									<input name="key" id="search_input" value="@lang(sprintf('%s/app.default_search', Config::get('app_settings.theme')))" class="SearchInput ui-autocomplete-input empty-search-item" autocomplete="off" onfocus="if (this.value == '@lang(sprintf('%s/app.default_search', Config::get('app_settings.theme')))') {this.value = ''; this.className='SearchInput ui-autocomplete-input'}" onblur="if (this.value == '') {this.value = '@lang(sprintf('%s/app.default_search', Config::get('app_settings.theme')))'; this.className='SearchInput ui-autocomplete-input empty-search-item'} a=setTimeout('hide_id(\'search_drop\')','500')" />

									<input type="submit" id="searchButton" value="" class="SearchKey">
									<br />
									<ul id="search_drop" class="ui-autocomplete ui-menu ui-widget ui-widget-content ui-corner-all" role="listbox" aria-activedescendant="ui-active-menuitem" style="z-index: 1000; display:none; width: 550px;"></ul>
								</div>
							</form>
						</td>
						<td class="header-link">
							<span><a href="@lang(sprintf('%s/app.change_lang_link', Config::get('app_settings.theme')))">@lang(sprintf('%s/app.change_lang', Config::get('app_settings.theme')))</a></span>
							<span><a href="/help" title="@lang(sprintf('%s/app.lib_help', Config::get('app_settings.theme')))">@lang(sprintf('%s/app.lib_help', Config::get('app_settings.theme')))</a></span>
							<span><a href="/advanced-search" title="@lang(sprintf('%s/app.advanced_search', Config::get('app_settings.theme')))">@lang(sprintf('%s/app.advanced_search', Config::get('app_settings.theme')))</a></span>
						</td>
					</tr>
			   </table>
			</div>
			
			<div id="navigationBar">
				@include(sprintf('%s/partials/navigation', Config::get('app_settings.theme')))
			</div>
			
			<!-- Content -->
			@yield('content')
			
		</td>
	</tr>
	<tr>
		<td colspan="2" class="sitelink">
			<span>
				@lang(sprintf('%s/app.footer_text', Config::get('app_settings.theme')), array('link' => sprintf('<a href="http://www.eShia.ir" title="%s"> %s </a>', trans(sprintf('%s/app.feqahat_school', Config::get('app_settings.theme'))), trans(sprintf('%s/app.feqahat_school', Config::get('app_settings.theme'))))))
			</span>
			<br>
			<span><a href="http://www.eShia.ir">www.eShia.ir</a></span>
		</td>
		<td class="rightsection_end"></td>
	</tr>
</table>

@show

<!-- eShiaLibrary -->
<script src="{{ asset(sprintf('/assets/%s/js/eShiaLibrary.js', Config::get('app_settings.theme'))) }}"></script>

<script type="text/javascript">
	function fixedEncodeURIComponent (str)
	{
		return encodeURIComponent(str).replace(/[!'()]/g, escape).replace(/\*/g, "%2A");
	}

	function do_search(query, bookId)
	{
		query = query.replace(/^\s+|\s+$/g, '');

		if (query && query != "@lang(sprintf('%s/app.default_search', Config::get('app_settings.theme')))" && query != "@lang(sprintf('%s/app.search_in_this_book', Config::get('app_settings.theme')))")
		{
			query = query.replace(/ +/g, '_').replace(/['\0\\]+/g, '');
			window.location.assign('/search/' + (bookId ? bookId + '/' : '') + fixedEncodeURIComponent(query));
		}

		return false;
	}
</script>
	
@section('javascript')
	<script type="text/javascript">

	</script>
@show

</body>
</html>