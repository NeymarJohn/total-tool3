@section('other-section')

<ul class="nav tabs-vertical">
    <li
        class="tab @if(\Illuminate\Support\Facades\Route::currentRouteName() == 'super-admin.settings.index') active @endif">
        <a href="{{ route('super-admin.settings.index') }}">@lang('app.global') @lang('app.menu.settings')</a></li>
    <li
        class="tab @if(\Illuminate\Support\Facades\Route::currentRouteName() == 'super-admin.email-settings.index') active @endif">
        <a href="{{ route('super-admin.email-settings.index') }}">@lang('app.email') @lang('app.menu.settings')</a></li>
    <li
        class="tab @if(\Illuminate\Support\Facades\Route::currentRouteName() == 'super-admin.security-settings.index') active @endif">
        <a href="{{ route('super-admin.security-settings.index') }}">@lang('app.security')</a></li>
    <li
        class="tab @if(\Illuminate\Support\Facades\Route::currentRouteName() == 'super-admin.push-notification-settings.index') active @endif">
        <a href="{{ route('super-admin.push-notification-settings.index') }}">@lang('app.menu.pushNotifications')
            @lang('app.menu.settings')</a>
    </li>
    <li
        class="tab @if(\Illuminate\Support\Facades\Route::currentRouteName() == 'super-admin.language-settings.index') active @endif">
        <a href="{{ route('super-admin.language-settings.index') }}">@lang('app.language')
            @lang('app.menu.settings')</a>
    </li>
    <li
        class="tab @if(\Illuminate\Support\Facades\Route::currentRouteName() == 'super-admin.currency.index') active @endif">
        <a href="{{ route('super-admin.currency.index') }}">@lang('app.menu.currencySettings')</a></li>
    <li
        class="tab @if(\Illuminate\Support\Facades\Route::currentRouteName() == 'super-admin.payment-settings.index') active @endif">
        <a href="{{ route('super-admin.payment-settings.index') }}">@lang('app.menu.paymentGatewayCredential')</a></li>
    <li
        class="tab @if(\Illuminate\Support\Facades\Route::currentRouteName() == 'super-admin.package-settings.index') active @endif">
        <a href="{{ route('super-admin.package-settings.index') }}">@lang('app.freeTrial')
            @lang('app.menu.settings')
            {{-- <i class="fa fa-check text-right pull-right text-success"></i> --}}
        </a>
        </li>
    <li
        class="tab @if(\Illuminate\Support\Facades\Route::currentRouteName() == 'super-admin.custom-modules.index' || \Illuminate\Support\Facades\Route::currentRouteName() == 'super-admin.custom-modules.create') active @endif">
        <a href="{{ route('super-admin.custom-modules.index') }}">@lang('app.menu.customModule')</a></li>
    <li
        class="tab @if(\Illuminate\Support\Facades\Route::currentRouteName() == 'super-admin.storage-settings.index') active @endif">
        <a href="{{ route('super-admin.storage-settings.index') }}">@lang('app.menu.storageSettings')</a>
    </li>
    <li
        class="tab @if(\Illuminate\Support\Facades\Route::currentRouteName() == 'super-admin.theme-settings.index') active @endif">
        <a href="{{ route('super-admin.theme-settings.index') }}">@lang('app.menu.themeSettings')</a>
    </li>
    <li
        class="tab @if(\Illuminate\Support\Facades\Route::currentRouteName() == 'super-admin.profile.index') active @endif">
        <a href="{{ route('super-admin.profile.index') }}">@lang('app.menu.profileSettings')</a>
    </li>
    <li
        class="tab @if(\Illuminate\Support\Facades\Route::currentRouteName() == 'super-admin.social-auth-settings.index') active @endif">
        <a href="{{ route('super-admin.social-auth-settings.index') }}">@lang('app.menu.socialLogin')</a>
    </li>

    @foreach ($worksuitePlugins as $item)
        @if(View::exists(strtolower($item).'::sections.super_admin_setting_menu'))
            @include(strtolower($item).'::sections.super_admin_setting_menu')
        @endif
    @endforeach

    @if($global->system_update == 1)
    <li
        class="tab @if(\Illuminate\Support\Facades\Route::currentRouteName() == 'super-admin.update-settings.index') active @endif">
        <a href="{{ route('super-admin.update-settings.index') }}">@lang('app.menu.updates')</a>
    </li>
    @endif
</ul>

<script src="{{ asset('plugins/bower_components/jquery/dist/jquery.min.js') }}"></script>
<script>
    var screenWidth = $(window).width();
    if(screenWidth <= 768){

        $('.tabs-vertical').each(function() {
            var list = $(this), select = $(document.createElement('select')).insertBefore($(this).hide()).addClass('settings_dropdown form-control');

            $('>li a', this).each(function() {
                var target = $(this).attr('target'),
                    option = $(document.createElement('option'))
                        .appendTo(select)
                        .val(this.href)
                        .html($(this).html())
                        .click(function(){
                            if(target==='_blank') {
                                window.open($(this).val());
                            }
                            else {
                                window.location.href = $(this).val();
                            }
                        });

                if(window.location.href == option.val()){
                    option.attr('selected', 'selected');
                }
            });
            list.remove();
        });

        $('.settings_dropdown').change(function () {
            window.location.href = $(this).val();
        })

    }

</script>
@endsection
