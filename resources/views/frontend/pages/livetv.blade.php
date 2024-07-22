@extends('frontend/partials/master')

@section('stylesheets-bottom')
    <style>
        nav#mainNav {
            background-color: #004E92;
        }
    </style>

    <link rel="stylesheet" href="{{ asset('frontend/owl.carousel.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('frontend/owl.theme.default.css') }}" />
    <script src="{{ asset('frontend/owl.carousel.min.js') }}"></script>

    <script>
        /*$('.live-news-carousel').owlCarousel({
            		    center: true,
            		    items:6,
            		    loop:true,
            		    margin:10,
            		    nav: true,
            		    responsive:{
            		        600:{
            		            items:4
            		        }
            		    }

            		});*/
        $(document).ready(function() {
            var owl = $('.owl-carousel');
            $.ajaxSetup({

                headers: {

                    'X-CSRF-TOKEN': $("input[name=_token]").val()

                }

            });
            owl.owlCarousel({
                slideBy: 5,
                loop: false,

                rewind: true,
                autoWidth: true,
                autoHeight: true,
                margin: 20,
                nav: true,
                responsiveClass: true,
                responsive: {
                    0: {
                        items: 1,
                        nav: true
                    },
                    600: {
                        items: 2,
                        nav: false
                    },
                    1000: {
                        items: 5,
                        nav: true,
                        loop: false
                    }
                }
            });

            $(document).on('click', '.btn-submit', function(e) {


                e.preventDefault();
                let channelid = $(this).data('id');


                $.ajax({

                    type: 'POST',

                    url: "{{ url('favorite') }}",

                    data: {
                        channelid: channelid,
                    },

                    success: function(data) {
                        $('#' + channelid).html('<p>Added to favorites</p> <span data-id=' +
                            channelid +
                            ' class=" btn-remove" style="color: red;font-size:2em">♥</span>'
                        );

                    }

                });



            });
            $(document).on('click', '.btn-remove', function(e) {




                e.preventDefault();
                let channelid = $(this).data('id');

                $.ajax({

                    type: 'POST',

                    url: "{{ url('unfavorite') }}",

                    data: {
                        channelid: channelid,
                    },

                    success: function(data) {

                        $('#' + channelid).html('<p>Add to favorites</p> <span data-id=' +
                            channelid +
                            ' class=" btn-submit" style="color: red;font-size:2em" >♡</span>'
                        );

                    }

                });



            });
        });
    </script>

    <style>
        body {
            background: #F9F9F9 0% 0% no-repeat padding-box;
        }

        /* .owl-carousel .owl-item img {
              display: block;
              width: auto;
              margin: 0 auto;
              height: 1.5rem;
              } */
        .channelogosame {

            display: block;
            margin: 0 auto;
            height: 100px;
            width: 100px;
        }

        .item {

            background-color: white;
            padding: 2rem 0;
            text-align: center;
            background: #FFFFFF 0% 0% no-repeat padding-box;
            box-shadow: 0px 3px 6px #00000029;
            border-radius: 8px;
        }

        .owl-nav {
            position: absolute;
            top: 0;
            width: 100%;
            line-height: 0.7;
            font-size: 2rem;
            height: 100%;
            z-index: -1;
        }

        .owl-nav button.disabled span {
            color: #21252961;
            cursor: auto;
        }

        .owl-nav button.disabled span:focus {
            outline: none;
        }

        .owl-nav button.owl-prev {
            float: left;
            position: relative;
            left: -20px;
        }

        .owl-nav button {
            top: 30%;
        }

        .owl-nav button.owl-next {
            float: right;
            position: relative;
            right: -20px;
        }

        .page-section {
            padding: 8rem 0;
        }

        .arrow-custom-car button.owl-prev {
            float: right !important;
            top: -30px !important;
        }

        .arrow-custom-car button.owl-next {
            top: -30px;
        }

        /* .owl-stage-outer {
               height: 100%;
              } */
        .popupwarning {
            display: none;
            position: absolute;
            padding: 30px 20px;
            margin: auto;
            width: 40vw;
            height: fit-content;
            text-align: center;
            left: 0;
            right: 0;
            top: 0;
            bottom: 0;
            z-index: 999;
            background-color: #004e92;
            color: #fff;
            border-radius: 5px;
            transition: background-color 0.5s ease-out;
        }

        .button {
            background-color: #fff;
            border: none;
            color: #000;
            padding: 6px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
        }

        .geolocation {
            text-align: center;
            margin-top: 1vw;
        }

        .mixs {
            filter: blur(2px);
            pointer-events: none;
        }

        .mixm {
            filter: blur(0px) !important;
            pointer-events: visible !important;
        }
    </style>
@endsection

@section('content')
    <div id="nodeElement">
    </div>

    <section id="blur" class="page-section">

        @foreach ($pagechannel as $key => $user)
            <div class="container">

                <h4 class="section-heading">{{ $key }} <a style="font-size:15px"
                        href="/livetv/{{ $key }}">show all</a></h4>
                <div class="row">
                    <div class="live-news-carousel owl-carousel">
                        @foreach ($user as $channeldet)
                            <div class="live-news-item item" style="width:200px;height:280px">

                                <a href="{{ url('livetv/' . $channeldet->CallSign . '/' . $key) }}">

                                    <div class="channelogosame"> <img
                                            style='background-color: #4D5659; height: 100%; width: 100%; object-fit: contain; padding:1vw'
                                            class="lazy" data-src="/uploads/channellogo/{{ $channeldet->image }}"
                                            loading="lazy" alt="{{ $channeldet->name }}"></div>

                                    @if ($channeldet->id == '11')
                                        <p
                                            style=" max-width: auto;
							padding: 0px 10px;
							overflow: hidden;
							white-space: nowrap;
							text-overflow: ellipsis;">
                                            TVG: Horse Racing</p>
                                    @else
                                        <p
                                            style=" max-width: auto;
							padding: 0px 10px;
							overflow: hidden;
							white-space: nowrap;
							text-overflow: ellipsis;">
                                            {{ $channeldet->StationName ?? $channeldet->name }}</p>
                                    @endif
                                </a>
                                <form> <input type="hidden" name="_token" value="{{ csrf_token() }}">



                                    <input type="hidden" name="channelid" value="{{ $channeldet->id }}"
                                        class="form-control">

                                    @if (Auth::check())
                                        <div class="panel-footer" id="{{ $channeldet->id }}">
                                            {{-- {{ $channeldet->id }} --}}
                                            @if ($channeldet->favorited())
                                                <p>Added to favorites</p> <span data-id="{{ $channeldet->id }}"
                                                    class=" btn-remove" style="color: red; font-size:2em">♥</span>
                                            @else
                                                <p>Add to favorites</p> <span data-id="{{ $channeldet->id }}"
                                                    class=" btn-submit" style="color: red; font-size:2em">♡</span>
                                            @endif

                                        </div>
                                    @endif
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach


    </section>
    <script>
        const div = document.createElement('div');
        div.className = 'row';
        div.innerHTML = `
	   <div id="hideElement" class="popupwarning">
	   You are away from your home zip code: click here to continue.
	   <div class="geolocation">
	   <button class="button" onclick="showWarning()">OK</button>
	   </div>
	   </div>
	   `;
        document.getElementById('nodeElement').appendChild(div);
    </script>
@endsection

<script>
    (function() {
        var base_url = window.location.origin;
        let url = base_url + '/api/auth/frontendepg';
        fetch(url).then(
            function(u) {
                return u.json();
            }
        ).then(
            function(json) {
                localStorage.setItem("mychanneldata", JSON.stringify(json.data));
            }
        )
    })();

    var x = document.getElementById("demo");


    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);
    } else {
        x.innerHTML = "Geolocation is not supported by this browser.";
    }

    function showPosition(position) {
        var latitude = position.coords.latitude;
        var longitude = position.coords.longitude;

        var myHeaders = new Headers();
        myHeaders.append("Accept", "application/json");
        myHeaders.append("Authorization", "Bearer {{ session()->get('jwttoken') }}");

        var formdata = new FormData();
        formdata.append("latitude", latitude);
        formdata.append("longitude", longitude);

        var requestOptions = {
            method: 'POST',
            headers: myHeaders,
            body: formdata,
            redirect: 'follow'
        };

        fetch("https://streamlytv.com/api/auth/geofence", requestOptions)
            .then(response => response.json())
            .then(result => {
                if ("Not within zipcode boundary" == result.message) {
                    //console.log("{{ session()->get('key1') }}");
                    if ("{{ session()->get('key1') }}") {

                    } else {
                        const targetDiv = document.getElementById("hideElement");
                        targetDiv.style.display = "block";
                        $('#blur').addClass('mixs');
                    }

                } else {
                    //    console.log("no");
                }
            })
            .catch(error => console.log('error', error));

    }


    function showWarning() {

        <?php session(['key1' => 1]); ?>
        const targetDiv = document.getElementById("hideElement");

        targetDiv.style.display = "none";

        $('#blur').addClass('mixm');

    }
</script>
<!-- The core Firebase JS SDK is always required and must be listed first -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.3.2/firebase.js"></script>
<script>
    var firebaseConfig = {
        apiKey: "AIzaSyDZUPtq5LXm8bbcX5P-z0r-B08V65xq2aw",
        authDomain: "streamly-tv-android.firebaseapp.com",
        databaseURL: "https://streamly-tv-android.firebaseio.com",
        projectId: "streamly-tv-android",
        storageBucket: "streamly-tv-android.appspot.com",
        messagingSenderId: "187207008307",
        appId: "1:187207008307:web:40473f0b50d96ab0d76346",
        measurementId: "G-5DTH6FMY71"
    };
    firebase.initializeApp(firebaseConfig);
    const messaging = firebase.messaging();
    messaging
        .requestPermission()
        .then(function() {
            return messaging.getToken()
        })
        .then(function(response) {
            console.log(response);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $("input[name=_token]").val()
                }
            });
            $.ajax({
                url: '{{ route('store.token') }}',
                type: 'POST',
                data: {
                    token: response,
                },
                dataType: 'JSON',
                success: function(response) {
                    alert('Token stored.');
                },
                error: function(error) {
                    //alert('');
                },
            });
        }).catch(function(error) {
            //alert('');
        });
    messaging.onMessage(function(payload) {
        const title = payload.notification.title;
        const options = {
            body: payload.notification.body,
            icon: payload.notification.icon,
        };
        new Notification(title, options);
    });
</script>
