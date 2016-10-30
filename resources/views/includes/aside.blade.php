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
                        <i class="fa fa-dashboard fa-lg"></i> Pagrindinis
                    </a>
                </li>

            @if(Auth::user()->level == 2)
                <li class="labelNice">
                    <div class="label">Administracinis pultas</div>
                </li>

                <li @if($route == 'showUsersList') class="active" @endif><a href="{{ route('showUsersList') }}"><i class="fa fa-users fa-lg"></i>Valdyti vartotojus</a></li>
                <li @if($route == 'dutiesManagingForm') class="active" @endif><a href="{{ route ('dutiesManagingForm') }}"><i class="fa fa-users fa-lg"></i>Valdyti pareigas</a></li>
                <li @if($route == 'newGroupsForm') class="active" @endif><a href="{{ route ('newGroupsForm') }}"><i class="fa fa-users fa-lg"></i>Valdyti grupes</a></li>

            @endif
                <li class="labelNice">
                    <div class="label">Meniu</div>
                </li>

            <?php
                $menuLinks = array();

                if(Auth::check()) {
                    $userDuties = Auth::user()->duties()->get();
                    foreach($userDuties AS $duty) {
                        $permits = $duty->permits()->get();

                        foreach($permits AS $permit) {
                            if($permit['code'] == 'USER_MARKS_VIEW') {
                                $menuLinks['marksBookView'] = $permit['id'];
                            }
                        }
                    }
                }

             ?>

                @if(isset($menuLinks['marksBookView']))
                    <li @if($route == 'showUserMarks') class="active" @endif><a href="{{ route ('showUserMarks') }}"><i class="fa fa-users fa-lg"></i>Pažymių knygelė</a></li>
                @endif

                <li  data-toggle="collapse" data-target="#products" >
                  <a href="#"><i class="glyphicon glyphicon-user"></i> bla bla bla <span class="arrow"></span></a>
                </li>
                <ul class="sub-menu collapse" id="products">
                    <li class="active"><a href="#">blablabla</a></li>
                    <li><a href="#">blabla</a></li>
                    <li><a href="#">bla</a></li>
                    <li><a href="#">blablablablabla</a></li>
                    <li><a href="#">blablabla</a></li>
                    <li><a href="#">blabla</a></li>
                </ul>

                <li><a href="{{ route('logoutGet') }}"><i class="glyphicon glyphicon-log-out"></i> Atsijungti</a></li>
          </ul>
   </div>
</div>
