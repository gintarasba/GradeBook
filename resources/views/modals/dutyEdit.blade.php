<div class="modal fade" id="dutyEditModal" tabindex="-1" role="dialog" aria-labelledby="dutyEditModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="myModalLabel">Redagavimas.</h4>
      </div>
      <div class="modal-body">
          <form id="editForm">
              <input type="hidden" name="_token" value="{{ csrf_token() }}"/ />
              <input type="hidden" id="editDutyId" value=""/>


              <div class="row">
                  <div class="col-md-3">
                      <div class="panel panel-green">
                          <div class="panel-heading">
                              <i class="fa fa-bar-chart-o fa-fw"></i> Pareigos informacija
                              <div class="pull-right">
                                  <div class="btn-group">

                                  </div>
                              </div>
                          </div>
                          <!-- /.panel-heading -->
                          <div class="panel-body">
                              <div class="form-group has-feedback" >
                                  <label for="title">Pavadinimas</label>
                                  <input name="title" id="title" type="text" class="form-control" placeholder="Pavadinimas" >
                                  <span class="glyphicon glyphicon-user form-control-feedback"></span>
                              </div>
                          </div>
                          <!-- /.panel-body -->
                      </div>
                  </div>

                  <div class="col-md-5">
                      <div class="panel panel-warning">
                          <div class="panel-heading">
                              <i class="fa fa-bar-chart-o fa-fw"></i> Pareigos Leidimai
                              <div class="pull-right">
                                  <div class="btn-group">

                                  </div>
                              </div>
                          </div>
                          <!-- /.panel-heading -->
                          <div class="panel-body permitsBody">

                                <div class="form-group has-feedback groups">
                                    <label for="permitsList">Pareigos</label>
                                    <table style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>Pavadinimas</th>
                                                <th>Aprašymas</th>
                                                <th>Valdymas</th>
                                            </tr>
                                        </thead>
                                        <tbody class="permitsList">

                                        </tbody>
                                    </table>
                                </div>

                          </div>
                      </div>
                  </div>

                  <div class="col-md-3">
                      @include('panels.timeInformation', [
                        'title' => 'Laiko informacija'
                      ]);
                  </div>
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
<script>
function openModalEdit(editId) {
    var row = $('#dutyId_' + editId);
    rowData = dutiesDataTable.row(row).data();
    var allInfo = JSON.parse(rowData[4]);
    console.log(allInfo);


    var permitsHtml = '';
    for(var key in allInfo.permitsList) {
        var permit = allInfo.permitsList[key];
        permitsHtml += "<tr id=\"permitS_"+permit.id+"\">";
        permitsHtml += "<td>"+permit.title+"</td>";
        permitsHtml += "<td>"+permit.comment+"</td>";
        permitsHtml += "<td><input type=\"checkbox\" class=\"permit_check\" name=\"permit_check[]\" value=\""+permit.id+"\" "+(permit.checked == true? "checked" : "")+"/></td>";
        permitsHtml += "</tr>";

    }
    $('#dutyEditModal #editDutyId').val(editId);
    $('#dutyEditModal .permitsList').html(permitsHtml);
    $('#dutyEditModal #title').val(allInfo.dutyInfo.title);
    $('#dutyEditModal #created_at').val(allInfo.dutyInfo.created_at);
    $('#dutyEditModal #updated_at').val(allInfo.dutyInfo.updated_at);
    $('#dutyEditModal').modal();

}

</script>
