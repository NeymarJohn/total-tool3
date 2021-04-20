<footer class="site-footer">
    <div class="container">
        <div class="row gap-y align-items-center">
            <div class="col-12 col-lg-3">
                <p class="text-center text-lg-left">
                    <a href="{{ route('front.home') }}">
                        <img src="{{ $setting->logo_front_url }}" alt="home" />
                    </a>
                </p>
            </div>

            <div class="col-12 col-lg-6">
                @php $routeName = request()->route()->getName(); @endphp
                <ul class="nav nav-primary nav-hero">
                    @forelse($footerSettings as $footerSetting)
                        @if($footerSetting->type != 'header')
                            <li class="nav-item">
                                <a class="nav-link" href="@if(!is_null($footerSetting->external_link)) {{ $footerSetting->external_link }} @else {{ route('front.page', $footerSetting->slug) }} @endif" >{{ $footerSetting->name }}</a>
                            </li>
                        @endif
                    @empty

                    @endforelse
                </ul>
            </div>

            <div class="col-12 col-lg-3">
                <div>
                    <div class="form-group d-inline-block mr-20">
                        <select class="form-control" onchange="location = this.value;">
                            <option value="{{ route('front.language.lang', 'en') }}" @if($locale == 'en') selected @endif>English </option>
                            @foreach($languages as $language)
                                <option value="{{ route('front.language.lang', $language->language_code) }}"  @if($locale == $language->language_code) selected @endif>{{ $language->language_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="social text-center text-lg-right d-inline-block">
                        @foreach (json_decode($detail->social_links,true) as $link)
                            @if (strlen($link['link']) > 0)
                                <a class="social-{{$link['name']}}" href="{{ $link['link'] }}" target="_blank">
                                    <i class="fab fa-{{$link['name']}}@if ($link['name'] == 'facebook')-f @endif"></i>
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
