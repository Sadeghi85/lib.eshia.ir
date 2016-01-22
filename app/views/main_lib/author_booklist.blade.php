
@section('content')
<div id="contents">
<div id="contents_cover">
<table id="AuthorBooksList" cellpadding="0" cellspacing="0">
<thead>
<tr><th colspan="3">{{ $author }}</th></tr>
<tr><th class="BookIndex">@lang(sprintf('%s/app.number', Config::get('app_settings.theme')))</th><th class="BookName">@lang(sprintf('%s/app.book_name', Config::get('app_settings.theme')))</th><th class="BookVolumes">@lang(sprintf('%s/app.book_volumes', Config::get('app_settings.theme')))</th></tr>
</thead>
<tbody>
@foreach ($books as $index => $book)
<tr>
<td class="BookIndex">{{ $index + 1 }}&nbsp;</td>
<td class="BookName-name">{{ Helpers::link_to($book['id'], $book['name'], array('title' => trans(sprintf('%s/app.show_book', Config::get('app_settings.theme'))))) }}&nbsp;</td>
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