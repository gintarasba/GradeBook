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
                    <div class="col-md-10 col-md-offset-2">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                
                            </div>

                        </div>

                    </div>
                </div>

                <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">Redaguoti vartotoja: <span id="loginNameTitle">login_name</span></h4>
                      </div>
                      <div class="modal-body">
                          <form id="editForm">
                              <input type="hidden" name="_token" value="{{ csrf_token() }}"/ />
                              <input name="idUpdated" id="idUpdated" type="hidden"  class="form-control" placeholder="" value="" >
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
                                  <select name="status" id="statusSelect" class="form-control">
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


                              <div class="form-group has-feedback" >
                                  <input name="loginName" id="loginName" type="text"  class="form-control" placeholder="Prisijungimo vardas" >
                                  <span class="glyphicon glyphicon-user form-control-feedback"></span>
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
                           </form>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Atšaukti</button>
                        <button type="button" class="btn btn-primary saveEdit">Išsaugoti pakeitimus</button>
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
        <script>

        </script>
    @endsection
@endif
