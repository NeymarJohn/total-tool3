@extends('layouts.public-gdpr')

@section('content')
    <div class="col-md-offset-1 col-md-10 col-md-offset-1">

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
                                        @if($allConsent->lead)
                                            <small>Last Updated: {{ $allConsent->lead->created_at }}</small>
                                        @endif
                                        @if($allConsent->lead && $allConsent->lead->status == 'agree')
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
                                @if($allConsent->lead)
                                    <div class="form-group">
                                        <label class="radio-inline">
                                            @if($allConsent->lead->status == 'agree')
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
    </div>
@endsection

@push('footer-script')
<script>
    $('#save-form').click(function () {
        $.easyAjax({
            url: '{{route('front.gdpr.consent.update', [md5($lead->id)])}}',
            container: '#updateConsent',
            type: "POST",
            data: $('#updateConsent').serialize(),
            redirect: true
        })
    });
</script>
@endpush