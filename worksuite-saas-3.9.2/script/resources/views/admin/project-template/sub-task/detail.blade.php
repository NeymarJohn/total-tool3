
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title"><i class="ti-eye"></i> @lang('app.menu.tasks') @lang('app.details')</h4>
</div>
<div class="modal-body">
    <div class="form-body">
        <div class="row">
            <div class="col-xs-12">
                <h5>{{ ucwords($task->heading) }}
                    @if($task->task_category_id)
                        <label class="label label-default text-dark m-l-5 font-light">{{ ucwords($task->category->category_name) }}</label>
                    @endif

                    <label class="m-l-5 font-light label
                @if($task->priority == 'high')
                            label-danger @elseif($task->priority == 'medium') label-warning @else label-success @endif ">
                        <span class="text-dark">@lang('modules.tasks.priority') ></span>  {{ ucfirst($task->priority) }}
                    </label>


                </h5>
                @if(!is_null($task->project_id))
                    <p><i class="icon-layers"></i> {{ ucfirst($task->project->project_name) }}</p>
                @endif
            </div>

            <div class="col-xs-6">
                <label class="font-12" for="">@lang('modules.tasks.assignTo')</label><br>
                @foreach($task->users_many as $user)
                <a href="javascript:;" data-toggle="tooltip" data-original-title="{{ ucwords($user->name) }}">
                    <img src="{{ $user->image_url }}" class="img-circle" width="25" height="25" alt=""></a>
                @endforeach
            </div>
            <div class="col-xs-6">
                <label class="font-12" for="">@lang('app.menu.projectTemplate')</label><br>
                {{ ucwords($task->projectTemplate->project_name) }}
            </div>
            <div class="col-xs-12 m-t-10">
                <label class="font-12" for="">@lang('app.description')</label><br>
                {!! ucwords($task->description) !!}
            </div>
        </div>

    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white waves-effect" data-dismiss="modal">Close</button>
</div>

