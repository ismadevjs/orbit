@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="{{getSettingValue('site_logo') ?? ''}}" class="logo" alt="{{getSettingValue('site_name')}}">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
