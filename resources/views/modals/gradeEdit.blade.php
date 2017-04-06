<div class="modal fade" id="gradeEditModal" tabindex="-1" role="dialog" aria-labelledby="gradeEditModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="myModalLabel">Redaguoti pažimį: <span id="groupDate"></span></h4>
      </div>
      <div class="modal-body">
          <form id="editForm">
              <input type="hidden" name="_token" id="_tokenEdit" value="{{ csrf_token() }}"/ />
              <input name="userId" id="userId" type="hidden"  class="form-control" placeholder="" value="" >
              <input name="subjectId" id="subjectId" type="hidden"  class="form-control" placeholder="" value="" >
              <input name="groupDateid" id="groupDateid" type="hidden"  class="form-control" placeholder="" value="" >
              <input name="markId" id="markId" type="hidden" value="" />
              <div class="row">
                  <div class="col-sm-6">
                      <div class="form-group has-feedback">
                        <label for="markSelect">Pažymys</label>
                        <select name="mark" id="markSelect" class="form-control">
                            <option value="-1">
                                Pasirinkite pažimį
                            </option>
                        @for($i = 0; $i <= 10; $i ++)
                            <option value="{{$i}}">
                                {{ $i }}
                            </option>
                        @endfor
                        </select>
                        <span class="glyphicon glyphicon-user form-control-feedback"></span>
                      </div>
                  </div>

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






</script>
