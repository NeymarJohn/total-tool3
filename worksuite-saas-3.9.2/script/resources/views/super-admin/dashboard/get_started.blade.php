<div class="col-sm-12">
    <div class="panel panel-inverse">
        <div class="panel-heading">

            {{__('messages.installationWelcome')}}
            <div class="row">
                <div class="col-md-12 col-sm-10">
                    <div class="progress progress-striped m-t-20 progress-lg">
                        <div role="progressbar" aria-valuenow="{{$progressPercent}}" aria-valuemin="0"
                             aria-valuemax="100"
                             class="progress-bar progress-bar-success progress-bar-striped"
                             style="width: {{$progressPercent}}%;"></div>


                    </div>

                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-sm-12 c-white m-t-10"><strong>{{__('messages.installationProgress')}}: </strong> {{$progressPercent}}%
                </div>
            </div>
        </div>

        <div class="panel-body">
            <div class="list-group lg-alt">
                <div class="list-group-item media checked">
                    <div class="pull-left">
                        <div class="col-xs-3">
                            <div>
                                <span class="bg-success"><i class="icon-check"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="media-body">
                        <div class="widget-title"><a href="#">{{__('messages.installationStep1')}}</a></div>
                        <h6 class="lgi-text">{{__('messages.installationCongratulation')}}</h6></div>
                </div>
            </div>
            <div class="list-group lg-alt">
                <div class="list-group-item media">
                    <div class="pull-left">
                        <div class="col-xs-3">
                            <div>
                                @if(isset($progress['smtp_setting_completed']))
                                    <span class="bg-success"><i class="icon-check"></i></span>
                                @else
                                    <span class="bg-danger"><i class="icon-close"></i></span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="media-body">
                        <div class="widget-title"><a href="{{ route('super-admin.email-settings.index') }}" class="">{{__('messages.installationStep2')}}</a>
                        </div>
                        <h6 class="lgi-text">{{__('messages.installationSmtp')}}</h6>
                    </div>
                </div>
            </div>
            <div class="list-group lg-alt">
                <div class="list-group-item media">
                    <div class="pull-left">
                        <div class="col-xs-3">
                            <div>
                                @if(isset($progress['company_setting_completed']))
                                    <span class="bg-success"><i class="icon-check"></i></span>
                                @else
                                    <span class="bg-danger"><i class="icon-close"></i></span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="media-body">
                        <div class="widget-title"><a href="{{ route('super-admin.settings.index') }}">{{__('messages.installationStep3')}}</a></div>
                        <h6 class="lgi-text">{{__('messages.installationCompanySetting')}}</h6>
                    </div>
                </div>
            </div>

            <div class="list-group lg-alt">
                <div class="list-group-item media">
                    <div class="pull-left">
                        <div class="col-xs-3">
                            <div>
                                @if(isset($progress['profile_setting_completed']))
                                    <span class="bg-success"><i class="icon-check"></i></span>
                                @else
                                    <span class="bg-danger"><i class="icon-close"></i></span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="media-body">
                        <div class="widget-title"><a href="{{ route('super-admin.profile.index') }}" class="">{{__('messages.installationStep4')}}</a>
                        </div>
                        <h6 class="lgi-text">{{__('messages.installationProfileSetting')}}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>