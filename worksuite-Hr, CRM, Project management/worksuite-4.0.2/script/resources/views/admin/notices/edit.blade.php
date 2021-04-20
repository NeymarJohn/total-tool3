@extends('layouts.app')
@push('head-script')
    <style>
        .d-none {
            display: none;
        }
    </style>
@endpush
@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> @lang($pageTitle)</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">@lang("app.menu.home")</a></li>
                <li><a href="{{ route('admin.notices.index') }}">@lang($pageTitle)</a></li>
                <li class="active">@lang('app.edit')</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@section('content')

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-inverse">
                <div class="panel-heading"> @lang('modules.notices.updateNotice')</div>
                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
                        {!! Form::open(['id'=>'updateNotice','class'=>'ajax-form','method'=>'PUT']) !!}
                        <div class="form-body">
                            <div class="row">
                                <div class="col-xs-12 ">
                                    <div class="form-group">
                                        <label class="required">@lang("modules.notices.noticeHeading")</label>
                                        <input type="text" name="heading" id="heading"  class="form-control" value="{{ $notice->heading }}">
                                    </div>
                                </div>
                            </div>
                            <!--/row-->
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        {{--                                            <label>Select Duration</label>--}}
                                        <div class="radio-list">
                                            <label class="radio-inline p-0">
                                                <div class="radio radio-info">
                                                    <input type="radio" name="to" id="toEmployee" @if($notice->to == 'employee') checked @endif value="employee">
                                                    <label for="toEmployee">@lang('modules.notices.toEmployee')</label>
                                                </div>
                                            </label>
                                            <label class="radio-inline">
                                                <div class="radio radio-info">
                                                    <input type="radio" name="to" id="toClient" value="client" @if($notice->to == 'client') checked @endif>
                                                    <label for="toClient">@lang('modules.notices.toClients')</label>
                                                </div>
                                            </label>

                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 @if($notice->to == 'client') d-none @endif" id="department">
                                    <div class="form-group">
                                        <label>@lang("app.department")</label>
                                        <select name="team_id" id="team_id" class="form-control">
                                            <option value=""> -- </option>
                                            @foreach($teams as $team)
                                                <option {{ isset($notice->department_id) && $notice->department_id == $team->id ? 'selected' : '' }} value="{{ $team->id }}">{{ ucwords($team->team_name) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label">@lang("modules.notices.noticeDetails")</label>
                                        <textarea name="description" id="description" rows="5" class="form-control">{{ $notice->description }}</textarea>
                                    </div>
                                </div>

                            </div>
                            <!--/span-->

                        </div>
                        <div class="form-actions">
                            <button type="submit" id="save-form" class="btn btn-success"> <i class="fa fa-check"></i> @lang('app.update')</button>
                            <button type="reset" class="btn btn-default">@lang('app.reset')</button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>    <!-- .row -->

@endsection

@push('footer-script')
<script>
    $(function () {

        $('.radio-list').click(function () {
            if($('input[name=to]:checked').val() === 'employee') {
                $('#department').removeClass('d-none').addClass('d-block');
            } else {
                $('#department').removeClass('d-block').addClass('d-none');
            }
        })

    });
    $('#save-form').click(function () {
        $.easyAjax({
            url: '{{route('admin.notices.update', [$notice->id])}}',
            container: '#updateNotice',
            type: "POST",
            redirect: true,
            data: $('#updateNotice').serialize()
        })
    });
</script>

@endpush
