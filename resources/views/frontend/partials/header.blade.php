<!-- Navigation-->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNav">
    <div class="container">
        <a class="navbar-brand js-scroll-trigger" href="/"><img src="{{ asset('frontend/img/streamly-logo.png') }}" alt="logo of streamly" /></a>

        @if( isset($login) && $login)

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
              </button>
        @else
            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive1" aria-controls="navbarResponsive" aria-expanded="false" onclick="openNav(event)" aria-label="Toggle navigation">Log In<i class="fas fa-bars ml-1"></i></button>
        @endif

        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
                <!-- <li class="nav-item"><a class="nav-link js-scroll-trigger" href="#services">Services</a></li>
                <li class="nav-item"><a class="nav-link js-scroll-trigger" href="#portfolio">Portfolio</a></li>
                <li class="nav-item"><a class="nav-link js-scroll-trigger" href="#about">About</a></li>
                <li class="nav-item"><a class="nav-link js-scroll-trigger" href="#team">Team</a></li> -->

                @if( isset($login) && $login)

                    <li class="nav-item">
                       
                        <div class="form-group has-search">
                            <img class="form-control-feedback" src="{{ asset('frontend/img/search.svg') }}" style="z-index:-1;"/>
                            <input type="text" style="background: none;" id="search-typehead" class="search-click typeahead" placeholder="Search">
                            {{-- <input type="text" name="search" class="form-control typeahead" id="search-btn-header" placeholder="Search channels or shows"> --}}
                            <div id="search_list"></div>  
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a style="background: " class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="{{ asset('frontend/img/noun_profile.svg') }}" width="40" height="40" class="rounded-circle" />
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            {{-- <span class="dropdown-menu-arrow"></span> --}}
                            <a class="dropdown-item disabled" href="/account/settings">{{ Auth::user()->name }}</a>
                            <a class="dropdown-item" href="/account/settings">Account Settings</a>
                            <a class="dropdown-item" href="/qrscanner">QR Scanner</a>
                            <a class="dropdown-item" href="/account/devices">Manage Connected device</a>
                            @if (Auth::user()->subscribed('main')) 
                            <a class="dropdown-item" href="/react">Program Guide</a>
                            <a class="dropdown-item" href="/livetv/list">All channels</a>
                            <a class="dropdown-item" href="/livetv">All channels with genre</a>
                            <a class="dropdown-item" href="/my_favorites">My favorites</a>
                            <a class="dropdown-item" href="/livetv/recent">My recents</a>
                            @endif
                            <a class="dropdown-item" href="#">Download app</a>
                            <a class="dropdown-item" href="/faq">Help & Support</a>
                            <a class="dropdown-item logouttheuser"  href="javascript:void(0)">Logout</a>
                        </div>
                      </li>
                    

                @else

                    <li class="nav-item"><a class="btn btn-primary  js-scroll-trigger "  href="/checkAvailabilty"><b>Check Availability</b></a></li>
                    <li class="nav-item"><a class="nav-link js-scroll-trigger btn-login" onclick="openNav(event)" href="javascript:void(0)">Log In</a></li>

                @endif
            </ul>
        </div>
    </div>
</nav>


<div class="modal fade" id="Logout">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-body text-center">
                <h4>Logout </h4>
                <p>Are you sure you want to logout?</p>
                <div>
                    <a class="btn btn-sm" href="javascript:void(0)" onclick="logoutUser(event)" style="background: #C4C4C4 0% 0% no-repeat padding-box;padding: 0.5rem 1.5rem;">Yes</a>
                    <a class="btn btn-sm" href="javascript:void(0)" data-dismiss="modal" style="border: 1px solid #707070;border-radius: 6px;padding: 0.5rem 1.5rem;">No</a>
                </div>
            </div>

        </div>
    </div>
</div>
<style>
.search-click {
  border: 1px solid #ccc;
  outline: none;
  background-size: 22px;
  background-position: 13px;
  border-radius: 10px;
  width: 50px;
  height: 50px;
  padding: 25px;
  transition: all 0.5s;
}
.search-click:focus {
  width: 300px;
  padding-left: 50px;
}
.search-click {
  position: relative;
  overflow: hidden;
  height: 50px;
}
.search-click input {
  background: transparent;
  border: 1px solid #ccc;
  outline: none;
  position: absolute;
  width: 300px;
  height: 50px;
  left: 0%;
  padding: 10px;
}</style>