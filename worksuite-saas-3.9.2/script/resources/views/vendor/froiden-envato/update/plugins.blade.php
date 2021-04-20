@php
$allModules = Module::all();
$activeModules = [];
foreach ($allModules as $module) {
$activeModules[] = config(strtolower($module) . '.envato_item_id');
}
@endphp

@if (!empty($plugins = \Froiden\Envato\Functions\EnvatoUpdate::plugins()) && count($activeModules) !== count($plugins ))


<div class="col-md-12 m-t-20">
    <h4>{{ucwords(config('froiden_envato.envato_product_name'))}} Official Modules</h4>
    <div class="row">

        @foreach ($plugins as $item)

        @if(!in_array($item['envato_id'],$activeModules))
        <div class="col-md-12 b-all p-10 m-t-10">
            <div class="row">
                <div class="col-xs-2 col-lg-1">
                    <a href="{{ $item['product_link'] }}" target="_blank">
                        <img src="{{ $item['product_thumbnail'] }}" class="img-responsive" alt="">
                    </a>
                </div>
                <div class="col-xs-8 col-lg-5">
                    <a href="{{ $item['product_link'] }}" target="_blank" class="font-bold">{{ $item['product_name'] }}
                    </a>

                    <p class="font-12">
                        {{ $item['summary'] }}
                    </p>
                </div>
                <div class="col-xs-2 col-lg-6 text-right">
                    <a href="{{ $item['product_link'] }}" target="_blank" class="btn btn-md btn-success"><i
                            class="fa fa-arrow-right text-white"></i></a>
                </div>
            </div>
        </div>
        @endif


        @endforeach

    </div>

</div>
@endif