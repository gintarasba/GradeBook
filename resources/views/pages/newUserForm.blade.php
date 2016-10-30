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



        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper niceBg">
            <section class="content">
                @include('includes.aside')

                <div class="row">
                    <div class="col-md-4 col-md-offset-4">
                        <div class="register-box-body">
                            <p class="login-box-msg">Naujo vartotojo kūrimas</p>

                            <form action="{{ route('newUser') }}" method="post">
                                {!! csrf_field() !!}
                                <div class="form-group has-feedback" >
                                    <input name="name" id="name" type="text"  class="form-control" placeholder="Vardas" >
                                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                                </div>

                                <div class="form-group has-feedback" >
                                    <input name="second_name" id="second_name" type="text"  class="form-control" placeholder="Pavardė" >
                                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                                </div>

                                <div class="form-group has-feedback" >
                                    <input name="pcode" id="pcode" type="text"  class="form-control" placeholder="Asmens kodas" >
                                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                                </div>

                                <div class="form-group has-feedback" >
                                    <select name="status" class="form-control">
                                        <option value="0" disabled>
                                            Pasirinkite statusą
                                        </option>
                                        <option value="1">
                                            Vartotojas
                                        </option>
                                        <option value="2">
                                            Administratorius
                                        </option>
                                    </select>
                                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                                </div>

                                <div class="row">
                                    <div class="col-sm-7">
                                        <div class="form-group has-feedback" >
                                            <input name="loginName" id="loginName" type="text"  class="form-control" placeholder="Prisijungimo vardas" >
                                            <span class="glyphicon glyphicon-user form-control-feedback"></span>
                                        </div>
                                    </div>

                                    <div class="col-sm-5">
                                        <button class="btn btn-primary ladda-button generateLogIn" data-style="expand-left"><span class="ladda-label">Generuoti</span><span class="ladda-spinner"></span><div class="ladda-progress" style="width: 0px;"></div></button>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-7">
                                        <div class="form-group has-feedback" >
                                            <input name="password" id="password" type="text"  class="form-control" placeholder="Slaptažodis" >
                                            <span class="glyphicon glyphicon-user form-control-feedback"></span>
                                        </div>
                                    </div>

                                    <div class="col-sm-5">
                                        <button class="btn btn-primary ladda-button generatePass" data-style="expand-left"><span class="ladda-label">Generuoti</span><span class="ladda-spinner"></span><div class="ladda-progress" style="width: 0px;"></div></button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-4">
                                        <button type="submit" class="btn btn-primary btn-block btn-flat">Kurti</button>
                                    </div>
                                </div>
                            </form>
                            @if(count($errors->pCreate->all()) || count($errors->csrf_error))
                            <div class="alert alert-warning">
                              <ul>
                                @foreach ($errors->pCreate->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                                @foreach ($errors->csrf_error->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                              </ul>
                            </div>
                            @endif

                            @if(Session::has('successReg'))
                                    <div class="alert alert-warning">{{ Session::get('successReg') }}</div>
                            @endif
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
        <script>
            var passwordLoader;
            var loginLoader;
            var nameCss;
            $('.generateLogIn').on('click', function(event) {
                event.preventDefault();
                /* Act on the event */
                loginLoader = Ladda.create( document.querySelector( '.generateLogIn' ) );
                loginLoader.toggle();
                generateLogin();

            });

            $('.generatePass').on('click', function(event) {
                event.preventDefault();
                /* Act on the event */
                passwordLoader = Ladda.create( document.querySelector( '.generatePass' ) );
                passwordLoader.toggle();
                generatePass();
            });

            function generateLogin() {
                var name = $('#name');
                var second_name = $('#second_name');
                var errorName = $('#errName');
                var errorSName = $('#errSName');
                var err = 0;
                if(name.val().length < 1 & errorName.length == 0) {
                    name.css('border', '1px solid red');
                    name.after('<div id="errName" class="alert-danger">Prašome įvesti vardą!</div>');
                    err = 1;
                } else {
                    if(errorName.length)
                        $('#errName').remove();
                    name.css('border','1px solid #d2d6de');
                }

                if(second_name.val().length < 2 & errorSName.length == 0) {
                    second_name.css('border', '1px solid red');
                    second_name.after('<div id="errSName" class="alert-danger">Prašome įvesti pavardę bent iš 2 simbolių!</div>');
                    err = 2;
                }else {
                    if(errorSName.length)
                        $('#errSName').remove();
                    second_name.css('border','1px solid #d2d6de');
                }

                if(err == 0) {
                    $.ajax({
                        url: "{{ route('generateLogin') }}",
                        type: 'GET',
                        dataType: 'json',
                        data: { name: name.val(), second_name: second_name.val()}
                    }).always(function(out) {
                        console.log(out);
                        if(out.err == '') {
                            $('#loginName').val(out.loginName);
                        } else {

                        }
                        loginLoader.stop();
                    });
                }

                loginLoader.stop();
            }
            function generatePass() {
                $.ajax({
                    url: "{{ route('generatePass') }}",
                    type: 'GET',
                    dataType: 'json',
                    data: { length: 8}
                }).always(function(out) {
                    if(out.err == '') {
                        $('#password').val(out.password);
                    } else {

                    }
                    passwordLoader.stop();
                });
            }
        </script>
    @endsection
@endif
