
@section('content')

@php
	$searchLabel = isset($id) ? trans(sprintf('%s/app.search_in_this_book', Config::get('app_settings.theme'))) : trans(sprintf('%s/app.default_search', Config::get('app_settings.theme')));
@endphp

<div id="contents">
	<div id="contents_cover" class="Page_showPage">
		<table class="content_headers" align="center" cellpadding="0" cellspacing="0">
			
			<tr>
				<td>
				<table class="Tools-Table" align="right">
					<tr>
					@if (isset($id))
						<td class="Tools-index">
							<a href="{{ sprintf('/%s/', $id) }}" title="{{ sprintf('%s %s', trans(sprintf('%s/app.show', Config::get('app_settings.theme'))), trans(sprintf('%s/app.first_page', Config::get('app_settings.theme')))) }}">@lang(sprintf('%s/app.first_page', Config::get('app_settings.theme')))</a>
						</td>
					@endif
						<td class="Tools-serach">
							<form action="#" id="search-query-form" onsubmit="do_search(document.getElementById('searchContentInput1').value, '{{ isset($id) ? $id : '' }}');return false;">

							<table class="Tools-serach-table">
							<tr>
							<td class="Tools-serach-input-td">
							<input id="searchContentInput1" value="{{ isset($query) ? htmlspecialchars(urldecode($query)) : $searchLabel }}" class="empty-search-item" autocomplete="off" onfocus="if (this.value == '{{ $searchLabel }}') {this.value = ''; this.className=''}" onblur="if (this.value == '') {this.value = '{{ $searchLabel }}'; this.className='empty-search-item'}" />
							</td>
							<td class="Tools-serach-submit-td">
							
							<input class="dofindButton" type="submit" value="" />
							</td>
							</tr>
							</table>
							</form>
						</td>
					</tr>
				</table>
				</td>
			</tr>
                
			<tr>
				<td>
					<table class="pageSelectorPanel">
						<tr>
							<td class="pg-Left"><span class="result_count">{{ $totalCount }}</span>@lang(sprintf('%s/app.query_search_result', Config::get('app_settings.theme')))&nbsp;&nbsp;({{ sprintf('%01.2f', $time) }}&nbsp;&nbsp;@lang(sprintf('%s/app.search_seconds', Config::get('app_settings.theme'))))</td>
                            <td class="pg-right">{{ $results->links() }}</td>
						</tr>
					</table>
				</td>  
			</tr>

			<tr>
				<td>
					<table id='search-result'>
					
						@foreach ($results as $key => $result)
						<tr>
							<td class="id">{{ $perPage * ($page - 1) + $key +1 }}</td>
							<td class="data">
								<div class="result">
								<a href="">&nbsp;</a>
							<a href="{{ Helpers::to(sprintf('/%s/%s/%s/%s', (int) $result['attrs']['bookid'], (int) $result['attrs']['volume'], (int) $result['attrs']['page'], $query)) }}" title="">{{ sprintf(trans(sprintf('%s/app.search_info', Config::get('app_settings.theme'))), '<font color="#ff6c13">', '</font>', $result['attrs']['bookName'], $result['attrs']['bookAuthor'], '<font color="#ff6c13">', '</font>', (int) $result['attrs']['volume'], '<font color="#ff6c13">', '</font>', (int) $result['attrs']['page']) }}</a>
							
							</div>
						<div class="preview">{{ $result['attrs']['excerpt'] }}</div>
						</td>
						</tr>
						@endforeach
					</table>
				</td>
			</tr>

			<tr>
				<td>
					<table class="pageSelectorPanel">
						<tr>
							<td class="pg-Left"><span class="result_count">{{ $totalCount }}</span>@lang(sprintf('%s/app.query_search_result', Config::get('app_settings.theme')))&nbsp;&nbsp;({{ sprintf('%01.2f', $time) }}&nbsp;&nbsp;@lang(sprintf('%s/app.search_seconds', Config::get('app_settings.theme'))))</td>
                            <td class="pg-right">{{ $results->links() }}</td>
						</tr>
					</table>
				</td>
			</tr>

			<tr>
				<td>
				<table class="Tools-Table" align="right">
					<tr>
					@if (isset($id))
						<td class="Tools-index">
							<a href="{{ sprintf('/%s/', $id) }}" title="{{ sprintf('%s %s', trans(sprintf('%s/app.show', Config::get('app_settings.theme'))), trans(sprintf('%s/app.first_page', Config::get('app_settings.theme')))) }}">@lang(sprintf('%s/app.first_page', Config::get('app_settings.theme')))</a>
						</td>
					@endif
						<td class="Tools-serach">
							<form action="#" id="search-query-form" onsubmit="do_search(document.getElementById('searchContentInput2').value, '{{ isset($id) ? $id : '' }}');return false;">

							<table class="Tools-serach-table">
							<tr>
							<td class="Tools-serach-input-td">
							<input id="searchContentInput2" value="{{ isset($query) ? htmlspecialchars(urldecode($query)) : $searchLabel }}" class="empty-search-item" autocomplete="off" onfocus="if (this.value == '{{ $searchLabel }}') {this.value = ''; this.className=''}" onblur="if (this.value == '') {this.value = '{{ $searchLabel }}'; this.className='empty-search-item'}" />
							</td>
							<td class="Tools-serach-submit-td">
							
							<input class="dofindButton" type="submit" value="" />
							</td>
							</tr>
							</table>
							</form>
						</td>
					</tr>
				</table>
				</td>
			</tr>
		</table>
	</div>
</div>
@stop
