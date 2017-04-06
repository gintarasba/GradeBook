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
                                <table id="dutiesList" class="display" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>pavadinimas</th>
                                            <th>Leidimai</th>
                                            <th>Valdymas</th>
                                            <th>Info</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>ID</th>
                                            <th>pavadinimas</th>
                                            <th>Leidimai</th>
                                            <th>Valdymas</th>
                                            <th>Info</th>
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
                                            <td>
                                                <button type="button" class="btn btn-primary btn-lg" onClick="openModalEdit('{{ $duty->id }}');" data-target="#dutyEditModal">
                                                  Redaguoti
                                                </button>
                                            </td>
                                            <td style="display: none;">
                                            @php
                                                $allInfo = array();
                                                $allInfo['dutyInfo'] = $duty;

                                                foreach(App\Permit::all() AS $permit) {
                                                    $allInfo['permitsList'][$permit->code] = array(
                                                        'id' => $permit->id,
                                                        'title' => $permit->title,
                                                        'code' => $permit->code,
                                                        'comment' => $permit->comment,
                                                        'checked' => false,
                                                    );
                                                 }

                                                foreach($duty->permits()->get() AS $permit) {
                                                    $allInfo['permitsList'][$permit->code] = array(
                                                        'id' => $permit->id,
                                                        'title' => $permit->title,
                                                        'code' => $permit->code,
                                                        'comment' => $permit->comment,
                                                        'checked' => true,
                                                    );
                                                 }
                                                 echo json_encode($allInfo);
                                            @endphp
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


            @include('modals.dutyEdit')

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
            var dutiesDataTable;


            $('.saveEdit').on('click', function(event) {
                console.log('saving');
                event.preventDefault();
                /* Act on the event */
                var editTitle = $($('#dutyEditModal').find('#title'));
                var dutyId = $('#editDutyId');
                var row = $('#dutyId_'+dutyId.val());
                var array = $('#dutyEditModal input[type=checkbox]:checked').map(function(_, el) {
                    return $(el).val();
                }).get();
                var list = '';
                for(var key in array) {
                    var elm = array[key];
                    list += elm + ",";
                }
                list = list.substr(0, list.length-1);


                $.ajax({
                    url: "{{ route('updateDuty') }}",
                    type: 'GET',
                    dataType: 'json',
                    data: {dutyId: dutyId.val(), dutyTitle: editTitle.val(), permits: list}
                }).always(function(out) {
                    if(out.success == true) {
                        var data = dutiesDataTable.row(row).data();
                        data[4] = JSON.stringify(out.allInfo);

                        var html = '';
                        // re draw small duty
                        for(var key in out.allInfo.permitsList) {
                            var permit = out.allInfo.permitsList[key];
                            if(permit.checked == true) {
                                html += '<div class="smallDuty">'+
                                '<div class="title">'+permit.title+'</div>'+
                                '<div class="comment">'+permit.comment+'</div>'+
                                '</div>';
                            }
                        }
                        data[2] = html;
                        dutiesDataTable.row(row).data(data).draw();
                        dutiesDataTable.columns.adjust().draw();
                        $('#dutyEditModal').modal('hide');
                    } else {
                        var element = $('<div class="alert alert-warning newAllert">'+out.message+'</div>');
                        $('#editForm').prepend(element);
                        element.delay( 1000 ).fadeOut( "300", function() {
                            element.remove();
                        });
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
                dutiesDataTable = $('#dutiesList').DataTable({
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
                    },
                    "columnDefs": [
                        {
                            "targets": [ 4 ],
                            "visible": false,
                            "searchable": false
                        },
                    ]

                });
            } );
        </script>
    @endsection
@endif
