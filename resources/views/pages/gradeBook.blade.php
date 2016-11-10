@extends('layouts.master')

@section('title')
    ProjectKK
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ URL::to('AdminLTE-2.3.5/dist/css/gradeBook.css') }}">
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
        <div class="content-wrapper niceBg" >
            <section class="content ">
                @include('includes.aside')

                <div class="row">
                    <div class="col-md-10 col-md-offset-2">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div id="outerDiv">
                            		<div id="innerDiv">
                            			<table class="gradeBook">
                            				<tr>
                            					<th>Dalykas</th>
                                                {!! $monthsOut !!}
                            				</tr>
                                            <tr>
                                                <th></th>
                                                <?php
                                                $year = date("Y");
                                                for ($m=0; $m<12; $m++) {
                                                    $daysCount = cal_days_in_month(CAL_GREGORIAN, $m+1, $year);
                                                    for($i = 0; $i < $daysCount; $i ++) {
                                                        $d = $i + 1;
                                                        $weekDay = date( "w", strtotime(date($year.'-'.$m.'-'.$d))) + 1;
                                                        $color = 'black';
                                                        if($weekDay == 1) {
                                                            $day = 'Pi';
                                                        }
                                                        if($weekDay == 2) {
                                                            $day = 'An';
                                                        }
                                                        if($weekDay == 3) {
                                                            $day = 'Tr';
                                                        }
                                                        if($weekDay == 4) {
                                                            $day = 'Ke';
                                                        }
                                                        if($weekDay == 5) {
                                                            $day = 'Pe';
                                                        }
                                                        if($weekDay == 6) {
                                                            $day = 'Še';
                                                            $color = 'red';
                                                        }
                                                        if($weekDay == 7)  {
                                                            $day = 'Se';
                                                            $color = 'red';
                                                        }
                                                        $isWeekday = ( ($weekDay == 6 OR $weekDay == 7) ? 'weekDay' : '');
                                                        if($i == 0) {
                                                            echo '
                                                                <td class="markPlace borderLeft '.$isWeekday.'">
                                                                    <font color="'.$color.'">'.$d.'<br/>'.$day.'</font>
                                                                </td>';
                                                        } else if($i == $daysCount -1) {
                                                            echo '<td class="markPlace borderRight '.$isWeekday.'">
                                                            <font color="'.$color.'">'.$d.'<br/>'.$day.'</font></td>';
                                                        } else {
                                                            echo '<td class="markPlace borderBoth '.$isWeekday.'">
                                                            <font color="'.$color.'">'.$d.'<br/>'.$day.'</font></td>';
                                                        }
                                                    }
                                                }

                                                 ?>
                                            </tr>
                                            @foreach($userSubjectList AS $subject)
                                            <tr>
                                                <th>{{$subject->title}}</th>
                                                <?php
                                                $year = date("Y");
                                                for ($m=0; $m<12; $m++) {
                                                    $daysCount = cal_days_in_month(CAL_GREGORIAN, $m+1, $year);
                                                    for($i = 0; $i < $daysCount; $i ++) {
                                                        $d = $i + 1;
                                                        $weekDay = date( "w", strtotime(date($year.'-'.$m.'-'.$d))) + 1;
                                                        $isWeekday = ( ($weekDay == 6 OR $weekDay == 7) ? 'weekDay' : '');
                                                        if($i == 0) {
                                                            echo '<td class="markPlace borderLeft '.$isWeekday.'"> </td>';
                                                        } else if($i == $daysCount -1) {
                                                            echo '<td class="markPlace borderRight '.$isWeekday.'"> </td>';
                                                        } else {
                                                            echo '<td class="markPlace borderBoth '.$isWeekday.'"> </td>';
                                                        }
                                                    }
                                                }

                                                 ?>
                                            </tr>
                                            @endforeach

                            			</table>
                            		</div>
                            	</div>











                            </div>
                        </div>
                    </div>
                </div>


            </section>
        </div>



    @endif



@endsection


@if(Auth::check())
    @section('javascripts')
        <script src="{{ URL::to('AdminLTE-2.3.5/dist/js/custom.js') }}"></script>

    @endsection
@endif
