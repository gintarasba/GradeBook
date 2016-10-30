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
        <div class="content-wrapper niceBg" >
            <section class="content ">
                @include('includes.aside')

            </section>
        </div>
    @endif



@endsection


@if(Auth::check())
    @section('javascripts')
        <script src="{{ URL::to('AdminLTE-2.3.5/dist/js/custom.js') }}"></script>

    @endsection
@endif
