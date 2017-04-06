<div class="modal fade" id="userEditModal" tabindex="-1" role="dialog" aria-labelledby="userEditModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="myModalLabel">
            Redaguoti vartotoja: <span id="loginNameTitleEdit">login_name</span>
            <button class="btn btn-primary" onclick="goToProfile();">Eiti į profilį</button>
        </h4>
      </div>
      <div class="modal-body">
        <form id="editForm">
          <div class="row">
            <input type="hidden" name="_token" id="_tokenEdit" value="{{ csrf_token() }}"/ />
            <input name="idUpdated" id="idUpdated" type="hidden"  class="form-control" placeholder="" value="" >

            <div class="nav-tabs-custom">
              <ul class="nav nav-tabs">
                  <li class="active"><a href="#basic_info" data-toggle="tab">Duomenys</a></li>
                  <li><a href="#role" data-toggle="tab">Rolės</a></li>
                  <li><a href="#loginData" data-toggle="tab">Prisijungimo duomenys</a></li>
                  <li><a href="#duties" data-toggle="tab">Pareigos</a></li>
                  <li><a href="#subjects" data-toggle="tab">Pamokos</a></li>
                  <li><a href="#groups" data-toggle="tab">Grupės</a></li>
              </ul>
              <div class="tab-content">
                  <div class="active tab-pane" id="basic_info">
                      <div class="row">
                        <div class="col-sm-4">
                          <div class="form-group">
                              <label for="name">Vardas</label>
                              <input name="name" id="name" type="text"  class="form-control" placeholder="Vardas" >

                          </div>
                        </div>

                        <div class="col-sm-4">
                          <div class="form-group" >
                              <label for="second_name">Pavardė</label>
                              <input name="second_name" id="second_name" type="text"  class="form-control" placeholder="Pavardė" >

                          </div>
                        </div>

                        <div class="col-sm-4">
                         <div class="form-group" >
                              <label for="pcode">Asmens kodas</label>
                              <input name="pcode" id="pcode" type="text"  class="form-control" placeholder="Asmens kodas" >

                          </div>
                        </div>
                      </div>
                      <!-- basic_info -->
                  </div>

                  <div class="tab-pane" id="role">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group" >
                          <label for="status">Statusas</label>
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

                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="tab-pane" id="loginData">
                    <div class="row">
                      <div class="col-sm-4">
                        <div class="form-group" >
                            <label for="loginName">Prisijungimo vardas</label>
                            <input name="loginName" id="loginName" type="text"  class="form-control" placeholder="Prisijungimo vardas" >
                        </div>
                      </div>

                      <div class="col-sm-4 col-xs-6">
                        <div class="form-group" >
                            <label for="password">Slaptažodis</label>
                            <input name="password" id="password" type="text"  class="form-control" placeholder="Slaptažodis" >
                        </div>
                      </div>

                      <div class="col-sm-4 col-xs-6">
                        <label for="generatePass">Patvirtinimas</label><br/>
                        <button class="btn btn-primary ladda-button generatePass" data-style="expand-left"><span class="ladda-label">Generuoti</span><span class="ladda-spinner"></span><div class="ladda-progress" style="width: 0px;"></div></button>
                      </div>
                    </div>
                  </div>

                  <div class="tab-pane" id="duties">
                    <div class="row">
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label for="dutySelect">Pareigos</label>
                          <select name="duty" id="dutySelect" class="form-control">
                              <option value="-1">
                                  Pasirinkite pareigas
                              </option>
                              @foreach($dutiesList AS $duty)
                              <option value="{{ $duty->id }}">
                                  {{ $duty->title }}
                              </option>
                          @endforeach
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="tab-pane" id="subjects">
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group dutySubjects">
                            <label for="subjectsList">Pamokos/Dalykai</label>
                            <table style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Pavadinimas</th>
                                        <th>Valdymas</th>
                                    </tr>
                                </thead>
                                <tbody class="subjectsList">

                                </tbody>
                            </table>
                        </div>
                      </div>
                      <div class="col-md-4">
                        @php
                        $subjectList = App\Subject::all();
                        $options = '';
                        foreach($subjectList AS $sbj) {
                            $options .= '<option value="'.$sbj->id.'">'.$sbj->title.'</option>';
                        }
                        @endphp
                          <div class="form-group">
                            <select name="subject_SList" id="subject_SList" class="form-control">
                              <option value="0" disabled="">
                                  Pasirinkite pamoką/dalyką
                              </option>
                              @php echo $options; @endphp
                            </select>
                          </div>
                          <button type="button" class="btn btn-primary addSubject">Pridėti</button>
                        </div>
                      </div>

                    </div>

                    <div class="tab-pane" id="groups">
                      <div class="row">
                        <div class="col-md-4">
                          <div class="form-group groups groupsTableEdit">
                              <label for="groupsList">Grupės</label>
                              <table class="groupsEditTable">
                                  <thead>
                                      <tr>
                                          <th>ID</th>
                                          <th>Pavadinimas</th>
                                          <th>Valdymas</th>
                                      </tr>
                                  </thead>
                                  <tbody class="groupsList">

                                  </tbody>
                              </table>
                          </div>
                        </div>
                        <div class="col-md-4">
                            @php
                            $groupList = App\Group::all();
                            $options = '';
                            foreach($groupList AS $group) {
                                $options .= '<option value="'.$group->id.'">'.$group->title.'</option>';
                            }
                            @endphp
                            <div class="form-group">
                                <select name="group_SList" id="group_SList" class="form-control">
                                  <option value="0" disabled="">
                                      Pasirinkite grupę
                                  </option>
                                  @php echo $options; @endphp
                                </select>
                            </div>
                            <button type="button" class="btn btn-primary addGroup">Pridėti</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
        </form>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Atšaukti</button>
          <button type="button" class="btn btn-primary saveEdit">Išsaugoti pakeitimus</button>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
function openEditModal(userId) {
    var row = $('#userId_'+userId);
    rowData = usersDataTable.row(row).data();
    var url = "{{ route('getDataAboutUser', ['userId' => ':id']) }}";
    url = url.replace(':id', userId);
    $.ajax({
        url: url,
        type: 'GET',
        dataType: 'json'
    }).always(function(out) {
        if(out.success == true) {
            var allInfo = out.allInfo;
            var loginNameTitle = $('#loginNameTitleEdit');
            if(allInfo.dutiesInfo.length > 0) {
                $('#dutySelect option:contains("' + allInfo.dutiesInfo[0].title + '")').prop('selected', true);
            }

            //null
            if($('#userEditModal #nameErr').length > 0) $('#userEditModal #nameErr').remove();
            if($('#userEditModal #second_nameErr').length > 0) $('#userEditModal #second_nameErr').remove();
            if($('#userEditModal #pcodeErr').length > 0) $('#userEditModal #pcodeErr').remove();


            loginNameTitle.html(allInfo.userInfo.loginName + " " + allInfo.userInfo.name + " " + allInfo.userInfo.second_name);

            var subjectsListHtml = '';
            for(var key in allInfo.subjectsInfo) {
                var subject = allInfo.subjectsInfo[key];
                subjectsListHtml += "<tr id=\"sbj_row_"+subject.id+"\">"+
                "<td>"+subject.id+"</td>"+
                "<td>"+subject.title+"</td>"+
                '<td><button type="button" class="btn btn-danger btn-xs" onclick="detachSubject(this, \''+parseInt(userId)+'\',\''+subject.id+'\');"><i class="fa fa-trash"></i></button></td>'+
                "</tr>";
            }

            var groupsList = '';
            for(var key in allInfo.groupsInfo) {
                var group = allInfo.groupsInfo[key];

                if($('#group_SList option[value="' + group.id + '"]').length > 0) {
                    $('#group_SList option[value="' + group.id + '"]').remove();
                }

                groupsList += "<tr id=\"group_row_"+group.id+"\">"+
                "<td>"+group.id+"</td>"+
                "<td>"+group.title+"</td>"+
                '<td><button type="button" class="btn btn-danger btn-xs" onclick="detachGroup(this, \''+parseInt(userId)+'\',\''+group.id+'\');"><i class="fa fa-trash"></i></button></td>'+
                "</tr>";
            }

            $('#userEditModal .subjectsList').html(subjectsListHtml);
            $('#userEditModal .groupsList').html(groupsList);
            $('#statusSelect option[value="'+allInfo.userInfo.level+'"]').prop('selected', true);
            $('#idUpdated').val(parseInt(userId));
            $('#userEditModal #name').val(allInfo.userInfo.name);
            $('#userEditModal #second_name').val(allInfo.userInfo.second_name);
            $('#userEditModal #pcode').val(allInfo.userInfo.pcode);
            $('#userEditModal #loginName').val(allInfo.userInfo.loginName);
            $('#userEditModal #created_at').val(allInfo.userInfo.created_at.date);
            $('#userEditModal #updated_at').val(allInfo.userInfo.updated_at.date);
            $('#userEditModal').modal()
        }
    });







}

function goToProfile() {
    var userId = parseInt($('#idUpdated').val());
    location.href = '{{ route('showProfile') }}/' + userId;
}


</script>
