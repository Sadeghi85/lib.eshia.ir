<?php if ( ! defined('VIEW_IS_ALLOWED')) { ob_clean(); die(); } ?>

@php
	//print_r($tabs, true)
	//$tabs = array(array(array('selected' => false, 'path' => 'ert', 'group' => 'شسی'),array('selected' => true, 'path' => 'sdf', 'group' => 'سیب')),array(array('selected' => false, 'path' => 'ert', 'group' => 'ثقص'),array('selected' => true, 'path' => 'sdf', 'group' => 'یبل')));
@endphp

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
				<a href="{{ $tab['path'] }}" title=""><span>{{ $tab['group'] }}</span></a>
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
