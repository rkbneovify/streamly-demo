
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
          
          <li class="nav-item ">
            <a href=" {{ url('/admin') }}"  class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
              Dashboard
                <span class="right badge badge-danger">New</span>
              </p>
            </a>
          </li>
          @role(['admin', 'superadmin'])
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-copy"></i>
              <p>
                User Management
                <i class="fas fa-angle-left right"></i>
                <!-- <span class="badge badge-info right">6</span> -->
              </p>
            </a>
            <ul class="nav nav-treeview">
              
              <li class="nav-item">
                <a href=" {{ url('/admin/users') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>User</p>
                </a>
              </li><li class="nav-item">
                <a href=" {{ url('/admin/users/session') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>User sessions</p>
                  </a>
                </li>
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-copy"></i>
              <p>
                Hotel Management
                <i class="fas fa-angle-left right"></i>
                <!-- <span class="badge badge-info right">6</span> -->
              </p>
            </a>
            <ul class="nav nav-treeview">
              
              <li class="nav-item">
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
              <li class="nav-item">
                <a href=" {{ url('/admin/hotelroomsess') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Hotel rooms</p>
                  </a>
                </li>
            </ul>
          </li>
          
          <li class="nav-item has-treeview">
                <a href=" {{ url('/admin/subscriptions') }}" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
                Subscriptions
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
              <a href=" {{ url('/admin/subscriptions') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Subscription manage</p>
                </a>
              </li>
            </ul><ul class="nav nav-treeview">
              <li class="nav-item">
              <a href=" {{ url('/admin/subscriptionpack') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Subscriptionpack manage</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview">
                <a href=" {{ url('/admin/channels') }}" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
                Channels
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
              <a href=" {{ url('/admin/channels') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Gracenote manage</p>
                </a>
              </li><li class="nav-item">
              <a href=" {{ url('/admin/channelstatus') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Channel status</p>
                </a>
              </li><li class="nav-item">
              <a href=" {{ url('/admin/hlsmonitor') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>HLS analyzer</p>
                </a>
              </li>
            </ul>
          <li class="nav-item has-treeview">
                <a href=" {{ url('/admin/zipcode') }}" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
                zipcodes
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
              <a href=" {{ url('/admin/zipcode') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Zipcode manage</p>
                </a>
              </li>
              <li class="nav-item">
              <a href=" {{ url('/admin/allowedzipcode') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Allowed Zipcode</p>
                </a>
              </li><li class="nav-item">
                <a href=" {{ url('/admin/zipcode/map') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Allowed Zipcode on map</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href=" {{ url('/admin/zipcode/checkfenceweb') }}" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Check coordinate on map</p>
                    </a>
                  </li>
            </ul>
            <li class="nav-item has-treeview">
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
              <li class="nav-item">
                <a href=" {{ url('/admin/report/usersdetails') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>User Details</p>
                  </a>
                </li>
                <li class="nav-item">
                <a href=" {{ url('/admin/report/zipcode') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Zipcode</p>
                  </a>
                </li>
                <li class="nav-item">
                <a href=" {{ url('/admin/report/transactionreport/DESC') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Transaction Reports</p>
                  </a>
                </li>
                <li class="nav-item">
                <a href=" {{ url('/admin/report/demographic') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Demographic Details</p>
                  </a>
                </li>
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-sliders-h"></i>
              <p>
                App Slider
                <i class="fas fa-angle-left right"></i>
                <!-- <span class="badge badge-info right">6</span> -->
              </p>
            </a>
            <ul class="nav nav-treeview">
              
              <li class="nav-item">
                <a href=" {{ url('/admin/slide/create') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add slide</p>
                </a>
              </li><li class="nav-item">
                <a href=" {{ url('/admin/slide/view') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>View all slides</p>
                  </a>
                </li>
            </ul>
          </li>
          <li class="nav-item ">
              <a href="#"  class="nav-link">
              <i class="nav-icon fas fa-image"></i>
                <p>
                App Banner
                <i class="fas fa-angle-left right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
              
              <li class="nav-item">
                <a href=" {{ url('/admin/banner/create') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add banner</p>
                </a>
              </li><li class="nav-item">
                <a href=" {{ url('/admin/banner/view') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>View banner</p>
                  </a>
                </li>
            </ul>
            </li>
            <li class="nav-item ">
              <a href=" {{ url('/admin/frontendtext') }}"  class="nav-link">
              <i class="nav-icon fas fa-edit"></i>
                <p>
                Frontend Text
                </p>
              </a>
            </li><li class="nav-item ">
              <a href=" {{ url('/admin/address') }}"  class="nav-link">
              <i class="nav-icon fas fa-address-card"></i>
                <p>
                Address availibilty
                </p>
              </a>
            </li>            <li class="nav-item ">
              <a href=" {{ url('/admin/Outsidelocationplay') }}"  class="nav-link">
              <i class="nav-icon fas fa-search-location"></i>
                <p>
                  Outside Location Play
                </p>
              </a>
            </li><li class="nav-item ">
              <a href=" {{ url('/admin/declinezipinquiries') }}"  class="nav-link">
              <i class="nav-icon fas fa-search-location"></i>
                <p>
                  Declined zip inquiries
                </p>
              </a>
            </li><li class="nav-item ">
              <a href=" {{ url('/admin/bugreport') }}"  class="nav-link">
              <i class="nav-icon fas fa-search-location"></i>
                <p>
                  Bug report
                </p>
              </a>
            </li>
             <!-- add Naresh 15-11-2022 -->
             <li class="nav-item ">
              <a href=" {{ url('/admin/affiliatereport') }}"  class="nav-link">
              <i class="nav-icon fas fa-file"></i>
                <p>
                 Affiliate Report
                </p>
              </a>
            </li>
            <!-- End Naresh 15-11-2022-->
            <!-- <ul class="nav nav-treeview">
              <li class="nav-item">
              <a href=" {{ url('/admin/subscriptionpack') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Subscriptionpack manage</p>
                </a>
              </li>
            </ul> 
          </li>
          @endrole
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

