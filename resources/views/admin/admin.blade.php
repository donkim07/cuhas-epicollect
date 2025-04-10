@extends('app')
@section('title', trans('site.admin'))
@section('page-name', 'admin')

@section('content')
    <div class="container-fluid page-admin">
        <h2 class="page-title">{{ trans('site.admin')}} Dashboard</h2>
        <div class="warning-well visible-xs-block">This section is best viewed on a larger screen</div>

        @include('toasts/success')
        @include('toasts/error')

        {{-- Nav tabs --}}
        <ul class="nav nav-tabs">
            <li role="presentation" @if($action == 'stats') class="active" @endif>
                <a href="{{ url('admin/stats') }}">{{trans('site.stats')}}
                </a>
            </li>
            <li role="presentation" @if($action == 'users') class="active" @endif>
                <a href="{{ url('admin/users') }}">{{trans('site.users')}}
                </a>
            </li>
            <li role="presentation" @if($action == 'projects') class="active" @endif>
                <a href="{{ url('admin/projects') }}">{{trans('site.projects')}}
                </a>
            </li>
            <li role="presentation" @if($action == 'settings') class="active" @endif>
                <a href="{{ url('admin/settings') }}">
                    Settings
                </a>
            </li>
            @if (Auth::user()->isSuperAdmin())
                <li role="presentation">
                    <a href="{{ url('admin/phpinfo') }}" target="_blank">
                        phpinfo()
                    </a>
                </li>
            @endif
        </ul>

        {{-- Tab panes --}}
        <div class="tab-content">
            @if($action == 'users')
                <div class="tab-pane active user-administration" id="user-administration">
                    @include('admin.tabs.users')
                </div>
            @elseif ($action == 'projects')
                <div class="tab-pane active projects" id="projects">
                    @include('admin.tabs.projects')
                </div>
            @elseif ($action == 'stats')
                <div class="tab-pane active stats" id="stats">
                    @include('admin.tabs.stats')
                </div>
            @elseif ($action == 'settings')
                <div class="tab-pane active stats" id="settings">
                    @include('admin.tabs.settings')
                </div>
            @endif
        </div>
    </div>

@stop
