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

<!-- header -->
<div class="header">

<div class="name">
<a href="/"><img src="/assets/alfeker/images/name.png"></a>
<!-- contacts -->
<div class="contacts">
<a href="http://www.facebook.com/alfekerbook"><img src="/assets/alfeker/images/fb.png"></a>
<a href="http://www.twitter.com/alfekerbooks"><img src="/assets/alfeker/images/twitter.png"></a>
<a href="http://www.instagram.com/alfeker"><img src="/assets/alfeker/images/instagram.png"></a>
</div>
</div>
<!-- contacts end-->

</div>
<!-- header end -->

<div class="main">


<!-- search -->
<div class="nav">


<div class="search">
	<form action="http://server2008:8097/#" method="post" accept-charset="utf-8" id="mainSearchPanel" class="searchPanel" onsubmit="do_search(document.getElementById('search_input').value);return false;">
		<div>
			<label for="search_input" accesskey="س"></label>
				<div class="searchbox">
					<input type="submit" name="" value="" id="searchButton" class="SearchKey"  />					<input type="text" name="key" value="جستجو..." id="search_input" class="SearchInput ui-autocomplete-input empty-search-item" autocomplete="off" accesskey="S" onfocus="if (this.value == 'جستجو...') {this.value = ''; this.className='SearchInput ui-autocomplete-input'}" onblur="if (this.value == '') {this.value = 'جستجو...'; this.className='SearchInput ui-autocomplete-input empty-search-item'} a=setTimeout('hide_id(\'search_drop\')','500')"  />
				</div>

				<div class="sujestlist">
					<ul id="search_drop" class="ui-autocomplete ui-menu ui-widget ui-widget-content ui-corner-all" role="listbox" aria-activedescendant="ui-active-menuitem" style="z-index: 1000; display:none; width: 550px;">
					</ul>
				</div>
		</div>
	</form>
</div>


</div>
<!-- search end -->


<!-- content -->

<div class="content">
@yield('content')
</div>
<!-- content end -->


<!-- right -->
<div class="right">

<div class="righthead">
القائمة
</div>

<div class="vernav">
	<ul>
		<li><a href="/"_blank">الرئيسية</a></li>
		<li><a href="/all" target="_blank">جميع الكتب</a></li>
		<li><a href="/authors/" target="_blank">المؤلفون</a></li>
		<li><a href="#" target="_blank">المقالات</a></li>
		<li><a href="#" target="_blank">الأقسام</a></li>
	</ul>
</div>

@include(sprintf('%s/partials/navigation', Config::get('app_settings.theme')))


</div>
<!-- right end -->

<div class="clear">
</div>

</div>
<!-- main end -->


<!-- footer -->
<div class="footer">
<span>
	@lang(sprintf('%s/app.footer_text', Config::get('app_settings.theme')), array('link' => sprintf('<a href="http://www.eShia.ir" title="%s"> %s </a>', trans(sprintf('%s/app.feqahat_school', Config::get('app_settings.theme'))), trans(sprintf('%s/app.feqahat_school', Config::get('app_settings.theme'))))))
</span>
	<br>
<span><a href="http://www.eShia.ir">www.eShia.ir</a></span>

</div>
<!-- footer end -->




	
	<script type="text/javascript" src="http://server2008:8097/assets/js/eShiaLibrary.js"></script>	
	<script type="text/javascript" defer>
		function do_search($term, $id)
		{
			$term = $term.replace(/^\s+|\s+$/g, '');

			if ($term && $term != 'جستجو...')
			{
				$term = $term.replace(/ +/g, '_').replace(/['\0\\]+/g, '');
				window.location.assign('/wait/' + ($id ? $id + '/' : '') + encodeURIComponent($term));
			}

			return false;
		}
	</script>

</body>
</html>