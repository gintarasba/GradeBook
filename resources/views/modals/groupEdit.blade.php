<div class="modal fade" id="groupEditModal" tabindex="-1" role="dialog" aria-labelledby="groupEditModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="myModalLabel">Redaguoti grupę: <span id="groupTitle"></span></h4>
      </div>
      <div class="modal-body">
          <form id="editForm">
              <input type="hidden" name="_token" id="_tokenEdit" value="{{ csrf_token() }}"/ />
              <input name="idUpdated" id="idEdited" type="hidden"  class="form-control" placeholder="" value="" >

              <div class="row">
                  <div class="col-sm-7">
                      <div class="form-group has-feedback" >
                          <input name="users" id="usersSearch" type="text" ontyped class="form-control" placeholder="Ieškoti" autocomplete="Off">
                          <div id="suggest" style="display:none;"></div>
                          <div id="suggestedId" style="display: none;"></div>
                          <span class="glyphicon glyphicon-user form-control-feedback"></span>
                      </div>
                  </div>

                  <div class="col-sm-5">
                      <button class="btn btn-primary ladda-button addButton" data-style="expand-left"><span class="ladda-label">Pridėti</span><span class="ladda-spinner"></span><div class="ladda-progress" style="width: 0px;"></div></button>
                  </div>
              </div>
              <table class="fullWidth">
                  <thead>
                      <tr>
                          <th>Vardas</th>
                          <th>Pareiga</th>
                          <th>Valdymas</th>
                      </tr>
                  </thead>
                  <tbody id="usersList">

                  </tbody>
              </table>



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






</script>
