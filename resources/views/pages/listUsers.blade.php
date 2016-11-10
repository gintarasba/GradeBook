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
                                <table id="usersList" class="display" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Vardas</th>
                                            <th>Pavardė</th>
                                            <th>Prisijungimo vardas</th>
                                            <th>Asmens kodas</th>
                                            <th>Statusas</th>
                                            <th>Slaptažodis</th>
                                            <th>Paskutinis prisijungimas</th>
                                            <th>Užregistruotas</th>
                                            <th>Pareigos</th>
                                            <th>Valdymas</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>ID</th>
                                            <th>Vardas</th>
                                            <th>Pavardė</th>
                                            <th>Prisijungimo vardas</th>
                                            <th>Asmens kodas</th>
                                            <th>Statusas</th>
                                            <th>Slaptažodis</th>
                                            <th>Paskutinis prisijungimas</th>
                                            <th>Užregistruotas</th>
                                            <th>Pareigos</th>
                                            <th>Valdymas</th>
                                        </tr>
                                    </tfoot>
                                    <tbody id="usersEntries">

                                    @foreach($usersList AS $user)
                                        <tr id="userId_{{ $user->id }}">
                                            <td id="idEdit">{{ $user->id }}</td>
                                            <td id="nameEdit">{{ $user->name }}</td>
                                            <td id="second_nameEdit">{{ $user->second_name }}</td>
                                            <td id="loginNameEdit">{{ $user->loginName }}</td>
                                            <td id="pcodeEdit">{{ $user->pcode }}</td>
                                            <td id="statusEdit" level="{{ $user->level }}">
                                                @php
                                                switch( $user->level ) {
                                                    case 1:
                                                        echo 'Vartotojas';
                                                    break;

                                                    case 2:
                                                        echo 'Administratorius';
                                                    break;

                                                    default:
                                                        echo 'Vartotojas';
                                                    break;

                                                }
                                                @endphp
                                            </td>
                                            <td>Užkoduotas</td>
                                            <td>{{ $user->updated_at }}</td>
                                            <td>{{ $user->created_at }}</td>
                                            <td>
                                            <?php $list = array(); ?>
                                            @foreach($user->duties()->get() AS $duty)
                                                <?php $list[] = array('id' =>$duty->id,'title' => $duty->title); ?>
                                            @endforeach
                                            <?php echo json_encode($list); ?>
                                            </td>
                                            <td><button type="button" class="btn btn-primary btn-lg" onClick="openEditModal('{{ $user->id }}');" data-target="#editModal">
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

                                <button type="button" class="btn btn-primary btn-lg" onClick="openNewUserModal();" data-target="#newUserModal">
                                     Kūrti naują
                                </button>
                            </div>

                        </div>

                    </div>
                </div>


                @include('modals.userNew')
                @include('modals.userEdit')

            </section>
        </div>

    @endif



@endsection


@if(Auth::check())
    @section('javascripts')
        <script>
        function openNewUserModal() {
            $('#newUserModal').modal();
        }

        var passwordLoader;
        var loginLoader;
        var nameCss;
        var usersDataTable;

        $('.generatePass').on('click', function(event) {
            event.preventDefault();
            /* Act on the event */
            var button = this;
            var col = $($(button).parent());
            var row = $(col.parent());
            var passwordField = $(row.find('input[name=password]'));
            passwordLoader = Ladda.create( button );
            passwordLoader.toggle();
            generatePass(passwordField);
        });

        function generatePass(field) {
            $.ajax({
                url: "{{ route('generateNewPassword') }}",
                type: 'GET',
                dataType: 'json',
                data: { length: 8}
            }).always(function(out) {
                if(out.err == '') {
                    $(field).val(out.password);
                } else {

                }
                passwordLoader.stop();
            });
        }

        $('.generateLogIn').on('click', function(event) {
            event.preventDefault();
            /* Act on the event */
            var button = this;
            var col = $($(button).parent());
            var row = $(col.parent());
            var loginNameField = $(row.find('input[name=loginName]'));
            loginLoader = Ladda.create( button );
            loginLoader.toggle();
            generateLogin(loginNameField);

        });
        function generateLogin(field) {
            var name = $('#name_new');
            var second_name = $('#second_name_new');
            var errorName = $('#errName_new');
            var errorSName = $('#errSName_new');
            var err = 0;
            if(name.val().length < 1 & errorName.length == 0) {
                name.css('border', '1px solid red');
                name.after('<div id="errName_new" class="alert-danger">Prašome įvesti vardą!</div>');
                err = 1;
            } else {
                if(errorName.length)
                    $('#errName_new').remove();
                name.css('border','1px solid #d2d6de');
            }

            if(second_name.val().length < 2 & errorSName.length == 0) {
                second_name.css('border', '1px solid red');
                second_name.after('<div id="errSName_new" class="alert-danger">Prašome įvesti pavardę bent iš 2 simbolių!</div>');
                err = 2;
            }else {
                if(errorSName.length)
                    $('#errSName_new').remove();
                second_name.css('border','1px solid #d2d6de');
            }

            if(err == 0) {
                $.ajax({
                    url: "{{ route('generateNewLoginName') }}",
                    type: 'GET',
                    dataType: 'json',
                    data: { name: name.val(), second_name: second_name.val()}
                }).always(function(out) {
                    if(out.err == '') {
                        $(field).val(out.loginName);
                    } else {

                    }
                    loginLoader.stop();
                });
            }

        }


        $('.saveNew').on('click', function(event) {
            event.preventDefault();
            /* Act on the event */
            var modal = $('#newUserModal');
            var loginName = $(modal.find('#loginName_new'));
            var name = $(modal.find('#name_new'));
            var second_name = $(modal.find('#second_name_new'));
            var pcode = $(modal.find('#pcode_new'));
            var password = $(modal.find('#password_new'));
            var status = $(modal.find('#statusSelect_new'));
            var token = $('#_token_new');
            $.ajax({
                url: "{{ route('pNewUser') }}",
                type: 'get',
                dataType: 'json',
                data: {
                    _token: token.val(),
                    name: name.val(),
                    second_name:second_name.val(),
                    pcode: pcode.val(),
                    password: password.val(),
                    loginName: loginName.val(),
                    status: status.val(),
                 },
            }).always(function(out) {
                console.log(out);
                if(out.err == '') {
                    modal.modal('hide');
                    reloadUsersList();
                    //location.reload();
                } else if(out.err == 'Fields') {
                    for(var field in out.messages) {
                        var error = out.messages[field];
                        console.log(field, error);
                        if(field == 'name') {
                            if($('#'+field+'Err').length == 0) {
                                name.after('<div id="nameErr" class="alert-danger">'+error+'</div>');
                            }
                        }

                        if(field == 'second_name') {
                            if($('#'+field+'Err').length == 0) {
                                second_name.after('<div id="second_nameErr" class="alert-danger">'+error+'</div>');
                            }
                        }

                        if(field == 'pcode') {
                            if($('#'+field+'Err').length == 0) {
                                pcode.after('<div id="pcodeErr" class="alert-danger">'+error+'</div>');
                            }
                        }

                        if(field == "status") {
                            if($('#'+field+'Err').length == 0) {
                                pcode.after('<div id="statusErr" class="alert-danger">'+error+'</div>');
                            }
                        }

                    }
                }
            });

        });

        function reloadUsersList() {
            $.ajax({
                url: "{{ route('showUsersList') }}/json",
                type: 'get',
                dataType: 'json',
            }).always(function(out) {
                if(out.usersList) {

                    for(var key in out.usersList) {
                        var entry = out.usersList[key];
                        var exist = false;
                        usersDataTable.rows().every( function () {
                            var d = this.data();
                            if(entry.id == d[0]) {
                                exist = true;
                                return;
                            }
                            //this.invalidate(); // invalidate the data DataTables has cached for this row
                        } );
                        if(exist == false) {
                            lastRow = usersDataTable.data().count();

                            var level = '';
                            if(entry.level == 1) level = 'Vartotojas';
                            else if(entry.level == 2) level = 'Administratorius';
                            else level = 'Vartotojas';

                            usersDataTable.row.add( [ entry.id, entry.name, entry.second_name, entry.loginName, entry.pcode,
                                level, 'Užkoduotas', entry.updated_at, entry.created_at ,
                                '<button type="button" class="btn btn-primary btn-lg" onClick="openModal(\''+entry.id+'\');" data-target="#editModal">'+
                                     'Redaguoti'+
                                    '</button>'+
                                    '<button type="button" class="btn btn-danger btn-lg" onClick="" data-target="#deleteRow">'+
                                          'Trinti'+
                                    '</button>'
                            ] ).draw( false );
                        }
                    }



                //$('#usersEntries').html(html);

                }
            });

        }





        $('.saveEdit').on('click', function(event) {
            event.preventDefault();
            /* Act on the event */
            var id = $('#idUpdated');
            var row = $('#userId_'+id.val());
            rowData = usersDataTable.row(row).data();
            var modal = $('#userEditModal');
            var loginName = rowData[3];
            var name = rowData[1];
            var second_name = rowData[2];
            var pcode = rowData[4];
            var level = rowData[5];
            var password = $(modal.find('#password'));
            var dutySelect = $(modal.find('#dutySelect'));
            var _token = $('#_tokenEdit').val();
            $.ajax({
                url: "{{ route('updateUserData') }}",
                type: 'get',
                dataType: 'json',
                data: {
                    _token: _token,
                    id: id.val(),
                    name: name,
                    second_name:second_name,
                    pcode: pcode,
                    password: password.val(),
                    dutyId: dutySelect.val(),
                 },
            }).always(function(out) {
                console.log(out);
                if(out.success == true) {
                    $('.modal').modal('hide');
                    //location.reload();
                } else if(out.code == 'Fields') {
                    for(var field in out.messages) {
                        var error = out.messages[field];
                        console.log(field, error);
                        if(field == 'name') {
                            if($('#'+field+'Err').length == 0) {
                                name.after('<div id="nameErr" class="alert-danger">'+error+'</div>');
                            }
                        }

                        if(field == 'second_name') {
                            if($('#'+field+'Err').length == 0) {
                                second_name.after('<div id="second_nameErr" class="alert-danger">'+error+'</div>');
                            }
                        }

                        if(field == 'pcode') {
                            if($('#'+field+'Err').length == 0) {
                                pcode.after('<div id="pcodeErr" class="alert-danger">'+error+'</div>');
                            }
                        }

                    }
                }
            });

        });


        function detachDuty(dutyId, dutyTitle, userId) {
            swal({
                title: "Ar tikrai?",
                text: "Vartotojui bus atimtos ("+dutyTitle+") pareigos!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Taip, nuimti pareigas.",
                cancelButtonText: "Ne, atšaukti.",
                closeOnConfirm: false,
                closeOnCancel: false
            }, function(isConfirm){
                if (isConfirm) {
                    $.ajax({
                        url: "{{ route('detachDuty') }}",
                        type: 'GET',
                        dataType: 'json',
                        data: {userid: userId, dutyId: dutyId}
                    }).always(function(out) {
                        if(out.code == "1") {
                            $('.duty_'+userId+'_'+dutyId).remove();
                            swal("Atimta", "Pareigos atimtos!", "success");
                        }
                    });
                } else {
                    swal("Atšaukta", "Veiksmas atšauktas sėkmingai!", "error");
                }
            });
        }
        <!-- ------------------------- -->









            $(document).ready(function() {
                usersDataTable = $('#usersList').DataTable({
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
                            "targets": [ 9, 8, 7, 6 ],
                            "visible": false,
                            "searchable": false
                        },
                    ]

                });
            } );
        </script>
    @endsection
@endif
