<table class="bio in">
    @foreach($rows as $label => $val)
    <tr><td class="l">{{ $label }}</td><td class="c">:</td><td>{{ $val }}</td></tr>
    @endforeach
</table>
