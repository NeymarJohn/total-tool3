<nav class="topbar topbar-expand-sm topbar-sticky">
    <div class="container-wide">
        <div class="row h-full">
            <div class="offset-1 col-10 col-md-4 offset-md-1 align-self-center">
                <button class="topbar-toggler">&#9776;</button>
                <a class="topbar-brand" href="{{ route('front.home') }}">
                    <img src="{{ $setting->logo_front_url }}" class="logo-default" alt="home" />
                </a>
            </div>

            <div class="col-1 col-md-5 text-md-right">
                @php $routeName = request()->route()->getName(); @endphp
                <ul class="topbar-nav nav">
                    <li class="nav-item"><a class="nav-link" @if($routeName != 'front.home') href="{{route('front.home').'#home'}}" @else href="javascript:;" data-scrollto="home" @endif >{{ $frontMenu->home }}</a></li>
                    <li class="nav-item"><a class="nav-link" @if($routeName != 'front.home') href="{{route('front.home').'#section-features'}}" @else href="javascript:;" data-scrollto="section-features" @endif>{{ $frontMenu->feature }}</a></li>
                    <li class="nav-item"><a class="nav-link" @if($routeName != 'front.home') href="{{route('front.home').'#section-pricing'}}" @else href="javascript:;" data-scrollto="section-pricing" @endif>{{ $frontMenu->price }}</a></li>
                    <li class="nav-item"><a class="nav-link" @if($routeName != 'front.home') href="{{route('front.home').'#section-contact'}}" @else href="javascript:;" data-scrollto="section-contact" @endif>{{ $frontMenu->contact }}</a></li>
                    @forelse($footerSettings as $footerSetting)
                        @if($footerSetting->type != 'footer')
                            <li class="nav-item">
                                <a class="nav-link" href="@if(!is_null($footerSetting->external_link)) {{ $footerSetting->external_link }} @else {{ route('front.page', $footerSetting->slug) }} @endif" >{{ $footerSetting->name }}</a>
                            </li>
                        @endif
                    @empty
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</nav>
