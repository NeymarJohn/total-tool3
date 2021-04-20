<!-- START Header -->
<header class="header position-relative">
    <!-- START Navigation -->
    <div class="navigation-bar" id="affix">
        <div class="container">
            <nav class="navbar navbar-expand-lg p-0">
                <a class="logo" href="{{ route('front.home') }}">
                    <img class="logo-default"  src="{{ $setting->logo_front_url }}" alt="home"  style="max-height:35px"/>
                </a>
                <button class="navbar-toggler border-0 p-0" type="button" data-toggle="collapse"
                        data-target="#theme-navbar" aria-controls="theme-navbar" aria-expanded="false"
                        aria-label="Toggle navigation">
                    <span class="navbar-toggler-lines"></span>
                </button>

                <div class="collapse navbar-collapse" id="theme-navbar">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('front.home') }}">{{ $frontMenu->home }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('front.feature') }}">{{ $frontMenu->feature }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('front.pricing') }}">{{ $frontMenu->price }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('front.contact') }}">{{ $frontMenu->contact }}</a>
                        </li>
                        @forelse($footerSettings as $footerSetting)
                            @if($footerSetting->type != 'footer')
                                <li class="nav-item">
                                    <a class="nav-link" href="@if(!is_null($footerSetting->external_link)) {{ $footerSetting->external_link }} @else {{ route('front.page', $footerSetting->slug) }} @endif" >{{ $footerSetting->name }}</a>
                                </li>
                            @endif
                        @empty
                        @endforelse
                    </ul>
                    <div class="my-3 my-lg-0">
                            <a href="{{ module_enabled('Subdomain')?route('front.workspace'):route('login') }}"
                               class="btn btn-border shadow-none">{{ $frontMenu->login }}</a>
                        @if($frontDetail->get_started_show == 'yes' && $global->enable_register == true)
                                <a href="{{ route('front.signup.index') }}" class="btn btn-menu-signup shadow-none ml-2">{{ $frontMenu->get_start }}</a>@endif
                    </div>
                </div>
            </nav>
        </div>
    </div>
    <!-- END Navigation -->
</header>
<!-- END Header -->
