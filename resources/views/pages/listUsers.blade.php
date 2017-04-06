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
                        <div class="panel panel-primary">
                            <div class="panel-heading">Vartotojų sąrašas</div>
                            <div class="panel-body">
                                <table id="usersList" class="display" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Prisijungimo vardas</th>
                                            <th>Pilnas vardas</th>
                                            <th>Asmens kodas</th>
                                            <th>Veiksmai</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>ID</th>
                                            <th>Prisijungimo vardas</th>
                                            <th>Pilnas vardas</th>
                                            <th>Asmens kodas</th>
                                            <th>Veiksmai</th>
                                        </tr>
                                    </tfoot>
                                    <tbody id="usersEntries">
                                    @php
                                    $usersList = array();
                                    @endphp
                                    @foreach($usersList AS $user)
                                        <tr id="userId_{{ $user->id }}">
                                            <td id="idEdit">{{ $user->id }}</td>
                                            <td id="loginNameEdit">{{ $user->loginName }}</td>
                                            <td id="nameEdit">{{ $user->name }} {{ $user->second_name }}</td>
                                            <td id="pcodeEdit">{{ $user->pcode }}</td>
                                            <td><button type="button" class="btn btn-primary btn-lg" onClick="openEditModal('{{ $user->id }}');" data-target="#editModal">
                                                 <i class="fa fa-pencil"></i>
                                                </button>
                                                <button type="button" class="btn btn-danger btn-lg"
                                                    onClick="openDeliteSwal('{{ $user->id }}', '{{ $user->loginName}}', '{{ $user->name }} {{ $user->second_name }}' );">
                                                      <i class="fa fa-trash"></i>
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
        function openDeliteSwal(userId, userLoginName, userFullName) {
            swal({
              title: "Ar tikrai?",
              text: "Ištryne vartotoja("+userLoginName+":"+userFullName+") nebelagėsite jo susigražinti!",
              type: "warning",
              showCancelButton: true,
              confirmButtonColor: "#DD6B55",
              confirmButtonText: "Taip, trinti!",
              cancelButtonText: "Atšaukti",
              closeOnConfirm: false
            },
            function(){
                $.ajax({
                    url: "{{ route('dellUserComp') }}",
                    type: 'GET',
                    dataType: 'JSON',
                    data: {userId: userId}
                }).always(function(out) {
                    console.log("complete", out);
                    if(out.success) {
                        swal("Ištrinta!", "Jūsų vartotojas buvo ištrintas", "success");

                    } else {
                        var element = $('<div class="alert alert-warning newAllert">'+out.message+'</div>');
                        $('.text-muted ').prepend(element);
                        element.delay( 1000 ).fadeOut( "300", function() {
                            element.remove();
                        });
                    }
                });


            });
        }

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

        $('.addSubject').on('click', function(event) {
            var selected = $('#subject_SList').val();
            var userId = $('#idUpdated').val();
            $.ajax({
                url: "{{ route('attachSubjectToUser') }}",
                type: 'GET',
                dataType: 'json',
                data: {subj_id: selected, user_id: userId}
            }).always(function(out) {
                if(out.success) {
                    var subjectHtml = "<tr id=\"sbj_row_"+out.subject.id+"\">"+
                    "<td>"+out.subject.id+"</td>"+
                    "<td>"+out.subject.title+"</td>"+
                    '<td><button type="button" class="btn btn-danger btn-xs" onclick="detachSubject(this, \''+userId+'\',\''+out.subject.id+'\');"><i class="fa fa-trash"></i></button></td>'+
                    "</tr>";
                    $('.subjectsList').append(subjectHtml);
                    var element = $('<div class="alert alert-success newAllert">Pridėta!</div>');
                    $('.dutySubjectsParent').prepend(element);
                    element.delay( 1000 ).fadeOut( "300", function() {
                        element.remove();
                    });
                } else {
                    if(out.code == '2UP') {
                        if($('.newAllert').length <= 0) {
                            var element = $('<div class="alert alert-warning newAllert">'+out.message+'</div>');
                            $('.dutySubjectsParent').prepend(element);
                            element.delay( 2000 ).fadeOut( "300", function() {
                                element.remove();
                            });

                        }
                    }
                }
            });
        });

        $('.addGroup').on('click', function(event) {
            var selected = $('#group_SList').val();
            var userId = $('#idUpdated').val();
            $.ajax({
                url: "{{ route('attachGroupToUser') }}",
                type: 'GET',
                dataType: 'json',
                data: {group_id: selected, user_id: userId}
            }).always(function(out) {
                if(out.success) {
                    $('#group_SList option[value="' + out.group.id + '"]').remove();
                    var groupHtml = "<tr id=\"group_row_"+out.group.id+"\">"+
                    "<td>"+out.group.id+"</td>"+
                    "<td>"+out.group.title+"</td>"+
                    '<td><button type="button" class="btn btn-danger btn-xs" onclick="detachGroup(this, \''+userId+'\',\''+out.group.id+'\');"><i class="fa fa-trash"></i></button></td>'+
                    "</tr>";
                    $('.groupsList').append(groupHtml);
                    var element = $('<div class="alert alert-success newAllert">Pridėta!</div>');
                    $('.groupParent').prepend(element);
                    element.delay( 1000 ).fadeOut( "300", function() {
                        element.remove();
                    });
                } else {
                    if(out.code == '2UP') {
                        if($('.newAllert').length <= 0) {
                            var element = $('<div class="alert alert-warning newAllert">'+out.message+'</div>');
                            $('.groupParent').prepend(element);
                            element.delay( 2000 ).fadeOut( "300", function() {
                                element.remove();
                            });
                        }
                    }
                }
            });
        });

        function detachSubject(me, userId, subjId) {
            $.ajax({
                url: "{{ route('detachFromSubject') }}",
                type: 'GET',
                dataType: 'json',
                data: {userId: userId, subjectId: subjId}
            }).always(function(out) {
                if(out.success) {
                    var element = $('<div class="alert alert-success newAllert">Atimta!</div>');
                    $('.groupParent').prepend(element);
                    element.delay( 1000 ).fadeOut( "300", function() {
                        element.remove();
                    });
                    $('#sbj_row_' + subjId).remove();
                }
            });

        }

        function detachGroup(me, userId, groupId) {
            $.ajax({
                url: "{{ route('detachFromGroup') }}",
                type: 'GET',
                dataType: 'json',
                data: {userId: userId, groupId: groupId}
            }).always(function(out) {
                if(out.success) {
                    var element = $('<div class="alert alert-success newAllert">Atimta!</div>');
                    $('.groupParent').prepend(element);
                    element.delay( 1000 ).fadeOut( "300", function() {
                        element.remove();
                    });
                    $('#group_row_' + groupId).remove();
                    $('#group_SList').append('<option value="'+groupId+'">'+out.group.title+'</option>');
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
            var name = $(modal.find('#name'));
            var second_name = $(modal.find('#second_name'));
            var pcode = $(modal.find('#pcode'));
            var password = $(modal.find('#password'));
            var dutySelect = $(modal.find('#dutySelect'));
            var _token = $('#_tokenEdit').val();
            var statusSelect = $(modal.find('#statusSelect'));
            $.ajax({
                url: "{{ route('updateUserData') }}",
                type: 'get',
                dataType: 'json',
                data: {
                    _token: _token,
                    id: id.val(),
                    name: name.val(),
                    second_name: second_name.val(),
                    pcode: pcode.val(),
                    password: password.val(),
                    dutyId: dutySelect.val(),
                    level: statusSelect.val()
                 },
            }).always(function(out) {
                if(out.success == true) {
                    $('.modal').modal('hide');
                } else if(out.code == 'Fields') {
                    for(var field in out.messages) {
                        var error = out.messages[field];
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
                        "loadingRecords": "<i class=\"glyphicon glyphicon-repeat gly-spin\"></i>",
                        "processing":     "<i class=\"glyphicon glyphicon-repeat gly-spin\"></i>",
                    },
                    "ajax": "{{ route('showUsersList') }}/json",
                    "columns": [
                        { "data": "id" },
                        { "data": "loginName" },
                        { "data": "fullName" },
                        { "data": "pcode" },
                        { "data": "action"},
                    ],
                    "processing": true, // you have to set this to true as well

                });
            } );
        </script>
    @endsection
@endif
