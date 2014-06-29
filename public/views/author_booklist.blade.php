@extends('layouts.default')
<?php if ( ! defined('VIEW_IS_ALLOWED')) { ob_clean(); die(); } ?>

@section('content')
<div id="contents">
<div id="contents_cover">
<table id="AuthorBooksList" cellpadding="0" cellspacing="0">
<thead>
<tr><th colspan="3">{{ $author }}</th></tr>
<tr><th class="BookIndex">@lang('app.number')</th><th class="BookName">@lang('app.book_name')</th><th class="BookVolumes">@lang('app.book_volumes')</th></tr>
</thead>
<tbody>
@foreach ($books as $index => $book)
<tr>
<td class="BookIndex">{{ $index + 1 }}&nbsp;</td>
<td class="BookName-name">{{ Helpers::link_to($book['id'], $book['name'], array('title' => trans('app.show_book'))) }}&nbsp;</td>
<td class="BookVolumes-vol">{{ $book['vols'] }}</td>
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