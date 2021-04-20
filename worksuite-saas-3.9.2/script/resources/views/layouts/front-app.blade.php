<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title> {{ __(isset($seoDetail) ? $seoDetail->seo_title : $pageTitle) }} | {{ ucwords($setting->company_name)}}
    </title>
    <meta name="description" content="{{ isset($seoDetail) ? $seoDetail->seo_description : '' }}">
    <meta name="author" content="{{ isset($seoDetail) ? $seoDetail->seo_author : '' }}">
    <meta property="og:image" content="{{ isset($seoDetail) ? $seoDetail->og_image_url:'' }}" />
    <!-- Styles -->
    <link href="{{ asset('front/css/core.min.css') }}" rel="stylesheet">
    <link href="{{ asset('front/css/theme.min.css') }}" rel="stylesheet">
    <link href="{{ asset('front/plugin/froiden-helper/helper.css') }}" rel="stylesheet">
    <link href="{{ asset('front/css/style.css') }}" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">

    <!-- Favicons -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ $setting->favicon_url }}">
    {{--<link rel="manifest" href="{{ asset('favicon/manifest.json') }}">--}}
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ $setting->favicon_url }}">
    <meta name="theme-color" content="#ffffff">

    <script src="https://www.google.com/recaptcha/api.js"></script>
    @foreach ($frontWidgets as $item)
    {!! $item->widget_code !!}

    @endforeach
    @stack('head-script')
    <style>
        {
             ! ! $frontDetail->custom_css ! !
        }

        .has-danger .help-block {
            display: block;
            margin-top: 5px;
            margin-bottom: 10px;
            color: #ff4954;
        }

        .feature-icon,
        .module-available {
            color: #ff0476;
        }

        .pick-plan .pricing__head {
            padding: 3.88em 1.85714286em;
            background: #ff0476;
        }

        .pick-plan .pricing__head h3 {
            color: white;
            font-weight: 500;
        }

        .pick-plan .pricing li {
            text-align: left;
            padding-left: 1em;
            font-weight: 500;
            text-transform: capitalize;
        }

        .boxed {
            position: relative;
            overflow: hidden;
            padding: 1.85714286em;
            margin-bottom: 30px;
        }

        .pricing-3 ul li:not(:last-child) {
            border-bottom: none;
        }

        .pricing-section-2 .pricing {
            border-radius: 0;
        }

        .pricing-section-2 div[class*='col-']:last-child .pricing {
            border-radius: 0 6px 6px 0px;
        }

        .pricing-section-2 div[class*='col-']:first-child .pricing {
            border-radius: 6px 0 0 6px;
        }

        .pricing-section-2 .pricing {
            border: 1px solid #ececec;
            border-radius: 6px 0 0 6px;
            border-right: none;
        }

        .pricing-section-2 div[class*='col-']:first-child .pricing .pricing__head {
            border-radius: 6px 0 0 0;
        }

        .pick-plan .pricing__head {
            padding: 2.78em 1.85714286em;
            background: #ff0476;
        }

        .pricing-3 .pricing__head {
            margin: 0;
            border-bottom: 1px solid #ececec;
        }

        .pricing-3 {
            padding: 0;
        }

        .pricing-section-2 div[class*='col-'] {
            padding: 0;
        }

        .pricing-section-2 div[class*='col-']:not(:first-child):not(:last-child) .pricing__head {
            border-radius: 0;
        }

        .pricing-3 .pricing__head {
            margin: 0;
            border-bottom: 1px solid #ececec;
        }

        .bg--secondary {
            background: #fafafa;
        }

        .pricing-section-2 .pricing {
            border-radius: 0;
        }

        .pricing-section-2 div[class*='col-']:last-child .pricing .pricing__head {
            border-radius: 0 6px 0 0;
        }

        .pricing-section-2 div[class*='col-']:last-child .pricing {
            border-radius: 0 6px 6px 6px;
            border-right: 1px solid #ececec;
        }

        .pricing-section-2 div[class*='col-']:last-child .pricing {
            border-radius: 0 6px 6px 0px;
        }

        .pricing-3 ul li {
            padding: 0.92857143em 0;
        }

        .d-inline-block {
            display: inline-block;
        }

        .d-inline-block .form-control {
            border-color: #b6b6b6 !important;
            color: #777777 !important;
        }

        .mr-20 {
            margin-right: 20px;
        }

        .sub-domain {
            display: flex !important;
            justify-content: center;
        }

        .help-block {
            color: red;
        }

        .domain-text {
            padding: 6px 20px;
            font-size: 14px;
            font-weight: 600;
            color: #3e4042;
            text-align: center;
            background-color: #eee;
        }

        .center-vh {
            height: unset !important;
        }
    </style>
</head>

<body id="home">


    <!-- Topbar -->
    @include('sections.front_header')
    <!-- END Topbar -->




    <!-- Header -->
    @yield('header-section')
    <!-- END Header -->




    <!-- Main container -->
    <main class="main-content">
        @yield('content')
    </main>
    <!-- END Main container -->


    <!-- Footer -->
    @include('sections.front_footer')
    <!-- END Footer -->



    <!-- Scripts -->
    <script src="{{ asset('front/js/core.min.js') }}"></script>
    <script src="{{ asset('front/js/theme.min.js') }}"></script>
    <script src="{{ asset('front/plugin/froiden-helper/helper.js') }}"></script>
    <script src="{{ asset('front/js/script.js') }}"></script>

    @stack('footer-script')
</body>

</html>