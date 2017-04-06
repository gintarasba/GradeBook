@extends('layouts.master')

@section('title')
    ProjectKK
@endsection

@section('content')

    @if(!Auth::check())
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper niceBg"  style="margin-left: 0;">
        <!-- Main content -->
            <section class="content">
                <div class="row">
                    @include('includes.login')
                </div>
            </section>

        </div>
    @else
        @php
        Auth::user()->updated_at = new \Datetime();
        Auth::user()->save();

        @endphp
        <div class="content-wrapper niceBg" >
            <section class="content ">
                <div class="row">
                    <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                        @include('includes.aside')
                    </div>

                    <div class="col-lg-10 col-md-10 col-sm-6 col-xs-12">
                        <div class="row">
                            <ol class="breadcrumb">
                              <li class="breadcrumb-item active"><a href="{{ route('home') }}">Pagrindinis</a></li>
                            </ol>

                            <div class="col-md-3">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <div class="row">
                                            <div class="col-xs-3">
                                                <i class="fa fa-user fa-5x"></i>
                                            </div>
                                            <div class="col-xs-9 text-right">
                                                <div class="huge">{{ $usersCount }}</div>
                                                <div>Viso vartotojų</div>
                                            </div>
                                        </div>
                                    </div>

                                @if(Auth::user()->isAdmin())
                                    <a href="{{ route('showUsersList') }}">
                                        <div class="panel-footer">
                                            <span class="pull-left">Valdyti</span>
                                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                            <div class="clearfix"></div>
                                        </div>
                                    </a>
                                @endif
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="panel panel-green">
                                    <div class="panel-heading">
                                        <div class="row">
                                            <div class="col-xs-3">
                                                <i class="fa fa-group fa-5x"></i>
                                            </div>
                                            <div class="col-xs-9 text-right">
                                                <div class="huge">{{ $groupsCount }}</div>
                                                <div>Viso grupių</div>
                                            </div>
                                        </div>
                                    </div>
                                @if(Auth::user()->isAdmin())
                                    <a href="{{ route('newGroupsForm') }}">
                                        <div class="panel-footer">
                                            <span class="pull-left">Valdyti</span>
                                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                            <div class="clearfix"></div>
                                        </div>
                                    </a>
                                @endif
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="panel panel-red">
                                    <div class="panel-heading">
                                        <div class="row">
                                            <div class="col-xs-3">
                                                <i class="fa fa-quote-left fa-5x"></i>
                                            </div>
                                            <div class="col-xs-9 text-right">
                                                <div class="huge">{{ $subjectsCount }}</div>
                                                <div>Viso dalykų/paskaitų</div>
                                            </div>
                                        </div>
                                    </div>
                                @if(Auth::user()->isAdmin())
                                    <a href="{{ route('newSubjectForm') }}">
                                        <div class="panel-footer">
                                            <span class="pull-left">Valdyti</span>
                                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                            <div class="clearfix"></div>
                                        </div>
                                    </a>
                                @endif
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="panel panel-yellow">
                                    <div class="panel-heading">
                                        <div class="row">
                                            <div class="col-xs-3">
                                                <i class="fa fa-quote-left fa-5x"></i>
                                            </div>
                                            <div class="col-xs-9 text-right">
                                                <div class="huge">{{ $conversationsCount }}</div>
                                                <div>Viso pokalbių</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="panel panel-success">
                                    <div class="panel-heading">
                                        <div class="row">
                                            <div class="col-xs-3">
                                                <i class="fa fa-quote-left fa-5x"></i>
                                            </div>
                                            <div class="col-xs-9 text-right">
                                                <div class="huge">{{ $messagesCount }}</div>
                                                <div>Viso žinučių</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </section>
        </div>
    @endif
@endsection


@if(Auth::check())
    @section('javascripts')
        <script src="{{ URL::to('AdminLTE-2.3.5/dist/js/custom.js') }}"></script>

    @endsection
@endif
