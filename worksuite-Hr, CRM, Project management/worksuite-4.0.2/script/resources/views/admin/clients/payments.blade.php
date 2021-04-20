@extends('layouts.app')

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
                <li><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li><a href="{{ route('admin.clients.index') }}">@lang($pageTitle)</a></li>
                <li class="active">@lang('app.menu.invoices')</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection


@section('content')

    <div class="row">


        @include('admin.clients.client_header')


        <div class="col-md-12">

            <section>
                <div class="sttabs tabs-style-line">
                    @include('admin.clients.tabs')

                    <div class="content-wrap">
                        <section id="section-line-1" class="show">
                            <div class="row">


                                <div class="col-md-12" >
                                    <div class="white-box">

                                        <ul class="list-group" id="invoices-list">
                                            <li class="list-group-item">
                                                <div class="row font-semi-bold">
                                                    <div class="col-md-3">
                                                        {{ __('app.project') }}
                                                    </div>
                                                    <div class="col-md-3">
                                                        {{ __('app.invoice') . '#' }}
                                                    </div>
                                                    <div class="col-md-3">
                                                        {{ __('modules.invoices.amount') }}
                                                    </div>
                                                    <div class="col-md-3">
                                                        {{ __('modules.payments.paidOn') }}
                                                    </div>
                                                </div>
                                            </li>
                                            @forelse($payments as $payment)
                                                <li class="list-group-item">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            @if (!is_null($payment->project))
                                                                <a href="{{ route('admin.projects.show', $payment->project_id) }}">{{ ucfirst($payment->project->project_name) }}</a>
                                                            @else
                                                                --
                                                            @endif
                                                        </div>
                                                        <div class="col-md-3">
                                                            @if (!is_null($payment->invoice))
                                                                <a href="{{ route('admin.all-invoices.show', $payment->invoice_id) }}">{{ ucfirst($payment->invoice->invoice_number) }}</a>
                                                            @else
                                                                --
                                                            @endif
                                                        </div>
                                                        <div class="col-md-3">
                                                            {{ $payment->amount .' ('.$payment->currency->currency_code . ')' }}
                                                            
                                                        </div>
                                                        
                                                        <div class="col-md-3">
                                                            {{ $payment->paid_on->format($global->date_format . ' ' . $global->time_format) }}
                                                        </div>
                                                        
                                                    </div>
                                                </li>
                                            @empty
                                                <li class="list-group-item">
                                                    <div class="row">
                                                        <div class="col-md-12 text-center">
                                                            <div class="empty-space" style="height: 200px;">
                                                                        <div class="empty-space-inner">
                                                                            <div class="icon" style="font-size:30px"><i
                                                                                        class="icon-doc"></i>
                                                                            </div>
                                                                            <div class="title m-b-15">@lang('messages.noPaymentFound')
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                    </div>
                                                </li>
                                            @endforelse
                                        </ul>
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
        $('ul.showClientTabs .clientPayments').addClass('tab-current');
    </script>
@endpush
