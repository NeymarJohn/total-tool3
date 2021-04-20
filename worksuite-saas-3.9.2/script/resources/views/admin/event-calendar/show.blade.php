<div id="event-detail">

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><i class="ti-eye"></i> @lang('app.menu.Events') @lang('app.details')</h4>
    </div>
    <div class="modal-body">
        {!! Form::open(['id'=>'updateEvent','class'=>'ajax-form','method'=>'GET']) !!}
        <div class="form-body">
            <div class="row">
                <div class="col-md-12 ">
                    <div class="form-group">
                        <label>@lang('modules.events.eventName')</label>
                        <p>
                            {{ ucfirst($event->event_name) }}
                        </p>
                        <p class="font-normal"> &mdash; <i>at</i> {{ $event->where }}</p>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-xs-12 ">
                    <div class="form-group">
                        <label>@lang('app.description')</label>
                        <p>{{ ucfirst($event->description) }}</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="font-12" for="">@lang('modules.events.attendees')</label><br>
                    @foreach ($event->attendee as $item)
                        <img src="{{ $item->user->image_url }}" data-toggle="tooltip"
                             data-original-title="{{ ucwords($item->user->name) }}" data-placement="right"
                             class="img-circle" width="25" height="25" alt="user">
                    @endforeach
                </div>

            </div>
            
            <div class="row">
                @if($event->category!=null)
                <div class="col-xs-12 col-md-4 ">
                    <div class="form-group">
                        <label>@lang('modules.tasks.category')</label>
                        <p>{{ $event->category->category_name }}</p>
                    </div>
                </div>
                @endif
                @if($event->eventType!=null)
                <div class="col-xs-12 col-md-4 ">
                    <div class="form-group">
                        <label>@lang('modules.events.eventType')</label>
                        <p>{{ $event->eventType->name }}</p>
                    </div>
                </div>
                @endif
            </div>
            <div class="row">
                <div class="col-xs-6 col-md-3 ">
                    <div class="form-group">
                        <label>@lang('modules.events.startOn')</label>
                        @if($event->repeat == 'yes')
                            <p>{{ $startDate->format($global->date_format. ' - '.$global->time_format) }}</p>
                        @else
                            <p>{{ $event->start_date_time->format($global->date_format. ' - '.$global->time_format) }}</p>
                        @endif
                    </div>
                </div>
                <div class="col-xs-6 col-md-3">
                    <div class="form-group">
                        <label>@lang('modules.events.endOn')</label>
                        @if ($event->repeat == 'yes')
                            <p>{{ $startDate->format($global->date_format) }} - {{ $event->end_date_time->format($global->time_format) }}</p>
                        
                        @else
                            <p>{{ $event->end_date_time->format($global->date_format. ' - '.$global->time_format) }}</p>    
                        @endif
                       
                    </div>
                </div>

            </div>
        </div>
        {!! Form::close() !!}

    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-white waves-effect" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-danger btn-outline delete-event waves-effect waves-light"><i class="fa fa-times"></i> @lang('app.delete')</button>
        <button type="button" class="btn btn-info save-event waves-effect waves-light"><i class="fa fa-edit"></i> @lang('app.edit')
        </button>
    </div>

</div>

<script>

    $('.save-event').click(function () {
        $.easyAjax({
            url: '{{route('admin.events.edit', $event->id)}}',
            container: '#updateEvent',
            type: "GET",
            data: $('#updateEvent').serialize(),
            success: function (response) {
                if(response.status == 'success'){
                    $('#event-detail').html(response.view);
                }
            }
        })
    })

    $('.delete-event').click(function(){
        swal({
            title: "@lang('messages.sweetAlertTitle')",
            text: "@lang('messages.confirmation.recoverEvent')",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "@lang('messages.deleteConfirmation')",
            cancelButtonText: "@lang('messages.confirmNoArchive')",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function(isConfirm){
            if (isConfirm) {

                var url = "{{ route('admin.events.destroy', $event->id) }}";

                var token = "{{ csrf_token() }}";

                $.easyAjax({
                    type: 'POST',
                            url: url,
                            data: {'_token': token, '_method': 'DELETE'},
                    success: function (response) {
                        if (response.status == "success") {
                            window.location.reload();
                        }
                    }
                });
            }
        });
    });
    $("[data-toggle=tooltip").tooltip();


</script>