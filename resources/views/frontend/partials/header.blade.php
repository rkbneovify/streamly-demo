<!-- Navigation-->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNav">
    <div class="container">
        <a class="navbar-brand js-scroll-trigger" href="/"><img src="{{ asset('frontend/img/streamly-logo.png') }}" /></a>

        @if( isset($login) && $login)

            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive1" aria-controls="navbarResponsive" aria-expanded="false" onclick="logoutUser()" aria-label="Toggle navigation">Logout<i class="fas fa-bars ml-1"></i></button>

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
                            <img class="form-control-feedback" src="{{ asset('frontend/img/search.svg') }}" />

                            <input type="text" name="search" class="form-control" id="search-btn-header" placeholder="Search channels or shows">
                        </div>
                    </li>

                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-list-4" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbar-list-4">
                        <ul class="navbar-nav">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <img src="{{ asset('frontend/img/noun_profile.svg') }}" width="40" height="40" class="rounded-circle" />
                                </a>
                                <div class="dropdown-menu navbarDropdownMenuLink text-center" aria-labelledby="navbarDropdownMenuLink">
                                    <a class="dropdown-item" href="/account/settings">{{ Auth::user()->name }}</a>
                                    <a class="dropdown-item" href="/account/settings">Account Settings</a>
                                    <a class="dropdown-item" href="#">Download app</a>
                                    <a class="dropdown-item" href="#">Help & Support</a>
                                    <a class="dropdown-item js-scroll-trigger btn-login" onclick="logoutUser(event)" href="javascript:void(0)">Logout</a>
                                </div>
                            </li>   
                        </ul>
                    </div>

                @else

                    <li class="nav-item"><a class="nav-link js-scroll-trigger btn-login" onclick="openNav(event)" href="javascript:void(0)">Log In</a></li>

                @endif
            </ul>
        </div>
    </div>
</nav>
