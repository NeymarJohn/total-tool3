@extends('layouts.front-app')
@section('header-section')
    <style>
        .mb-3, .my-3{
            margin-bottom: 0px !important;
        }
        .section-header small{
            font-size: 18px;
        }
        .container-scroll > .row{
            overflow-x: auto;
            white-space: nowrap;
        }
        .container-scroll > .row > .col-md-2{
            display: inline-block;
            float: none;
        }
        .pricing__head h3, .pricing__head h5{
            white-space: normal;
        }
        .container .gap-y .col-12 .flexbox{
            justify-content: unset;
        }
    </style>
    @include('front.section.header')

@endsection
@section('content')
    <!--
        |‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒`‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒
        | Features
        |‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒
        !-->
    @include('front.section.feature')

    <!--
        |‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒
        | Pricing
        |‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒
        !-->
    @if(!empty($packages))
        @include('front.section.pricing')
    @endif

    <!--
        |‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒
        | CONTACT
        |‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒
        !-->
    @include('front.section.contact')
@endsection
@push('footer-script')
    <script>
        var maxHeight = -1;
        $(document).ready(function() {

            var promise1 = new Promise(function(resolve, reject) {

                $('.planNameHead').each(function() {
                    maxHeight = maxHeight > $(this).height() ? maxHeight : $(this).height();
                });
                // console.log('hello1', maxHeight);
                resolve(maxHeight);
            }).then(function(maxHeight) {
                // console.log(maxHeight);
                $('.planNameHead').each(function() {
                    $(this).height(Math.round(maxHeight));
                });
                $('.planNameTitle').each(function() {
                    $(this).height(Math.round(maxHeight-28));
                });

            });
        });
        function planShow(type){
            if(type == 'monthly'){
                $('#monthlyPlan').show();
                $('#annualPlan').hide();
            }
            else{
                $('#monthlyPlan').hide();
                $('#annualPlan').show();
            }
        }

        $('#save-form').click(function () {

            @if($global->google_recaptcha_status)
                if(grecaptcha.getResponse().length == 0){
                    alert('Please click the reCAPTCHA checkbox');
                    return false;
                }
            @endif

            $.easyAjax({
                url: '{{route('front.contact-us')}}',
                container: '#contactUs',
                type: "POST",
                data: $('#contactUs').serialize(),
                messagePosition: "inline",
                success: function (response) {
                    if(response.status == 'success'){
                        $('#contactUsBox').remove();
                    }
                }
            })
        });

    </script>

@endpush
