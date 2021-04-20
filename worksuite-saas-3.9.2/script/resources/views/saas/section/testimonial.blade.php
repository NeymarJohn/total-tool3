@if(sizeof($testimonials) > 0)
<section class="section-testimonial bg-white sp-100">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="sec-title mb-5">
                    <h3>{{ $trFrontDetail->testimonial_title }}</h3>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div id="testimonial-slider" class="testimonial-slider mb-0 text-center">
                    @forelse($testimonials as $testimonial)
                        <div class="testimonial-item">
                            <div class="client-info">
                                <p class="mb-4">{{ ucfirst($testimonial->comment) }}</p>
                                <h5 class="mb-1">{{ $testimonial->name }}</h5>
                            </div>
                            <div class="rating text-warning">
                                <i class="zmdi zmdi-star "></i>
                                <i class="zmdi @if($testimonial->rating < 2)zmdi-star-border @else zmdi-star @endif "></i>
                                <i class="zmdi  @if($testimonial->rating < 3)zmdi-star-border @else zmdi-star @endif"></i>
                                <i class="zmdi  @if($testimonial->rating < 4)zmdi-star-border @else zmdi-star @endif"></i>
                                <i class="zmdi  @if($testimonial->rating < 5) zmdi-star-border @else zmdi-star @endif"></i>
                            </div>
                        </div>
                    @empty
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>
@endif
