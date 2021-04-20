@extends('layouts.app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-6 col-sm-8 col-md-8 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> @lang($pageTitle) #{{ $lead->id }} - <span class="font-bold">{{ ucwords($lead->company_name) }}</span></h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-6 col-sm-8 col-md-8 col-xs-12 text-right">
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">@lang('app.home')</a></li>
                <li><a href="{{ route('admin.leads.index') }}">@lang($pageTitle)</a></li>
                <li class="active">@lang('modules.lead.followUp')</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@push('head-script')
<link rel="stylesheet" href="{{ asset('plugins/bower_components/icheck/skins/all.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bower_components/multiselect/css/multi-select.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datetime-picker/datetimepicker.css') }}">
@endpush

@section('content')

    <div class="row">
        <div class="col-md-12">

            <section>
                <div class="sttabs tabs-style-line">
                    <div class="white-box">

                        <nav>
                            <ul>
                                <li ><a href="{{ route('admin.leads.show', $lead->id) }}"><span>@lang('modules.lead.profile')</span></a>
                                </li>
                                <li ><a href="{{ route('admin.proposals.show', $lead->id) }}"><span>@lang('modules.lead.proposal')</span></a></li>
                                <li ><a href="{{ route('admin.lead-files.show', $lead->id) }}"><span>@lang('modules.lead.file')</span></a></li>

                                <li class="tab-current"><a href="{{ route('admin.leads.followup', $lead->id) }}"><span>@lang('modules.lead.followUp')</span></a></li>
                                @if($gdpr->enable_gdpr)
                                    <li><a href="{{ route('admin.leads.gdpr', $lead->id) }}"><span>GDPR</span></a></li>
                                @endif
                            </ul>
                        </nav>
                    </div>
                    <div class="content-wrap">
                        <section id="section-line-3" class="show">
                            <div class="row">
                                <div class="col-md-12" id="follow-list-panel">
                                    <div class="white-box">

                                        <div class="row m-b-10">
                                            <div class="col-md-5">
                                                @if($lead->next_follow_up == 'yes')
                                                <a href="javascript:;" id="show-new-follow-panel"
                                                   class="btn btn-success btn-outline"><i class="fa fa-plus"></i> @lang('modules.followup.newFollowUp')</a>
                                                @endif
                                            </div>
                                            <div class="col-md-3">

                                            </div>
                                            <div class="col-md-4 text-right">
                                                <select class="selectpicker" data-style="form-control" id="sort-task" data-lead-id="{{ $lead->id }}">
                                                    <option value="id">@lang('modules.tasks.lastCreated')</option>
                                                    <option value="next_follow_up_date">@lang('modules.tasks.dueSoon')</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="list-group" id="listGroup" style="max-height: 400px; overflow-y: auto;">
                                            @forelse($lead->follow as $follow)
                                                <a href="javascript:;" data-follow-id="{{ $follow->id }}" class="list-group-item edit-task">
                                                    <h4 class="list-group-item-heading sbold">@lang('app.createdOn'): {{ $follow->created_at->format($global->date_format .' '. $global->time_format) }}</h4>
                                                    <p class="list-group-item-text">
                                                    <div class="row margin-top-5">
                                                        <div class="col-md-12">
                                                            @lang('app.remark'): <br>
                                                            {!!  ($follow->remark != '') ? ucfirst($follow->remark) : "<span class='font-red'>Empty</span>" !!}
                                                        </div>
                                                    </div>
                                                    <div class="row margin-top-5">
                                                        <div class="col-md-6">
                                                        </div>
                                                        <div class="col-md-6">
                                                            @lang('app.next_follow_up'): {{ $follow->next_follow_up_date->format($global->date_format .' '.$global->time_format) }}
                                                        </div>
                                                    </div>
                                                    </p>
                                                </a>
                                            @empty
                                                <a href="javascript:;" class="list-group-item">
                                                    <h4 class="list-group-item-heading sbold">@lang('modules.followup.followUpNotFound')</h4>
                                                </a>
                                            @endforelse
                                        </div>
                                        <span class="help-block"><b>@lang('app.notice'): </b>  @lang('modules.followup.followUpNote')</span>
                                    </div>
                                </div>

                                <div class="col-md-4 hide" id="new-follow-panel">
                                    <div class="panel panel-default">
                                        <div class="panel-heading "><i class="ti-plus"></i> @lang('modules.followup.newFollowUp')
                                            <div class="panel-action">
                                                <a href="javascript:;" id="hide-new-follow-panel"><i class="ti-close"></i></a>
                                            </div>
                                        </div>
                                        <div class="panel-wrapper collapse in">
                                            <div class="panel-body">
                                                {!! Form::open(['id'=>'createFollow','class'=>'ajax-form','method'=>'POST']) !!}

                                                {!! Form::hidden('lead_id', $lead->id) !!}

                                                <div class="form-body">
                                                    <div class="row">
                                                        <!--/span-->
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="control-label">@lang('app.next_follow_up')</label>
                                                                <input type="text" name="next_follow_up_date" autocomplete="off" id="next_follow_up_date"
                                                                       class="form-control">
                                                                <input type="hidden"  name="type" class="form-control datepicker" value="datetime">

                                                            </div>
                                                        </div>
                                                        <!--/span-->
                                                        <!--/span-->
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="control-label">@lang('app.description')</label>
                                                                <textarea id="remark" name="remark"
                                                                          class="form-control"></textarea>
                                                            </div>
                                                        </div>
                                                        <!--/span-->
                                                    </div>
                                                    <!--/row-->

                                                </div>
                                                <div class="form-actions">
                                                    <button type="submit" id="save-task" class="btn btn-success"><i
                                                                class="fa fa-check"></i> @lang('app.save')
                                                    </button>
                                                </div>
                                                {!! Form::close() !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 hide" id="edit-follow-panel">
                                </div>
                            </div>
                        </section>

                    </div><!-- /content -->
                </div><!-- /tabs -->
            </section>
        </div>


    </div>
    <!-- .row -->

@endsection

@push('footer-script')
<script src="{{ asset('js/cbpFWTabs.js') }}"></script>
<script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>

<script src="{{ asset('js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/moment/moment.js') }}"></script>
<script src="{{ asset('plugins/datetime-picker/datetimepicker.js') }}"></script><script type="text/javascript">
    //    (function () {
    //
    //        [].slice.call(document.querySelectorAll('.sttabs')).forEach(function (el) {
    //            new CBPFWTabs(el);
    //        });
    //
    //    })();

    var newTaskpanel = $('#new-follow-panel');
    var taskListPanel = $('#follow-list-panel');
    var editTaskPanel = $('#edit-follow-panel');

    $(".select2").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });

    //    save new task
    $('#save-task').click(function () {
        $.easyAjax({
            url: '{{route('admin.leads.follow-up-store')}}',
            container: '#createFollow',
            type: "POST",
            data: $('#createFollow').serialize(),
            formReset: true,
            success: function (data) {
                $('#listGroup').html(data.html);
                $("body").tooltip({
                    selector: '[data-toggle="tooltip"]'
                });
            }
        })
    });
    @if($lead->next_follow_up == 'yes')
        //    save new task
        taskListPanel.on('click', '.edit-task', function () {
            var id = $(this).data('follow-id');
            var url = "{{route('admin.leads.follow-up-edit', ':id')}}";
            url = url.replace(':id', id);

            $.easyAjax({
                url: url,
                type: "GET",
                data: {taskId: id},
                success: function (data) {
                    editTaskPanel.html(data.html);
                    taskListPanel.switchClass("col-md-12", "col-md-8", 1000, "easeInOutQuad");
                    newTaskpanel.addClass('hide').removeClass('show');
                    editTaskPanel.switchClass("hide", "show", 300, "easeInOutQuad");
                    $("body").tooltip({
                        selector: '[data-toggle="tooltip"]'
                    });
                }
            })
        });

    @endif

    //    save new task
    $('#sort-task, #hide-completed-tasks').change(function() {
        var sortBy = $('#sort-task').val();
        var id = $('#sort-task').data('lead-id');

        var url = "{{route('admin.leads.follow-up-sort')}}";
        var token = "{{ csrf_token() }}";

        $.easyAjax({
            url: url,
            type: "GET",
            data: {'_token': token, leadId: id, sortBy: sortBy},
            success: function (data) {
                $('#listGroup').html(data.html);
                $("body").tooltip({
                    selector: '[data-toggle="tooltip"]'
                });
            }
        })
    });

    $('#show-new-follow-panel').click(function () {
//    taskListPanel.switchClass('col-md-12', 'col-md-8', 1000, 'easeInOutQuad');
        taskListPanel.switchClass("col-md-12", "col-md-8", 1000, "easeInOutQuad");
        editTaskPanel.addClass('hide').removeClass('show');
        newTaskpanel.switchClass("hide", "show", 300, "easeInOutQuad");
    });

    $('#hide-new-follow-panel').click(function () {
        newTaskpanel.addClass('hide').removeClass('show');
        taskListPanel.switchClass("col-md-8", "col-md-12", 1000, "easeInOutQuad");
    });

    editTaskPanel.on('click', '#hide-edit-follow-panel', function () {
        editTaskPanel.addClass('hide').removeClass('show');
        taskListPanel.switchClass("col-md-8", "col-md-12", 1000, "easeInOutQuad");
    });

    jQuery('#next_follow_up_date').datetimepicker({
        format: 'DD/MM/Y HH:mm',
    });

</script>
@endpush