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
                                <table id="dutiesList" class="display" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>pavadinimas</th>
                                            <th>Leidimai</th>
                                            <th>Paskutinis prisijungimas</th>
                                            <th>Užregistruotas</th>
                                            <th>Valdymas</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>ID</th>
                                            <th>pavadinimas</th>
                                            <th>Leidimai</th>
                                            <th>Paskutinis prisijungimas</th>
                                            <th>Užregistruotas</th>
                                            <th>Valdymas</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>

                                    @foreach($dutiesList AS $duty)
                                        <tr id="dutyId_{{ $duty->id }}">
                                            <td id="id">{{ $duty->id }}</td>
                                            <td id="title">{{ $duty->title }}</td>
                                            <td>

                                            @foreach($duty->permits()->get() AS $permit)
                                                <div class="smallDuty">
                                                    <div class="title">{{ $permit->title }}</div>
                                                    <div class="comment">{{ $permit->comment }}</div>
                                                </div>
                                            @endforeach
                                            </td>
                                            <td>{{ $duty->updated_at }}</td>
                                            <td>{{ $duty->created_at }}</td>
                                            <td>
                                                <button type="button" class="btn btn-primary btn-lg" onClick="openModalEdit('{{ $duty->id }}');" data-target="#dutyEditModal">
                                                  Redaguoti
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>


                                <button type="button" class="btn btn-primary btn-lg" onClick="openModal();" data-target="#newDutyModal">
                                     Kūrti naują
                                </button>

                            </div>
                        </div>
                    </div>
                </div>


                <div class="modal fade" id="dutyEditModal" tabindex="-1" role="dialog" aria-labelledby="dutyEditModal" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">Redaguokite pavadinima.</h4>
                      </div>
                      <div class="modal-body">
                          <form id="editForm">
                              <input type="hidden" name="_token" value="{{ csrf_token() }}"/ />
                              <input type="hidden" id="editDutyId" value=""/>
                              <div class="form-group has-feedback" >
                                  <input name="title" id="title" type="text" class="form-control" placeholder="Pavadinimas" >
                                  <span class="glyphicon glyphicon-user form-control-feedback"></span>
                              </div>

                           </form>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Atšaukti</button>
                        <button type="button" class="btn btn-primary saveEdit">Išsaugoti</button>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="modal fade" id="newDutyModal" tabindex="-1" role="dialog" aria-labelledby="newDutyModal" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">Įveskite pavadinima norint sukurti naują pareigą</h4>
                      </div>
                      <div class="modal-body">
                          <form id="editForm">
                              <input type="hidden" name="_token" value="{{ csrf_token() }}"/ />
                              <div class="form-group has-feedback" >
                                  <input name="title" id="title" type="text"  class="form-control" placeholder="Pavadinimas" >
                                  <span class="glyphicon glyphicon-user form-control-feedback"></span>
                              </div>

                           </form>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Atšaukti</button>
                        <button type="button" class="btn btn-primary save">Išsaugoti</button>
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
            function openModal() {
                $('#newDutyModal').modal()
            }

            function openModalEdit(editId) {
                var duty = $('#dutyId_' + editId);
                var title = $(duty.find('#title'));
                var editTitle = $($('#dutyEditModal').find('#title'));
                editTitle.val(title.html());
                $('#editDutyId').val(editId);
                $('#dutyEditModal').modal();

            }

            $('.saveEdit').on('click', function(event) {
                event.preventDefault();
                /* Act on the event */
                var editTitle = $($('#dutyEditModal').find('#title'));
                var dutyId = $('#editDutyId');
                var token = $('meta[name="_token"]').attr('content')
                $.ajax({
                    url: "{{ route('updateDuty') }}",
                    type: 'GET',
                    dataType: 'json',
                    data: {dutyId: dutyId.val(), dutyTitle: editTitle.val(), _token: token}
                }).always(function(out) {
                    if(out.err == '') {
                        $('#dutyEditModal').modal('hide');
                    }
                });


            });


            $('.save').on('click', function(event) {
                event.preventDefault();
                /* Act on the event */
                var title = $('#title');
                var token = $('meta[name="_token"]').attr('content')
                $.ajax({
                    url: "{{ route('createNewDuty') }}",
                    type: 'GET',
                    dataType: 'json',
                    data: {dutyTitle: title.val(), _token: token}
                }).always(function(out) {
                    if(out.err == '') {
                        $('#newDutyModal').modal('hide');
                    }
                });


            });


            $(document).ready(function() {
                $('#dutiesList').DataTable({
                    "language": {
                        "lengthMenu": "Rodyti _MENU_ įrašų per puslapį",
                        "zeroRecords": "Nėra sukurtų vartotojų - atsiprašome",
                        "info": "Rodomas puslapis _PAGE_ iš _PAGES_",
                        "infoEmpty": "Nėra įrašų",
                        "infoFiltered": "(filtruota iš _MAX_ įrašų)",
                        "paginate": {
                            "first":      "Pirmas",
                            "last":       "Paskutinis",
                            "next":       "Kitas",
                            "previous":   "Ankstesnis"
                        },
                        "search":         "Ieškoti:",
                        "loadingRecords": "Kraunama...",
                        "processing":     "Ruošiama...",
                    }

                });
            } );
        </script>
    @endsection
@endif
