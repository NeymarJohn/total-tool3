<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title">@lang('modules.customFields.addField')</h4>
</div>

{!!  Form::open(['url' => '' ,'method' => 'post', 'id' => 'add-edit-form','class'=>'form-horizontal']) 	 !!}
<div class="modal-body">
    <div class="box-body">
        <div class="form-group">
            <label class="col-sm-2 control-label" for="display_name">@lang('modules.invoices.type')</label>
            <div class="col-sm-10">
                   <select name="module" id="module" class="form-control">
                     @foreach ($customFieldGroups as $item)
                        <option value="{{ $item->id }}"> @lang('app.'.strtolower($item->name))</option>
                     @endforeach
                 </select>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label required" for="name">@lang('modules.customFields.label')</label>
            <div class="col-sm-10">
                <input type="text" name="label" id="label" class="form-control" onkeyup="slug(this.value)">
                <div class="form-control-focus"> </div>
                <span class="help-block"></span>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label required" for="name">@lang('app.name')</label>
            <div class="col-sm-10">
                <input type="text" name="name" id="name" class="form-control" >
                <div class="form-control-focus"> </div>
                <span class="help-block"></span>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label required" for="required">@lang('app.required')</label>
            <div class="col-sm-10">
                <div class="radio radio-success">

                    <input type="radio" name="required" id="optionsRadios1" value="yes" checked>
                    <label for="optionsRadios1"> @lang('app.yes') </label>


                </div>
                <div class="radio radio-success">

                    <input type="radio" name="required" id="optionsRadios2" value="no" >
                    <label for="optionsRadios2"> @lang('app.no') </label>

                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label required" for="display_name">@lang('modules.invoices.type')</label>
            <div class="col-sm-10">
                {!! Form::select('type',
                    [
                        'text'      => 'text',
                        'number'    =>'number',
                        'password'  => 'password',
                        'textarea'  =>'textarea',
                        'select'    =>'select',
                        'radio'    =>'radio',
                        'date'      =>'date'

                    ],
                    '',['class' => 'form-control gender','id' => 'type'])
                 !!}
            </div>
        </div>

        <div class="form-group mt-repeater" style="display: none;">

            <div data-repeater-list="group">
                <div data-repeater-item>
                    <div class="row mt-repeater-row">
                        <label class="col-md-3 control-label"></label>
                        <div class="col-md-8">
                            <label class="control-label">@lang('app.value')</label>
                            <input type="text" name="value[]" class="form-control" /> </div>
                        <div class="col-md-1 m-t-30" >
                            <a href="javascript:;" data-repeater-delete class="btn btn-danger btn-xs mt-repeater-delete">
                                <i class="fa fa-close"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div><br>
            <div class="row">
                <div class="col-md-12 text-center">
                    <a href="javascript:;" data-repeater-create class="btn btn-info mt-repeater-add">
                        <i class="fa fa-plus"></i></a>
                </div>
            </div>

        </div>

    </div>
</div>

<div class="modal-footer">
    <button id="save" type="button" class="btn btn-custom">@lang('app.submit')</button>
</div>
{{ Form::close() }}
<script>
    var FormRepeater = function () {

        return {
            //main function to initiate the module
            init: function () {
                $('.mt-repeater').each(function(){
                    $(this).repeater({
                        show: function () {
                            $(this).slideDown();
                        },

                        hide: function (deleteElement) {
                            $(this).slideUp(deleteElement);
                        },

                        ready: function (setIndexes) {

                        },
                        isFirstItemUndeletable: true,


                    });
                });
            }

        };

    }();

    jQuery(document).ready(function() {
        FormRepeater.init();
    });

    $('#type').on('change', function () {
        // if (this.value == '1'); { No semicolon and I used === instead of ==
        if (this.value === 'select' || this.value === 'radio' || this.value === 'checkbox'){
            $(".mt-repeater").show();
        } else {
            $(".mt-repeater").hide();
        }
    });

    function convertToSlug(Text)
    {
        return Text
                .toLowerCase()
                .replace(/[^\w ]+/g,'')
                .replace(/ +/g,'-')
                ;
    }

    function slug(text){
        $('#name').val(convertToSlug(text));
    }

    $('#save').click(function () {
        $.easyAjax({
            url: '{{route('admin.custom-fields.store')}}',
            container: '#add-edit-form',
            type: "POST",
            data: $('#add-edit-form').serialize(),
            file:true,
            success: function (response) {
                if(response.status == 'success'){
                    window.location.reload();
                }
            }
        })
        return false;
    })
</script>

