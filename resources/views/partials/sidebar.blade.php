<style>
.activeElement {
  background-color: rgba(255,255,255,.1)!important;
}
</style>
  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/" class="brand-link">
      <img src="{{ asset('dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">Streamly</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ asset('dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{ Auth::user()->name }} </a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          @role(['salesuser'])
          <li class="nav-item ">
            <a href="#"  class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
              Dashboard
                <span class="right badge badge-danger">New</span>
              </p>
            </a>
          </li>
          <li class="nav-item has-treeview {{ (request()->is('salesuser/add_masteruser')) ? 'menu-open' : '' }}">
            <a href=" {{ url('/salesuser/add_masteruser') }}" class="nav-link">
              <i class="nav-icon fas fa-user-plus"></i>
              <p>
                Add Master User
              </p>
            </a>
          </li>
          <li class="nav-item has-treeview {{ (request()->is('salesuser/paymentlink')) ? 'menu-open' : '' }}">
            <a href=" {{ url('/salesuser/paymentlink') }}" class="nav-link">
              <i class="nav-icon fas fa-link"></i>
              <p>
                Create Payment
              </p>
            </a>
          </li>
          <li class="nav-item has-treeview {{ (request()->is('salesuser/masteruserlist')) ? 'menu-open' : '' }}">
            <a href=" {{ url('/salesuser/masteruserlist') }}" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Master Users
              </p>
            </a>
          </li>
          <li class="nav-item has-treeview {{ (request()->is('salesuser/paymentlist')) ? 'menu-open' : '' }}">
            <a href=" {{ url('/salesuser/paymentlist') }}" class="nav-link">
              <i class="nav-icon fas fa-credit-card"></i>
              <p>
                Payment Status
              </p>
            </a>
          </li>
          @endrole
          @role(['admin', 'superadmin'])
          <li class="nav-item ">
            <a href=" {{ url('/admin') }}"  class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
              Dashboard
                <span class="right badge badge-danger">New</span>
              </p>
            </a>
          </li>
          <li class="nav-item has-treeview {{ (request()->is('admin/stripekeys')) ? 'menu-open' : '' }}">
            <a href=" {{ url('/admin/stripekeys') }}" class="nav-link">
              <i class="nav-icon fas fa-key"></i>
              <p>
                Stripe Keys
              </p>
            </a>
          </li>
          <li class="nav-item has-treeview {{ (request()->is('admin/users')) ? 'menu-open' : '' }}{{ (request()->is('admin/users/session')) ? 'menu-open' : '' }}">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-copy"></i>
              <p>
                User Management
                <i class="fas fa-angle-left right"></i>
                <!-- <span class="badge badge-info right">6</span> -->
              </p>
            </a>
            <ul class="nav nav-treeview">

              <li class="nav-item {{ (request()->is('admin/users')) ? 'activeElement' : '' }}">
                <a href=" {{ url('/admin/users') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>User</p>
                </a>
              </li><li class="nav-item {{ (request()->is('admin/users/session')) ? 'activeElement' : '' }}">
                <a href=" {{ url('/admin/users/session') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>User sessions</p>
                  </a>
                </li>
            </ul>
          </li>
          <li class="nav-item has-treeview {{ (request()->is('admin/hotelrooms')) ? 'menu-open' : '' }} {{ (request()->is('admin/hotelroomsess')) ? 'menu-open' : '' }}">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-copy"></i>
              <p>
                Hotel Management
                <i class="fas fa-angle-left right"></i>
                <!-- <span class="badge badge-info right">6</span> -->
              </p>
            </a>
            <ul class="nav nav-treeview">

              <li class="nav-item {{ (request()->is('admin/hotelrooms')) ? 'activeElement' : '' }}">
                <a href=" {{ url('/admin/hotelrooms') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Hotel users</p>
                </a>
              </li>
              <!-- <li class="nav-item">
                <a href=" {{ url('/admin/hotelcontracts') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Hotel contracts</p>
                  </a>
                </li> -->
              <li class="nav-item {{ (request()->is('admin/hotelroomsess')) ? 'activeElement' : '' }}">
                <a href=" {{ url('/admin/hotelroomsess') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Hotel rooms</p>
                  </a>
                </li>
            </ul>
          </li>

          <li class="nav-item has-treeview {{ (request()->is('admin/subscriptions')) ? 'menu-open' : '' }} {{ (request()->is('admin/subscriptionpack')) ? 'menu-open' : '' }}">
                <a href=" {{ url('/admin/subscriptions') }}" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
                Subscriptions
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item {{ (request()->is('admin/subscriptions')) ? 'activeElement' : '' }}">
              <a href=" {{ url('/admin/subscriptions') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Subscription manage</p>
                </a>
              </li>
            </ul><ul class="nav nav-treeview">
              <li class="nav-item {{ (request()->is('admin/subscriptionpack')) ? 'activeElement' : '' }}">
              <a href=" {{ url('/admin/subscriptionpack') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Subscriptionpack manage</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview {{ (request()->is('admin/channels')) ? 'menu-open' : '' }}{{ (request()->is('admin/channelstatus')) ? 'menu-open' : '' }}{{ (request()->is('admin/hlsmonitor')) ? 'menu-open' : '' }}">
                <a href=" {{ url('/admin/channels') }}" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
                Channels
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item {{ (request()->is('admin/channels')) ? 'activeElement' : '' }}">
              <a href=" {{ url('/admin/channels') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Gracenote manage</p>
                </a>
              </li><li class="nav-item {{ (request()->is('admin/channelstatus')) ? 'activeElement' : '' }}">
              <a href=" {{ url('/admin/channelstatus') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Channel status</p>
                </a>
              </li></li><li class="nav-item {{ (request()->is('admin/channelstore')) ? 'activeElement' : '' }}">
                <a href=" {{ url('/admin/channelstore') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Channel Store Status</p>
                  </a>
                </li><li class="nav-item {{ (request()->is('admin/hlsmonitor')) ? 'activeElement' : '' }}">
              <a href=" {{ url('/admin/hlsmonitor') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>HLS analyzer</p>
                </a>
              </li>
            </ul>

            <li class="nav-item has-treeview {{ (request()->is('admin/allgenre')) ? 'menu-open' : '' }}{{ (request()->is('admin/addgenre')) ? 'menu-open' : '' }}">
                <a href=" {{ url('/admin/allgenre') }}" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
                Genres
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item {{ (request()->is('admin/addgenre')) ? 'activeElement' : '' }}">
              <a href=" {{ url('/admin/addgenre') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Genre</p>
                </a>
              </li><li class="nav-item {{ (request()->is('admin/allgenre')) ? 'activeElement' : '' }}">
              <a href=" {{ url('/admin/allgenre') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>View Genre</p>
                </a>
            </ul>

          <li class="nav-item has-treeview {{ (request()->is('admin/zipcode')) ? 'menu-open' : '' }}{{ (request()->is('admin/allowedzipcode')) ? 'menu-open' : '' }}{{ (request()->is('admin/zipcode/map')) ? 'menu-open' : '' }}{{ (request()->is('admin/zipcode/checkfenceweb')) ? 'menu-open' : '' }}">
                <a href=" {{ url('/admin/zipcode') }}" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
                zipcodes
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item {{ (request()->is('admin/zipcode')) ? 'activeElement' : '' }}">
              <a href=" {{ url('/admin/zipcode') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Zipcode manage</p>
                </a>
              </li>
              <li class="nav-item {{ (request()->is('admin/allowedzipcode')) ? 'activeElement' : '' }}">
              <a href=" {{ url('/admin/allowedzipcode') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Allowed Zipcode</p>
                </a>
              </li>
              <!-- <li class="nav-item {{ (request()->is('admin/zipcode/map')) ? 'activeElement' : '' }}">
                <a href=" {{ url('/admin/zipcode/map') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Allowed Zipcode on map</p>
                  </a>
                </li>
                <li class="nav-item {{ (request()->is('admin/zipcode/checkfenceweb')) ? 'activeElement' : '' }}">
                  <a href=" {{ url('/admin/zipcode/checkfenceweb') }}" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Check coordinate on map</p>
                    </a>
                  </li> -->
            </ul>
            <li class="nav-item has-treeview {{ (request()->is('admin/report/usersdetails')) ? 'menu-open' : '' }}{{ (request()->is('admin/report/zipcode')) ? 'menu-open' : '' }}{{ (request()->is('admin/report/subscriptionsreport/DESC')) ? 'menu-open' : '' }}{{ (request()->is('admin/report/transactionsreport')) ? 'menu-open' : '' }}{{ (request()->is('admin/report/demographic')) ? 'menu-open' : '' }}">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-copy"></i>
              <p>
                Reports
                <i class="fas fa-angle-left right"></i>
                <!-- <span class="badge badge-info right">6</span> -->
              </p>
            </a>
            <ul class="nav nav-treeview">

              <!-- <li class="nav-item">
                <a href=" {{ url('/admin/report/channelanalysis') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Channel Analysis</p>
                </a>
              </li> -->
              <li class="nav-item {{ (request()->is('admin/report/usersdetails')) ? 'activeElement' : '' }}">
                <a href=" {{ url('/admin/report/usersdetails') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>User Details</p>
                  </a>
                </li>
                <li class="nav-item {{ (request()->is('admin/report/zipcode')) ? 'activeElement' : '' }}">
                <a href=" {{ url('/admin/report/zipcode') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Zipcode</p>
                  </a>
                </li>
                <li class="nav-item {{ (request()->is('admin/report/subscriptionsreport/DESC')) ? 'activeElement' : '' }}">
                <a href=" {{ url('/admin/report/subscriptionsreport/DESC') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Subscriptions Report</p>
                  </a>
                </li>
                <li class="nav-item {{ (request()->is('admin/report/transactionsreport')) ? 'activeElement' : '' }}">
                <a href=" {{ url('/admin/report/transactionsreport') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Transactions Report</p>
                  </a>
                </li>
                <li class="nav-item {{ (request()->is('admin/report/demographic')) ? 'activeElement' : '' }}">
                <a href=" {{ url('/admin/report/demographic') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Demographic Details</p>
                  </a>
                </li>
            </ul>
          </li>
          <li class="nav-item has-treeview {{ (request()->is('admin/channel-slider-create')) ? 'menu-open' : '' }}{{ (request()->is('admin/slide/view')) ? 'menu-open' : '' }}">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-sliders-h"></i>
              <p>
                Channel Slider
                <i class="fas fa-angle-left right"></i>
                <!-- <span class="badge badge-info right">6</span> -->
              </p>
            </a>
            <ul class="nav nav-treeview">

              <li class="nav-item {{ (request()->is('admin/channel-slider-create')) ? 'activeElement' : '' }}">
                <a href=" {{ url('/admin/channel-slider-create') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add slide</p>
                </a>
              </li><li class="nav-item {{ (request()->is('admin/slide/view')) ? 'activeElement' : '' }}">
                <a href=" {{ url('/admin/all-channel-sliders') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>View all slides</p>
                  </a>
                </li>
            </ul>
          </li>
          <li class="nav-item has-treeview {{ (request()->is('admin/slide/create')) ? 'menu-open' : '' }}{{ (request()->is('admin/slide/view')) ? 'menu-open' : '' }}">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-sliders-h"></i>
              <p>
                App Slider
                <i class="fas fa-angle-left right"></i>
                <!-- <span class="badge badge-info right">6</span> -->
              </p>
            </a>
            <ul class="nav nav-treeview">

              <li class="nav-item {{ (request()->is('admin/slide/create')) ? 'activeElement' : '' }}">
                <a href=" {{ url('/admin/slide/create') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add slide</p>
                </a>
              </li><li class="nav-item {{ (request()->is('admin/slide/view')) ? 'activeElement' : '' }}">
                <a href=" {{ url('/admin/slide/view') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>View all slides</p>
                  </a>
                </li>
            </ul>
          </li>
          <li class="nav-item {{ (request()->is('admin/banner/create')) ? 'menu-open' : '' }}{{ (request()->is('admin/banner/view')) ? 'menu-open' : '' }}">
              <a href="#"  class="nav-link">
              <i class="nav-icon fas fa-image"></i>
                <p>
                App Banner
                <i class="fas fa-angle-left right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">

              <li class="nav-item {{ (request()->is('admin/banner/create')) ? 'activeElement' : '' }}">
                <a href=" {{ url('/admin/banner/create') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add banner</p>
                </a>
              </li><li class="nav-item {{ (request()->is('admin/banner/view')) ? 'activeElement' : '' }}">
                <a href=" {{ url('/admin/banner/view') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>View banner</p>
                  </a>
                </li>
            </ul>
            </li>
            <li class="nav-item {{ (request()->is('admin/frontendtext')) ? 'menu-open' : '' }}">
              <a href=" {{ url('/admin/frontendtext') }}"  class="nav-link">
              <i class="nav-icon fas fa-edit"></i>
                <p>
                Frontend Text
                </p>
              </a>
            </li><li class="nav-item {{ (request()->is('admin/address')) ? 'menu-open' : '' }}">
              <a href=" {{ url('/admin/address') }}"  class="nav-link">
              <i class="nav-icon fas fa-address-card"></i>
                <p>
                Address availibilty
                </p>
              </a>
            </li>            <li class="nav-item {{ (request()->is('admin/Outsidelocationplay')) ? 'menu-open' : '' }}">
              <a href=" {{ url('/admin/Outsidelocationplay') }}"  class="nav-link">
              <i class="nav-icon fas fa-search-location"></i>
                <p>
                  Outside Location Play
                </p>
              </a>
            </li><li class="nav-item {{ (request()->is('admin/declinezipinquiries')) ? 'menu-open' : '' }}">
              <a href=" {{ url('/admin/declinezipinquiries') }}"  class="nav-link">
              <i class="nav-icon fas fa-search-location"></i>
                <p>
                  Declined zip inquiries
                </p>
              </a>
            </li><li class="nav-item {{ (request()->is('admin/bugreport')) ? 'menu-open' : '' }}">
              <a href=" {{ url('/admin/bugreport') }}"  class="nav-link">
              <i class="nav-icon fas fa-search-location"></i>
                <p>
                  Bug report
                </p>
              </a>
            </li>
             <!-- add Naresh 15-11-2022 -->
             <li class="nav-item {{ (request()->is('admin/affiliatereport')) ? 'menu-open' : '' }}">
              <a href=" {{ url('/admin/affiliatereport') }}"  class="nav-link">
              <i class="nav-icon fas fa-file"></i>
                <p>
                 Affiliate Report
                </p>
              </a>
            </li>
            <li class="nav-item has-treeview {{ (request()->is('admin/faq')) ? 'menu-open' : '' }}{{ (request()->is('admin/chatboat_category')) ? 'menu-open' : '' }}{{ (request()->is('admin/chat-history')) ? 'menu-open' : '' }}">
                <a href=" {{ url('/admin/faq') }}" class="nav-link">
              <i class="nav-icon fa fa-question-circle"></i>
              <p>
                FAQ
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
              <a href=" {{ url('/admin/faq') }}" class="nav-link {{ (request()->is('admin/faq')) ? 'activeElement' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>FAQ</p>
                </a>
              </li>
              <li class="nav-item">
              <a href=" {{ url('/admin/chatboat_category') }}" class="nav-link {{ (request()->is('admin/chatboat_category')) ? 'activeElement' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Faq Category</p>
                </a>
              </li>
              <li class="nav-item">
              <a href=" {{ url('/admin/chat-history') }}" class="nav-link {{ (request()->is('admin/chat-history')) ? 'activeElement' : '' }}">
                  <i class="fa fa-history nav-icon"></i>
                  <p>Chat History</p>
                </a>
              </li>
            </ul>
          </li>
          @endrole
            <!-- <ul class="nav nav-treeview">
              <li class="nav-item">
              <a href=" {{ url('/admin/subscriptionpack') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Subscriptionpack manage</p>
                </a>
              </li>
            </ul>
          </li>
          <!-- <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-tree"></i>
              <p>
                UI Elements
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="pages/UI/general.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>General</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/UI/icons.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Icons</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/UI/buttons.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Buttons</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/UI/sliders.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Sliders</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/UI/modals.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Modals & Alerts</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/UI/navbar.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Navbar & Tabs</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/UI/timeline.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Timeline</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/UI/ribbons.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Ribbons</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-edit"></i>
              <p>
                Forms
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="pages/forms/general.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>General Elements</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/forms/advanced.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Advanced Elements</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/forms/editors.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Editors</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/forms/validation.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Validation</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-table"></i>
              <p>
                Tables
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="pages/tables/simple.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Simple Tables</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/tables/data.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>DataTables</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/tables/jsgrid.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>jsGrid</p>
                </a>
              </li>

            </ul> -->

            <li class="nav-item ">
            <a href="{{ route('logout') }}"  class="nav-link" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt"></i>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
              <p>
              Logout

              </p>
            </a>
          </li>

      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

