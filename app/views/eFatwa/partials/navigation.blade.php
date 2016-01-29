
@if (isset($tabs) and is_array($tabs))
	@foreach ($tabs as $tabcontainer)
	<div class="tab-panel">
    <table class="tab-panel-table" cellpadding="0" cellspacing="0">
    <tr>
    <td class="tab-panel-right">&nbsp;</td>
	<td class="tab-panel-middle">	
		<ul>
			@foreach ($tabcontainer as $tab)
			<li {{ ($tab['selected'] === TRUE) ? 'class="selected-tab"' : '' }}>
				<span>{{ Helpers::link_to($tab['path'], $tab['group']) }}</span>
			</li>
			@endforeach
		</ul>
    </td>
    <td class="tab-panel-left">&nbsp;</td>
    </tr>
    </table>
	</div>
	@endforeach
@endif
