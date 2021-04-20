@php($updateVersionInfo = \Froiden\Envato\Functions\EnvatoUpdate::updateVersionInfo())
@php($envatoUpdateCompanySetting = \Froiden\Envato\Functions\EnvatoUpdate::companySetting())
<div class="table-responsive">

    <table class="table table-bordered">
        <thead>
        <th>@lang('modules.update.systemDetails')</th>
        </thead>
        <tbody>
        <tr>
            <td>App Version </td>
            <td>{{ $updateVersionInfo['appVersion'] }}</td>
        </tr>
        <tr>
            <td>Laravel Version</td>
            <td>{{ $updateVersionInfo['laravelVersion'] }}</td>
        </tr>
        <td>PHP Version

        <td>
            @if (version_compare(PHP_VERSION, '7.2.5') >= 0)
                {{ phpversion() }} <i class="fa fa fa-check-circle text-success"></i>
            @else
                {{ phpversion() }} <i  data-toggle="tooltip" data-original-title="@lang('messages.phpUpdateRequired')" class="fa fa fa-warning text-danger"></i>
            @endif
        </td>
        </td>
        @if(!is_null($envatoUpdateCompanySetting->purchase_code))
            <tr>
                <td>Envato Purchase code </td>
                <td>{{$envatoUpdateCompanySetting->purchase_code}}</td>
            </tr>
        @endif
        </tbody>
    </table>
</div>
