
@section('content')
<div id="dialog-form">
	<div id="contents">
    <div id="contents_cover" class="Page_showPage">
		<form id="frmPDF" method="post" action="#" onsubmit="validate_pdf_form(document.getElementById('start').value, document.getElementById('end').value);return false;">
			<table class="pdf_form">
            <tr><td class="pdf_logo" rowspan="6">
			<img src="{{ asset(sprintf('/assets/%s/images/Logo_PDF.jpg', Config::get('app_settings.theme'))) }}" alt="" /></td></tr>
            <tr>
            <td class="pdf_book_Name" colspan="2">@lang(sprintf('%s/app.pdf_book_name', Config::get('app_settings.theme'))) : {{ $bookName }}</td>
            </tr>
				<tr>
					<td class="pdf_left"><input type="text" size="4" name="start" id="start" /></td>
					<td class="pdf_right">@lang(sprintf('%s/app.pdf_from_page', Config::get('app_settings.theme')))</td>
				</tr>
				<tr>
					<td class="pdf_left"><input type="text" size="4" name="end" id="end" /></td>
					<td class="pdf_right">@lang(sprintf('%s/app.pdf_to_page', Config::get('app_settings.theme')))</td>
				</tr>
				<tr>
					<td class="pdf_Attention" colspan="2" >@lang(sprintf('%s/app.pdf_attention', Config::get('app_settings.theme')))</td>
				</tr>
				<tr>
					<td class="pdf_submit" colspan="2" >
					<input class="pdf_submit_button"  type="submit" value="@lang(sprintf('%s/app.pdf_save', Config::get('app_settings.theme')))" name="submit"/>
					</td>
				</tr>
			</table>
		</form>
	</div>
    </div>
</div>
<br />
@stop

@section('javascript')
	@parent
	
	<script type="text/javascript">
		function validate_pdf_form(start, end)
		{
			start = start.replace(/^\s+|\s+$/g, '');
			end = end.replace(/^\s+|\s+$/g, '');
			
			if (start && end && (/\d+/.test(start)) && (/\d+/.test(end)))
			{
				window.location.assign('{{ Config::get('app_settings.pdf_url') }}'+'?url='+encodeURIComponent('{{ url($id.'/'.$volume.'/1') }}')+'&book_name='+encodeURIComponent('{{ $bookName }}')+'&start='+start+'&end='+end+'&submit=true');
			}
			
			return false;
		}
	</script>
@stop

@section('title')
	@parent
@stop