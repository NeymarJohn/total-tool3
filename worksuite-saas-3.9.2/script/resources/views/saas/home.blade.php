@extends('layouts.sass-app')

@section('content')

    <!--
        |‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒`‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒
        | Features
        |‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒
        !-->

    @include('saas.section.header')

    @include('cookieConsent::index')

    @include('saas.section.client')

    @include('saas.section.feature')

    @include('saas.section.testimonial')



@endsection
@push('footer-script')
    <script>
        var maxHeight = -1;
        $(document).ready(function() {


            var promise1 = new Promise(function (resolve, reject) {

                $('.planNameHead').each(function () {
                    maxHeight = maxHeight > $(this).height() ? maxHeight : $(this).height();
                });
                resolve(maxHeight);
            }).then(function (maxHeight) {
                // console.log(maxHeight);
                $('.planNameHead').each(function () {
                    $(this).height(Math.round(maxHeight));
                });
                $('.planNameTitle').each(function () {
                    $(this).height(Math.round(maxHeight - 28));
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
    </script>

@endpush
