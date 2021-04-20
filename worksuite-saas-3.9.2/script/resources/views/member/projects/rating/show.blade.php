@extends('layouts.member-app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-6 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }} #{{ $project->id }} - <span
                        class="font-bold">{{ ucwords($project->project_name) }}</span></h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-6 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('member.dashboard') }}">@lang('app.menu.home')</a></li>
                <li><a href="{{ route('member.projects.index') }}">{{ __($pageTitle) }}</a></li>
                <li class="active">@lang('modules.projects.files')</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
<link rel="stylesheet" href="{{ asset('plugins/bower_components/dropzone-master/dist/dropzone.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/switchery/dist/switchery.min.css') }}">

<style>
    /* Rating Star Widgets Style */
    .rating-stars ul {
        list-style-type:none;
        padding:0;

        -moz-user-select:none;
        -webkit-user-select:none;
    }
    .rating-stars ul > li.star {
        display:inline-block;
        margin:1px;

    }

    /* Idle State of the stars */
    .rating-stars ul > li.star > i.fa {
        font-size:1.6em; /* Change the size of the stars */
        color:#ccc; /* Color on idle state */
    }

    /* Hover state of the stars */
    .rating-stars ul > li.star.hover > i.fa {
        color:#FFCC36;
    }

    /* Selected state of the stars */
    .rating-stars ul > li.star.selected > i.fa {
        color:#FF912C;
    }
    .m-switch {
        margin: 10px 0px;
    }
</style>
@endpush

@section('content')

    <div class="row">
        <div class="col-xs-12">

            <section>
                <div class="sttabs tabs-style-line">

                    @include('member.projects.show_project_menu')

                    <div class="content-wrap">
                        <section id="section-line-3" class="show">
                            <div class="row">
                                <div class="col-xs-12" id="invoices-list-panel">
                                    <div class="white-box">


                                        <div class="panel-body b-b">
                                            <div class="row">
                                                <div class="col-md-7">
                                                    <h4>@lang('app.menu.projectRating')</h4>
                                                </div>
                                            </div>
                                                <div class="row m-t-20">
                                                    @if(!is_null($project->rating))
                                                        <div class="col-xs-10">
                                                            <div class='rating-stars '>
                                                                <ul >
                                                                    <li class='star @if(!is_null($project->rating) && $project->rating->rating >= 1) selected @endif' title='Poor' data-value='1' >
                                                                        <i class='fa fa-star fa-fw'></i>
                                                                    </li>
                                                                    <li class='star @if(!is_null($project->rating) && $project->rating->rating >= 2) selected @endif' title='Fair' data-value='2'>
                                                                        <i class='fa fa-star fa-fw'></i>
                                                                    </li>
                                                                    <li class='star @if(!is_null($project->rating) && $project->rating->rating >= 3) selected @endif' title='Good' data-value='3'>
                                                                        <i class='fa fa-star fa-fw'></i>
                                                                    </li>
                                                                    <li class='star @if(!is_null($project->rating) && $project->rating->rating >= 4) selected @endif' title='Excellent' data-value='4'>
                                                                        <i class='fa fa-star fa-fw'></i>
                                                                    </li>
                                                                    <li class='star @if(!is_null($project->rating) && $project->rating->rating >= 5) selected @endif' title='WOW!!!' data-value='5'>
                                                                        <i class='fa fa-star fa-fw'></i>
                                                                    </li>
                                                                </ul>
                                                            </div>

                                                            <div class="font-light">
                                                                @if(!is_null($project->rating))
                                                                    {!! ucfirst(nl2br($project->rating->comment)) !!}
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-2 ">

                                                        </div>
                                                    @else
                                                        <div class="col-xs-10" >
                                                            <div class="font-light">
                                                               @lang('messages.noRatingFound')
                                                            </div>
                                                        </div>
                                                    @endif
                                            </div>

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </section>

                    </div><!-- /content -->
                </div><!-- /tabs -->
            </section>
        </div>


    </div>
    <!-- .row -->

@endsection

@push('footer-script')
<script src="{{ asset('plugins/bower_components/switchery/dist/switchery.min.js') }}"></script>
<script>
    // Switchery
    var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
    var switchData = [];
    $('.js-switch').each(function () {
        new Switchery($(this)[0], $(this).data());

    });
    $('ul.showProjectTabs .projectRatings').addClass('tab-current');

</script>
@endpush
