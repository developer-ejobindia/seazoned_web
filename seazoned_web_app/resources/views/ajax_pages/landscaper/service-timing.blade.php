<table class="table">
    <tbody>
    @foreach($service_time as $time)
        <tr>
            <td><b>{{ $time->service_day }}</b></td>
            <td class="text-right">{{ strtoupper(date("g:i a", strtotime($time->start_time))) }} - {{ strtoupper(date("g:i a", strtotime($time->end_time))) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>