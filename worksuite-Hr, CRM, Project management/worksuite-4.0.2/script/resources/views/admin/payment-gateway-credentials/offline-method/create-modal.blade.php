<style>
    .panel-black .panel-heading a, .panel-inverse .panel-heading a {
        color: unset!important;
    }
</style>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title">@lang('app.addNew') @lang('app.menu.offlinePaymentMethod')</h4>
</div>

<div class="modal-body">
    <div class="portlet-body">

        {!! Form::open(['id'=>'createMethods','class'=>'ajax-form','method'=>'POST']) !!}

        <div class="form-body">

            <div class="form-group">
                <label class="required">@lang('modules.offlinePayment.method')</label>
                <input type="text" name="name" id="name" class="form-control">
            </div>
            <div class="form-group">
                <label class="required">@lang('modules.offlinePayment.description')</label>
                <textarea id="description" name="description" class="form-control summernote"></textarea>
            </div>

        </div>

        {!! Form::close() !!}
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white waves-effect" data-dismiss="modal">Close</button>
    <button type="button" id="save-method" class="btn btn-info save-event waves-effect waves-light"><i class="fa fa-check"></i> @lang('app.save')
    </button>
</div>

<script>

    //    save project members
    $('#save-method').click(function () {
        $.easyAjax({
            url: '{{route('admin.offline-payment-setting.store')}}',
            container: '#createMethods',
            type: "POST",
            data: $('#createMethods').serialize()
        })
    });
</script>

