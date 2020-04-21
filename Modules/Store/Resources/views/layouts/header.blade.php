<header id="header">
   <audio id="myAudio" controls style="visibility: hidden; position:absolute;">
      <!-- <source src="http://www.soundjay.com/misc/sounds/bell-ringing-01.ogg" type="audio/ogg"> -->
      <source src="{{asset('vintage4.mp3')}}" type="audio/mpeg">      
    </audio>
    <div class="toggle-icon d-flex align-items-center justify-content-center">
        <a href="javascript:void(0);" id="menu">
            <span></span>
            <span></span>
            <span></span>
        </a>
    </div>
    <nav class="navbar justify-content-between">
        <div class="logo text-center">
            <a href="{{url('store')}}"><img src="{{ asset('img/logo_black.png')}}" class="logo"')  }}" alt="logo"></a>
        </div>
        <ul class="nav right_nav d-flex align-items-center">
            <li class="nav-item notification dropdown" style="display:none">
                <a class="nav-link dropdown-toggle" href="javascript:void(0)" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="icon-savour-notification" aria-hidden="true"></i>
                    <span class="count rounded-circle">5</span>
                </a>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" >
                    <h2 class="heading font-libre-bold mb-0">Notification</h2>
                    <ul class="list-unstyled notification-list position-relative mCustomScrollbar">
                        <li>
                            <p class="mb-0">Lorem ipsum dolor sit amet, new manage jobs adipisicing elit. <a href="manage-jobs-view.php" class="theme-color ml-1">View Details</a></p>
                        </li>
                        <li>
                            <p class="mb-0">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quia. <a href="manage-individuals-lender-view.php" class="theme-color ml-1">View Details</a></p>
                        </li>
                        <li>
                            <p class="mb-0">Lorem ipsum dolor sit amet.<a href="manage-manager-lender.php" class="theme-color ml-1">View Details</a></span></p>
                        </li>
                        <li>
                            <p class="mb-0">Lorem ipsum dolor sit amet, new manage jobs adipisicing elit. <a href="manage-jobs-view.php" class="theme-color ml-1">View Details</a></p>
                        </li>
                        <li>
                            <p class="mb-0">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quia. <a href="manage-individuals-lender-view.php" class="theme-color ml-1">View Details</a></p>
                        </li>
                    </ul>
                    <div class="view-all">
                        <a href="notifications.php">View All</a>
                    </div>
                </div>
            </li>
            <li class="nav-item dropdown user_info">
                <a class="nav-link d-flex align-items-center dropdown-toggle" href="javascript: void(0);" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="name">
                        {{!empty(Auth::user())?Auth::user()->name:''}}
                    </div>
                </a>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <!-- <a class="dropdown-item" href="{{ URL::To('store/profile-setting') }}">Profile Setting</a> -->
                    <a class="dropdown-item" href="{{ URL::To('store/change-password') }}">Change Password</a>
                    <a class="dropdown-item" href="{{url('store/manage-store/edit/'.Auth::user()->store_id)}}">Store Setting</a>
                    <a class="dropdown-item" href="{{url('store/logout')}}">Logout</a>
                </div>
            </li>
            <li class="nav-item user_img">
                <a class="nav-link" href="javascript: void(0);"><img class="user_img" src="{{ getUserImage(isset(Auth::user()->profile_image)?Auth::user()->profile_image:'','users')}}" alt="user-img">
                </a>


            </li>
        </ul>
    </nav>
</header>
<script>
    $('.number_only').on('input', function(event) {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
</script>
