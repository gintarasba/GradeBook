@extends('layouts.master') @section('title') ProjectKK @endsection @section('styles')
<link rel="stylesheet" href="{{ URL::to('AdminLTE-2.3.5/dist/css/gradeBook.css') }}"> @endsection @section('content')


<div class="content-wrapper niceBg">
  <section class="content ">
    <div class="row">
      <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
        @include('includes.aside')
      </div>

      <div class="col-lg-10 col-md-10 col-sm-6 col-xs-12">
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
                @php $meSubjectsList = Auth::user()->subjects()->get(); $selectHtml = ''; $firstSubj = null;
                foreach($meSubjectsList AS $subject) {
                    if(is_null($firstSubj)) {
                        $firstSubj = $subject->id;
                    }
                    $selectHtml.= '<option value="'.$subject->id.'">'.$subject->title.'</option>';
                }

                @endphp
                <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="subjects">Dalykai</label>
                      <select name="subjects" id="subjectsSelect" class="form-control">
                            <option value="0" disabled>
                                Pasirinkite dalyką
                            </option>
                            {!! $selectHtml !!}
                        </select>
                    </div>
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
                        $usersInGroup = $currentGroup->users()->get();
                        $usersMarks = array();
                        $userIdList = array();
                        $userPohots = array();
                        foreach($usersInGroup AS $user) {
                            //if(Auth::user()->id == $user->id) continue;

                            $userIdList['user_id'][] = $user->id;

                            $userPhotos[$user->id]['photos'] = $user->photos()->where('piPhoto', 1)
                                ->orderBy('created_at', 'desc')
                                ->take(1)
                                ->first();
                            $userPhotos[$user->id]['usr'] = $user;
                        }

                        $userMarks = App\Mark::where($userIdList)->get();
                        foreach($userMarks AS $mark) {
                            $day = explode('-', $mark->day);
                            $usersMarks[$mark->user_id][$mark->subject_id][$day[0]][$day[1]][$day[2]] = $mark->mark;
                        }

                        $photosList = App\UserPhoto::where($userIdList)->get();

                        foreach ($photosList as $photo) {
                            if ($photo->profilePic) {
                                $profilePic = $photo->photoPath;
                                $usersMarks[$photo->user_id]['profilepic'] = $profilePic;
                            }
                        }

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

                        foreach(\App\FancyLib\Month::monthsList() AS $key => $month) {
                           $monthNumber = $key+1;
                           $daysInMonthCount = cal_days_in_month(CAL_GREGORIAN, $monthNumber, $currentYear); // 31
                        @endphp
                            <div class="@if($monthNumber == $currentMonth) active @endif tab-pane gradeBookAll" id="month_tab_{{ $monthNumber }}">
                                <table class="table gradeBookTable">
                                @php
                                $headOut = '<tr><th class="strikeout"></th>';
                                for($i = 1; $i <= $daysInMonthCount; $i ++) {
                                    $date = $currentYear.'-'.$monthNumber.'-'.$i;
                                    $weekDay = date('w', strtotime($date));
                                    $shortDay = \App\FancyLib\Month::getShortDay($weekDay);
                                    echo '<div class="hidden">'.$date.' - '.$weekDay.' - '.$shortDay->title.'</div>';
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
                                    foreach($usersInGroup AS $user) {

                                        $bodyOut .= '
                                            <tr subject_id="'.$subject->id.'" class="subject" style="'.($subject->id != $firstSubj ? 'display: none;' : '').'">
                                                <td>   
                                                    <div class="tableRowTitlePhotoContainer">
                                                        <img
                                                            class="profile-user-img img-responsive img-circle"
                                                            src="'.(!isset($usersMarks[$user->id]['profilepic']) ? '/users/photos/default.jpg'  :$usersMarks[$user->id]['profilepic']).'"
                                                            alt="User profile picture">
                                                    </div>

                                                    <div class="tableRowContentPhotoContainer">
                                                        <b>'.$user->loginName.'</b><br/>
                                                        '.$user->name.' '.$user->second_name.'
                                                    </div>

                                                    <div class="tableRowButtonsPhotoContainer">
                                                        <a href="'.route('showUserGrades', ['user' => $user->id]).'"><i class="glyphicon glyphicon-chevron-right"></i></a>
                                                    </div>
                                                </td>';


                                        if(!isset($usersMarks[$user->id][$subject->id]['subjectExists'])) {
                                            $userSubjectExists = $user->subjects();
                                            $userSubjectExists = $userSubjectExists->where('subject_id', $subject->id)->first();
                                            $usersMarks[$user->id][$subject->id]['subjectExists'] = $userSubjectExists;
                                        }
                                        for($i = 1; $i <= $daysInMonthCount; $i ++) {
                                            $mark = isset($usersMarks[$user->id][$subject->id][$currentYear][$monthNumber][$i]) ? $usersMarks[$user->id][$subject->id][$currentYear][$monthNumber][$i] : '';
                                            $date = $currentYear.'-'.$monthNumber.'-'.$i;
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
                                            if(is_null($usersMarks[$user->id][$subject->id]['subjectExists'])) {
                                                $bodyOut .= '<td class="notAvailable '.$classNames.'"></td>';
                                            } else {
                                                $id = 'dateid_'.$currentYear.'-'.($monthNumber).'-'.($i);
                                                $onclick = '';
                                                if ($canEditAsTeacher == true) {
                                                    $onclick = 'openEditModal(\''.$user->id.'\',\''.$subject->id.'\', \''.$id.'\');';
                                                }


                                                $bodyOut .= '<td onclick="'.$onclick.'" id="'.$id.'" markId="userId_'.$user->id.''.$id.'subject_id_'.$subject->id.'" class="'.$classNames.'">'.$mark.'</td>';
                                            }



                                        }
                                        $bodyOut .= '</tr>';
                                    }


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
      </div><!-- row ends here -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">Paskutiniai vaizdai</div>
                  <div class="panel-body">
                      <div class="row">
                          @php
                          foreach($userPhotos AS $userp) {
                             if( !is_null($userp['photos'])) {
                                 echo '<div class="col-md-2"><img
                                     class="profile-user-img img-responsive img-circle imgPi"
                                     src="'.$userp['photos']->photoPath.'"
                                     alt="User profile picture">'.$userp['usr']->name.' '.$userp['usr']->second_name.' ('.$userp['usr']->loginName.')</div>';
                             }
                          }
                          @endphp
                      </div>
                  </div>
              </div>
            </div>
        </div>
      </div>
    </div>
  </section>
  @include('modals.gradeEdit')
</div>
<script>
    $('#subjectsSelect').on('change', function(event) {
        var value = $(this).val();
        $('.subject').each(function( index ) {
            if($(this).attr('subject_id') == value) {
                $(this).attr('style', '');
            } else {
                $(this).attr('style', 'display: none;');
            }

        });

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
