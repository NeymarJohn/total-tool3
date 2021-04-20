@section('other-section')
<ul class="nav tabs-vertical">
    <li class="tab">
        <a href="{{ route('admin.settings.index') }}" class="text-danger"><i class="ti-arrow-left"></i> @lang('app.back')</a></li>
    <li class="tab @if(\Illuminate\Support\Facades\Route::currentRouteName() == 'admin.gdpr.index') active @endif">
        <a href="{{ route('admin.gdpr.index') }}">@lang('app.general')</a></li>

    <li class="tab @if(\Illuminate\Support\Facades\Route::currentRouteName() == 'admin.gdpr.right-to-data-portability') active @endif">
        <a href="{{ route('admin.gdpr.right-to-data-portability') }}">@lang('modules.gdpr.rightToDataProtability')</a></li>
    <li class="tab @if(\Illuminate\Support\Facades\Route::currentRouteName() == 'admin.gdpr.right-to-erasure') active @endif">
        <a href="{{ route('admin.gdpr.right-to-erasure') }}">@lang('modules.gdpr.rightToErasure')</a></li>
    <li class="tab @if(\Illuminate\Support\Facades\Route::currentRouteName() == 'admin.gdpr.right-to-informed') active @endif">
        <a href="{{ route('admin.gdpr.right-to-informed') }}">@lang('modules.gdpr.rightToInform')</a></li>

    <li class="tab @if(\Illuminate\Support\Facades\Route::currentRouteName() == 'admin.gdpr.right-of-access') active @endif">
        <a href="{{ route('admin.gdpr.right-of-access') }}">@lang('modules.gdpr.rightOfAccessRectification')</a></li>

    <li class="tab @if(\Illuminate\Support\Facades\Route::currentRouteName() == 'admin.gdpr.consent') active @endif">
        <a href="{{ route('admin.gdpr.consent') }}">@lang('modules.gdpr.consent')</a></li>
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