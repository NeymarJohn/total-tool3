<footer class="bg-white footer">
    <div class="container border-bottom">
        <div class="footer__widgets">
            <div class="row">

                <div class="col-md-3 col-sm-3 col-xs-12">
                    <div class="widget footer__about-us">
                        <a href="./" class="hover-logo d-inline-block">
                            <img src="{{ $setting->logo_front_url }}" class="logo" style="max-height:35px">
                        </a>

                        <div class="socials mt-4">
                            @if($frontDetail->social_links)
                            @foreach (json_decode($frontDetail->social_links,true) as $link)
                            @if (strlen($link['link']) > 0)
                            <a href="{{ $link['link'] }}" class="zmdi zmdi-{{$link['name']}}" target="_blank">
                            </a>
                            @endif
                            @endforeach
                            @endif

                        </div>

                    </div>
                </div> <!-- end about us -->

                <div class="col-md-3 col-sm-3 col-xs-12">
                    <div class="widget widget-links">
                        <h5 class="widget-title">{{__('app.main')}}</h5>
                        <ul class="list-no-dividers">
                            <ul class="navbar-nav">
                                @if($global->enable_register == true)
                                <li class="nav-item">
                                    <a class="nav-link"
                                        href="{{ route('front.signup.index') }}">@lang('modules.frontCms.getStarted')</a>
                                </li>
                                @endif
                                <li class="nav-item">
                                    <a class="nav-link"
                                        href="{{ route('front.feature') }}">{{ $frontMenu->feature }}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('front.pricing') }}">{{ $frontMenu->price }}</a>
                                </li>
                                <li class="nav-item">
                                    @if(module_enabled('Subdomain'))
                                    <a href="{{ route('front.workspace') }}"
                                        class="nav-link">{{ $frontMenu->login }}</a>
                                    @else
                                    <a href="{{ route('login') }}" class="nav-link">{{ $frontMenu->login }}</a>
                                    @endif
                                </li>
                            </ul>

                        </ul>
                    </div>
                </div>

                <div class="col-md-3 col-sm-3 col-xs-12">
                    <div class="widget widget-links">
                        <h5 class="widget-title">{{__('app.others')}}</h5>
                        <ul class="navbar-nav ml-auto">
                            @foreach($footerSettings as $footerSetting)
                            @if($footerSetting->type != 'header')
                            <li class="nav-item active"><a class="nav-link"
                                    href="@if(!is_null($footerSetting->external_link)) {{ $footerSetting->external_link }} @else {{ route('front.page', $footerSetting->slug) }} @endif">{{ $footerSetting->name }}</a>
                            </li>
                            @endif
                            @endforeach
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('front.contact') }}">{{ $frontMenu->contact }}</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-3 col-sm-3 col-xs-12">
                    <div class="widget widget-links">
                        <h5 class="widget-title">{{ $frontMenu->contact }}</h5>

                        <div class="socials mt-40">

                            <div class="f-contact-detail mt-20">
                                <i class="flaticon-email"></i>
                                <p class="mb-0">{{ $frontDetail->email }}</p>
                            </div>
                            @if($frontDetail->phone)
                            <div class="f-contact-detail mt-20">
                                <i class="flaticon-call"></i>
                                <p class="mb-0">{{ $frontDetail->phone }}</p>
                            </div>
                            @endif

                            <div class="f-contact-detail mt-20">
                                <i class="flaticon-placeholder"></i>
                                <p class="mb-0">{{ $frontDetail->address }}</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div> <!-- end container -->

    <div class="footer__bottom top-divider">
        <div class="container text-center ">
            <span class="copyright mr-3">
                {{ $trFrontDetail->footer_copyright_text ?? "" }}
            </span>
            @if(count($languages)>0)
            <div class="input-group d-inline-flex lang-selector">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroupPrepend"><i class="zmdi zmdi-globe-alt"></i></span>
                </div>

                <select class="custom-select custom-select-sm" onchange="location = this.value;">
                    <option value="{{ route('front.language.lang', 'en') }}" @if($locale=='en' ) selected @endif>English
                    </option>
                    @foreach($languages as $language)
                    <option value="{{ route('front.language.lang', $language->language_code) }}"
                        @if($locale==$language->language_code) selected @endif>{{
                                    $language->language_name }}
                    </option>
                    @endforeach
                </select>

            </div>
            @endif
        </div>
    </div> <!-- end footer bottom -->

</footer>