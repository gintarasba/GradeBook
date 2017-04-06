@extends('layouts.master')

@section('title')
    ProjectKK
@endsection

@section('content')



<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper niceBg">
    <section class="content">
        <div class="row">
            <div class="col-md-2">
                @include('includes.aside')
            </div>

            <div class="col-md-10">
                <div class="row">
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a href="{{ route('home') }}">Pagrindinis</a></li>
                      <li class="breadcrumb-item active">Vartotojo profilis</li>
                    </ol>

                    <div class="col-md-3">

                    <!-- Profile Image -->
                    <div class="box box-success">
                        <div class="box-body box-profile parentProfileBox">
                            <img
                                class="profile-user-img img-responsive img-circle"
                                src="@php
                                    echo isset($userInfo->profilePicture)
                                    ? $userInfo->profilePicture
                                    : $defaults->defProfilePicture;
                                    @endphp"
                                alt="User profile picture">

                            <h3 class="profile-username text-center">{{ $userInfo->user->name }} {{ $userInfo->user->second_name }}</h3>

                            <p class="text-muted text-center">
                                @foreach($userInfo->duties AS $duty)
                                    {{ $duty->title }}<br/>
                                @endforeach
                            </p>

                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item">
                                  <b>Vardas</b> <a class="pull-right">{{ $userInfo->user->name }}</a>
                                </li>
                                <li class="list-group-item">
                                  <b>Pavardė</b> <a class="pull-right">{{ $userInfo->user->second_name }}</a>
                                </li>
                                @php
                                if($userInfo->isMe) {
                                echo '
                                    <li class="list-group-item">
                                        <b>El. paštas</b> <a class="pull-right">'.$userInfo->user->email.'</a>
                                    </li>
                                    <li class="list-group-item">
                                        <b>Prisijungimo vardas</b> <a class="pull-right">'.$userInfo->user->loginName.'</a>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="row ">
                                            <div class="col-md-7">
                                                <div class="form-group has-feedback" >
                                                    <label for="password">Slaptažodis</label>
                                                    <input name="password" id="password" type="text"  class="form-control" placeholder="Slaptažodis" >
                                                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                                                </div>
                                            </div>

                                            <div class="col-md-5">
                                                <label for="generatePass">Generavimas</label>
                                                <button class="btn btn-primary ladda-button generatePass" data-style="expand-left"><span class="ladda-label">Generuoti</span><span class="ladda-spinner"></span><div class="ladda-progress" style="width: 0px;"></div></button>
                                            </div>
                                        </div>
                                    </li>
                                    ';
                                }

                                @endphp
                            </ul>
                            @php
                            if($userInfo->isMe) {
                                echo '<button class="btn btn-block btn-primary saveInfoButton" disabled>Išsaugoti</button>';
                            }

                            @endphp

                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->

                    <!-- About Me Box -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Apie mane</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <strong><i class="fa fa-book margin-r-5"></i> Grupės</strong>

                            <p class="text-muted">
                                @php $groupsArray = array();
                                foreach($userInfo->groups AS $group) {
                                    $groupsArray[] =  $group->title;
                                }
                                echo implode(',', $groupsArray);
                                @endphp
                            </p>

                            <hr>


                            <strong><i class="fa fa-pencil margin-r-5"></i> Dalykai</strong>

                            <p>
                                @php $subjectsArray = array();
                                foreach($userInfo->subjects AS $subjct) {
                                    $subjectsArray[] =  $subjct->title;
                                }
                                echo implode(', ', $subjectsArray);
                                @endphp
                            </p>


                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->
                <div class="col-md-9">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#basic_info" data-toggle="tab">Statistika</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="active tab-pane" id="basic_info">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="panel panel-primary">
                                            <div class="panel-heading">
                                                <div class="row">
                                                    <div class="col-xs-3">
                                                        <i class="fa fa-quote-left fa-5x"></i>
                                                    </div>
                                                    <div class="col-xs-9 text-right">
                                                        <div class="huge">@php echo count($userInfo->groups); @endphp</div>
                                                        <div>Priskirtas grupėms</div>
                                                    </div>
                                                </div>
                                            </div>
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
                                                        <div class="huge">@php echo count($userInfo->subjects); @endphp</div>
                                                        <div>Viso pamokų/dalykų</div>
                                                    </div>
                                                </div>
                                            </div>
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
                                                        <div class="huge">@php echo count($userInfo->marks); @endphp</div>
                                                        <div>Viso pažymių</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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





@endsection



@section('javascripts')
    @php
    if($userInfo->isMe) {
    @endphp
        <script>
        var _token = '{{ Session::token() }}';
        var passwordLoader;
        $('.saveInfoButton').on('click', function() {
            var password = $('#password').val();

            $.ajax({
                url: "{{ route('updateUserInformation') }}",
                type: 'POST',
                dataType: 'json',
                data: {
                    _token: _token,
                    password: password
                }
            }).done(function(out) {
                console.log("complete", out);
                if(out.success == true) {
                    showMessageBox('.parentProfileBox', out.messages, 'success');
                    $('.saveInfoButton').attr('disabled', 'disabled');
                    clearInputsValues(['#password']);
                } else {
                    showMessageBox('.parentProfileBox', out.errors, 'danger', 2000);
                    $('.saveInfoButton').attr('disabled', 'disabled');
                    clearInputsValues(['#password']);
                }
            });

        });


        $('#password').on('change keyup paste',function() {
            if($(this).val().length > 0) {
                $('.saveInfoButton').removeAttr('disabled');
            } else {
                if(!$('.saveInfoButton').is(':disabled')) {
                    $('.saveInfoButton').attr('disabled', 'disabled');
                }
            }


        });

        $('.generatePass').on('click', function(event) {
            event.preventDefault();
            /* Act on the event */
            var button = this;
            var col = $($(button).parent());
            var row = $(col.parent());
            var passwordField = $(row.find('input[name=password]'));
            passwordLoader = Ladda.create( button );
            passwordLoader.start();
            generatePass(passwordField);
        });

        function generatePass(field) {
            $.ajax({
                url: "{{ route('generateNewPassword') }}/8",
                type: 'GET',
                dataType: 'json',
            }).always(function(out) {
                if(out.success == true) {
                    $(field).val(out.password);
                    $('.saveInfoButton').removeAttr('disabled');
                } else {

                }
                passwordLoader.stop();
            });
        }

        </script>
    @php
    }



    @endphp



    <script>

    </script>
@endsection
