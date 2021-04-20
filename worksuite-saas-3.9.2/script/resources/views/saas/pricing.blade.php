@extends('layouts.sass-app')

@push('head-script')
    @if(count($packages) > 0)
    <style>
        .package-column {
            max-width: {{ 100/count($packages) }}%;
            flex: 0 0 {{ 100/count($packages) }}%
        }
    </style>
    @endif
@endpush

@section('content')
    <!-- START Pricing Section -->
    <section class="pricing-section bg-white sp-100">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="sec-title mb-5">
                        <h3>{{ $trFrontDetail->price_title ?: $defaultTrFrontDetail->price_title }}</h3>
                        <p>{{ $trFrontDetail->price_description ?: $defaultTrFrontDetail->price_description }}</p>
                    </div>
                    {{--@if (isset($packageSetting) && isset($trialPackage) && $packageSetting && !is_null($trialPackage))--}}
                        {{--<h4 class="text-center mb-5">{{$packageSetting->trial_message}}</h4>--}}
                    {{--@endif--}}
                </div>
            </div>
            <div class="text-center mb-5">
                <div class="nav price-tabs justify-content-center" role="tablist">
                    <a class="nav-link active" href="#monthly" role="tab" data-toggle="tab">@lang('app.monthly')</a>
                    <a class="nav-link " href="#yearly"  role="tab" data-toggle="tab">@lang('app.annual')</a>
                </div>
            </div>
            <div class="tab-content wow fadeIn">
                <div role="tabpanel" class="tab-pane fade " id="yearly">
                    <div class="container">
                        <div class="price-wrap border row no-gutters">
                            <div class="diff-table col-6 col-md-3">
                                <div class="price-top">
                                    <div class="price-top title">
                                        <h3>@lang('app.pickUp') <br> @lang('app.yourPlan')</h3>
                                        {{--@lang('modules.frontCms.pickPlan')--}}
                                    </div>
                                    <div class="price-content">

                                        <ul>
                                            <li>
                                                @lang('app.max') @lang('app.menu.employees')
                                            </li>
                                            <li>
                                                @lang('app.menu.fileStorage')
                                            </li>
                                            @foreach($packageFeatures as $packageFeature)
                                                @if(in_array($packageFeature, $activeModule))
                                                    <li>
                                                        {{ __('modules.module.'.$packageFeature) }}
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>

                                <div class="all-plans col-6 col-md-9">
                                <div class="row no-gutters flex-nowrap flex-wrap overflow-x-auto row-scroll">
                                    @foreach ($packages as $key => $item)
                                        <div class="col-md-3 package-column">
                                            <div class="pricing-table price-@if($item->is_recommended == 1)pro @endif">
                                                <div class="price-top">
                                                    <div class="price-head text-center">
                                                        <h5 class="mb-0">{{ ($item->name) }}</h5>
                                                    </div>
                                                    <div class="rate">
                                                        <h2 class="mb-2">
                                                            @if(currency_position(null,$global->currency->currency_symbol) =='front')
                                                                <sup>{{ $global->currency->currency_symbol }}</sup>
                                                            @endif
                                                            <span class="font-weight-bolder">{{ $item->annual_price }}</span>
                                                            @if(currency_position(null,$global->currency->currency_symbol) !=='front')
                                                                <sup>{{ $global->currency->currency_symbol }}</sup>
                                                            @endif

                                                        </h2>
                                                        <p class="mb-0">@lang('app.billedAnnually')</p>
                                                    </div>
                                                </div>
                                                <div class="price-content">
                                                    <ul>
                                                        <li>
                                                            {{ $item->max_employees }}
                                                        </li>
                                                        @if($item->max_storage_size == -1)
                                                            <li>
                                                                @lang('app.unlimited')
                                                            </li>
                                                        @else
                                                            <li>
                                                                {{ $item->max_storage_size }} {{ strtoupper($item->storage_unit) }}
                                                            </li>
                                                        @endif
                                                        @php
                                                            $packageModules = (array)json_decode($item->module_in_package);
                                                        @endphp
                                                        @foreach($packageFeatures as $packageFeature)
                                                            @if(in_array($packageFeature, $activeModule))
                                                                <li>
                                                                    @if(in_array($packageFeature, $packageModules))
                                                                        <i class="zmdi zmdi-check-circle blue"></i>
                                                                    @else
                                                                        <i class="zmdi zmdi-close-circle"></i>
                                                                    @endif
                                                                    &nbsp;
                                                                </li>
                                                            @endif
                                                        @endforeach
                                                    </ul>
                                                </div>
                                                {{--<div class="price-bottom py-4 px-2">--}}
                                                    {{--<a href="#" class="btn btn-border shadow-none">buy now</a>--}}
                                                {{--</div>--}}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane fade show active" id="monthly">
                        <div class="container">
                            <div class="price-wrap border row no-gutters">
                                <div class="diff-table col-6 col-md-3">
                                    <div class="price-top">
                                        <div class="price-top title">
                                            <h3>@lang('app.pickUp') <br> @lang('app.yourPlan')</h3>
                                            {{--@lang('modules.frontCms.pickPlan')--}}
                                        </div>
                                        <div class="price-content">

                                            <ul>
                                                <li>
                                                    @lang('app.max') @lang('app.menu.employees')
                                                </li>
                                                <li>
                                                    @lang('app.menu.fileStorage')
                                                </li>
                                                @foreach($packageFeatures as $packageFeature)
                                                    @if(in_array($packageFeature, $activeModule))
                                                        <li>
                                                            {{ __('modules.module.'.$packageFeature) }}
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                    <div class="all-plans col-6 col-md-9">
                                    <div class="row no-gutters flex-nowrap flex-wrap overflow-x-auto row-scroll">
                                        @foreach ($packages as $key=>$item)
                                            <div class="col-md-3 package-column">
                                                <div class="pricing-table price-@if($item->is_recommended == 1)pro @endif ">
                                                    <div class="price-top">
                                                        <div class="price-head text-center">
                                                            <h5 class="mb-0">{{ ($item->name) }}</h5>
                                                        </div>
                                                        <div class="rate">
                                                            <h2 class="mb-2">
                                                                @if(currency_position(null,$global->currency->currency_symbol) =='front')
                                                                    <sup>{{ $global->currency->currency_symbol }}</sup>
                                                                @endif
                                                                <span class="font-weight-bolder">{{ $item->monthly_price }}</span>
                                                                @if(currency_position(null,$global->currency->currency_symbol) !=='front')
                                                                    <sup>{{ $global->currency->currency_symbol }}</sup>
                                                                @endif
                                                            </h2>
                                                            <p class="mb-0">@lang('app.billedMonthly')</p>
                                                        </div>
                                                    </div>
                                                    <div class="price-content">
                                                        <ul>
                                                            <li>
                                                                {{ $item->max_employees }}
                                                            </li>

                                                            @if($item->max_storage_size == -1)
                                                                <li>
                                                                    @lang('app.unlimited')
                                                                </li>
                                                            @else
                                                                <li>
                                                                    {{ $item->max_storage_size }} {{ strtoupper($item->storage_unit) }}
                                                                </li>
                                                            @endif

                                                            @php
                                                                $packageModules = (array)json_decode($item->module_in_package);
                                                            @endphp
                                                            @foreach($packageFeatures as $packageFeature)
                                                                @if(in_array($packageFeature, $activeModule))
                                                                    <li>
                                                                        @if(in_array($packageFeature, $packageModules))
                                                                            <i class="zmdi zmdi-check-circle blue"></i>
                                                                        @else
                                                                            <i class="zmdi zmdi-close-circle"></i>
                                                                        @endif
                                                                        &nbsp;
                                                                    </li>
                                                                @endif
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                    {{--<div class="price-bottom py-4 px-2">--}}
                                                        {{--<a href="#" class="btn btn-border shadow-none">buy now</a>--}}
                                                    {{--</div>--}}
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </section>
    <!-- END Pricing Section -->

    <!-- START Section FAQ -->
    <section class="bg-white sp-100-70 pt-0">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="sec-title mb-60">
                        <h3>{{ $trFrontDetail->faq_title ?: $defaultTrFrontDetail->faq_title }}</h3>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <div id="accordion" class="theme-accordion">
                        @forelse($frontFaqs as $frontFaq)
                            <div class="card border-0 mb-30">
                                <div class="card-header border-bottom-0 p-0" id="acc{{ $frontFaq->id }}">
                                    <h5 class="mb-0">
                                        <button class="position-relative text-decoration-none w-100 text-left collapsed"
                                                data-toggle="collapse" data-target="#collapse{{ $frontFaq->id }}"
                                                aria-controls="collapse{{ $frontFaq->id }}">
                                           {{ $frontFaq->question }}
                                        </button>
                                    </h5>
                                </div>

                                <div id="collapse{{ $frontFaq->id }}" class="collapse" aria-labelledby="acc{{ $frontFaq->id }}" data-parent="#accordion">
                                    <div class="card-body">
                                        <p>{!! $frontFaq->answer  !!}</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- END Section FAQ -->

@endsection
@push('footer-script')

@endpush
