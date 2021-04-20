@forelse($lead->follow as $follow)
    <a href="javascript:;" data-follow-id="{{ $follow->id }}" class="list-group-item edit-task">
        <h4 class="list-group-item-heading sbold">@lang('app.createdOn'): {{ $follow->created_at->toFormattedDateString() }}</h4>
        <p class="list-group-item-text">
        <div class="row margin-top-5">
            <div class="col-md-12">
                @lang('app.remark'): <br>
                {!!  ($follow->remark != '') ? ucfirst($follow->remark) : "<span class='font-red'>Empty</span>" !!}
            </div>
        </div>
        <div class="row margin-top-5">
            <div class="col-md-6">
            </div>
            <div class="col-md-6">
                @lang('app.next_follow_up'): {{ $follow->next_follow_up_date->toFormattedDateString() }}
            </div>
        </div>
        </p>
    </a>
@empty
    <a href="javascript:;" class="list-group-item">
        <h4 class="list-group-item-heading sbold">@lang('modules.followup.followUpNotFound')</h4>
    </a>
@endforelse