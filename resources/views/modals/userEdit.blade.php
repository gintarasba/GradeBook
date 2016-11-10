<div class="modal fade" id="userEditModal" tabindex="-1" role="dialog" aria-labelledby="userEditModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="myModalLabel">Redaguoti vartotoja: <span id="loginNameTitleEdit">login_name</span></h4>
      </div>
      <div class="modal-body">
          <form id="editForm">
              <input type="hidden" name="_token" id="_tokenEdit" value="{{ csrf_token() }}"/ />
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

              <div class="form-group has-feedback" >
                  <label for="created_at">Sukūrtas</label>
                  <input name="created_at" id="created_at" type="text"  class="form-control" disabled>
                  <span class="glyphicon glyphicon-user form-control-feedback"></span>
              </div>

              <div class="form-group has-feedback" >
                  <label for="created_at">Atnaujintas</label>
                  <input name="updated_at" id="updated_at" type="text"  class="form-control" disabled>
                  <span class="glyphicon glyphicon-user form-control-feedback"></span>
              </div>



                <div class="form-group has-feedback">
                    <label for="statusSelect">Pareigos</label>
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
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
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
<script>
function openEditModal(userId) {
    var row = $('#userId_'+userId);
    rowData = usersDataTable.row(row).data();
    var loginName = rowData[3];
    var name = rowData[1];
    var second_name = rowData[2];
    var pcode = rowData[4];
    var level = rowData[5];
    var loginNameTitle = $('#loginNameTitleEdit');
    var dutiesList = JSON.parse(rowData[9]);

    if(dutiesList.length > 0) {
        $('#dutySelect option:contains("' + dutiesList[0].title + '")').prop('selected', true);
    }

    //null
    if($('#userEditModal #nameErr').length > 0) $('#userEditModal #nameErr').remove();
    if($('#userEditModal #second_nameErr').length > 0) $('#userEditModal #second_nameErr').remove();
    if($('#userEditModal #pcodeErr').length > 0) $('#userEditModal #pcodeErr').remove();


    loginNameTitle.html(loginName);

    $('#statusSelect option:contains('+level+')').prop('selected', true);
    $('#idUpdated').val(parseInt(userId));
    $('#userEditModal #name').val(name);
    $('#userEditModal #second_name').val(second_name);
    $('#userEditModal #pcode').val(pcode);
    $('#userEditModal #loginName').val(loginName);
    $('#userEditModal #created_at').val(rowData[8]);
    $('#userEditModal #updated_at').val(rowData[7]);
    $('#userEditModal').modal()
}



</script>
