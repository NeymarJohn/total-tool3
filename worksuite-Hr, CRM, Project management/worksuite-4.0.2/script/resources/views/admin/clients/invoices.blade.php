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
                                            @forelse($invoices as $invoice)
                                                <li class="list-group-item">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <a href="{{ route('admin.all-invoices.show', $invoice->id) }}">{{ $invoice->invoice_number }}</a>
                                                            
                                                        </div>
                                                        <div class="col-md-3 text-center">
                                                            {{ $invoice->currency_symbol }} {{ $invoice->total }}
                                                        </div>
                                                        <div class="col-md-2 text-center">
                                                            {{ $invoice->issue_date->format($global->date_format) }}
                                                        </div>
                                                        <div class="col-md-2 text-center">
                                                            
                                                            @if ($invoice->credit_note)
                                                                <label class="label label-warning">{{ strtoupper(__('app.credit-note')) }}</label>
                                                            @else
                                                                @if ($invoice->status == 'unpaid')
                                                                    <label class="label label-danger">{{ strtoupper($invoice->status) }}</label>
                                                                @elseif ($invoice->status == 'paid')
                                                                    <label class="label label-success">{{ strtoupper($invoice->status) }}</label>
                                                                @elseif ($invoice->status == 'canceled') 
                                                                    <label class="label label-danger">{{ strtoupper(__('app.canceled')) }}</label>
                                                                @else
                                                                    <label class="label label-info">{{ strtoupper(__('modules.invoices.partial')) }}</label>
                                                                @endif
                                                            @endif
                                                            
                                                        </div>
                                                        <div class="col-md-2 text-right">
                                                            <a href="{{ route('admin.invoices.download', $invoice->id) }}" data-toggle="tooltip" data-original-title="Download" class="btn btn-inverse btn-circle"><i class="fa fa-download"></i></a>
                                                        </div>
                                                    </div>
                                                </li>
                                                @if($loop->last)
                                                    <li class="list-group-item">
                                                        {{--<div class="row">--}}
                                                            {{--<div class="col-md-3">--}}
                                                                {{--<span class="pull-right">@lang('modules.invoices.totalUnpaidInvoice')</span>--}}
                                                            {{--</div>--}}
                                                            {{--<div class="col-md-2 text-danger text-center">--}}
                                                                {{--{{ $invoice->currency_symbol }} {{ ($invoices->sum('total')-$invoices->sum('paid_payment')) }}--}}
                                                            {{--</div>--}}
                                                            {{--<div class="col-md-3">--}}

                                                            {{--</div>--}}
                                                        {{--</div>--}}
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <span class="pull-right">@lang('modules.invoices.totalUnpaidInvoice')</span>

                                                            </div>
                                                            <div class="col-md-3 text-center text-danger">
                                                                {{ $invoice->currency_symbol }} {{ ($invoices->sum('total')-$invoices->sum('paid_payment')) }}
                                                            </div>
                                                            <div class="col-md-2 text-center">
                                                            </div>
                                                            <div class="col-md-2 text-center">

                                                            </div>
                                                            <div class="col-md-2 text-right">
                                                            </div>
                                                        </div>
                                                    </li>
                                                @endif
                                            @empty
                                                <li class="list-group-item">
                                                    <div class="row">
                                                        <div class="col-md-12 text-center">
                                                            <div class="empty-space" style="height: 200px;">
                                                                        <div class="empty-space-inner">
                                                                            <div class="icon" style="font-size:30px"><i
                                                                                        class="icon-doc"></i>
                                                                            </div>
                                                                            <div class="title m-b-15">@lang('messages.noInvoiceFound')
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
        $('ul.showClientTabs .clientInvoices').addClass('tab-current');
    </script>
@endpush
