@forelse($holidays as $index => $holiday)
<tr> <td align="center">{{ $index+1 }}</td> <td>{{ $holiday->date->format($global->date_format) }}</td> <td>{{ ucwords($holiday->occassion) }}</td> </tr>
@empty
    <tr> <td align="center" colspan="3">@lang('messages.monthWiseDataNotFound') </td> </tr>

@endforelse