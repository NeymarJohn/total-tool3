<div class="clients bg-light">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 mb-30 text-center">
                <p class="c-blue mb-2">{{ $trFrontDetail->client_title ?: $defaultTrFrontDetail->client_title }}</p>
                <h4>{{ $trFrontDetail->client_detail ?: $defaultTrFrontDetail->client_detail }}</h4>

            </div>
            <div class="col-12">
                <div class="client-slider" id="client-slider">
                    @foreach($frontClients as $frontClient)
                        <div class="client-img">
                            <div class="img-holder">
                                <img src="{{ $frontClient->image_url }}" alt="partner">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
