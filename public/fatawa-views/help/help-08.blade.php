@extends('layouts.default')
<?php if ( ! defined('VIEW_IS_ALLOWED')) { ob_clean(); die(); } ?>

@section('content')
<div id="contents">
<div id="contents_cover" class="Page_default">
<table id="helpContent" cellpadding="0" cellspacing="0">
<tr>
<!--///////////////////////////////////////////////////////////////////////////////////////-->
<!--TD-0-0-->
<td class="help-td-0">
<a href="/help">@lang('app.help_help')</a>
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
<td class="help-td-0-current">
<span>@lang('app.help_documentation')</span>
</td>
<!--///////////////////////////////////////////////////////////////////////////////////////-->
<tr>
<td class="helptd" colspan="8">
<table class="help-textpics"><tr>
<td class="help-textpics-text">
<span class="help-title">@lang('app.help_documentation')</span>
<hr color="#FFFFFF" width="100%" align="right">
<span class="help-textpics-pics">
<img src="{{ asset('/assets/images/help/help-07.jpg') }}" alt="" class="help-img" />
</span>
<p class="help-text">چنانچه دین‌پژوهان، مقالات اینترنتی خود را به مطالبی از کتب موجود در کتابخانه مدرسه فقاهت مستند کرده باشند، قادر خواهند بود امکان دسترسی به آن را برای کاربران فراهم سازند.<br>
به عنوان مثال اگر یکی از ارجاعات مقاله، حدیثی از صفحه ۲۰ جلد پنجم کتاب وسایل الشیعه باشد، محقق می‌تواند برای رفتن به صفحه مورد نظر مراحل زیر را انجام دهید:<br>
1- انتخاب کتاب: در قسمت جستجو (که در بالای صفحه، سمت چپ قرار دارد)، بخشی از نام کتاب «وسائل الشیعه» را تایپ کند. <br>
2- حاضر کردن کتاب: بعد از نمایش نام کتاب، برروی آن کلیک کنید. صفحه اول از جلد اول کتاب حاضر می شود.<br>
3- انتخاب جلد: در قسمت شماره جلد، عدد ۵ را درج کنید.<br>
4- انتخاب صفحه: در قسمت شماره صفحه، عدد 20 را وارد و بر روی کلید «برو به صفحه» کلیک کنید.<br>
5- نشانی کتاب: در نوار نشانی، آدرس http://lib.eshia.ir/11024/5/20 مشاهده می‌شود.<br>
6- انتخاب  نشانی: نشانی را انتخاب کنید و به صورت لینک در قسمت پانویس مربوط قرار دهد.<br>
با این کار کاربرانی که به مقاله مراجعه می‌کنند با کلیک کردن بر روی نشانی مندرج در پانویس به کتاب وسایل الشیعه، ج ۵ ، صفحه ۲۰ منتقل شده و متن حدیث را از منبع دست اول مشاهده خواهند کرد.<br>
7- تغییر رنگ کلمه: اگر می‌خواهید کلمه‌ای از صفحه مذکور به رنگ قرمز نشان داده شود، آن کلمه را در انتهای نشانی قرار دهید. به طور مثال برای قرمز شدن کلمه «السفر» آن را در انتهای نشانی قرار می‌دهیم که به شکل<br>السفر/http://lib.eshia.ir/11024/5/20<br> در می‌آید. <br>
با این کار کاربرانی که به مقاله مراجعه می‌کنند با کلیک کردن بر روی نشانی مندرج در پانویس، کلمه «السفر»  را قرمز می‌بینند و به راحتی می‌توانند مراد نویسنده را متوجه شوند.<br>
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