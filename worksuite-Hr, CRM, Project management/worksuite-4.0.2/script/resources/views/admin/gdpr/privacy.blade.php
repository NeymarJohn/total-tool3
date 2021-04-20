@extends('layouts.client-app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> @lang($pageTitle)</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('client.dashboard.index') }}">@lang('app.menu.home')</a></li>
                <li class="active">@lang($pageTitle)</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
    <style>
        .col-in {
            padding: 0 20px !important;

        }

        .fc-event {
            font-size: 10px !important;
        }

        @media (min-width: 769px) {
            .panel-wrapper {
                height: 500px;
                overflow-y: auto;
            }
        }

    </style>
@endpush

@section('content')
    <div class="white-box">
        <div class="row dashboard-stats">

            <div class="col-sm-12">
                <p>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam dictum neque et pulvinar
                    dapibus. Duis odio elit, sollicitudin nec aliquet id, tristique quis libero. Vivamus sit
                    amet est eget lacus blandit varius. Vestibulum ante ipsum primis in faucibus orci luctus et
                    ultrices posuere cubilia Curae; Suspendisse euismod feugiat nibh non malesuada. Proin eget
                    nisl mattis, maximus arcu quis, ornare erat. Duis at sollicitudin sem. Nulla facilisi. Proin
                    ac risus in erat volutpat pellentesque. Donec scelerisque elit ac mauris ullamcorper, sit
                    amet rhoncus erat bibendum.

                    Vestibulum laoreet odio eget leo congue lacinia. Donec suscipit pellentesque urna, a
                    lobortis metus suscipit id. Donec placerat aliquet pulvinar. Proin vel eros at risus commodo
                    lacinia in id tellus. Sed in ultricies magna. Fusce euismod ipsum vitae ipsum tristique, ut
                    lacinia eros dapibus. Maecenas pharetra efficitur orci, in porta nibh faucibus quis. Donec
                    posuere nisi est, ut ultrices massa dictum quis. Mauris semper lobortis est. Aliquam quis
                    neque nec nisi elementum rutrum. Pellentesque habitant morbi tristique senectus et netus et
                    malesuada fames ac turpis egestas.

                    Pellentesque eget vehicula tellus, a malesuada purus. Nulla hendrerit erat eget tellus
                    aliquam, quis blandit metus consequat. Donec accumsan fermentum lacus non mollis. Vestibulum
                    nec mollis dolor. Sed consectetur rhoncus nibh, et aliquet lectus lobortis id. Pellentesque
                    eros arcu, laoreet viverra feugiat sed, luctus id nisi. Nam posuere, eros at pellentesque
                    ullamcorper, turpis tortor finibus quam, eu dapibus mi nulla sed tellus. Nullam eleifend
                    aliquet commodo. Sed molestie enim sed orci sodales facilisis. Etiam velit orci, mollis sed
                    sem et, varius sagittis diam.

                    Donec nibh tellus, viverra id eleifend vel, posuere nec diam. Donec eget nisl turpis. Fusce
                    consequat ex id ex placerat consequat. Ut quis auctor lectus. Curabitur sed justo ornare,
                    tincidunt libero placerat, pretium magna. Pellentesque id lacinia orci. Duis eleifend at leo
                    in ornare.

                    Donec auctor tellus ac nunc eleifend, at sodales eros malesuada. Proin vitae tellus vitae
                    elit vulputate posuere. Vestibulum magna nulla, condimentum ac tempus sit amet, bibendum ut
                    metus. Maecenas rhoncus ex non turpis vehicula malesuada. Suspendisse felis sem, sodales nec
                    facilisis quis, consequat ultrices nibh. Praesent fermentum libero ac enim aliquam, eu
                    dapibus metus mollis. Mauris dignissim, enim eu vestibulum tincidunt, diam tellus viverra
                    augue, ac sollicitudin libero sem sit amet nulla. In ac posuere magna. Vestibulum ultrices
                    interdum quam vitae rutrum. Proin at elit commodo, porttitor dolor nec, dictum diam. Proin
                    sed sapien sit amet turpis ullamcorper dignissim vitae in ligula. Vestibulum auctor eros sit
                    amet turpis rhoncus maximus. In hac habitasse platea dictumst. Aliquam massa tellus,
                    sagittis nec tortor et, lobortis semper est. Integer semper, neque id interdum aliquam,
                    magna sapien dignissim ipsum, vel gravida mi enim et libero. Nam sit amet turpis ut velit
                    aliquam feugiat.
                </p>
            </div>

        </div>
        <!-- .row -->

@endsection
