@if(!empty($featureWithImages))
    <!-- START Saas Features -->
    <section class="saas-features bg-white overflow-hidden">
        <div class="container">
            @foreach($featureWithImages as $key => $value)
                @if($loop->iteration % 2 == 0)
                    <div class="sp-100 pt-0">
                        <div class="row align-items-center">
                            <div class="col-lg-6 order-lg-1 wow fadeIn  d-lg-block" data-wow-delay="0.2s">
                                <div class="mock-img">
                                    <img src="{{ $value->image_url }}" alt="mockup">
                                </div>
                            </div>
                            <div class="col-lg-6 pl-lg-5 order-lg-2">
                                <h3>{{ $value->title }}</h3>
                                <p>{!! $value->description !!}</p>
                            </div>
                        </div>
                    </div>

                @else
                    <div class="sp-100">
                        <div class="row align-items-center">
                            <div class="col-lg-6 pr-lg-5">
                                <h3>{{ $value->title }}</h3>
                                <p>{!! $value->description !!}</p>
                            </div>
                            <div class="col-lg-6 wow fadeIn  d-lg-block" data-wow-delay="0.2s">
                                <div class="mock-img">
                                    <img src="{{ $value->image_url }}" alt="mockup">
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </section>
@endif
<!-- END Saas Features -->
@if(sizeof($featureWithIcons) > 0)
    <!-- START Features -->
    <section class="features bg-light sp-100-70">
        <div class="container">

            <div class="row">
                <div class="col-12">
                    <div class="sec-title mb-60">
                        <h3>{{ $trFrontDetail->feature_title }}</h3>
                        <p>{{ $trFrontDetail->feature_description }}</p>
                    </div>
                </div>
            </div>

            <div class="row">
                @foreach($featureWithIcons as $featureWithIcon)
                    <div class="col-lg-4 col-md-6 col-12 mb-30 wow fadeIn" data-wow-delay="0.2s">
                        <div class="feature-box bg-white br-10 text-center">
                            <span class="align-items-center d-inline-flex icon justify-content-center mx-auto">
                                <i class="{{ $featureWithIcon->icon }}"></i>
                            </span>
                            <h5>{{ $featureWithIcon->title }}</h5>
                            <p>{!! $featureWithIcon->description !!}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!-- END Features -->
@endif
{{-- <!-- START Saas Features -->
<section class="saas-features bg-white overflow-hidden">
    <div class="container">
    </div>
</section>
<!-- END Saas Features --> --}}
