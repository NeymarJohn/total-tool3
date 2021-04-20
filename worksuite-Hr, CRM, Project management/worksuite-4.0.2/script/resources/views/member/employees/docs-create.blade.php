<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title"> <i class="fa fa-plus"></i>  @lang('app.menu.employeeDocs')</h4>
</div>
<div class="modal-body">
    {!! Form::open(array('id' => 'add_docs_form', 'class'=>'form-horizontal ','method'=>'POST')) !!}
    <input type="hidden" name="user_id" value="{{ $employeeID }}">
    <div class="form-body">
        <div class="row">
            <div id="addMoreBox1" class="clearfix">
                <div class="col-md-5">
                    <div id="dateBox" class="form-group ">
                        <input class="form-control" autocomplete="off" id="dateField1" name="name[0]" type="text" value="" placeholder="Name"/>
                        <div id="errorDate"></div>
                    </div>
                </div>
                <div class="col-md-5" style="margin-left: 5px;">
                    <div class="form-group" id="occasionBox">
                        <input class="form-control"  type="file" name="file[0]" placeholder="Docs"/>
                        <div id="errorOccasion"></div>
                    </div>
                </div>
                <div class="col-md-1">
                    {{--<button type="button"  onclick="removeBox(1)"  class="btn btn-sm btn-danger"><i class="fa fa-times"></i></button>--}}
                </div>
            </div>
            <div id="insertBefore"></div>
            <div class="clearfix">

            </div>
            <button type="button" id="plusButton" class="btn btn-sm btn-info" style="margin-bottom: 20px">
                Add More <i class="fa fa-plus"></i>
            </button>
            <div class="alert alert-info"><i class="fa fa-info-circle"></i> @lang('messages.employeeDocsAllowedFormat')</div>

        </div>
        <!--/row-->
    </div>
    {!! Form::close() !!}
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white waves-effect" data-dismiss="modal">Close</button>
    <button type="button" onclick="storeDocs()" class="btn btn-info save-event waves-effect waves-light"><i class="fa fa-check"></i> @lang('app.save')
    </button>
</div>

<script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>

<script>
    var $insertBefore = $('#insertBefore');
    var $i = 0;

    // Add More Inputs
    $('#plusButton').click(function(){
        $i = $i+1;
        var indexs = $i+1;
        $(' <div id="addMoreBox'+indexs+'" class="clearfix"> ' +
            '<div class="col-md-5"><div class="form-group "><input autocomplete="off" class="form-control '+$i+'" id="dateField'+indexs+'" name="name['+$i+']" data-date-format="dd/mm/yyyy" type="text" value="" placeholder="Name"/></div></div>' +
            '<div class="col-md-5 "style="margin-left:5px;"><div class="form-group"><input class="form-control " name="file['+$i+']" type="file" value="" placeholder="Docs"/></div></div>' +
            '<div class="col-md-1"><button type="button" onclick="removeBox('+indexs+')" class="btn btn-sm btn-danger"><i class="fa fa-times"></i></button></div>' +
            '</div>').insertBefore($insertBefore);
    });
    // Remove fields
    function removeBox(index){
        $('#addMoreBox'+index).remove();
    }

    // Store Holidays
    function storeDocs(){
        $('#dateBox').removeClass("has-error");
        $('#occasionBox').removeClass("has-error");
        $('#errorDate').html('');
        $('#errorOccasion').html('');
        $('.help-block').remove();
        var url = "{{ route('member.employee-docs.store') }}";
        $.easyAjax({
            type: 'POST',
            url: url,
            container: '#add_docs_form',
            file: true,
            success: function (response) {
                $('#employeeDocsList').html(response.html);
                $('#edit-column-form').modal('hide');
            }
            ,error: function (response) {
                if(response.status == '422'){
                    if(typeof response.responseJSON.errors['name.0'] != 'undefined' && typeof response.responseJSON.errors['name.0'][0] != 'undefined'){
                        $('#dateBox').addClass("has-error");
                        $('#errorDate').html('<span class="help-block" id="errorDate">'+response.responseJSON.errors['name.0'][0]+'</span>');
                    }
                    if(typeof response.responseJSON.errors['file.0'] != "undefined" && response.responseJSON.errors['file.0'][0]  != 'undefined'){
                        $('#occasionBox').addClass("has-error");
                        $('#errorOccasion').html('<span class="help-block" id="errorOccasion">'+response.responseJSON.errors['file.0'][0]+'</span>');
                    }
                }
            }
        });
    }

</script>


