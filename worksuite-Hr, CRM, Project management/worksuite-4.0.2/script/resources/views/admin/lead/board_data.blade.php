@foreach($boardColumns as $key=>$column)
    <div class="panel col-xs-3 board-column p-0" data-column-id="{{ $column->id }}" >
        <div class="panel-heading p-t-5 p-b-5" >
            <div class="panel-title">
                <h6 style="color: {{ $column->label_color }}">{{ ucwords($column->type) }} (<span id="statusTotal{{$column->id}}">{{ global_setting()->currency->currency_symbol.$column->leads->sum('value') }}</span>)

                    <div style="position: relative;" class="dropdown pull-right">
                        <a href="javascript:;"  data-toggle="dropdown"  class="dropdown-toggle "><i class="ti-settings font-normal"></i></a>
                        <ul role="menu" class="dropdown-menu">
                            <li><a href="javascript:;" data-type-id="{{ $column->id }}" class="edit-type" >@lang('app.edit')</a>
                            </li>
                            @if(!$column->default)
                                <li><a href="javascript:;" data-column-id="{{ $column->id }}" class="delete-column"  >@lang('app.delete')</a></li>
                            @endif
                        </ul>

                    </div>
                </h6>
            </div>
        </div>
        <div class="panel-body" id="taskBox_{{ $column->id }}" style="height: 70vh; overflow-y: auto">
            <div class="row">
                <div class="col-xs-12" style="height: 400px !important;">
                    @foreach($column->leads as $lead)
                        <div class="panel panel-default lobipanel view-task" data-task-id="{{ $lead->id }}" data-sortable="true">
                            <div class="panel-body">
                                <div class="p-10 font-12 font-semi-bold">{{ ucfirst($lead->client_name) }} @if(!is_null($lead->currency_id)) ({{ $lead->currency->currency_symbol }}<span id="leadValue{{ $lead->id }}">{{ $lead->value }}</span>) @endif</div>
                                <div class="p-10 p-t-0 text-muted"><small><i class="fa fa-building-o"></i> {{ ucfirst($lead->company_name) }}</small></div>

                                @if (!is_null($lead->agent_id))
                                <div class="p-t-10 p-b-10 p-10">
                                    <img src="{{ $lead->lead_agent->user->image_url }}" data-toggle="tooltip" data-original-title="{{ ucwords($lead->lead_agent->user->name)}} " data-placement="right"
                                        alt="user" class="img-circle" width="25" height="25">
                                </div>
                                @endif

                                <div class="p-t-0 p-10 font-12 col-xs-12">
                                    @if ($lead->next_follow_up_date != null && $lead->next_follow_up_date != '')
                                        <i class="icon-calender"></i> {{ \Carbon\Carbon::parse($lead->next_follow_up_date)->format($global->date_format) }}
                                    @else
                                        @lang('modules.followup.followUpNotFound')
                                    @endif
                                </div>
                
                             
                            </div>
                        </div>
                    @endforeach
                    <div class="panel panel-default lobipanel"  data-sortable="true"></div>
                </div>
            </div>
        </div>
    </div>
@endforeach
<script>
    $(function () {

        var data ='@lang("app.menu.leads") @lang("app.from")<strong> {{ \Carbon\Carbon::parse($startDate)->format($global->date_format) }} </strong> to <strong>{{ \Carbon\Carbon::parse($endDate)->format($global->date_format) }}</strong>';
        $('#filter-result').html(data);

        // @foreach($boardColumns as $key=>$column)
        // $('#taskBox_{{ $column->id }}').slimScroll({
        //     height: '70vh'
        // });
        // @endforeach

        let draggingTaskId = 0;
        let draggedTaskId = 0;

        $('.lobipanel').on('dragged.lobiPanel', function (ev, lobiPanel) {
            var body = lobiPanel.$el.find('.view-task');
            var $parent = $(this).parent(),
                $children = $parent.children();

            var boardColumnIds = [];
            var taskIds = [];
            var prioritys = [];

            $children.each(function (ind, el) {
                boardColumnIds.push($(el).closest('.board-column').data('column-id'));
                boardColumnIds.push($(el).closest('.board-column').data('column-id'));
                taskIds.push($(el).data('task-id'));
                prioritys.push($(el).index());
            });
            var startDate = '{{ $startDate }}';
            var endDate = '{{ $endDate }}';
            var assignedTo = '{{ $assignedTo }}';
            // update values for all tasks
            $.easyAjax({
                url: '{{ route("admin.leads.updateIndex") }}',
                type: 'POST',
                async: false,
                data:{boardColumnIds: boardColumnIds, taskIds: taskIds, prioritys: prioritys,'_token':'{{ csrf_token() }}', draggingTaskId: draggingTaskId, draggedTaskId: draggedTaskId,
                    startDate: startDate, endDate: endDate, assignedTo: assignedTo},
                success: function (response) {
                    draggedTaskId = draggingTaskId;
                    draggingTaskId = 0;

                    if(response.columnData){
                        response.columnData.forEach(function (ind, el) {
                            console.log([ind['columnId'], ind['value']]);
                            $('#statusTotal'+ind['columnId']).html(ind['value']);
                        });
                    }
                }
            });

            $("body").tooltip({
                selector: '[data-toggle="tooltip"]'
            });

            $('.board-column').each(function () {
                let lobipanelItems = $(this).find('.view-task').length;
                // console.log(lobipanelItems);
                if (lobipanelItems == 1) {
                    $(this).find('.lobipanel:first').addClass('m-b-0');
                }
            })

        }).lobiPanel({
            sortable: true,
            reload: false,
            editTitle: false,
            close: false,
            minimize: false,
            unpin: false,
            expand: false

        });

        var isDragging = 0;
        $('.lobipanel-parent-sortable').on('sortactivate', function(){
            // console.log("activate event handle");
            $('.board-column > .panel-body').css('overflow-y', 'unset');
            isDragging = 1;
        });
        $('.lobipanel-parent-sortable').on('sortstop', function(e){
            // console.log("stop event handle");
            $('.board-column > .panel-body').css('overflow-y', 'auto');
            isDragging = 0;
        });

        $('.view-task').click(function () {
            var id = $(this).data('task-id');
            var url = "{{ route('admin.leads.show',':id') }}";
            url = url.replace(':id', id);

            draggingTaskId = id;

            if (isDragging == 0) {
                window.open(url, '_blank');
            }
        })

        $('.delete-column').click(function () {
            var id = $(this).data('column-id');

            var url = "{{ route('admin.lead-status-settings.destroy',':id') }}";
            url = url.replace(':id', id);

            var token = "{{ csrf_token() }}";

            swal({
                title: "@lang('messages.sweetAlertTitle')",
                text: "@lang('messages.deleteColumn')",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "@lang('messages.confirmDelete')",
                cancelButtonText: "@lang('messages.confirmNoArchive')",
                closeOnConfirm: true,
                closeOnCancel: true
            }, function(isConfirm){
                if (isConfirm) {
                    $.easyAjax({
                        type: 'POST',
                        url: url,
                        data: {'_token': token, '_method': 'DELETE'},
                        success: function (response) {
                            if (response.status == "success") {
                                $.unblockUI();
                                window.location.reload();
                            }
                        }
                    });

                }
            });

        })

    });
</script>
