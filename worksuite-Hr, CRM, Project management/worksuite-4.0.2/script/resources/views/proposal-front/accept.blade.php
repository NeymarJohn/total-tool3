<style>
    .signature-pad {
        position: absolute;
        left: 0;
        top: 0;
        width:100%;
        height: 150px;
        background-color: white;
    }
    .signature-pad {
        border: 1px solid #000000;
    }
</style>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    @if($type == 'accept')
        <h4 class="modal-title">@lang('modules.estimates.signatureAndConfirmation')</h4>
    @else
        <h4 class="modal-title">@lang('modules.proposal.rejectConfirm')</h4>
    @endif
</div>
<div class="modal-body">
    <div class="portlet-body">
        {!! Form::open(['id'=>'acceptProposal','class'=>'ajax-form','method'=>'POST']) !!}
        <input type="hidden" name="type" value="{{ $type }}" id="type">
        <div class="form-body">
            <div class="row ">
                @if($type == 'accept')
                    <div class="col-xs-12 m-b-10">
                        <div class="form-group">
                            <label class="col-xs-3">@lang('modules.estimates.firstName')</label>
                            <div class="col-xs-9">
                                <input type="text" name="name" id="name" class="form-control" value="{{ $proposal->lead->client_name }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 m-b-10">
                        <div class="form-group">
                            <label class="col-xs-3">@lang('modules.lead.email')</label>
                            <div class="col-xs-9">
                                <input type="email" name="email" id="email" class="form-control"  value="{{ $proposal->lead->client_email }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 m-b-10" style="height: 150px">
                        <div class="form-group">
                            <label>@lang('modules.estimates.signature')</label>
                            <div class="wrapper form-control">
                                <canvas id="signature-pad" class="signature-pad"></canvas>
                            </div>

                        </div>
                    </div>
                    <button id="undo" class="btn btn-default m-l-10">@lang('modules.estimates.undo')</button>
                    <button id="clear" class="btn btn-danger">@lang('modules.estimates.clear')</button>
                @else
                    <div class="col-xs-12 m-b-10">
                        <div class="form-group">
                            <label class="col-xs-3">@lang('app.reason') (@lang('app.optional')) :</label>
                            <div class="col-xs-9">
                                <textarea name="comment" id="comment" rows="5" class="form-control"> </textarea>
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
        @if($type == 'accept')
            <button type="button" id="accept-signature" class="btn btn-success save-action"> <i class="fa fa-check"></i> @lang('app.accept')</button>
        @else
            <button type="button" id="reject-signature" class="btn btn-danger save-action"> <i class="fa fa-check"></i> @lang('app.reject')</button>
        @endif
    </div>
</div>

<script>
    @if($type == 'accept')
        $(function () {
            var canvas = document.getElementById('signature-pad');

    // Adjust canvas coordinate space taking into account pixel ratio,
    // to make it look crisp on mobile devices.
    // This also causes canvas to be cleared.
            function resizeCanvas() {
                // When zoomed out to less than 100%, for some very strange reason,
                // some browsers report devicePixelRatio as less than 1
                // and only part of the canvas is cleared then.
                var ratio =  Math.max(window.devicePixelRatio || 1, 1);
                canvas.width = canvas.offsetWidth * ratio;
                canvas.height = canvas.offsetHeight * ratio;
                canvas.getContext("2d").scale(ratio, ratio);
            }

            window.onresize = resizeCanvas;
            resizeCanvas();

            signaturePad = new SignaturePad(canvas, {
                backgroundColor: 'rgb(255, 255, 255)' // necessary for saving image as JPEG; can be removed is only saving as PNG or SVG
            });

            document.getElementById('clear').addEventListener('click', function (e) {
                e.preventDefault();
                signaturePad.clear();
            });

            document.getElementById('undo').addEventListener('click', function (e) {
                e.preventDefault();
                var data = signaturePad.toData();
                if (data) {
                    data.pop(); // remove the last dot or line
                    signaturePad.fromData(data);
                }
            });
        });
    @endif

    $('.save-action').click(function () {
        var type = $('#type').val();
        if(type == 'accept'){
            var signature = signaturePad.toDataURL('image/png');

            if (signaturePad.isEmpty()) {
                return $.showToastr("Please provide a signature first.", 'error');
            }
            var name = $('#name').val();
            var email = $('#email').val();
            $.easyAjax({
                url: '{{route('front.proposal-action-post', md5($proposal->id))}}',
                container: '#acceptEstimate',
                type: "POST",
                disableButton:true,
                data: {
                    type:type,
                    name:name,
                    email:email,
                    comment:comment,
                    signature:signature,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    window.location.reload();
                }
            })
        }
        else{
            var comment = $('#comment').val();
            $.easyAjax({
                url: '{{route('front.proposal-action-post', md5($proposal->id))}}',
                container: '#acceptEstimate',
                type: "POST",
                disableButton:true,
                data: {
                    comment:comment,
                    type:type,
                    _token: '{{ csrf_token() }}'
                },
                success: function(data){
                    if(data.status == 'success'){
                        window.location.reload();
                    }
                }
            })
        }

    });
</script>