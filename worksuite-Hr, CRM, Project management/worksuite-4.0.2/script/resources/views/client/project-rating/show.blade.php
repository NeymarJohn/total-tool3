@extends('layouts.client-app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-6 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> @lang($pageTitle) #{{ $project->id }} - <span class="font-bold">{{ ucwords($project->project_name) }}</span></h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-6 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('client.dashboard.index') }}">@lang('app.menu.home')</a></li>
                <li><a href="{{ route('client.projects.index') }}">@lang($pageTitle)</a></li>
                <li class="active">@lang('modules.projects.expenses')</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>

@endsection

@push('head-script')
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
    .d-none {
        display: none;
    }
</style>
@endpush

@section('content')
    <div class="row">
        <div class="col-xs-12">

            <section>
                <div class="sttabs tabs-style-line">

                    @include('client.projects.show_project_menu')

                    <div class="content-wrap">
                        <section id="section-line-3" class="show">
                            <div class="row">
                                <div class="col-xs-12" id="invoices-list-panel">
                                    <div class="white-box">
                                        <div class="panel-body b-b">
                                            <h4>@lang('app.menu.projectRating')</h4>
                                            <div class="row m-t-20 @if(!is_null($project->rating))d-none @endif"  id="ratingInput">
                                                <div class="col-xs-12">
                                                    <div class='rating-stars'>
                                                        <ul id='stars'>
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
                                                </div>
                                                <div class="col-xs-6">
                                                    <div class="form-group">
                                                        <label class="control-label">@lang('app.comment')</label>
                                                        <textarea class="form-control" cols="6" rows="6" id="comment" name="comment">@if(!is_null($project->rating)) {{ $project->rating->comment }} @endif</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12">
                                                    <div class="form-actions">
                                                        <button type="submit" id="post-rating" class="btn btn-success"><i class="fa fa-check"></i>
                                                            @if(!is_null($project->rating)) @lang('app.update') @else @lang('app.save') @endif
                                                        </button>
                                                    </div>
                                                </div>
                                           </div>
                                            <div class="row m-t-20 @if(is_null($project->rating))d-none @endif" id="ratingView">
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
                                                            <input type="hidden" id="ratingID" @if(!is_null($project->rating)) value="{{ $project->rating->id }}" @endif >
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-2 ">
                                                        <div class="pull-right">
                                                            <a href="javascript:;"  @if(!is_null($project->rating)) onclick="editComment({{ $project->rating->id }});" @endif
                                                               class="btn btn-sm btn-info btn-rounded btn-outline edit-type"><i
                                                                        class="fa fa-edit"></i> @lang('app.edit')</a>
                                                            <a href="javascript:;"   @if(!is_null($project->rating)) onclick="deleteComment({{ $project->rating->id }});" @endif
                                                               class="btn btn-sm btn-danger btn-rounded btn-outline delete-type"><i
                                                                        class="fa fa-times"></i> @lang('app.remove')</a>
                                                        </div>

                                                     </div>
                                                <!--/row-->
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
<script>
    ratingValue = 0;
            @if(!is_null($project->rating)) ratingValue = {{ $project->rating->rating }} @endif


$(document).ready(function(){
        /* 1. Visualizing things on Hover - See next part for action on click */
        $('#stars li').on('mouseover', function(){
            var onStar = parseInt($(this).data('value'), 10); // The star currently mouse on

            // Now highlight all the stars that's not after the current hovered star
            $(this).parent().children('li.star').each(function(e){
                if (e < onStar) {
                    $(this).addClass('hover');
                }
                else {
                    $(this).removeClass('hover');
                }
            });

        }).on('mouseout', function(){
            $(this).parent().children('li.star').each(function(e){
                $(this).removeClass('hover');
            });
        });


        /* 2. Action to perform on click */
        $('#stars li').on('click', function(){
            var onStar = parseInt($(this).data('value'), 10); // The star currently selected
            var stars = $(this).parent().children('li.star');

            for (i = 0; i < stars.length; i++) {
                $(stars[i]).removeClass('selected');
            }

            for (i = 0; i < onStar; i++) {
                $(stars[i]).addClass('selected');
            }

            // JUST RESPONSE (Not needed)
             ratingValue = parseInt($('#stars li.selected').last().data('value'), 10);
            var msg = "";
            if (ratingValue > 1) {
                msg = "Thanks! You rated this " + ratingValue + " stars.";
            }
            else {
                msg = "We will improve ourselves. You rated this " + ratingValue + " stars.";
            }
            responseMessage(msg);

        });
    });

    $('#post-rating').click(function () {
        var token = '{{ csrf_token() }}';
        var url =  '{{route('client.project-ratings.store')}}';
        var method = 'POST';
        var ratingID = $('#ratingID').val();

        if(ratingID)
        {
            url = "{{ route('client.project-ratings.update',':id') }}";
            url = url.replace(':id', ratingID);
            method = 'PUT';
        }
        if(ratingValue !== 0){
            $.easyAjax({
                url: url,
                container: '#invoices-list-panel',
                type: "POST",
                data: {'rating': ratingValue,'project_id': {{ $project->id }}, 'comment': $('#comment').val(), '_token': token, '_method': method},
                success: function (response) {
                    $('#ratingView').show();
                    $('#ratingInput').hide();
                    $('#ratingView').html(response.view);
                }
            })
        }
    });

    function editComment() {
        $('#ratingView').hide();
        $('#ratingInput').show();

    }
        function deleteComment() {
            var ratingID = $('#ratingID').val();
            if(ratingID)
            {
                url = "{{ route('client.project-ratings.destroy',':id') }}";
                url = url.replace(':id', ratingID);
                var token = '{{ csrf_token() }}';
                $.easyAjax({
                    url: url,
                    container: '#invoices-list-panel',
                    type: "POST",
                    data: {'_token': token, '_method': 'DELETE'},
                    success: function (response) {
                        $('#ratingView').hide();
                        $('#ratingInput').show();
                        $('#comment').val('');
                        $('#ratingID').val('');
                        var stars = $('#stars li').parent().children('li.star');

                        for (i = 0; i < stars.length; i++) {
                            $(stars[i]).removeClass('selected');
                        }
                    }
                });
            }
        }


    function responseMessage(msg) {
        $('.success-box').fadeIn(200);
        $('.success-box div.text-message').html("<span>" + msg + "</span>");
    }

    $('ul.showProjectTabs .projectRatings').addClass('tab-current');
</script>
@endpush