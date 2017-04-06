@extends('layouts.master') @section('title') ProjectKK @endsection @section('styles')
<link rel="stylesheet" href="{{ URL::to('AdminLTE-2.3.5/dist/css/gradeBook.css') }}"> @endsection @section('content')


<div class="content-wrapper niceBg">
    <section class="content ">
        <div class="row">
            <div class="col-md-2">
                @include('includes.aside')
            </div>

            <div class="col-md-10">
                <div class="row">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Pagrindinis</a></li>
                        <li class="breadcrumb-item active">Pažymių knygelė</li>
                    </ol>

                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                             <div class="panel-heading">Pažymių knygelė</div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <b>{{ $userInfo->name }} {{ $userInfo->second_name }} ({{ $userInfo->loginName }})</b>
                                        <hr/>
                                    </div>
                                </div>
                                <div class="row">
                                  <div class="col-sm-12">
                                     <div class="nav-tabs-custom gradeBookMonths">
                                         <ul class="nav nav-tabs">
                                         @php
                                         $currentYear = date("Y");
                                         $currentMonth = date("m");
                                         $currentDay = date("d");
                                         foreach(\App\FancyLib\Month::monthsList() AS $key => $month) {
                                             $monthNumber = $key+1;
                                         @endphp
                                            <li @if($monthNumber == $currentMonth)  class="active" @endif><a href="#month_tab_{{ $monthNumber }}" data-toggle="tab">{{ $month }}</a></li>
                                         @php
                                         }
                                         @endphp
                                        </ul>
                                    </div>
                                    <div class="tab-content">
                                        @php
                                        $myMarks = array();
                                        $userMarks = App\Mark::where('user_id', $userInfo->id)->get();
                                        foreach($userMarks AS $mark) {
                                            $day = explode('-', $mark->day);
                                            $myMarks[$mark->subject_id][$day[0]][$day[1]][$day[2]] = $mark->mark;
                                        }

                                        $meSubjectsList = $userInfo->subjects()->get();
                                        foreach(\App\FancyLib\Month::monthsList() AS $key => $month) {
                                           $monthNumber = $key+1;
                                           $daysInMonthCount = cal_days_in_month(CAL_GREGORIAN, $monthNumber, $currentYear); // 31
                                        @endphp
                                            <div class="@if($monthNumber == $currentMonth) active @endif tab-pane gradeBookAll" id="month_tab_{{ $monthNumber }}">
                                                <table class="table gradeBookTable">
                                                @php
                                                $headOut = '<tr><th></th>';
                                                for($i = 1; $i <= $daysInMonthCount; $i ++) {
                                                    $date = $currentYear.'-'.$currentMonth.'-'.$i;
                                                    $weekDay = date('w', strtotime($date));
                                                    $shortDay = \App\FancyLib\Month::getShortDay($weekDay);
                                                    $classNames = array();

                                                    if($currentMonth == $monthNumber && $currentDay == $i) {
                                                        $classNames[] = 'currentDay';
                                                    }
                                                    if($weekDay == 0 || $weekDay == 6) {
                                                        $classNames[] = 'weekDay';
                                                        $onclick = '';
                                                    }
                                                    $classNames = implode(' ', $classNames);
                                                    $headOut .= '<th class="'.$classNames.'">'.$i.' '.$shortDay->title.'</th>';
                                                }
                                                $headOut .= '</tr>';
                                                $bodyOut = '';
                                                foreach($meSubjectsList AS $subject) {

                                                    $canEditAsTeacher = false;
                                                    $userDuties = Auth::user()->duties()->get();
                                                    foreach($userDuties AS $duty) {
                                                        $permits = $duty->permits()->get();

                                                        foreach($permits AS $permit) {
                                                            if($permit->code == 'OBJECT_MARKS_EDIT') {
                                                                $canEditAsTeacher = true;
                                                                break;
                                                            }
                                                        }
                                                    }


                                                        $bodyOut .= '
                                                            <tr subject_id="'.$subject->id.'" class="subject" >
                                                                <td><b>'.$subject->title.'</b></td>';
                                                        for($i = 1; $i <= $daysInMonthCount; $i ++) {
                                                            $mark = isset($myMarks[$subject->id][$currentYear][$monthNumber][$i]) ? $myMarks[$subject->id][$currentYear][$monthNumber][$i] : '';
                                                            $date = $currentYear.'-'.$currentMonth.'-'.$i;
                                                            $weekDay = date('w', strtotime($date));
                                                            $classNames = array();

                                                            if($currentMonth == $monthNumber && $currentDay == $i) {
                                                                $classNames[] = 'currentDay';
                                                            }
                                                            if($weekDay == 0 || $weekDay == 6) {
                                                                $classNames[] = 'weekDay';
                                                                $onclick = '';
                                                            }
                                                            $classNames = implode(' ', $classNames);

                                                            $id = 'dateid_'.$currentYear.'-'.($monthNumber).'-'.($i);
                                                            $onclick = '';
                                                            if ($canEditAsTeacher == true) {
                                                                $onclick = 'openEditModal(\''.$userInfo->id.'\',\''.$subject->id.'\', \''.$id.'\');';
                                                            }


                                                            $bodyOut .= '<td onclick="'.$onclick.'" id="'.$id.'" markId="userId_'.$userInfo->id.''.$id.'subject_id_'.$subject->id.'" class="'.$classNames.'">'.$mark.'</td>';

                                                        }
                                                        $bodyOut .= '</tr>';

                                                }
                                                echo '<thead>'.$headOut.'</thead>';
                                                echo '<tbody>'.$bodyOut.'</tbody>';
                                                @endphp
                                                </table>
                                            </div>
                                       @php
                                       }


                                       @endphp
                                    </div>
                                  </div>


                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('modals.gradeEdit')
    </section>
</div>



@endsection


@if(Auth::check())
    @section('javascripts')
        <script src="{{ URL::to('AdminLTE-2.3.5/dist/js/custom.js') }}"></script>
        <script>
        var lastCss;
        $('.teachEdit').on('mouseover', function(e) {
            var target = e.target;
            lastCss = $(target).css('background');
            $(target).css('background', '#5d9261');
        });
        $('.teachEdit').on('mouseout', function(e) {
            var target = e.target;
            $(target).css('background', lastCss);
        });

        function openEditModal(userId, subjectId, dateId) {
            var date = dateId.replace('dateid_','');
            var grade = parseInt($('td[markId=userId_' + userId + '' + dateId+'subject_id_'+subjectId+']').html());
            $('#markSelect option[value="-1"]').prop('selected', true);
            if(!isNaN(grade)) {
                $('#markSelect option:contains("' + grade + '")').prop('selected', true);
            }
            $('#markId').val('userId_' + userId + '' + dateId+'subject_id_'+subjectId);
            $('#userId').val(userId);
            $('#subjectId').val(subjectId);
            $('#groupDateid').val(date);
            $('#groupDate').html(date);
            $('#gradeEditModal').modal();
        }

        $('.saveEdit').on('click', function(e) {
            mark = $('#markSelect').val();
            user = $('#userId').val();
            subj = $('#subjectId').val();
            dat  = $('#groupDateid').val();
            token = $('#_tokenEdit').val();
            markId = $('#markId').val();
            $.ajax({
                url: "{{ route('updateGrade') }}",
                type: 'GET',
                dataType: 'json',
                data: {
                    _token: token,
                    mark:mark,
                    user:user,
                    subj:subj,
                    dat:dat,
                }
            }).always(function(out) {
                if(out.success == true) {
                    if(mark == -1) mark = '';
                    $('td[markId='+markId+']').html(mark);
                    $('#gradeEditModal').modal('hide');
                }
            });

        });
        </script>
    @endsection
@endif
