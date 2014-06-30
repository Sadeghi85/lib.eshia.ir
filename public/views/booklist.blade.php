@extends('layouts.default')
<?php if ( ! defined('VIEW_IS_ALLOWED')) { ob_clean(); die(); } ?>

@section('content')
<div id="contents">
<div id="contents_cover">
<table id="BooksList" cellpadding="0" cellspacing="0">
<thead>
<tr><th class="BookVolumes">@lang('app.book_volumes')</th><th class="BookAuthor">@lang('app.author')</th><th class="BookName">@lang('app.book_name')</th><th class="BookIndex">@lang('app.number')</th></tr>
</thead>
<tbody>
@foreach ($books as $index => $book)
<tr>
<td class="BookVolumes-sub">
{{ $book['vols'] }}
</td>
<td class="BookAuthor-sub">
{{ Helpers::link_to($book['author'], $book['author'], array('title' => '')) }}
</td>
<td class="BookName-sub">
{{ Helpers::link_to($book['id'], $book['name'], array('title' => trans('app.show_book'))) }}
&nbsp;
</td>
<td class="BookIndex-sub">{{ $index + 1 }}&nbsp;</td>
</tr>
@endforeach
</tbody>
</table>
</div>
</div>
@stop

@section('title')
	@parent
@stop