@section('other-section')
<ul class="nav tabs-vertical">

    <li class="tab">
        <a href="{{ route('admin.settings.index') }}"><i class="ti-arrow-left"></i> @lang('app.menu.settings')</a></li>
    @if(isset($menuInnerSettingMenu['children']))

        @foreach($menuInnerSettingMenu['children'] as $menu)
            @php
                $panel = strtok($menu['menu_name'], 'M')
            @endphp
            <li class="tab @if($type == $panel) active @endif">
                <a href="{{ route($menu['route']) }}{{ $panel !== 'admin' ? '?type='.strtok($menu['menu_name'], 'M') : '' }}">@lang($menu['translate_name'])</a></li>
        @endforeach
    @else
        @if(count($menuInnerSettingMenu) > 0)
            @php
                $panel = strtok($menuInnerSettingMenu['menu_name'], 'M')
            @endphp
            <li class="tab @if($type == $panel) active @endif">
                <a href="{{ route($menuInnerSettingMenu['route']) }}?type={{ strtok($menuInnerSettingMenu['menu_name'], 'M') }}">@lang($menuInnerSettingMenu['translate_name'])</a></li>
         @endif
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