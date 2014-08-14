@extends('layouts.default')
<?php if ( ! defined('VIEW_IS_ALLOWED')) { ob_clean(); die(); } ?>

@section('content')

@php
	$searchLabel = isset($id) ? trans('app.search_in_this_book') : trans('app.default_search');
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
							<a href="{{ sprintf('/%s/', $id) }}" title="{{ sprintf('%s %s', trans('app.show'), trans('app.first_page')) }}">@lang('app.first_page')</a>
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
							<td class="pg-Left"><span class="result_count">{{ $totalCount }}</span>@lang('app.query_search_result')&nbsp;&nbsp;({{ sprintf('%01.2f', $time) }}&nbsp;&nbsp;@lang('app.search_seconds'))</td>
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
							<a href="{{ Helpers::to(sprintf('/%s/%s/%s/%s', (int) $result['attrs']['bookid'], (int) $result['attrs']['volume'], (int) $result['attrs']['page'], $query)) }}" title="">{{ sprintf(trans('app.search_info'), '<font color="#ff6c13">', '</font>', $result['attrs']['bookName'], '<font color="#ff6c13">', '</font>', (int) $result['attrs']['volume'], '<font color="#ff6c13">', '</font>', (int) $result['attrs']['page']) }}</a>
							{{-- <\?= anchor($item['address'].'/'.$term, $item['label'], array('title' => '')) ?> --}}
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
							<td class="pg-Left"><span class="result_count">{{ $resultCount }}</span>@lang('app.query_search_result')</td>
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
							<a href="{{ sprintf('/%s/', $id) }}" title="{{ sprintf('%s %s', trans('app.show'), trans('app.first_page')) }}">@lang('app.first_page')</a>
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
