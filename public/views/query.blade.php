@extends('layouts.default')
<?php if ( ! defined('VIEW_IS_ALLOWED')) { ob_clean(); die(); } ?>

@section('content')
<div id="contents">
<div id="contents_cover" class="Page_showPage">
<table class="content_headers" align="center" cellpadding="0" cellspacing="0">
<tr>	
<td class="Tools-serach">

<form action="#" id="search-query-form" onsubmit="do_search(document.getElementById('searchContentInput1').value, '{{ $bookID ? $bookID : '' }}');return false;">
<table class="Tools-serach-table">
<tr>
<td class="Tools-serach-input-td">
<input id="searchContentInput1" value="@lang('app.default_search')" class="empty-search-item" autocomplete="off" onfocus="if (this.value == '@lang('app.default_search')') {this.value = ''; this.className=''}" onblur="if (this.value == '') {this.value = '@lang('app.default_search')'; this.className='empty-search-item'}">
</td>
<td class="Tools-serach-submit-td">
<input type="submit" value="" class="dofindButton">
</td>
</tr>
</table>
</form>
</td>	
</tr>

<tr>

@if ($resultCount == 0)
<td>	
<table class="Page-query-noresualt">
<tr>
<td>
<span class="not-found">@lang('app.query_search_result_not_found', array('query' => sprintf('<u><font color="#000000">%s</font></u>', $query)))</span>
</td>
</tr>
</table>
</td>
@else
<td>
<table class="pageSelectorPanel">
<tr>
<td class="pg-Left"><span class="result_count">{{ $resultCount }}</span>@lang('app.query_search_result')</td>
<td class="pg-right"><\?= $pagination_links ?></td>
</tr>
</table>
</td>
   
</tr>

<tr>
<td>
<table id='search-result'>
<\?php foreach($result_items as $item): ?>
<tr>
<td class="id"><\?= $item['index'] ?></td>
<td class="data">
<div class="result">
<\?= anchor($item['address'].'/'.$term, $item['label'], array('title' => '')) ?>
</div>
<div class="preview"><\?= $item['preview'] ?></div>
</td>
</tr>
<\?php endforeach; ?>
</table>
</td>
</tr>


<tr>
<td>
<table class="pageSelectorPanel">
<tr>
<td class="pg-Left"><span class="result_count">{{ $resultCount }}</span>@lang('app.query_search_result')</td>
<td class="pg-right"><\?= $pagination_links ?></td>
</tr>
</table>
</td>
</tr>
@endif

<tr>	
<td class="Tools-serach">
<form action="#" id="search-query-form" onsubmit="do_search(document.getElementById('searchContentInput2').value, '{{ $bookID ? $bookID : '' }}');return false;">
<table class="Tools-serach-table">
<tr>
<td class="Tools-serach-input-td">
<input id="searchContentInput2" value="@lang('app.default_search')" class="empty-search-item" autocomplete="off" onfocus="if (this.value == '@lang('app.default_search')') {this.value = ''; this.className=''}" onblur="if (this.value == '') {this.value = '@lang('app.default_search')'; this.className='empty-search-item'}">
</td>
<td class="Tools-serach-submit-td">
<input type="submit" value="" class="dofindButton">
</td>
</tr>
</table>
</form>
</td>	
</tr>
</table>
</div>
</div>
@stop

@section('title')
	@parent
@stop