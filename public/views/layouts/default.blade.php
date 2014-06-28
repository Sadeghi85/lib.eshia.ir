<?php if ( ! defined('VIEW_IS_ALLOWED')) { ob_clean(); die(); } ?>
<!DOCTYPE html>
<html lang="{{ Config::get('app.locale') }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="@lang('app.title_meta')">

  <link rel="shortcut icon" href="{{ asset('/assets/icon/favicon.ico') }}">

  <title>
    @section('title')
          @lang('app.title_meta')
    @show
  </title>

    <!-- eShiaLibrary -->
    <link href="{{ asset('/assets/css/eShiaLibrary.css') }}" rel="stylesheet" media="screen">
 
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
			<a href="/"><img class="vertical-logo" src="{{ asset('/assets/images/vertical-logo.jpg') }}" alt="logo"></a>

			@include('partials/links')
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
									<input name="key" id="search_input" value="@lang('app.default_search')" class="SearchInput ui-autocomplete-input empty-search-item" autocomplete="off" onfocus="if (this.value == '@lang('app.default_search')') {this.value = ''; this.className='SearchInput ui-autocomplete-input'}" onblur="if (this.value == '') {this.value = '@lang('app.default_search')'; this.className='SearchInput ui-autocomplete-input empty-search-item'} a=setTimeout('hide_id(\'search_drop\')','500')">

									<input type="submit" id="searchButton" value="" class="SearchKey">
									<br />
									<ul id="search_drop" class="ui-autocomplete ui-menu ui-widget ui-widget-content ui-corner-all" role="listbox" aria-activedescendant="ui-active-menuitem" style="z-index: 1000; display:none; width: 550px;"></ul>
								</div>
							</form>
						</td>
						<td class="header-link">
							<span><a href="@lang('app.change_lang_link')">@lang('app.change_lang')</a></span>
							<span><a href="/help" title="@lang('app.lib_help')">@lang('app.lib_help')</a></span>
						</td>
					</tr>
			   </table>
			</div>
			
			<div id="navigationBar">
				@include('partials/navigation')
			</div>
			
			<!-- Content -->
			@yield('content')
			
		</td>
	</tr>
	<tr>
		<td colspan="2" class="sitelink">
			<span>
				@lang('app.footer_text', array('link' => sprintf('<a href="http://www.eShia.ir" title="%s"> %s </a>', trans('app.feqahat_school'), trans('app.feqahat_school'))))
			</span>
			<br>
			<span><a href="http://www.eShia.ir">www.eShia.ir</a></span>
		</td>
		<td class="rightsection_end"></td>
	</tr>
</table>

@show

<!-- eShiaLibrary -->
<script src="{{ asset('/assets/js/eShiaLibrary.js') }}"></script>

<script type="text/javascript">
	function do_search($query, $bookID)
	{
		$query = $query.replace(/^\s+|\s+$/g, '');

		if ($query && $query != "@lang('app.default_search')")
		{
			$query = $query.replace(/ +/g, '_').replace(/['\0\\]+/g, '');
			window.location.assign('/search/' + ($bookID ? $bookID + '/' : '') + encodeURIComponent($query));
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