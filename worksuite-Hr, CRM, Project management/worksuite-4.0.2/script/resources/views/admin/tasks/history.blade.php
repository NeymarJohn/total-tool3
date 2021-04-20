<div class="row">

    <div class="col-xs-12"   id="project-timeline">
        <div class="panel">
     
            <div class="panel-wrapper collapse in">
                <div class="panel-body p-t-15">
                    <div class="steamline">
                        @foreach($task->history as $activ)
                        <div class="sl-item">
                            <div class="sl-left"><img class="img-circle" src="{{ $activ->user->image_url }}" width="25" height="25" alt="">
                            </div>
                            <div class="sl-right">
                                <div>
                                    <h6>{{ __("modules.tasks.".$activ->details) }} {{ ucwords($activ->user->name) }} <label style="background: {{ $activ->board_column->label_color }}" class="label">{{ $activ->board_column->column_name }}</label></h6>

                                    @if (!is_null($activ->sub_task_id))
                                        <h6><small class="text-info">{{ $activ->sub_task->title }}</small></h6>
                                    @endif

                                    <span class="sl-date">{{ $activ->created_at->timezone($global->timezone)->format($global->date_format)." ".$activ->created_at->timezone($global->timezone)->format($global->time_format) }}</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
