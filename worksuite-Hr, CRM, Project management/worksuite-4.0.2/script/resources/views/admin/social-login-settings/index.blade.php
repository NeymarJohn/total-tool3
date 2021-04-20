@extends('layouts.app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-6 col-md-6 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li class="active">{{ __($pageTitle) }}</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
<link rel="stylesheet" href="{{ asset('plugins/bower_components/switchery/dist/switchery.min.css') }}">
@endpush

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-heading">{{ __($pageTitle) }}</div>

                <div class="vtabs customvtab m-t-10">

                    @include('sections.admin_setting_menu')

                    <div class="tab-content">
                        <div id="vhome3" class="tab-pane active">
                            <div class="row">
                                <div class="col-md-12">

{{--                                    <h3 class="box-title m-b-0">@lang('app.menu.onlinePayment')</h3>--}}

                                    <div class="row">
                                        <div class="col-sm-12 col-xs-12 ">
                                            {!! Form::open(['id'=>'updateSettings','class'=>'ajax-form','method'=>'PUT']) !!}
                                            <div class="form-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <h3 class="box-title text-success">@lang('app.socialAuthSettings.google')</h3>
                                                        <hr class="m-t-0 m-b-20">
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>@lang('app.socialAuthSettings.googleClientId')</label>
                                                            <input type="text" name="google_client_id" id="google_client_id"
                                                                   class="form-control" value="{{ $credentials->google_client_id }}">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>@lang('app.socialAuthSettings.googleSecret')</label>
                                                            <input type="password" name="google_secret_id"
                                                                   id="google_secret_id"
                                                                   class="form-control"
                                                                   value="{{ $credentials->google_secret_id }}">
                                                            <span class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                                        </div>
                                                    </div>

                                                    <!--/span-->

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label" >@lang('app.status')</label>
                                                            <div class="switchery-demo">
                                                                <input
                                                                        type="checkbox"
                                                                        data-type-name="google"
                                                                        name="google_status"
                                                                        @if($credentials->google_status == 'enable') checked @endif
                                                                        class="js-switch special" id="googleButton"
                                                                        data-color="#00c292"
                                                                        data-secondary-color="#f96262"
                                                                />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="mail_from_name">@lang('app.callback')</label>
                                                            <p class="text-bold">{{ route('social.login-callback', 'google') }}</p>
                                                            <p class="text-info">(@lang('messages.addGoogleCallback'))</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 m-t-20">
                                                        <h3 class="box-title text-warning">@lang('app.socialAuthSettings.facebook')</h3>
                                                        <hr class="m-t-0 m-b-20">
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>@lang('app.socialAuthSettings.facebookClientId')</label>
                                                            <input type="text" name="facebook_client_id" id="facebook_client_id"
                                                                   class="form-control" value="{{ $credentials->facebook_client_id }}">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>@lang('app.socialAuthSettings.facebookSecret')</label>
                                                            <input type="password" name="facebook_secret_id"
                                                                   id="facebook_secret_id"
                                                                   class="form-control"
                                                                   value="{{ $credentials->facebook_secret_id }}">
                                                            <span class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                                        </div>
                                                    </div>

                                                    <!--/span-->

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label" >@lang('app.status')</label>
                                                            <div class="switchery-demo">
                                                                <input
                                                                        type="checkbox"
                                                                        data-type-name="facebook"
                                                                        name="facebook_status"
                                                                        @if($credentials->facebook_status == 'enable') checked @endif
                                                                        class="js-switch special" id="facebookButton"
                                                                        data-color="#00c292"
                                                                        data-secondary-color="#f96262"
                                                                />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="mail_from_name">@lang('app.callback')</label>
                                                            <p class="text-bold">{{ route('social.login-callback', 'facebook') }}</p>
                                                            <p class="text-info">(@lang('messages.addFacebookCallback'))</p>
                                                        </div>
                                                    </div>
                                                    <!--/span-->

                                                    <div class="col-md-12 m-t-20">
                                                        <h3 class="box-title text-info">@lang('app.socialAuthSettings.linkedin')</h3>
                                                        <hr class="m-t-0 m-b-20">
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>@lang('app.socialAuthSettings.linkedinClientId')</label>
                                                            <input type="text" name="linkedin_client_id" id="linkedin_client_id"
                                                                   class="form-control" value="{{ $credentials->linkedin_client_id }}">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>@lang('app.socialAuthSettings.linkedinSecret')</label>
                                                            <input type="password" name="linkedin_secret_id"
                                                                   id="linkedin_secret_id"
                                                                   class="form-control"
                                                                   value="{{ $credentials->linkedin_secret_id }}">
                                                            <span class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                                        </div>
                                                    </div>

                                                    <!--/span-->

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label" >@lang('app.status')</label>
                                                            <div class="switchery-demo">
                                                                <input
                                                                        type="checkbox"
                                                                        data-type-name="linkedin"
                                                                        name="linkedin_status"
                                                                        @if($credentials->linkedin_status == 'enable') checked @endif
                                                                        class="js-switch special" id="linkedinButton"
                                                                        data-color="#00c292"
                                                                        data-secondary-color="#f96262"
                                                                />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="mail_from_name">@lang('app.callback')</label>
                                                            <p class="text-bold">{{ route('social.login-callback', 'linkedin') }}</p>
                                                            <p class="text-info">(@lang('messages.addLinkedinCallback'))</p>
                                                        </div>
                                                    </div>
                                                    <!--/span-->

                                                    <div class="col-md-12 m-t-20">
                                                        <h3 class="box-title text-success">@lang('app.socialAuthSettings.twitter')</h3>
                                                        <hr class="m-t-0 m-b-20">
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>@lang('app.socialAuthSettings.twitterClientId')</label>
                                                            <input type="text" name="twitter_client_id" id="twitter_client_id"
                                                                   class="form-control" value="{{ $credentials->twitter_client_id }}">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>@lang('app.socialAuthSettings.twitterSecret')</label>
                                                            <input type="password" name="twitter_secret_id"
                                                                   id="twitter_secret_id"
                                                                   class="form-control"
                                                                   value="{{ $credentials->twitter_secret_id }}">
                                                            <span class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                                        </div>
                                                    </div>

                                                    <!--/span-->

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label" >@lang('app.status')</label>
                                                            <div class="switchery-demo">
                                                                <input
                                                                        type="checkbox"
                                                                        data-type-name="twitter"
                                                                        name="twitter_status"
                                                                        @if($credentials->twitter_status == 'enable') checked @endif
                                                                        class="js-switch special" id="twitterButton"
                                                                        data-color="#00c292"
                                                                        data-secondary-color="#f96262"
                                                                />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="mail_from_name">@lang('app.callback')</label>
                                                            <p class="text-bold">{{ route('social.login-callback', 'twitter') }}</p>
                                                            <p class="text-info">(@lang('messages.addTwitterCallback'))</p>
                                                        </div>
                                                    </div>
                                                    <!--/span-->

                                                </div>

                                                <!--/row-->

                                            </div>
                                            <div class="form-actions m-t-20">
                                                <button type="submit" id="save-form" class="btn btn-success"><i class="fa fa-check"></i>
                                                    @lang('app.save')
                                                </button>

                                            </div>
                                            {!! Form::close() !!}
                                        </div>
                                    </div>

                                </div>
                                <!-- .row -->

                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>


        </div>
        <!-- .row -->


        @endsection

        @push('footer-script')
        <script src="{{ asset('plugins/bower_components/switchery/dist/switchery.min.js') }}"></script>
        <script>

            $('.js-switch').each(function() {
                new Switchery($(this)[0], $(this).data());
            });

            $('#save-form').click(function () {
                var url = '{{route('admin.social-auth-settings.update', $credentials->id)}}';
                $('#method').val('PUT');
                $.easyAjax({
                    url: url,
                    type: "POST",
                    container: '#updateSettings',
                    data: $('#updateSettings').serialize()
                })
            });

        </script>
    @endpush
