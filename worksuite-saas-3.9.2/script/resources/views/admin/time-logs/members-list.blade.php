
@forelse ($employees as $item)
    <div class="col-md-12 bg-owner-reply p-10 timelog-user-{{ $item->id }}">
        <div class="row">
            <div class="col-md-1">
                <img src="{{ $item->image_url }}" width="35" height="35" class="img-circle">
            </div>
            <div class="col-md-4">
                <span class="font-semi-bold">{{ ucwords($item->name) }}</span><br>
                <span class="text-muted font-12">{{ $item->designation_name }}</span>
            </div>
            <div class="col-md-3 m-t-10 text-center b-l">

                <span class="text-info">
                    {{ intdiv($item->total_minutes, 60) }}
                </span> <span class="font-12 text-muted m-l-5"> @lang('modules.projects.hoursLogged')</span>
            </div>
            
            <div class="col-md-3 m-t-10 text-center b-l">

                <span class="text-success">
                    {{ $global->currency->currency_symbol.floatval($item->earnings) }}
                </span> <span class="font-12 text-muted m-l-5"> @lang('app.earnings')</span>
            </div>

            <div class="col-md-1 text-center b-l">
                <button class="btn btn-outline show-user-timelogs" data-user-id="{{ $item->id }}"><i class="ti-plus"></i></button>
            </div>

            <div class="col-md-1 text-center b-l">
                <button class="btn btn-outline hide-user-timelogs hide" data-user-id="{{ $item->id }}"><i class="ti-minus"></i></button>
            </div>
            
        </div>
    </div>    
@empty
    <div class="col-md-12 p-10">
        @lang('messages.noRecordFound')
    </div>
@endforelse
