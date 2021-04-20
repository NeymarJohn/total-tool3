<header class="header h-fullscreen" style="background-image: linear-gradient(150deg, #fdfbfb 0%, #eee 100%);">
    <div class="container-wide">

        <div class="row h-full align-items-center text-center text-lg-left">

            <div class="offset-1 col-10 col-lg-4 offset-lg-1">
                <h1>{{ $trFrontDetail->header_title ?: $defaultTrFrontDetail->header_title }}</h1>
                <br>
                <p class="lead mx-auto">{!!  $trFrontDetail->header_description ?: $defaultTrFrontDetail->header_description !!} </p>
                <br>
                @if($frontDetail->get_started_show == 'yes' && $global->enable_register == true)
                    <a class="btn btn-lg btn-success" href="{{ route('front.signup.index') }}">{{ $frontMenu->get_start }}</a>
                @endif
                @if($frontDetail->sign_in_show == 'yes')
                    <a class="btn btn-lg btn-info" href="{{ route('login') }}">{{ $frontMenu->login }}</a>
                @endif
            </div>

            <div class="col-12 col-lg-6 offset-lg-1 img-outside-right hidden-md-down">
                <img class="shadow-4 mt-80" src="{{ $trFrontDetail->image_url ?: $defaultTrFrontDetail->image_url }}" alt="...">
            </div>
        </div>

    </div>
</header>
