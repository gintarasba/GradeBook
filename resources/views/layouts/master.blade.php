<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>@yield('title')</title>

        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.6 -->
        <link rel="stylesheet" href="{{ URL::to('AdminLTE-2.3.5/bootstrap/css/bootstrap.min.css') }}">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="{{ URL::to('AdminLTE-2.3.5/dist/css/AdminLTE.min.css') }}">
        <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
              page. However, you can choose any other skin. Make sure you
              apply the skin class to the body tag so the changes take effect.
        -->
        <link rel="stylesheet" href="{{ URL::to('AdminLTE-2.3.5/dist/css/skins/skin-blue.min.css') }}">
        <link rel="stylesheet" href="{{ URL::to('AdminLTE-2.3.5/dist/css/sidemenu.css') }}">
        <link rel="stylesheet" href="{{ URL::to('AdminLTE-2.3.5/dist/css/custom.blade.css') }}">

        <!-- iCheck -->
        <link rel="stylesheet" href="{{ URL::to('AdminLTE-2.3.5/plugins/iCheck/square/blue.css') }}">

        <link rel="stylesheet" href="{{ URL::to('plugins/DataTables/css/jquery.datatables.min.css') }}">

        <link rel="stylesheet" href="{{ URL::to('plugins/sweetAlert/sweetalert2.css') }}">
        <link rel="stylesheet" href="{{ URL::to('plugins/ladda/css.css') }}">
        <link rel="stylesheet" href="{{ URL::to('AdminLTE-2.3.5/dist/css/suggest.css') }}">
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->


        @yield('styles')
    </head>
    <body class="hold-transition skin-blue">

            @yield('content')




        <!-- REQUIRED JS SCRIPTS -->

        <!-- jQuery 2.2.3 -->
        <script src="{{ URL::to('AdminLTE-2.3.5/plugins/jQuery/jquery-2.2.3.min.js') }}"></script>
        <!-- Bootstrap 3.3.6 -->
        <script src="{{ URL::to('AdminLTE-2.3.5/bootstrap/js/bootstrap.min.js') }}"></script>
        <!-- AdminLTE App -->
        <script src="{{ URL::to('AdminLTE-2.3.5/dist/js/app.min.js') }}"></script>

        <!-- iCheck -->
        <script src="{{ URL::to('AdminLTE-2.3.5/plugins/iCheck/icheck.min.js') }}"></script>

        <script src="{{ URL::to('plugins/DataTables/datatables.min.js') }}"></script>

        <script src="{{ URL::to('plugins/sweetAlert/sweetalert2.min.js') }}"></script>
        <script src="{{ URL::to('plugins/ladda/spin.js') }}"></script>
        <script src="{{ URL::to('plugins/ladda/ladda.js') }}"></script>
        <script src="{{ URL::to('AdminLTE-2.3.5/dist/js/suggest.js') }}"></script>
        @yield('javascripts')
        <script>
            $(function () {
                $('input').iCheck({
                    checkboxClass: 'icheckbox_square-blue',
                    radioClass: 'iradio_square-blue',
                    increaseArea: '20%' // optional
                });
            });
        </script>
    </body>



</html>
