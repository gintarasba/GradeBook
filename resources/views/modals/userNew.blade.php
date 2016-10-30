<div class="modal fade" id="newUserModal" tabindex="-1" role="dialog" aria-labelledby="newUserModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="myModalLabel">Kūrti vartotoja</h4>
      </div>
      <div class="modal-body">
          <form id="editForm">
              <input type="hidden" name="_token_new" id="_token_new" value="{{ csrf_token() }}"/ />
              <div class="form-group has-feedback" >
                  <input name="name" id="name_new" type="text"  class="form-control" placeholder="Vardas" >
                  <span class="glyphicon glyphicon-user form-control-feedback"></span>
              </div>

              <div class="form-group has-feedback" >
                  <input name="second_name" id="second_name_new" type="text"  class="form-control" placeholder="Pavardė" >
                  <span class="glyphicon glyphicon-user form-control-feedback"></span>
              </div>

              <div class="form-group has-feedback" >
                  <input name="pcode" id="pcode_new" type="text"  class="form-control" placeholder="Asmens kodas" >
                  <span class="glyphicon glyphicon-user form-control-feedback"></span>
              </div>

              <div class="form-group has-feedback" >
                  <select name="status" id="statusSelect_new" class="form-control">
                      <option value="" disabled>
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


              <div class="row">
                  <div class="col-sm-7">
                      <div class="form-group has-feedback" >
                          <input name="loginName" id="loginName_new" type="text"  class="form-control" placeholder="Prisijungimo vardas" >
                          <span class="glyphicon glyphicon-user form-control-feedback"></span>
                      </div>
                  </div>

                  <div class="col-sm-5">
                      <button class="btn btn-primary ladda-button generateLogIn" data-style="expand-left"><span class="ladda-label">Generuoti</span><span class="ladda-spinner"></span><div class="ladda-progress" style="width: 0px;"></div></button>
                  </div>
              </div>

              <div class="row">
                  <div class="col-sm-7">
                      <div class="form-group has-feedback" >
                          <input name="password" id="password_new" type="text"  class="form-control" placeholder="Slaptažodis" >
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
        <button type="button" class="btn btn-primary saveNew">Išsaugoti pakeitimus</button>
      </div>
    </div>
  </div>
</div>
