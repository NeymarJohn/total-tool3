<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title">Renew Contract</h4>
</div>
<div class="modal-body">
    <div class="portlet-body">
        {!! Form::open(['id'=>'renewContract','class'=>'ajax-form','method'=>'POST']) !!}
        <div class="form-body">
            <div class="row ">
                <div class="col-xs-12 m-b-10">
                    <div class="form-group">
                        <label class="col-xs-3">@lang('app.startDate')</label>
                        <div class="col-xs-9">
                            <input type="text" name="start_date_1" id="start_date_1" class="form-control" value="{{ $contract->start_date->format($global->date_format) }}">
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 m-b-10">
                    <div class="form-group">
                        <label class="col-xs-3">@lang('modules.contracts.endDate')</label>
                        <div class="col-xs-9">
                            <input type="text" name="end_date_1" id="end_date_1" class="form-control" value="@if(!is_null($contract->end_date)){{ $contract->end_date->format($global->date_format) }}@endif">
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 m-b-10">
                    <div class="form-group">
                        <label class="col-xs-3">@lang('app.amount')</label>
                        <div class="col-xs-9">
                            <input type="number" name="amount_1" id="amount_1" class="form-control" value="{{ $contract->amount }}">
                        </div>
                    </div>
                </div>
                @if($contract->signature)
                    <div class="col-xs-12">
                        <div class="form-group">
                            <div class="checkbox checkbox-info">
                                <input id="keep_customer_signature" name="keep_customer_signature" value="true"
                                       type="checkbox">
                                <label for="keep_customer_signature">@lang('modules.contracts.keepSignature')</label>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
<div class="modal-footer">
    <div class="form-actions">
        <button type="button" id="renew-contract" class="btn btn-success"> <i class="fa fa-check"></i> @lang('app.renew')</button>
    </div>
</div>
{{--<script src="{{ asset('plugins/bower_components/moment/moment.js') }}"></script>--}}
<script>
    jQuery('#start_date_1, #end_date_1').datepicker({
        format: '{{ $global->date_picker_format }}',
        autoclose: true,
        todayHighlight: true,
    });

    $('#renew-contract').click(function () {
        $.easyAjax({
            url: '{{route('member.contracts.renew-submit', $contract->id)}}',
            container: '#renewContract',
            type: "POST",
            data: $('#renewContract').serialize(),
            success: function (res) {
                if(res.status == "success")
                {
                    window.location.reload()
                }

            }
        })
    });
</script>