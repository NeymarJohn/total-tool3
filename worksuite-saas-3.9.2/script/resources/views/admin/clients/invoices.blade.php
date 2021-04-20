@extends('layouts.app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li><a href="{{ route('admin.clients.index') }}">{{ __($pageTitle) }}</a></li>
                <li class="active">@lang('app.menu.invoices')</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection


@section('content')

    <div class="row">


        @include('admin.clients.client_header')


        <div class="col-xs-12">

            <section>
                <div class="sttabs tabs-style-line">
                    @include('admin.clients.tabs')

                    <div class="content-wrap">
                        <section id="section-line-1" class="show">
                            <div class="row">


                                <div class="col-xs-12" >
                                    <div class="white-box">
                                        <h2>@lang('app.menu.invoices')</h2>

                                        <ul class="list-group" id="invoices-list">
                                            @forelse($invoices as $invoice)
                                                <li class="list-group-item">
                                                    <div class="row">
                                                        <div class="col-md-7">
                                                            @lang('app.invoice') # {{ $invoice->invoice_number }}
                                                        </div>
                                                        <div class="col-md-2">
                                                            {{ $invoice->currency_symbol }} {{ $invoice->total }}
                                                        </div>
                                                        <div class="col-md-3">
                                                            <a href="{{ route('admin.invoices.download', $invoice->id) }}" data-toggle="tooltip" data-original-title="Download" class="btn btn-default btn-circle"><i class="fa fa-download"></i></a>
                                                            <span class="m-l-10">{{ $invoice->issue_date->format('d M, y') }}</span>
                                                        </div>
                                                    </div>
                                                </li>
                                                @if($loop->last)
                                                    <li class="list-group-item">
                                                        <div class="row">
                                                            <div class="col-md-7 ">
                                                                <span class="pull-right">@lang('modules.invoices.totalUnpaidInvoice')</span>
                                                            </div>
                                                            <div class="col-md-2 text-danger">
                                                                {{ $invoice->currency_symbol }} {{ ($invoices->sum('total')-$invoices->sum('paid_payment')) }}
                                                            </div>
                                                            <div class="col-md-3">

                                                            </div>
                                                        </div>
                                                    </li>
                                                @endif
                                            @empty
                                                <li class="list-group-item">
                                                    <div class="row">
                                                        <div class="col-md-7">
                                                            @lang('modules.invoices.noInvoiceForClient')
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
        $('ul.showClientTabs .clientInvoices').addClass('tab-current');
    </script>
@endpush