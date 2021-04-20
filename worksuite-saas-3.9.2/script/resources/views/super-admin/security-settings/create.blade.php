    <style>
        .captcha-response{
            margin-top: 25px;
        }
        #message p{
            font-size: 17px;
            text-align: center; 
            color: green;
        }
        #projectCategoryModal .modal-dialog{
                height: 90%;
                 width: 100%;
            }
            
            #projectCategoryModal .modal-content{
                width: 600px;
                margin: 0 auto;
            
        }
        .footer_section{
            display: flex;
            justify-content: space-between;
            padding: 15px;
            align-items: center;
            border-top: 1px solid #e5e5e5;
        }
        </style>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title"@lang('app.add') >@lang('app.verifyCaptcha')</h4>
</div>

{!!  Form::open(['url' => '' ,'method' => 'PUT', 'id' => 'verify-captcha-form','class'=>'form-horizontal']) 	 !!}
<div class="modal-body">
    <div class="box-body">
        <div>
            <input type="hidden" name="_method" value ="PUT">
                @if($google_recaptcha_status == true)
                <input type="hidden" id= "captcha_key" name="google_recaptcha_key" value ="{{ $captcha_key }}">
                <input type="hidden" id= "captcha_secret" name ="google_recaptcha_secret" value ="{{ $google_captcha_secret }}">
                <input type="hidden" id= "captcha_status" name ="google_recaptcha_status" value ="{{ $google_recaptcha_status }}">
                <input type="hidden" id= "google_captcha_version" name ="google_captcha_version" value ="{{ $google_captcha_version }}">

                <input type="hidden" id= "system_update" name ="system_update" value ="{{ $system_update }}">
                <input type="hidden" id= "app_debug" name ="app_debug" value ="{{ $app_debug }}">
                <input type="hidden" id= "enable_register" name ="enable_register" value ="{{ $enable_register }}">
                <input type="hidden" id= "email_verification" name ="email_verification" value ="{{ $email_verification }}">

                @endif
                @if($google_captcha_version == "v2")
                    <div class="form-group captcha-response {{ $errors->has('g-recaptcha-response') ? 'has-error' : '' }}">
                        <div class="col-xs-12">
                            <div class="g-recaptcha gCaptchaDiv" style="margin-left: 123px;"
                                data-sitekey="{{ $captcha_key }}" data-callback="recaptchaCallback" > 
                            </div>
                            @if ($errors->has('g-recaptcha-response'))
                                <div class="help-block with-errors">{{ $errors->first('g-recaptcha-response') }}</div>
                                @endif
                           
                        </div>
                    </div>
                @else
                    <div class="form-group v3-captcha" style="text-align: center;
                    font-size: 16px;" id="v3-captcha">
                    @lang('messages.captchaKeyVerify')
                    </div>
                @endif
            
        </div>
    </div>
</div>
<div class="footer_section">
    @if ($google_captcha_version  == "v2")
        <label class = "submit_detail" style="display: none">@lang('messages.submitDetail')</label>
    <button id="save" type="button"  class="btn btn-custom" style="display: none">@lang('app.submit')</button>
 @endif
 @if ($google_captcha_version  == "v3")
 <button class="g-recaptcha btn btn-custom btn-save" style="display: none" data-sitekey="{{$captcha_key}}" data-callback='savedata' data-error-callback='errorMsg'
    >@lang('app.submit')</button> 
@endif
</div>

{{ Form::close() }}

<script src='https://www.google.com/recaptcha/api.js'></script>
<script>
  var  notError = true;
@if ($google_captcha_version  == "v3")
    setTimeout(() => {
        if(notError){
            let msg = `@lang('messages.verifyKeyVersion')`;
            $('#v3-captcha').html(msg);
           // $("#v3-captcha").css({"color": "green"});
            $(".btn-save").show(); 
        }
        
    }, 3000);
@endif

// For captcha callback version2
function recaptchaCallback() {

    $('#save').show();
    $('.submit_detail').show();
}
// version2
$('#save').click(function () {
    save('verify-captcha-form', 'POST');     
 });

 
//callback for version3
function errorMsg(){
    $('#v3-captcha').html("@lang('messages.captchaError')");
    $("#v3-captcha").css({"color": "red", "margin-left": "48px"});
    $(".btn-save").hide(); 
    notError = false;
}
//save version3
function savedata(){
     $.easyAjax({
         url: '{{route('super-admin.security-settings.update', $global->id)}}',
        container: '#verify-captcha-form',
        type: 'POST',
         redirect: true,
        data: $('#verify-captcha-form').serialize(),
                success: function (response) {
                    if(response.status == 'success'){
                        $('#projectCategoryModal').modal('hide');

                        //location.reload();
                    }
                }
            })
}

</script>

