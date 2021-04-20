@forelse($employeeDocs as $key=>$employeeDoc)
    <tr>
        <td>{{ $key+1 }}</td>
        <td>{{ ucwords($employeeDoc->name) }}</td>
        <td>
            <a href="{{ route('member.employee-docs.download', $employeeDoc->id) }}"
               data-toggle="tooltip" data-original-title="Download"
               class="btn btn-default btn-circle"><i
                        class="fa fa-download"></i></a>
            <a target="_blank" href="{{ asset_url('employee-docs/'.$employeeDoc->user_id.'/'.$employeeDoc->hashname) }}"
               data-toggle="tooltip" data-original-title="View"
               class="btn btn-info btn-circle"><i
                        class="fa fa-search"></i></a>
            <a href="javascript:;" data-toggle="tooltip" data-original-title="Delete" data-file-id="{{ $employeeDoc->id }}"
               data-pk="list" class="btn btn-danger btn-circle sa-params"><i class="fa fa-times"></i></a>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="3">@lang('messages.noDocsFound')</td>
    </tr>
@endforelse
