@extends('layouts.app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> @lang($pageTitle)</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12 text-right">
            <a class="btn btn-default btn-outline btn-sm"
               href="{{ route('admin.proposals.download', $proposal->id) }}"> <span><i class="fa fa-file-pdf-o"></i> @lang('modules.invoices.downloadPdf')</span> </a>
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">@lang("app.menu.home")</a></li>
                <li><a href="{{ route('admin.proposals.index') }}">@lang("app.menu.invoices")</a></li>
                <li class="active">@lang('app.menu.proposal')</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')

<style>
    .ribbon-wrapper {
        background: #ffffff !important;
    }
</style>
@endpush

@section('content')


    <div class="row">

        <div class="col-md-12">
            <div class="white-box printableArea ribbon-wrapper">

                <div class="clearfix"></div>
                <div class="ribbon-content m-t-40 b-all p-20">

                    @if($proposal->status == 'accept')
                        <div class="ribbon ribbon-bookmark ribbon-success">@lang('app.accepted')</div>
                    @elseif($proposal->status == 'waiting')
                        <div class="ribbon ribbon-bookmark ribbon-warning">@lang('app.pending')</div>
                    @else
                        <div class="ribbon ribbon-bookmark ribbon-danger">@lang('app.rejected')</div>
                    @endif

                    <h4 class="text-right"><b>@lang('app.menu.proposal')</b> </h4>
                    <hr>

                    <div class="row">
                        <div class="row">
                            <div class="col-xs-6 b-r">
                                <strong class="clearfix">@lang('app.lead')</strong> <br>
                                <span class="text-muted">{{ $proposal->lead->client_name }} </span>
                            </div>
                            <div class="col-xs-6">
                                <strong class="clearfix">@lang('modules.proposal.validTill')</strong> <br>
                                <p class="text-muted">{{ $proposal->valid_till->format($global->date_format) }}</p>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive m-t-40" style="clear: both;">
                                <table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>@lang("modules.invoices.item")</th>
                                        @if($invoiceSetting->hsn_sac_code_show)
                                            <th >@lang('modules.invoices.hsnSacCode')</th>
                                        @endif
                                        <th class="text-right">@lang("modules.invoices.qty")</th>
                                        <th class="text-right">@lang("modules.invoices.unitPrice")</th>
                                        <th class="text-right">@lang("modules.invoices.price")</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $count = 0; ?>
                                    @foreach($proposal->items as $item)
                                        @if($item->type == 'item')
                                            <tr>
                                                <td class="text-center">{{ ++$count }}</td>
                                                <td>{{ ucfirst($item->item_name) }}
                                                    @if(!is_null($item->item_summary))
                                                        <p class="font-12">{{ $item->item_summary }}</p>
                                                    @endif
                                                </td>
                                                @if($invoiceSetting->hsn_sac_code_show)
                                                    <td>{{ ($item->hsn_sac_code) ?? '--' }}</td>
                                                @endif
                                                <td class="text-right">{{ $item->quantity }}</td>
                                                <td class="text-right"> {!! htmlentities($proposal->currency->currency_symbol)  !!}{{ $item->unit_price }} </td>
                                                <td class="text-right"> {!! htmlentities($proposal->currency->currency_symbol)  !!}{{ $item->amount }} </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="pull-right m-t-30 text-right">
                                <p>@lang("modules.invoices.subTotal")
                                    : {!! htmlentities($proposal->currency->currency_symbol)  !!}{{ $proposal->sub_total }}</p>

                                @if ($discount > 0)
                                    <p>@lang("modules.invoices.discount")
                                    : {!! htmlentities($proposal->currency->currency_symbol)  !!}{{ $discount }} </p>
                                @endif
                                @foreach($taxes as $key=>$tax)
                                    <p>{{ strtoupper($key) }}
                                        : {!! htmlentities($proposal->currency->currency_symbol)  !!}{{ $tax }} </p>
                                @endforeach
                                <hr>
                                <h3><b>@lang("modules.invoices.total")
                                        :</b> {!! htmlentities($proposal->currency->currency_symbol)  !!}{{ $proposal->total }}
                                </h3>
                                <hr>
                            </div>

                            @if($proposal->signature)
                                <div style="text-align: right;">
                                    <h2 class="name" style="margin-bottom: 20px;">@lang('modules.estimates.signature')</h2>
                                    <img src="{{ $proposal->signature->signature }}" style="width:250px">

                                    <p>{{ ucwords($proposal->signature->full_name) }}</p>
                                </div>
                            @endif
                            @if($proposal->client_comment)
                                <div>
                                    <h5 class="name" style="margin-bottom: 20px;">@lang('app.comment')</h5>
                                    <p> {{ $proposal->client_comment }} </p>
                                </div>
                            @endif

                            @if(!is_null($proposal->note))
                                <div class="col-md-12">
                                    <p><strong>@lang('app.note')</strong>: {{ $proposal->note }}</p>
                                </div>
                            @endif
                            <div class="clearfix"></div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
