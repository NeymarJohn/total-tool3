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
    </div>

</div>
