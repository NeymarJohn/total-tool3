@section('other-section')

<ul class="nav tabs-vertical">
    <li class="tab">
        <a href="{{ route('super-admin.settings.index') }}" class="text-danger"><i class="ti-arrow-left"></i> @lang('app.menu.settings')</a></li>
    <li class="tab @if(\Illuminate\Support\Facades\Route::currentRouteName() == 'super-admin.payment-settings.index') active @endif">
        <a href="{{ route('super-admin.payment-settings.index') }}">@lang('app.menu.onlinePayment')</a></li>
    <li class="tab @if(\Illuminate\Support\Facades\Route::currentRouteName() == 'super-admin.offline-payment-setting.index') active @endif">
        <a href="{{ route('super-admin.offline-payment-setting.index') }}">@lang('app.menu.offlinePaymentMethod')</a></li>
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
