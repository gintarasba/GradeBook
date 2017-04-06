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
                <div class="row">
                    <div class="col-md-2">
                        @include('includes.aside')
                    </div>

                    <div class="col-md-10">
                        <div class="panel panel-default">
                            <div class="panel-body">

                                <table id="userSubjects" class="display" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Pavadinimas</th>
                                            <th>Vartotojai</th>
                                            <th>Valdymas</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>ID</th>
                                            <th>Pavadinimas</th>
                                            <th>Vartotojai</th>
                                            <th>Valdymas</th>
                                        </tr>
                                    </tfoot>
                                    <tbody id="usersEntries">
                                    @foreach($subjectsList AS $subject)
                                        <tr>
                                            <td>{{ $subject->id }}</td>
                                            <td>{{ $subject->title }}</td>
                                            <td></td>
                                            <td>
                                                <button type="button" class="btn btn-primary btn-lg" onClick="openEditModal('{{ $subject->id }}');" data-target="#editModal">
                                                  Redaguoti
                                                </button>
                                                <button type="button" class="btn btn-danger btn-lg" onClick="" data-target="#deleteRow">
                                                      Trinti
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>

                                <button type="button" class="btn btn-primary btn-lg" onClick="openNewSubjectModal();" data-target="#newSubjectModal">
                                     Kūrti naują pamoką/dalyką
                                </button>


                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="newSubjectModal" tabindex="-1" role="dialog" aria-labelledby="newSubjectModal" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">Kurti naują pamoką/dalyką</h4>
                      </div>
                      <div class="modal-body">
                          <form id="editForm">
                              <input type="hidden" name="_tokenNew" id="_tokenNew" value="{{ csrf_token() }}"/ />
                              <input name="idUpdated" id="idUpdated" type="hidden"  class="form-control" placeholder="" value="" >
                              <div class="form-group has-feedback" >
                                  <input name="titleNew" id="titleNew" type="text"  class="form-control" placeholder="Pavadinimas" >
                                  <span class="glyphicon glyphicon-user form-control-feedback"></span>
                              </div>

                           </form>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Atšaukti</button>
                        <button type="button" class="btn btn-primary saveNew">Išsaugoti</button>
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
        var subjectsDataTable;


        function openNewSubjectModal() {
            $('#newSubjectModal').modal();
        }


        $('.saveNew').on('click', function(event) {
            event.preventDefault();
            /* Act on the event */
            var modal = $('#newSubjectModal');
            var token = $('#_tokenNew');
            var title = $(modal.find('#titleNew'));

            $.ajax({
                url: "{{ route('newSubjectTitle') }}",
                type: 'GET',
                dataType: 'json',
                data: {
                    _token: token.val(),
                    title: title.val(),
                }
            }).always(function(out) {
                if(out.success == true) {
                    modal.modal('hide');
                }
            });
        });


        $(document).ready(function() {
            subjectsDataTable = $('#userSubjects').DataTable({
                "language": {
                    "lengthMenu": "Rodyti _MENU_ įrašų per puslapį",
                    "zeroRecords": "Nėra sukurtų pamokų/dalykų - atsiprašome",
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
                },
            });
        } );
        </script>
    @endsection
@endif
