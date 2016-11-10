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

                <li @if($route == 'showUsersList') class="active" @endif><a href="{{ route('showUsersList') }}"><i class="fa fa-users fa-lg"></i>Valdyti vartotojus</a></li>
                <li @if($route == 'dutiesManagingForm') class="active" @endif><a href="{{ route ('dutiesManagingForm') }}"><i class="fa fa-users fa-lg"></i>Valdyti pareigas</a></li>
                <li @if($route == 'newGroupsForm') class="active" @endif><a href="{{ route ('newGroupsForm') }}"><i class="fa fa-users fa-lg"></i>Valdyti grupes</a></li>
                <li @if($route == 'newSubjectForm') class="active" @endif><a href="{{ route ('newSubjectForm') }}"><i class="fa fa-users fa-lg"></i>Valdyti pamokas/dalykus</a></li>

            @endif
                <li class="labelNice">
                    <div class="label">Meniu</div>
                </li>

            <?php
                $menuLinks = array();

                if(Auth::check()) {
                    $userDuties = Auth::user()->duties()->first();
                    if(!empty($userDuties)) {
                        $permits = $userDuties->permits()->get();
                        foreach($permits AS $permit) {
                            $menuLinks[$permit['code']] = $permit['id'];
                        }
                    }

                }
             ?>

                @if(isset($menuLinks['USER_MARKS_VIEW']))
                    <li @if($route == 'showMyMarks') class="active" @endif><a href="{{ route ('showMyMarks') }}"><i class="fa fa-users fa-lg"></i>Pažymių knygelė</a></li>
                @endif

                @if(isset($menuLinks['OBJECT_MARKS_VIEW']))
                    <li data-toggle="collapse" data-target="#products" >
                      <a href="#"><i class="glyphicon glyphicon-user"></i> Pažymių knygelės <span class="arrow"></span></a>
                    </li>
                    <ul class="sub-menu collapse" id="products">
                        @php
                        $userGroups = Auth::user()->group()->get();
                        $out = '';
                        foreach($userGroups AS $key => $group) {
                            $out .='
                                <li  data-toggle="collapse" data-target="#users" >
                                    <a href="#">'.$group->title.'<span class="arrow"></span></a>
                                </li>
                                <ul class="sub-menu collapse" id="users">';

                            $users = $group->users()->get();
                            $usersOut = '';
                            foreach($users AS $usr) {
                                if($usr->id != Auth::user()->id) {
                                    $duty = $usr->duties()->first();
                                    $usersOut .= "<li><a href=\"".route('showUserGrades', ['user' => $usr->id])."\">".$usr->name." ".$usr->second_name." ".(!empty($duty) ? "(".$duty->title.")" : "")."</a></li>";
                                }

                            }

                            $out .= $usersOut.'</ul>';

                        }
                        echo $out;
                        @endphp


                    </ul>
                @endif
                <li><a href="{{ route('logoutGet') }}"><i class="glyphicon glyphicon-log-out"></i> Atsijungti</a></li>
          </ul>
   </div>
</div>
