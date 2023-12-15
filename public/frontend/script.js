/*!
    * Start Bootstrap - Agency v6.0.0 (https://startbootstrap.com/template-overviews/agency)
    * Copyright 2013-2020 Start Bootstrap
    * Licensed under MIT (https://github.com/BlackrockDigital/startbootstrap-agency/blob/master/LICENSE)
    */
    (function ($) {
    "use strict"; // Start of use strict
    
    
    // Smooth scrolling using jQuery easing
    $('a.js-scroll-trigger[href*="#"]:not([href="#"])').click(function () {
        if (
            location.pathname.replace(/^\//, "") ==
                this.pathname.replace(/^\//, "") &&
            location.hostname == this.hostname
        ) {
            var target = $(this.hash);
            target = target.length
                ? target
                : $("[name=" + this.hash.slice(1) + "]");
            if (target.length) {
                $("html, body").animate(
                    {
                        scrollTop: target.offset().top - 72,
                    },
                    1000,
                    "easeInOutExpo"
                );
                return false;
            }
        }
    });

    // Closes responsive menu when a scroll trigger link is clicked
    $('.js-scroll-trigger').on('click',function(){
      $(".navbar-collapse").collapse("hide");
    });
    const $dropdown = $(".dropdown");
const $dropdownToggle = $(".dropdown-toggle");
const $dropdownMenu = $(".dropdown-menu");
const showClass = "show";
$('.navbar-toggler').on('click',function(){
  
  $dropdown.addClass(showClass);
  $dropdown.find($dropdownToggle).attr("aria-expanded", "true");
        $dropdown.find($dropdownMenu).addClass(showClass);



});



    // Activate scrollspy to add active class to navbar items on scroll
    $("body").scrollspy({
        target: "#mainNav",
        offset: 74,
    });

    // Collapse Navbar
    var navbarCollapse = function () {
        if ($("#mainNav").offset().top > 100) {
            $("#mainNav").addClass("navbar-shrink");
        } else {
            $("#mainNav").removeClass("navbar-shrink");
        }
    };
    // Collapse now if page is not at top
    navbarCollapse();
    // Collapse the navbar when page is scrolled
    $(window).scroll(navbarCollapse);
    
})(jQuery); // End of use strict


function openNav(event) {
    event.preventDefault();
    closeNavReg();
    closeNavPasscode();
    // document.getElementById("mySidenav").style.width = "34%";
    document.getElementById("mySidenav").classList.add("show");
    document.getElementById("email").focus();
}

function openNavPasscode(event) {
  event.preventDefault();
  closeNavReg();
  closeNav();
  // document.getElementById("mySidenav").style.width = "34%";
  document.getElementById("mySidenavPasscode").classList.add("show");
  document.getElementById("passcode").focus();
}

function openNavReg() {
    closeNav();
    closeNavPasscode();
    // document.getElementById("mySidenav").style.width = "34%";
    document.getElementById("mySidenavRegister").classList.add("show");
    document.getElementById("name").focus();
}

function closeNav() {

    // document.getElementById("mySidenav").style.width = "0";
    document.getElementById("mySidenav").classList.remove("show");
}

function logoutUser() {
    window.location.href = '/logout';
}

function closeNavReg() {
    document.getElementById("mySidenavRegister").classList.remove("show");
}

function closeNavPasscode() {
  document.getElementById("mySidenavPasscode").classList.remove("show");
}

window.setTimeout(function() {
    $(".alert-position").slideUp(500, function() {
        $(this).remove();
    });
}, 3000);

jQuery(document).ready(function(){

    jQuery('.logouttheuser').on('click', function(){
        jQuery('#Logout').modal('show');
    });

});

document.addEventListener("DOMContentLoaded", function() {
    var lazyloadImages;    
  
    if ("IntersectionObserver" in window) {
      lazyloadImages = document.querySelectorAll(".lazy");
      var imageObserver = new IntersectionObserver(function(entries, observer) {
        entries.forEach(function(entry) {
          if (entry.isIntersecting) {
            var image = entry.target;
            image.src = image.dataset.src;
            image.classList.remove("lazy");
            imageObserver.unobserve(image);
          }
        });
      });
  
      lazyloadImages.forEach(function(image) {
        imageObserver.observe(image);
      });
    } else {  
      var lazyloadThrottleTimeout;
      lazyloadImages = document.querySelectorAll(".lazy");
      
      function lazyload () {
        if(lazyloadThrottleTimeout) {
          clearTimeout(lazyloadThrottleTimeout);
        }    
  
        lazyloadThrottleTimeout = setTimeout(function() {
          var scrollTop = window.pageYOffset;
          lazyloadImages.forEach(function(img) {
              if(img.offsetTop < (window.innerHeight + scrollTop)) {
                img.src = img.dataset.src;
                img.classList.remove('lazy');
              }
          });
          if(lazyloadImages.length == 0) { 
            document.removeEventListener("scroll", lazyload);
            window.removeEventListener("resize", lazyload);
            window.removeEventListener("orientationChange", lazyload);
          }
        }, 20);
      }
  
      document.addEventListener("scroll", lazyload);
      window.addEventListener("resize", lazyload);
      window.addEventListener("orientationChange", lazyload);
    }
  })
  
  
