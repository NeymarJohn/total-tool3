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

    <div class="panel panel-inverse">
        <div class="panel-heading"> @lang('modules.gdpr.consent')</div>
        <div class="panel-wrapper collapse in" aria-expanded="true">
            <div class="panel-body">
                {!! Form::open(['id'=>'updateConsent','class'=>'ajax-form','method'=>'POST']) !!}
                <div class="form-body">
                    <p>{!! $gdprSetting->consent_block !!}</p>
                    <hr>

                    @forelse($allConsents as $allConsent)
                        <div class="row">
                            <div class="col-md-12 m-b-10">
                                <p>
                                    <label style="font-size: 24px">{{ $allConsent->name }}</label>
                                    @if($allConsent->user)
                                        <small>Last Updated: {{ $allConsent->user->created_at }}</small>
                                    @endif
                                    @if($allConsent->user && $allConsent->user->status == 'agree')
                                        <span class="pull-right">
                                                <i class="fa fa-check fa-2x text-success"></i>
                                            </span>
                                    @else
                                        <span class="pull-right">
                                                <i class="fa fa-remove fa-2x text-danger"></i>
                                            </span>
                                    @endif
                                </p>
                                <p>{{ $allConsent->description }}</p>
                            </div>
                            <div class="col-md-12">
                                @if($allConsent->user)
                                    <div class="form-group">
                                        <label class="radio-inline">
                                            @if($allConsent->user->status == 'agree')
                                                <input type="radio" class="checkbox" value="disagree" name="consent_customer[{{$allConsent->id}}]">I disagree
                                            @else
                                                <input type="radio" class="checkbox" value="agree" name="consent_customer[{{$allConsent->id}}]">I agree
                                            @endif
                                        </label>
                                    </div>
                                @else
                                    <div class="form-group">
                                        <label class="radio-inline">
                                            <input type="radio"
                                                   class="checkbox"
                                                   value="agree" name="consent_customer[{{$allConsent->id}}]">I agree
                                        </label>
                                        <label class="radio-inline m-l-10">
                                            <input type="radio"
                                                   value="disagree" name="consent_customer[{{$allConsent->id}}]">I disagree
                                        </label>
                                    </div>
                                @endif
                                <hr>
                            </div>
                        </div>
                    @empty
                        <p class="text-center">
                            <strong>No Consent available for this lead.</strong>
                        </p>
                    @endforelse

                </div>
                @if($allConsents->count() > 0)
                    <div class="form-actions">
                        <button type="submit" id="save-form" class="btn btn-success"> <i class="fa fa-check"></i> @lang('app.update')</button>
                    </div>
                @endif
                {!! Form::close() !!}
            </div>
        </div>
    </div>

@endsection

@push('footer-script')
<script>
    $('#save-form').click(function () {
        $.easyAjax({
            url: '{{route('client.gdpr.update-consent')}}',
            container: '#updateConsent',
            type: "POST",
            data: $('#updateConsent').serialize(),
            redirect: true
        })
    });
</script>
@endpush