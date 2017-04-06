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
        @if(isset($content)) {!! $content !!} @endif
    </div>
</div>
