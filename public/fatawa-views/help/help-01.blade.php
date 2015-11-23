@extends('layouts.default')
<?php if ( ! defined('VIEW_IS_ALLOWED')) { ob_clean(); die(); } ?>

@section('content')
<div id="contents">
<div id="contents_cover" class="Page_default">
<table id="helpContent" cellpadding="0" cellspacing="0">
<tr>
<!--///////////////////////////////////////////////////////////////////////////////////////-->
<!--TD-0-0-->
<td class="help-td-0-current">
<span>@lang('app.help_help')</span>
</td>
<!--///////////////////////////////////////////////////////////////////////////////////////-->
<!--///////////////////////////////////////////////////////////////////////////////////////-->
<!--TD-0-1-->
<td class="help-td-0">
<a href="/help/02">@lang('app.help_content_toolbar')</a>
</td>
<!--///////////////////////////////////////////////////////////////////////////////////////-->
<!--///////////////////////////////////////////////////////////////////////////////////////-->
<!--TD-0-2-->
<td class="help-td-0">
<a href="/help/03">@lang('app.help_search')</a>
</td>
<!--///////////////////////////////////////////////////////////////////////////////////////-->
<!--///////////////////////////////////////////////////////////////////////////////////////-->
<!--TD-0-3-->
<td class="help-td-0">
<a href="/help/04">@lang('app.help_move_in_book')</a>
</td>
<!--///////////////////////////////////////////////////////////////////////////////////////-->
<!--///////////////////////////////////////////////////////////////////////////////////////-->
<!--TD-0-4-->
<td class="help-td-0">
<a href="/help/05">@lang('app.help_search_in_book')</a>
</td>
<!--///////////////////////////////////////////////////////////////////////////////////////-->
<!--///////////////////////////////////////////////////////////////////////////////////////-->
<!--TD-0-5-->
<td class="help-td-0">
<a href="/help/06">@lang('app.help_book_text')</a>
</td>
<!--///////////////////////////////////////////////////////////////////////////////////////-->
<!--///////////////////////////////////////////////////////////////////////////////////////-->
<!--TD-0-6-->
<td class="help-td-0">
<a href="/help/07">@lang('app.help_making_pdf')</a>
</td>
<!--///////////////////////////////////////////////////////////////////////////////////////-->
<!--TD-0-7-->
<td class="help-td-0">
<a href="/help/08">@lang('app.help_documentation')</a>
</td>
<!--///////////////////////////////////////////////////////////////////////////////////////-->
<tr>
<td class="helptd" colspan="8">
<table class="help-textpics"><tr>
<td class="help-textpics-text">
<span class="help-title">@lang('app.help_help_text_title')</span><br>
<hr color="#FFFFFF" width="100%" align="right">
<span class="help-textpics-pics">
<img src="{{ asset('/assets/images/help/help-00.jpg') }}" alt="" usemap="#help-map" class="help-img" />
</span>
<p class="help-text">کتابخانه مدرسه فقاهت با هدف دسترسی آسان دین‌پژوهان به منابع مورد نیاز و همچنین مستند‌ سازی مفاهیم دینی در فضای اینترنتی طراحی شده است.<br>
این کتابخانه بیش ٣٣۰۰ جلد کتاب به زبان عربی و ۵۰۰ جلد به زبان فارسی را به صورت رایگان در اختیار محققین قرار داده و به طور مستمر موجودی خود را افزایش می‌دهد.<br>
<br>
کتابخانه شامل بخش‌های زیر است:<br>
۱- <a href="/help/02">نوار موضوعات</a><br>
۲- <a href="/help/03">جستجو</a><br>
٣- <a href="/help/04">جابجایی در کتاب</a><br>
۴- <a href="/help/05">جستجو در کتاب</a><br>
۵- <a href="/help/06">متن کتاب</a><br>
۶- <a href="/help/07">ساخت PDF</a><br>
٧- <a href="/help/08">مستند‌سازی مقالات</a></p>
</td>

<map name="help-map" id="help-map">
<area  alt="" title="@lang('app.help_content_toolbar')" href="/help/02" shape="rect" coords="218,4,474,64" style="outline:none;" target="_self" />
<area  alt="" title="@lang('app.help_book_text')" href="/help/06" shape="rect" coords="14,106,512,163" style="outline:none;" target="_self" />
<area  alt="" title="@lang('app.help_search')" href="/help/03" shape="rect" coords="10,16,193,55" style="outline:none;" target="_self" />

<area  alt="" title="@lang('app.help_move_in_book')" href="/help/04" shape="poly" coords="431,100,173,101,182,84,432,85" style="outline:none;" target="_self" />
<area  alt="" title="@lang('app.help_making_pdf')" href="/help/07" shape="poly" coords="515,59,460,66,401,66,402,83,454,83,473,96,509,97" style="outline:none;" target="_self" />
<area  alt="" title="@lang('app.help_search_in_book')" href="/help/05" shape="poly" coords="17,65,373,67,375,82,236,79,176,81,154,85,152,99,18,101" style="outline:none;" target="_self" />
</map>

</tr></table>
</td>
</tr>
</table>
</div>
</div>
@stop

@section('title')
	@parent
@stop