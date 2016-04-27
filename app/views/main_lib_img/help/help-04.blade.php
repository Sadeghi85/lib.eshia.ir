@extends(sprintf('%s/layouts.default', Config::get('app_settings.theme')))

@section('content')
<div id="contents">
<div id="contents_cover" class="Page_default">
<table id="helpContent" cellpadding="0" cellspacing="0">
<tr>
<!--///////////////////////////////////////////////////////////////////////////////////////-->
<!--TD-0-0-->
<td class="help-td-0">
<a href="/help">@lang(sprintf('%s/app.help_help', Config::get('app_settings.theme')))</a>
</td>
<!--///////////////////////////////////////////////////////////////////////////////////////-->
<!--///////////////////////////////////////////////////////////////////////////////////////-->
<!--TD-0-1-->
<td class="help-td-0">
<a href="/help/02">@lang(sprintf('%s/app.help_content_toolbar', Config::get('app_settings.theme')))</a>
</td>
<!--///////////////////////////////////////////////////////////////////////////////////////-->
<!--///////////////////////////////////////////////////////////////////////////////////////-->
<!--TD-0-2-->
<td class="help-td-0">
<a href="/help/03">@lang(sprintf('%s/app.help_search', Config::get('app_settings.theme')))</a>
</td>
<!--///////////////////////////////////////////////////////////////////////////////////////-->
<!--///////////////////////////////////////////////////////////////////////////////////////-->
<!--TD-0-3-->
<td class="help-td-0-current">
<span>@lang(sprintf('%s/app.help_move_in_book', Config::get('app_settings.theme')))</span>
</td>
<!--///////////////////////////////////////////////////////////////////////////////////////--> 
<!--///////////////////////////////////////////////////////////////////////////////////////-->
<!--TD-0-4-->
<td class="help-td-0">
<a href="/help/05">@lang(sprintf('%s/app.help_search_in_book', Config::get('app_settings.theme')))</a>
</td>
<!--///////////////////////////////////////////////////////////////////////////////////////-->
<!--///////////////////////////////////////////////////////////////////////////////////////-->
<!--TD-0-5-->
<td class="help-td-0">
<a href="/help/06">@lang(sprintf('%s/app.help_book_text', Config::get('app_settings.theme')))</a>
</td>
<!--///////////////////////////////////////////////////////////////////////////////////////-->
<!--///////////////////////////////////////////////////////////////////////////////////////-->
<!--TD-0-6-->
<td class="help-td-0">
<a href="/help/07">@lang(sprintf('%s/app.help_making_pdf', Config::get('app_settings.theme')))</a>
</td>
<!--///////////////////////////////////////////////////////////////////////////////////////-->
<!--TD-0-7-->
<td class="help-td-0">
<a href="/help/08">@lang(sprintf('%s/app.help_documentation', Config::get('app_settings.theme')))</a>
</td>
<!--///////////////////////////////////////////////////////////////////////////////////////-->
<tr>
<td class="helptd" colspan="8">
<table class="help-textpics"><tr>
<td class="help-textpics-text">
<span class="help-title">@lang(sprintf('%s/app.help_move_in_book', Config::get('app_settings.theme')))</span><br>
<hr color="#FFFFFF" width="100%" align="right">
<span class="help-textpics-pics">
<img src="{{ asset(sprintf('/assets/%s/images/help/help-03.jpg', Config::get('app_settings.theme'))) }}" alt="" class="help-img" />
</span>
<p class="help-text">بعد از حاضر کردن کتاب مورد نظر‌، نام کتاب و نام نویسنده در بالای صفحه ظاهر می‌شود و در قسمت پایین آن امکاناتی جهت جابجایی در کتاب به نمایش گذاشته می‌شود.<br>
با وارد کردن شماره جلد (در کتاب‌های چند جلدی ) و شماره صفحه در قسمت‌های مربوطه و فشار دکمه اینتر‌، صفحه مورد نظر حاضر می‌شود.<br>
در صورت نیاز به مرور صفحه به صفحه کتاب می‌توان بر روی "صفحه بعدی" یا "صفحه قبلی" کلیک کرد.<br>
امکانات مربوط به جابجایی در کتاب در قسمت متن نیز تعبیه شده است.</p>
</td>
</tr></table>
</td>
</tr>
<!--///////////////////////////////////////////////////////////////////////////////////////-->
</table>
</div>
</div>
@stop

@section('title')
	@parent
@stop