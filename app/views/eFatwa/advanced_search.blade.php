
@section('content')
<div id="contents">
	<div id="contents_cover" class="Page_advancedSearch">
		<table id="advancedsearch">
			<tbody>
				<tr>
					<td>
						<form name="frmQuery" method="post">
							<span class="tdLabel">&nbsp;</span>
							<table id="advanced" cellspacing="0" cellpadding="0">
								<tbody>
									<tr>
										<td class="tdLabel">@lang(sprintf('%s/app.search_find_items_that', Config::get('app_settings.theme')))</td>
										<td class="tdInput"></td>
									</tr>
									<tr>
										<td class="tdLabel">@lang(sprintf('%s/app.search_all_these_words', Config::get('app_settings.theme')))</td>
										<td class="tdInput"><input name="and" type="text" value=""></td>
									</tr>
									<tr>
										<td class="tdLabel">@lang(sprintf('%s/app.search_this_phrase', Config::get('app_settings.theme')))</td>
										<td class="tdInput"><input name="phrase" type="text" value=""></td>
									</tr>
									<tr>
										<td class="tdLabel">@lang(sprintf('%s/app.search_any_these_words', Config::get('app_settings.theme')))</td>
										<td class="tdInput"><input name="or" type="text" value=""></td>
									</tr>
								</tbody>
							</table>
							<span class="tdLabel">@lang(sprintf('%s/app.search_but', Config::get('app_settings.theme')))</span>
							<table cellspacing="0" cellpadding="0">
								<tbody>
									<tr>
										<td class="tdLabel">@lang(sprintf('%s/app.search_not_these_words', Config::get('app_settings.theme')))</td>
										<td class="tdInput"><input name="not" type="text" value=""></td>
									</tr>
								</tbody>
							</table>

							<span class="tdLabel">@lang(sprintf('%s/app.search_where', Config::get('app_settings.theme')))</span>
							<table cellspacing="0" cellpadding="0">
								<tbody>
									<tr>
										<td class="tdLabel">&nbsp;</td>
										<td class="tdInput">
											<select name="groupKey">
												@foreach ($groupArray as $groupKey => $groupName)
													<option value="{{ $groupKey }}">{{ $groupName }}</option>
												@endforeach
											</select>
										</td>
									</tr>
								</tbody>
							</table>
							<div align="center" style="margin: 0.5em 2em"><input type="submit" value="@lang(sprintf('%s/app.search_search', Config::get('app_settings.theme')))"></div>
						</form>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
@stop

@section('title')
	@parent
@stop