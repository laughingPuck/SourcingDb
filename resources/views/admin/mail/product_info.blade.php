<style>
    td{border: 1px solid #333;padding: 5px 10px;}
    .item,.header{font-weight: bold;}
    .header{color: #999;}
    p{font-weight: bold;}
</style>
<p>You're receiving the attached product information and attached image from Sourcing DB server.</p>
<table>
    <tr>
        <td class="header">Item</td>
        <td class="header">Value</td>
    </tr>
    @if ($info[0])
        @foreach ($info[0] as $k => $v)
            <tr>
                <td class="item">{{ $k }}</td>
                <td>{{ $v }}</td>
            </tr>
        @endforeach
    @endif
</table>