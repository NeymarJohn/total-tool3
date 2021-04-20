<form id="editSettings" class="ajax-form" data-language-id="{{ $frontDetail->language_setting_id }}" data-type="{{ $type }}">
    @csrf
    <input type="hidden" name="type" value="{{ $type }}">

    <div class="row">
        <div class="col-sm-12 col-md-6 col-xs-12">
            <div class="form-group">
                <label for="title">@lang('app.title')</label>
                <input type="text" class="form-control" id="title" name="title"
                @if($type == 'task')
                    value="{{ $frontDetail->task_management_title }}"
                @elseif($type == 'bills')
                    value="{{ $frontDetail->manage_bills_title }}"
                @elseif($type == 'image')
                    value="{{ $frontDetail->feature_title }}"
                @elseif($type == 'team')
                    value="{{ $frontDetail->teamates_title }}"
                @elseif($type == 'apps')
                    value="{{ $frontDetail->favourite_apps_title }}"
                @endif
                >
            </div>
        </div>

        <div class="col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="address">@lang('app.description')</label>

                @if($type == 'task')
                    <textarea class="form-control" id="detail" rows="5" name="detail">{{ $frontDetail->task_management_detail }}</textarea>
                @elseif($type == 'bills')
                    <textarea class="form-control" id="detail" rows="5" name="detail">{{ $frontDetail->manage_bills_detail }}</textarea>
                @elseif($type == 'image')
                    <textarea class="form-control" id="detail" rows="5" name="detail">{{ $frontDetail->feature_description }}</textarea>
                @elseif($type == 'team')
                    <textarea class="form-control" id="detail" rows="5" name="detail">{{ $frontDetail->teamates_detail }}</textarea>
                @elseif($type == 'apps')
                    <textarea class="form-control" id="detail" rows="5" name="detail">{{ $frontDetail->favourite_apps_detail }}</textarea>
                @endif
            </div>
        </div>
    </div>

    <button type="button" id="save-form"
            class="btn btn-success waves-effect waves-light m-r-10">
        @lang('app.update')
    </button>
</form>