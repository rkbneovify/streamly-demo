<!-- Navigation-->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNav">
    <div class="container">
        <a class="navbar-brand js-scroll-trigger" href="#page-top"><img src="{{ asset('frontend/img/streamly-logo.png') }}" /></a>

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

                    <li class="nav-item"><a class="nav-link js-scroll-trigger btn-login" onclick="logoutUser(event)" href="javascript:void(0)">Logout</a></li>
                @else

                    <li class="nav-item"><a class="nav-link js-scroll-trigger btn-login" onclick="openNav(event)" href="javascript:void(0)">Log In</a></li>

                @endif
            </ul>
        </div>
    </div>
</nav>
