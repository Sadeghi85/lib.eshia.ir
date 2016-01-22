
@section('content')
<div id="contents">
<div id="contents_cover">
<table id="AuthorsList" cellpadding="0" cellspacing="0">
<thead>
<tr><th class="AuthorIndex">@lang(sprintf('%s/app.number', Config::get('app_settings.theme')))</th><th class="AuthorName">@lang(sprintf('%s/app.author_name', Config::get('app_settings.theme')))</th><th class="AuthorBooks">@lang(sprintf('%s/app.author_books', Config::get('app_settings.theme')))</th></tr>
</thead>
<tbody>
@php $i = 1 @endphp
@foreach ($authorsBooksCount as $author => $bookCount)
<tr>
<td class="AuthorIndex-Sub">
{{ $i }}&nbsp;
</td>
<td class="AuthorName-Sub">
{{ Helpers::link_to($author, $author, array('title' => '')) }}
</td>
<td class="AuthorBooksCount">
{{ $bookCount }}
</td>
</tr>
@php $i++ @endphp
@endforeach
</tbody>
</table>
</div>
</div>
@stop

@section('title')
	@parent
@stop