@extends('layouts.app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-6 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> @lang($pageTitle)</h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-6 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                <li><a href="{{ route('admin.clients.index') }}">@lang($pageTitle)</a></li>
                <li class="active">@lang('app.menu.projects')</li>
            </ol>
        </div>
        <div class="col-lg-6 col-sm-8 col-md-8 col-xs-12 text-right">

            <a href="{{ route('admin.clients.edit',$client->id) }}"
               class="btn btn-outline btn-success btn-sm">@lang('modules.lead.edit')
                <i class="fa fa-edit" aria-hidden="true"></i></a>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection


@section('content')

    <div class="row">


        @include('admin.clients.client_header')

        <div class="col-md-12">

            <section>
                <div class="sttabs tabs-style-line">

                    @include('admin.clients.tabs')

                    <div class="content-wrap">
                        <section id="section-line-1" class="show">
                            <div class="row">


                                <div class="col-md-12">
                                    <div class="white-box">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>@lang('modules.client.projectName')</th>
                                                    <th>@lang('modules.client.startedOn')</th>
                                                    <th>@lang('modules.client.deadline')</th>
                                                    <th>@lang('app.action')</th>
                                                </tr>
                                                </thead>
                                                <tbody id="timer-list">
                                                @forelse($client->projects as $key=>$project)
                                                    <tr>
                                                        <td>{{ $key+1 }}</td>
                                                        <td>{{ ucwords($project->project_name) }}</td>
                                                        <td>{{ $project->start_date->format($global->date_format) }}</td>
                                                        <td>@if($project->deadline){{ $project->deadline->format($global->date_format) }}@else - @endif</td>
                                                        <td><a href="{{ route('admin.projects.show', $project->id) }}" class="btn btn-info btn-outline btn-sm">@lang('modules.client.viewDetails')</a></td>
                                                    </tr>
                                                @empty

                                                    <tr>
                                                        <td colspan="5" class="text-center">
                                                            <div class="empty-space" style="height: 200px;">
                                                                <div class="empty-space-inner">
                                                                    <div class="icon" style="font-size:30px"><i
                                                                                class="icon-layers"></i>
                                                                    </div>
                                                                    <div class="title m-b-15">@lang('messages.noProjectFound')
                                                                    </div>
                                                                    <div class="subtitle">
                                                                        <a href="{{route('admin.projects.create')}}" type="button" class="btn btn-info"><i
                                                                                    class="zmdi zmdi-arrow-left"></i>
                                                                            @lang('modules.client.assignProject')
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                                </tbody>
                                            </table>
                                        </div>
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
        $('ul.showClientTabs .clientProjects').addClass('tab-current');
    </script>
@endpush