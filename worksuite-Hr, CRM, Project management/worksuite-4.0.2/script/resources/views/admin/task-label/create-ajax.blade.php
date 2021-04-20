<link rel="stylesheet" href="{{ asset('plugins/bower_components/jquery-asColorPicker-master/css/asColorPicker.css') }}">
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title">@lang('app.add') @lang('app.menu.taskLabel')</h4>
</div>
<style>
    .suggest-colors a {
        border-radius: 4px;
        width: 30px;
        height: 30px;
        display: inline-block;
        margin-right: 10px;
        margin-bottom: 10px;
        text-decoration: none;
    }
    .asColorPicker-dropdown {
        min-width: 259px !important;
        max-width: 265px !important;
    }
    .asColorPicker-trigger{
        position: absolute;
        height: 30px;
        width: 30px;
    }
</style>
<div class="modal-body">
    <div class="portlet-body">
        {!! Form::open(['id'=>'createTaskLabelForm','method'=>'POST']) !!}
        <div class="form-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="company_name" class="required">@lang('app.label') @lang('app.name')</label>
                        <input type="text" class="form-control" name="label_name" value="" />
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="description">@lang('app.description') </label>
                        <textarea class="form-control" name="description"></textarea>
                    </div>

                </div>
            </div>
            <div class="form-group">
                <label class="required">@lang('modules.sticky.colors')</label>
                <div class="example m-b-10">
                    <input type="text" class="complex-colorpicker form-control" name="color" id="color" value="#428BCA" />
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">

                </div>
                <div class="col-md-12">
                    <p>
                        @lang('messages.taskLabel.labelColorSuggestion')
                    </p>
                </div>
                <div class="col-md-12">
                    <div class="suggest-colors">
                        <a style="background-color: #0033CC" data-color="#0033CC" href="javascript:;">&nbsp;
                        </a><a style="background-color: #428BCA" data-color="#428BCA" href="javascript:;">&nbsp;
                        </a><a style="background-color: #CC0033" data-color="#CC0033" href="javascript:;">&nbsp;
                        </a><a style="background-color: #44AD8E" data-color="#44AD8E" href="javascript:;">&nbsp;
                        </a><a style="background-color: #A8D695" data-color="#A8D695" href="javascript:;">&nbsp;
                        </a><a style="background-color: #5CB85C" data-color="#5CB85C" href="javascript:;">&nbsp;
                        </a><a style="background-color: #69D100" data-color="#69D100" href="javascript:;">&nbsp;
                        </a><a style="background-color: #004E00" data-color="#004E00" href="javascript:;">&nbsp;
                        </a><a style="background-color: #34495E" data-color="#34495E" href="javascript:;">&nbsp;
                        </a><a style="background-color: #7F8C8D" data-color="#7F8C8D" href="javascript:;">&nbsp;
                        </a><a style="background-color: #A295D6" data-color="#A295D6" href="javascript:;">&nbsp;
                        </a><a style="background-color: #5843AD" data-color="#5843AD" href="javascript:;">&nbsp;
                        </a><a style="background-color: #8E44AD" data-color="#8E44AD" href="javascript:;">&nbsp;
                        </a><a style="background-color: #FFECDB" data-color="#FFECDB" href="javascript:;">&nbsp;
                        </a><a style="background-color: #AD4363" data-color="#AD4363" href="javascript:;">&nbsp;
                        </a><a style="background-color: #D10069" data-color="#D10069" href="javascript:;">&nbsp;
                        </a><a style="background-color: #FF0000" data-color="#FF0000" href="javascript:;">&nbsp;
                        </a><a style="background-color: #D9534F" data-color="#D9534F" href="javascript:;">&nbsp;
                        </a><a style="background-color: #D1D100" data-color="#D1D100" href="javascript:;">&nbsp;
                        </a><a style="background-color: #F0AD4E" data-color="#F0AD4E" href="javascript:;">&nbsp;
                        </a><a style="background-color: #AD8D43" data-color="#AD8D43" href="javascript:;">&nbsp;
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-actions">
            <button type="button" id="save-label" class="btn btn-success"> <i class="fa fa-check"></i> @lang('app.save')</button>
        </div>
        {!! Form::close() !!}

    </div>
</div>
<script src="{{ asset('plugins/bower_components/jquery-asColorPicker-master/libs/jquery-asColor.js') }}"></script>
<script src="{{ asset('plugins/bower_components/jquery-asColorPicker-master/libs/jquery-asGradient.js') }}"></script>
<script src="{{ asset('plugins/bower_components/jquery-asColorPicker-master/dist/jquery-asColorPicker.min.js') }}"></script>
<script>
    $(".colorpicker").asColorPicker();
    $(".complex-colorpicker").asColorPicker({
        mode: 'complex'
    });
    $(".gradient-colorpicker").asColorPicker({
        mode: 'gradient'
    });

    $('#save-label').click(function () {
        $.easyAjax({
            url: '{{route('admin.task-label.store-label')}}',
            container: '#createTaskLabelForm',
            type: "POST",
            data: $('#createTaskLabelForm').serialize(),
            success: function(response){
                if(response.status == 'success'){
                    if ($('#multiselect').length !== 0) {
                        $('#multiselect').html(response.labels);
                        $('#multiselect').selectpicker('refresh');
                        $('#taskLabelModal').modal('hide');                        
                    } else {
                        window.location.reload();
                    }
                }
            }
        })
    });

    $('.suggest-colors a').click(function () {
        var color = $(this).data('color');
        $('#color').val(color);
        $('.asColorPicker-trigger span').css('background', color);
    });

</script>