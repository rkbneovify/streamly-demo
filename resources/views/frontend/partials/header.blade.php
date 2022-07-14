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
                        <div class="search-box">
    <button class="btn-search"><img class="form-control-feedback" src="{{ asset('frontend/img/search.svg') }}" style="z-index:-1;"/></button>
    <input type="text" id="search-typehead" class="input-search" placeholder="Search...">
  </div>
                        {{-- <div class="form-group has-search">
                            <img class="form-control-feedback" src="{{ asset('frontend/img/search.svg') }}" style="z-index:-1;"/>
                            <input type="text" style="background: none;" id="search-typehead" class=" typeahead" placeholder="Search">
                            <input type="text" name="search" class="form-control typeahead" id="search-btn-header" placeholder="Search channels or shows">
                            <div id="search_list"></div>  
                        </div> --}}
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
                            <a class="dropdown-item" href="/#">Download app</a>
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
.search-box{
  width: fit-content;
  height: fit-content;
  position: relative;
}
.input-search{
  height: 50px;
  width: 50px;
  border-style: none;
  padding: 10px;
  font-size: 18px;
  letter-spacing: 2px;
  outline: none;
  border-radius: 25px;
  transition: all .5s ease-in-out;
  background-color: #004E92;
  padding-right: 40px;
  color:#fff;

  box-sizing: border-box !important; 
}
.input-search::placeholder{
  color:rgba(255,255,255,.5);
  font-size: 18px;
  letter-spacing: 2px;
  font-weight: 100;
}
.btn-search{
  width: 50px;
  height: 50px;
  border-style: none;
  font-size: 20px;
  font-weight: bold;
  outline: none;
  cursor: pointer;
  border-radius: 50%;
  position: absolute;
  right: 0px;
  color:#ffffff ;
  background-color:transparent;
  pointer-events: painted;  
}
.btn-search:focus ~ .input-search{
  width: 300px;
  border-radius: 0px;
  background-color: transparent;
  border-bottom:1px solid rgba(255,255,255,.5);
  transition: all 500ms cubic-bezier(0, 0.110, 0.35, 2);
}
.input-search:focus{
  width: 300px;
  border-radius: 0px;
  background-color: transparent;
  border-bottom:1px solid rgba(255,255,255,.5);
  transition: all 500ms cubic-bezier(0, 0.110, 0.35, 2);
}</style>