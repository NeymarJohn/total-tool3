@extends('layouts.sass-app')
@section('content')

@forelse($frontFeatures as $frontFeature)
    <!-- START Saas Features -->
    <section class="border-bottom bg-white sp-100 pb-3 overflow-hidden">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="sec-title mb-60">
                        <h3>{{ $frontFeature->title }}</h3>
                        <p>{!!  $frontFeature->description !!}</p>
                    </div>
                </div>
            </div>
            <div class="row">
                @forelse($frontFeature->features as $feature)
                    <div class="col-md-4 col-sm-6 col-12 mb-60">
                        @if($feature->type != 'image')
                        <div class="saas-f-box">
                            <div class="align-items-center icon justify-content-center">
                                <i class="{{ $feature->icon }}"></i>
                            </div>
                            <h5>{{ $feature->title }}</h5>
                            <p class="mb-0">{!!  $feature->description !!} </p>
                        </div>
                        @else
                        <div class="integrate-box shadow">
                            <img src="{{ $feature->image_url }}"   alt="{{ $feature->title }}">
                            <h5 class="mb-0">{{ ucfirst($feature->title) }} </h5>
                        </div>
                        @endif

                    </div>
                @empty
                @endforelse
            </div>
        </div>
    </section>
@empty
@endforelse
    {{--<!-- END SAAS Features -->--}}

    <!-- START Clients Section -->
    @include('saas.section.client')
    <!-- END Clients Section -->

    <!-- START Integration Section -->
    <section class="sp-100-70 bg-white">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="sec-title mb-60">
                        <h3>{{ $trFrontDetail->favourite_apps_title ?: $defaultTrFrontDetail->favourite_apps_title }}</h3>
                        <p>{{ $trFrontDetail->favourite_apps_detail ?: $defaultTrFrontDetail->favourite_apps_detail }}</p>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                @forelse($featureApps as $index => $featureApp)
                    <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-30 wow fadeIn" data-wow-delay="0.4s">
                        <div class="integrate-box shadow">
                            <img src="{{ $featureApp->image_url }}"   alt="{{ $featureApp->title }}">
                            <h5 class="mb-0">{{ ucfirst($featureApp->title) }} </h5>
                        </div>
                    </div>
                @empty
                @endforelse
            </div>
        </div>
    </section>
    <!-- END Integration Section -->
@endsection
@push('footer-script')

@endpush
