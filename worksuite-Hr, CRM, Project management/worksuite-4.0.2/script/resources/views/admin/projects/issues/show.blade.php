@extends('layouts.app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> @lang($pageTitle) #{{ $project->id }} - <span
                        class="font-bold">{{ ucwords($project->project_name) }}</span></h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li><a href="{{ route('admin.projects.index') }}">@lang($pageTitle)</a></li>
                <li class="active">@lang('app.menu.issues')</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@section('content')

    <div class="row">
        <div class="col-md-12">

            <section>
                <div class="sttabs tabs-style-line">
                    <div class="white-box">
                        <nav>
                            <ul>
                                <li ><a href="{{ route('admin.projects.show', $project->id) }}"><span>@lang('modules.projects.overview')</span></a>
                                </li>
                                <li><a href="{{ route('admin.project-members.show', $project->id) }}"><span>@lang('modules.projects.members')</span></a></li>
                                <li><a href="{{ route('admin.tasks.show', $project->id) }}"><span>@lang('app.menu.tasks')</span></a></li>
                                <li><a href="{{ route('admin.files.show', $project->id) }}"><span>@lang('modules.projects.files')</span></a>
                                </li>
                                <li><a href="{{ route('admin.invoices.show', $project->id) }}"><span>@lang('app.menu.invoices')</span></a></li>
                                <li class="tab-current"><a href="{{ route('admin.issues.show', $project->id) }}"><span>@lang('app.client') @lang('app.menu.issues')</span></a></li>
                                <li><a href="{{ route('admin.time-logs.show', $project->id) }}"><span>@lang('app.menu.timeLogs')</span></a></li>
                            </ul>
                        </nav>
                    </div>
                    <div class="content-wrap">
                        <section id="section-line-3" class="show">
                            <div class="row">
                                <div class="col-md-12" id="issues-list-panel">
                                    <div class="white-box">
                                        <h2>@lang('app.client') @lang('app.menu.issues')</h2>

                                        <ul class="list-group" id="issues-list">

                                            @forelse($project->issues as $issue)
                                                <li class="list-group-item">
                                                <div class="row">
                                                    <div class="col-md-5 col-md-offset-7 text-right">
                                                        <span class="btn btn-xs btn-info btn-rounded">{{ $issue->created_at->format('d M, y') }}</span>
                                                        <i class="text-muted">by {{ ucwords($project->client->name) }}</i>
                                                        <span class="@if($issue->status == 'pending') text-danger @else text-success @endif m-l-10">
                                                            <i class="fa @if($issue->status == 'pending') fa-exclamation-circle @else fa-check-circle @endif"></i> @if($issue->status == 'pending') @lang('modules.issues.pending') @else @lang('modules.issues.resolved') @endif
                                                        </span>
                                                        @if($issue->status == 'pending')
                                                            <a href="javascript:;" class="btn btn-primary btn-xs btn-outline m-l-10 change-status" data-issue-id="{{ $issue->id }}" data-new-status="resolved">@lang('modules.issues.markResolved')</a>
                                                        @else
                                                            <a href="javascript:;" class="btn btn-primary btn-xs btn-outline m-l-10 change-status" data-issue-id="{{ $issue->id }}" data-new-status="pending">@lang('modules.issues.markPending')</a>
                                                        @endif

                                                    </div>
                                                </div>

                                                <div class="row m-t-20">
                                                    <div class="col-md-12">
                                                        {{ nl2br($issue->description) }}
                                                    </div>
                                                </div>
                                            </li>
                                            @empty
                                                <li class="list-group-item">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            @lang('messages.noIssue')
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforelse
                                        </ul>
                                    </div>
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
<script>
    $('body').on('click', '.change-status', function () {
        var id = $(this).data('issue-id');
        var url = "{{ route('admin.issues.update',':id') }}";
        url = url.replace(':id', id);

        var status = $(this).data('new-status');
        var token = "{{ csrf_token() }}";

        $.easyAjax({
            type: 'POST',
            url: url,
            data: {'_token': token, '_method': 'PUT', 'status': status},
            success: function (response) {
                if (response.status == "success") {
                    $.unblockUI();
//                                    swal("Deleted!", response.message, "success");
                    $('#issues-list-panel ul.list-group').html(response.html);

                }
            }
        });
    });
</script>
@endpush
