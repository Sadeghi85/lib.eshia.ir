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
			<td class="help-td-0-current">
			<span>@lang(sprintf('%s/app.help_content_toolbar', Config::get('app_settings.theme')))</span>
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
			<td class="help-td-0">
			<a href="/help/04">@lang(sprintf('%s/app.help_move_in_book', Config::get('app_settings.theme')))</a>
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
 			<table class="help-textpics">
            <tr>
            <td class="help-textpics-text">
				<span class="help-title">@lang(sprintf('%s/app.help_content_toolbar', Config::get('app_settings.theme')))</span><br>
                <hr color="#FFFFFF" width="100%" align="right">
				<span class="help-textpics-pics">
<img src="{{ asset(sprintf('/assets/%s/images/help/help-01.jpg', Config::get('app_settings.theme'))) }}" alt="" class="help-img" />
</span>
				<p class="help-text">
				کتاب‌های مدرسه فقاهت در موضوعاتی هم‍‌چون فقه و اصول‌، علوم حدیث، قرآنی،عقاید، سیره، فرق‌اسلامی و ... دسته‌بندی شده است.با بهره‌گیری از نوار موضوعات که در بالای صفحه قرار دارد، امکان ورود به هر یک از موضوعات و مشاهده تمام کتاب‌ها‌ی آن فراهم شده است‌.با استفاده از این نوار می‌توان به مجموعه کتاب‌های آیت الله سبحانی ، آیت الله مکارم شیرازی ، آیت الله مطهری و شیخ محمد سند نیز دسترسی داشت. به علاوه امکان مشاهده نام تمام نویسندگان نیز در این بخش فراهم شده است.
				</p>
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