@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
{{-- PNG logo: most email clients do not render SVG. --}}
<img src="{{ asset('brand/sinowala-logo-horizontal@2x.png') }}" height="32" style="height: 32px; width: auto; border: 0;" alt="SinoWala">
</a>
</td>
</tr>
