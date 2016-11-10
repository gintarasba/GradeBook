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

                                <table id="userGroups" class="display" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Pavadinimas</th>
                                            <th>Vartotojai priskirti grupei</th>
                                            <th>Vartotojai priskirti grupei json</th>
                                            <th>Valdymas</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>ID</th>
                                            <th>Pavadinimas</th>
                                            <th>Vartotojai priskirti grupei</th>
                                            <th>Vartotojai priskirti grupei json</th>
                                            <th>Valdymas</th>
                                        </tr>
                                    </tfoot>
                                    <tbody id="usersEntries">
                                    @foreach($groupsList AS $group)
                                        <tr id="groupId_{{ $group->id }}">
                                            <td>{{ $group->id }}</td>
                                            <td>{{ $group->title }}</td>
                                            <td>
                                            @php $jsonArray = array(); @endphp
                                            @foreach( $group->users()->get() AS $groupUser )
                                                @php $duty = $groupUser->duties()->first(); @endphp
                                                <div>{{ $groupUser->name }} ({{ $duty->title }})</div>
                                                @php $jsonArray[] = array('id'=> $groupUser->id, 'name' => $groupUser->name,
                                                    'dutyTitle' => $duty->title, 'dutyId' => $duty->id, 'groupId' => $group->id);
                                                @endphp
                                            @endforeach
                                            </td>
                                            <td>{{ json_encode($jsonArray) }}</td>
                                            <td>
                                                <button type="button" class="btn btn-primary btn-lg" onClick="openEditModal('{{ $group->id }}');" data-target="#editModal">
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

                                <button type="button" class="btn btn-primary btn-lg" onClick="openNewGroupModal();" data-target="#newUserModal">
                                     Kūrti naują grupę
                                </button>


                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="newGroupModal" tabindex="-1" role="dialog" aria-labelledby="newGroupModal" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">Kurti naują grupę</h4>
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

                @include('modals.groupEdit')

            </section>
        </div>


    @endif



@endsection


@if(Auth::check())
    @section('javascripts')
        <script>
        var groupsDataTable;
        $('.saveEdit').on('click', function() {
            $('#groupEditModal').modal('hide');
        });


        function openEditModal(groupId) {
            var row = $('#groupId_'+groupId);
            rowData = groupsDataTable.row(row).data();
            console.log(rowData);
            var groupTitle = rowData[1];
            var usersList  = JSON.parse(rowData[3]);

            $('#groupTitle').html(groupTitle);
            $('#idEdited').val(groupId);

            var html = '';
            for(var key in usersList) {
                var userInfo = usersList[key];
                html += '<tr id="userInGroup_'+userInfo.id+'"><td>'
                +userInfo.name+'</td><td>'+userInfo.dutyTitle+
                '</td><td><button type="button" class="btn btn-primary btn-xs" onClick="detachUserFromGroup(\''+groupId+'\',\''+userInfo.id+'\');" data-target="#editModal">X</button></td></tr>';
            }
            $('#usersList').html(html);

            $('#groupEditModal').modal()
        }

        function detachUserFromGroup(groupId, userId) {
            $.ajax({
                url: "{{ route('detachFromGroup') }}",
                type: 'GET',
                dataType: 'json',
                data: {userId: userId, groupId: groupId}
            }).always(function(out) {
                if(out.success) {
                    $('#userInGroup_'+userId).remove();
                }
            });
        }

        function openNewGroupModal() {
            $('#newGroupModal').modal();
        }


        $('.saveNew').on('click', function(event) {
            event.preventDefault();
            /* Act on the event */
            var modal = $('#newGroupModal');
            var token = $('#_tokenNew');
            var title = $(modal.find('#titleNew'));

            $.ajax({
                url: "{{ route('newGroupTitle') }}",
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





        function startSuggest(usersList, keyword) {
            html = '';
            list = [];
            if(keyword.length <= 0 ){
                $('#suggest').attr('style', 'display: none;').html('');
                return false;
            }
            if(usersList.length <= 0) {
                $('#suggest').attr('style', 'display: none;').html('');
                return false;
            }
            for(var key in usersList) {
                var user = usersList[key];
                list[key] = user.name + ' ' + user.second_name + '('+user.dutyTitle+')';
                html += '<div id="attached_'+user.id+'" onclick="replaceCurrent(\''+user.id+'\', \''+user.name+'\');">'+list[key]+'</div>';
            }
            $('#suggest').attr('style', '').html(html);
            console.log(list);

        }

        function replaceCurrent(userId, userName) {
            $('#suggestedId').html(userId);
            $('#users').val(userName);
            $('#suggest').attr('style', 'display: none;').html('');
        }


        $('.addButton').on('click', function() {
            event.preventDefault();
            /* Act on the event */
            var button = this;
            var userId = $('#suggestedId').html();
            var groupId = $('#idEdited').val();

            $.ajax({
                url: "{{ route('addUserToGroup') }}",
                type: 'GET',
                dataType: 'json',
                data: {userId: userId, groupId: groupId}
            }).always(function(out) {
                if(out.success) {
                    $('#usersList').append('<tr id="userInGroup_'+out.user.id+'"><td>'
                    +out.user.name+'</td><td>'+out.user.dutyTitle+
                    '</td><td><button type="button" class="btn btn-primary btn-xs" onClick="detachUserFromGroup(\''+groupId+'\',\''+out.user.id+'\');" data-target="#editModal">X</button></td></tr>');
                }
            });

        });

        $(document).ready(function() {
            groupsDataTable = $('#userGroups').DataTable({
                "language": {
                    "lengthMenu": "Rodyti _MENU_ įrašų per puslapį",
                    "zeroRecords": "Nėra sukurtų grupių - atsiprašome",
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
                        "targets": [ 3 ],
                        "visible": false,
                        "searchable": false
                    },
                ]
            });

            $('#users').on('keyup', function() {
                var keyword = $('#users').val();
                $.ajax({
                    url: "{{ route('getUsersListByKeyword') }}",
                    type: 'GET',
                    dataType: 'JSON',
                    data: {keyword: keyword}
                }).always(function(out) {
                    if(out.success == true) {
                        startSuggest(out.usersList, keyword);
                    }

                });
            });

        } );
        </script>
    @endsection
@endif
