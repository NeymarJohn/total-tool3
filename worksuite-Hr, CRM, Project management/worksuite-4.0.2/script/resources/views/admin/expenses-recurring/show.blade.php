@extends('layouts.app')
@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-8 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> @lang($pageTitle) - {{ ucwords($expense->item_name) }}</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-4 col-sm-8 col-md-8 col-xs-12 text-right">

            <a href="{{ route('admin.expenses-recurring.edit',$expense->id) }}"
               class="btn btn-outline btn-info btn-sm">@lang('app.edit')
                <i class="fa fa-edit" aria-hidden="true"></i></a>

            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li><a href="{{ route('admin.expenses-recurring.index') }}">@lang($pageTitle)</a></li>
                <li class="active">@lang('app.details')</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection
@push('head-script')

<link rel="stylesheet" href="{{ asset('plugins/bower_components/dropzone-master/dist/dropzone.css') }}">
@endpush

@section('content')

    <div class="row">
        <div class="col-md-12">

            <section>
                <div class="sttabs tabs-style-line">
                    <div class="white-box">
                        <div class="row">
                            <div class="col-md-12 p-r-0">
                                <nav>
                                    <ul >
                                        <li class="tab-current"><a href="{{ route('admin.expenses-recurring.show', $expense->id) }}"><span>@lang('app.menu.expensesRecurring') @lang('app.info')</span></a>
                                        </li>
                                        <li><a href="{{ route('admin.expenses-recurring.recurring-expenses', $expense->id) }}"><span>@lang('app.menu.expenses')</span></a></li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>

                    <div class="white-box">
                            <div class="row">
                                <div class="col-xs-6 b-r"> <strong class="clearfix">@lang('app.title')</strong> <br>
                                    <span class="text-muted">{{ ucwords($expense->item_name) }}</span>
                                    @if ($expense->status == 'inactive')
                                        <label class="label label-danger">{{ strtoupper($expense->status) }}</label>
                                    @else
                                        <label class="label label-success">{{ strtoupper($expense->status) }}</label>
                                    @endif
                                </div>
                                <div class="col-xs-6"> <strong class="clearfix">@lang('app.category')</strong> <br>
                                    <p class="text-muted">{{ (!is_null($expense->category_id)) ? ucwords($expense->category->category_name) : "--"}}</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-xs-6 b-r"> <strong class="clearfix">@lang('app.project')</strong> <br>
                                    <p class="text-muted">{{ (!is_null($expense->project_id) && !is_null($expense->project)) ? ucwords($expense->project->project_name) : "--"}}</p>
                                </div>
                                <div class="col-xs-6 b-r" > <strong class="clearfix">@lang('app.member')</strong> <br>
                                    <p class="text-muted"> {{ (!is_null($expense->user_id)) ? ucfirst($expense->user->name) : "--"}}</p>
                                </div>
                            </div>
                            <div class="row">
                                @if($expense->bill)
                                    <div class="col-xs-12">
                                        <strong class="clearfix">@lang('app.bill')</strong> <br>
                                    </div>
                                    <div class="col-xs-6">
                                        {{ $expense->bill }}
                                    </div>

                                    <div class="col-xs-3">

                                        <a target="_blank" href="{{ $expense->bill_url }}"
                                           data-toggle="tooltip" data-original-title="View"
                                           class="btn btn-info btn-circle"><i
                                                    class="fa fa-search"></i></a>

                                        <a href="{{ route('admin.expenses-recurring.download', $expense->id) }}"
                                           data-toggle="tooltip" data-original-title="Download"
                                           class="btn btn-default btn-circle"><i
                                                    class="fa fa-download"></i></a>
                                    </div>
                                @endif
                            </div>
                            <div class="row">
                                <div class="col-xs-12 b-r">
                                    <strong class="clearfix">@lang('app.description')</strong> <br>
                                    <p class="text-muted">{!! $expense->description !!}</p>
                                </div>
                            </div>
                            <div class="row">
                                <h4 >@lang('app.recurringDetail')</h4>
                                <hr>
                            </div>

                            <div class="row">
                                <div class="col-xs-6 b-r">
                                    <strong class="clearfix">@lang('app.price')</strong> <br>
                                    <span class="text-muted">{{ $expense->total_amount }} </span> <label class="label label-info">{{ strtoupper($expense->rotation) }}</label>
                                </div>
                                <div class="col-xs-6">
                                    <strong class="clearfix">@lang('app.totalAmount')</strong> <br>
                                    <p class="text-muted">{{ $expense->currency->currency_symbol.''.$expense->recurrings->sum('price') }}</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-xs-6 b-r">
                                    <strong class="clearfix">@lang('app.completedExpense')</strong> <br>
                                    <p class="text-muted">{{ $expense->recurrings->count() }}</p>
                                </div>
                                <div class="col-xs-6 ">
                                    <strong class="clearfix">@lang('app.pendingExpense')</strong> <br>
                                    @if($expense->unlimited_recurring == 0 )
                                        @if($expense->billing_cycle > $expense->recurrings->count())
                                            <p class="text-muted">{{ $expense->billing_cycle - $expense->recurrings->count() }}</p>
                                        @else
                                            <p><label class="label label-success"> @lang('app.completed') </label></p>
                                        @endif
                                    @else
                                        <p><label class="label label-info"> @lang('app.infinite') </label></p>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-6"> <strong class="clearfix">@lang('modules.expensesRecurring.lastPaymentDate')</strong> <br>
                                    @if($expense->recurrings->count() > 0)
                                        {{ $expense->recurrings[$expense->recurrings->count()-1]->created_at->format($global->date_format) }}
                                    @else
                                        --
                                    @endif
                                </div>

                            </div>


                    </div>
                    <!-- /content -->
                </div>
                <!-- /tabs -->
            </section>
        </div>


    </div>
@endsection

@push('footer-script')

@endpush
