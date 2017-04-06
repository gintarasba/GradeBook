<div class="nav-side-menu">
  <div class="brand">ProjectKK</div>
  <i class="fa fa-bars fa-2x toggle-btn" data-toggle="collapse" data-target="#menu-content"></i>
  <?php
      $route = Request::route()->getName();
  ?>
      <div class="menu-list">


          <ul id="menu-content" class="menu-content collapse out">
                <li @if($route == 'home') class="active"  @endif>
                    <a href="{{ route('home') }}">
                        <i class="fa fa-dashboard fa-lg"></i> Pagrindinis @if(Auth::check()) ({{ Auth::user()->loginName }}) @endif
                    </a>
                </li>

            @if(Auth::user()->level == 2)
                <li class="labelNice">
                    <div class="label">Administracinis pultas</div>
                </li>

                <li @if($route == 'showUsersList') class="active" @endif><a href="{{ route('showUsersList') }}"><i class="glyphicon glyphicon-asterisk"></i>Valdyti vartotojus</a></li>
                <li @if($route == 'dutiesManagingForm') class="active" @endif><a href="{{ route ('dutiesManagingForm') }}"><i class="glyphicon glyphicon-asterisk"></i>Valdyti pareigas</a></li>
                <li @if($route == 'newGroupsForm') class="active" @endif><a href="{{ route ('newGroupsForm') }}"><i class="glyphicon glyphicon-asterisk"></i>Valdyti grupes</a></li>
                <li @if($route == 'newSubjectForm') class="active" @endif><a href="{{ route ('newSubjectForm') }}"><i class="glyphicon glyphicon-asterisk"></i>Valdyti pamokas/dalykus</a></li>

            @endif
                <li class="labelNice">
                    <div class="label">Meniu</div>
                </li>

            <?php
                $menuLinks = array();

                if (Auth::check()) {
                    $userDuties = Auth::user()->duties()->first();
                    if (!empty($userDuties)) {
                        $permits = $userDuties->permits()->get();
                        foreach ($permits as $permit) {
                            $menuLinks[$permit['code']] = $permit['id'];
                        }
                    }
                }
             ?>
                <li><a href="{{ route('showProfile') }}"><i class="glyphicon glyphicon-briefcase"></i> Mano profilis</a></li>
                @if(isset($menuLinks['USER_MARKS_VIEW']))
                    <li @if($route == 'showMyMarks') class="active" @endif><a href="{{ route ('showMyMarks') }}"><i class="fa fa-users fa-lg"></i>Pažymių knygelė</a></li>
                @endif
                <li @if($route == 'conversation.list') class="active" @endif><a href="{{ route ('conversation.list') }}"><i class="fa fa-users fa-lg"></i>Žinutės</a></li>
                @if(isset($menuLinks['OBJECT_MARKS_VIEW']))
                    <li data-toggle="collapse" data-target="#products" @if($route == 'showUsersGrades') aria-expanded="true"  @endif >
                      <a href="#"><i class="glyphicon glyphicon-user"></i> Pažymių knygelės <span class="arrow"></span></a>
                    </li>
                    <ul class="sub-menu collapse  @if($route == 'showUsersGrades') in @endif" id="products" @if($route == 'showUsersGrades') aria-expanded="true"  @endif>
                        @php
                        $currentGroupId = null;
                        if(isset($currentGroup->id)) {
                            $currentGroupId = $currentGroup->id;
                        }
                        $userGroups = Auth::user()->group()->get();
                        $out = '';
                        foreach($userGroups AS $key => $group) {
                            $usersCount = $group->users()->count();
                            $out .='
                                <li '.($route == 'showUsersGrades' & $group->id == $currentGroupId ? 'class="active"' : '').'>
                                    <a href="'.route('showUsersGrades', ['group' => $group->id]).'">'.$group->title.' ('.$usersCount.')</a>
                                </li>';

                        }
                        echo $out;
                        @endphp


                    </ul>
                @endif
                <li><a href="{{ route('auth.logoutGet') }}"><i class="glyphicon glyphicon-log-out"></i> Atsijungti</a></li>

                <li class="labelNice">
                    <div class="label">Prisijunge vartotojai <small>( Per paskutines 5 min )</small></div>
                </li>
                @php
                $date = new \DateTime();
                $date->modify("-5 minutes");
                $usersList = App\User::where('updated_at', '>', $date)->get();
                foreach($usersList AS $onlineUser) {
                    echo '<li>
                            <a href="#">'.$onlineUser->name.' '.$onlineUser->second_name.'</a>
                        </li>';
                }
                @endphp

          </ul>
   </div>
</div>
