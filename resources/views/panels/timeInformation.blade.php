<div class="panel @if(isset($color)) {{ $color }} @else panel-red @endif">
    <div class="panel-heading">
        <i class="fa fa-bar-chart-o fa-fw"></i> @if(isset($title)) {{ $title }} @else Laiko informacija @endif
        <div class="pull-right">
            <div class="btn-group">

            </div>
        </div>
    </div>
    <!-- /.panel-heading -->
    <div class="panel-body">
        <div class="form-group has-feedback" >
            <label for="created_at">Sukūrta</label>
            <input name="created_at" id="created_at" type="text"  class="form-control" disabled>
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
        </div>

        <div class="form-group has-feedback" >
            <label for="created_at">Atnaujinta</label>
            <input name="updated_at" id="updated_at" type="text"  class="form-control" disabled>
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
        </div>
    </div>
</div>
