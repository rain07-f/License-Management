<div class="horizontal-menu">
  <nav class="navbar top-navbar">
    <div class="container">
      <div class="navbar-content">
						
        <a href="#" class="navbar-brand d-none d-lg-flex">
          Noble<span>UI</span>
        </a>
        
        <!-- Logo-mini for small screen devices (mobile/tablet) -->
        <div class="logo-mini-wrapper">
          <img src="{{ url('build/images/logo-mini-light.png') }}" class="logo-mini logo-mini-light" alt="logo">
          <img src="{{ url('build/images/logo-mini-dark.png') }}" class="logo-mini logo-mini-dark" alt="logo">
        </div>

        <form class="search-form">
          <div class="input-group">
            <div class="input-group-text">
              <i data-lucide="search"></i>
            </div>
            <input type="text" class="form-control" id="navbarForm" placeholder="Search here...">
          </div>
        </form>
        
        <ul class="navbar-nav">
          <li class="theme-switcher-wrapper nav-item">
            <input type="checkbox" value="" id="theme-switcher">
            <label for="theme-switcher">
              <div class="box">
                <div class="ball"></div>
                <div class="icons">
                  <i class="link-icon" data-lucide="sun"></i>
                  <i class="link-icon" data-lucide="moon"></i>
                </div>
              </div>
            </label>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle d-flex" href="#" id="languageDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <img src="{{ url('build/images/flags/us.svg') }}" class="w-20px" title="us" alt="flag">
              <span class="ms-2 d-none d-md-inline-block">English</span>
            </a>
            <div class="dropdown-menu" aria-labelledby="languageDropdown">
              <a href="javascript:;" class="dropdown-item py-2 d-flex"><img src="{{ url('build/images/flags/us.svg') }}" class="w-20px" title="us" alt="us"> <span class="ms-2"> English </span></a>
              <a href="javascript:;" class="dropdown-item py-2 d-flex"><img src="{{ url('build/images/flags/fr.svg') }}" class="w-20px" title="fr" alt="fr"> <span class="ms-2"> French </span></a>
              <a href="javascript:;" class="dropdown-item py-2 d-flex"><img src="{{ url('build/images/flags/de.svg') }}" class="w-20px" title="de" alt="de"> <span class="ms-2"> German </span></a>
              <a href="javascript:;" class="dropdown-item py-2 d-flex"><img src="{{ url('build/images/flags/pt.svg') }}" class="w-20px" title="pt" alt="pt"> <span class="ms-2"> Portuguese </span></a>
              <a href="javascript:;" class="dropdown-item py-2 d-flex"><img src="{{ url('build/images/flags/es.svg') }}" class="w-20px" title="es" alt="es"> <span class="ms-2"> Spanish </span></a>
            </div>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="appsDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i data-lucide="layout-grid"></i>
            </a>
            <div class="dropdown-menu p-0" aria-labelledby="appsDropdown">
              <div class="px-3 py-2 d-flex align-items-center justify-content-between border-bottom">
                <p class="mb-0 fw-bold">Web Apps</p>
                <a href="javascript:;" class="text-secondary">Edit</a>
              </div>
              <div class="row g-0 p-1">
                <div class="col-3 text-center">
                  <a href="{{ url('/apps/chat') }}" class="dropdown-item d-flex flex-column align-items-center justify-content-center w-70px h-70px"><i data-lucide="message-square" class="icon-lg mb-1"></i><p class="fs-12px">Chat</p></a>
                </div>
                <div class="col-3 text-center">
                  <a href="{{ url('/apps/calendar') }}" class="dropdown-item d-flex flex-column align-items-center justify-content-center w-70px h-70px"><i data-lucide="calendar" class="icon-lg mb-1"></i><p class="fs-12px">Calendar</p></a>
                </div>
                <div class="col-3 text-center">
                  <a href="{{ url('/email/inbox') }}" class="dropdown-item d-flex flex-column align-items-center justify-content-center w-70px h-70px"><i data-lucide="mail" class="icon-lg mb-1"></i><p class="fs-12px">Email</p></a>
                </div>
                <div class="col-3 text-center">
                  <a href="{{ url('/general/profile') }}" class="dropdown-item d-flex flex-column align-items-center justify-content-center w-70px h-70px"><i data-lucide="instagram" class="icon-lg mb-1"></i><p class="fs-12px">Profile</p></a>
                </div>
              </div>
              <div class="px-3 py-2 d-flex align-items-center justify-content-center border-top">
                <a href="javascript:;">View all</a>
              </div>
            </div>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="messageDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i data-lucide="mail"></i>
            </a>
            <div class="dropdown-menu p-0" aria-labelledby="messageDropdown">
              <div class="px-3 py-2 d-flex align-items-center justify-content-between border-bottom">
                <p>9 New Messages</p>
                <a href="javascript:;" class="text-secondary">Clear all</a>
              </div>
              <div class="p-1">
                <a href="javascript:;" class="dropdown-item d-flex align-items-center py-2">
                  <div class="me-3">
                    <img class="w-30px h-30px rounded-circle" src="{{ url('https://placehold.co/30x30') }}" alt="userr">
                  </div>
                  <div class="d-flex justify-content-between flex-grow-1">
                    <div class="me-4">
                      <p>Leonardo Payne</p>
                      <p class="fs-12px text-secondary">Project status</p>
                    </div>
                    <p class="fs-12px text-secondary">2 min ago</p>
                  </div>	
                </a>
                <a href="javascript:;" class="dropdown-item d-flex align-items-center py-2">
                  <div class="me-3">
                    <img class="w-30px h-30px rounded-circle" src="{{ url('https://placehold.co/30x30') }}" alt="userr">
                  </div>
                  <div class="d-flex justify-content-between flex-grow-1">
                    <div class="me-4">
                      <p>Carl Henson</p>
                      <p class="fs-12px text-secondary">Client meeting</p>
                    </div>
                    <p class="fs-12px text-secondary">30 min ago</p>
                  </div>	
                </a>
                <a href="javascript:;" class="dropdown-item d-flex align-items-center py-2">
                  <div class="me-3">
                    <img class="w-30px h-30px rounded-circle" src="{{ url('https://placehold.co/30x30') }}" alt="userr">
                  </div>
                  <div class="d-flex justify-content-between flex-grow-1">
                    <div class="me-4">
                      <p>Jensen Combs</p>
                      <p class="fs-12px text-secondary">Project updates</p>
                    </div>
                    <p class="fs-12px text-secondary">1 hrs ago</p>
                  </div>	
                </a>
                <a href="javascript:;" class="dropdown-item d-flex align-items-center py-2">
                  <div class="me-3">
                    <img class="w-30px h-30px rounded-circle" src="{{ url('https://placehold.co/30x30') }}" alt="userr">
                  </div>
                  <div class="d-flex justify-content-between flex-grow-1">
                    <div class="me-4">
                      <p>Amiah Burton</p>
                      <p class="fs-12px text-secondary">Project deatline</p>
                    </div>
                    <p class="fs-12px text-secondary">2 hrs ago</p>
                  </div>	
                </a>
                <a href="javascript:;" class="dropdown-item d-flex align-items-center py-2">
                  <div class="me-3">
                    <img class="w-30px h-30px rounded-circle" src="{{ url('https://placehold.co/30x30') }}" alt="userr">
                  </div>
                  <div class="d-flex justify-content-between flex-grow-1">
                    <div class="me-4">
                      <p>Yaretzi Mayo</p>
                      <p class="fs-12px text-secondary">New record</p>
                    </div>
                    <p class="fs-12px text-secondary">5 hrs ago</p>
                  </div>	
                </a>
              </div>
              <div class="px-3 py-2 d-flex align-items-center justify-content-center border-top">
                <a href="javascript:;">View all</a>
              </div>
            </div>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i data-lucide="bell"></i>
              <div class="indicator">
                <div class="circle"></div>
              </div>
            </a>
            <div class="dropdown-menu p-0" aria-labelledby="notificationDropdown">
              <div class="px-3 py-2 d-flex align-items-center justify-content-between border-bottom">
                <p>6 New Notifications</p>
                <a href="javascript:;" class="text-secondary">Clear all</a>
              </div>
              <div class="p-1">
                <a href="javascript:;" class="dropdown-item d-flex align-items-center py-2">
                  <div class="w-30px h-30px d-flex align-items-center justify-content-center bg-primary rounded-circle me-3">
                    <i class="icon-sm text-white" data-lucide="gift"></i>
                  </div>
                  <div class="flex-grow-1 me-2">
                    <p>New Order Recieved</p>
                    <p class="fs-12px text-secondary">30 min ago</p>
                  </div>	
                </a>
                <a href="javascript:;" class="dropdown-item d-flex align-items-center py-2">
                  <div class="w-30px h-30px d-flex align-items-center justify-content-center bg-primary rounded-circle me-3">
                    <i class="icon-sm text-white" data-lucide="alert-circle"></i>
                  </div>
                  <div class="flex-grow-1 me-2">
                    <p>Server Limit Reached!</p>
                    <p class="fs-12px text-secondary">1 hrs ago</p>
                  </div>	
                </a>
                <a href="javascript:;" class="dropdown-item d-flex align-items-center py-2">
                  <div class="w-30px h-30px d-flex align-items-center justify-content-center bg-primary rounded-circle me-3">
                    <img class="w-30px h-30px rounded-circle" src="{{ url('https://placehold.co/30x30') }}" alt="userr">
                  </div>
                  <div class="flex-grow-1 me-2">
                    <p>New customer registered</p>
                    <p class="fs-12px text-secondary">2 sec ago</p>
                  </div>	
                </a>
                <a href="javascript:;" class="dropdown-item d-flex align-items-center py-2">
                  <div class="w-30px h-30px d-flex align-items-center justify-content-center bg-primary rounded-circle me-3">
                    <i class="icon-sm text-white" data-lucide="layers"></i>
                  </div>
                  <div class="flex-grow-1 me-2">
                    <p>Apps are ready for update</p>
                    <p class="fs-12px text-secondary">5 hrs ago</p>
                  </div>	
                </a>
                <a href="javascript:;" class="dropdown-item d-flex align-items-center py-2">
                  <div class="w-30px h-30px d-flex align-items-center justify-content-center bg-primary rounded-circle me-3">
                    <i class="icon-sm text-white" data-lucide="download"></i>
                  </div>
                  <div class="flex-grow-1 me-2">
                    <p>Download completed</p>
                    <p class="fs-12px text-secondary">6 hrs ago</p>
                  </div>	
                </a>
              </div>
              <div class="px-3 py-2 d-flex align-items-center justify-content-center border-top">
                <a href="javascript:;">View all</a>
              </div>
            </div>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <img class="w-30px h-30px ms-1 rounded-circle" src="{{ url('https://placehold.co/30x30') }}" alt="profile">
            </a>
            <div class="dropdown-menu p-0" aria-labelledby="profileDropdown">
              <div class="d-flex flex-column align-items-center border-bottom px-5 py-3">
                <div class="mb-3">
                  <img class="w-80px h-80px rounded-circle" src="{{ url('https://placehold.co/80x80') }}" alt="">
                </div>
                <div class="text-center">
                  <p class="fs-16px fw-bolder">Amiah Burton</p>
                  <p class="fs-12px text-secondary">amiahburton@gmail.com</p>
                </div>
              </div>
              <ul class="list-unstyled p-1">
                <li>
                  <a href="{{ url('/general/profile') }}" class="dropdown-item py-2 text-body ms-0">
                    <i class="me-2 icon-md" data-lucide="user"></i>
                    <span>Profile</span>
                  </a>
                </li>
                <li>
                  <a href="javascript:;" class="dropdown-item py-2 text-body ms-0">
                    <i class="me-2 icon-md" data-lucide="edit"></i>
                    <span>Edit Profile</span>
                  </a>
                </li>
                <li>
                  <a href="javascript:;" class="dropdown-item py-2 text-body ms-0">
                    <i class="me-2 icon-md" data-lucide="repeat"></i>
                    <span>Switch User</span>
                  </a>
                </li>
                <li>
                  <a href="javascript:;" class="dropdown-item py-2 text-body ms-0">
                    <i class="me-2 icon-md" data-lucide="log-out"></i>
                    <span>Log Out</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
        </ul>

        <!-- navbar toggler for small devices -->
        <div data-toggle="horizontal-menu-toggle" class="navbar-toggler navbar-toggler-right d-lg-none align-self-center">
          <span></span>
          <span></span>
          <span></span>
          <span></span>
        </div>
        
      </div>
    </div>
  </nav>
  <nav class="bottom-navbar">
    <div class="container">
      <ul class="nav page-navigation">
        <li class="nav-item {{ active_class(['/']) }}">
          <a class="nav-link" href="{{ url('/') }}">
            <i class="link-icon" data-lucide="home"></i>
            <span class="menu-title">Dashboard</span>
          </a>
        </li>
        <li class="nav-item {{ active_class(['email/*', 'apps/*']) }}">
          <a href="#" class="nav-link">
            <i class="link-icon" data-lucide="mail"></i>
            <span class="menu-title">Apps</span>
            <i class="link-arrow"></i>
          </a>
          <div class="submenu">
            <ul class="submenu-item">
              <li class="category-heading">Email</li>
              <li class="nav-item"><a class="nav-link {{ active_class(['email/inbox']) }}" href="{{ url('/email/inbox') }}">Inbox</a></li>
              <li class="nav-item"><a class="nav-link {{ active_class(['email/read']) }}" href="{{ url('/email/read') }}">Read</a></li>
              <li class="nav-item"><a class="nav-link {{ active_class(['email/compose']) }}" href="{{ url('/email/compose') }}">Compose</a></li>
              <li class="category-heading">Other<li>
              <li class="nav-item"><a class="nav-link {{ active_class(['apps/chat']) }}" href="{{ url('/apps/chat') }}">Chat</a></li>
              <li class="nav-item"><a class="nav-link {{ active_class(['apps/calendar']) }}" href="{{ url('/apps/calendar') }}">Calendar</a></li>
            </ul>
          </div>
        </li>
        <li class="nav-item mega-menu {{ active_class(['ui-components/*', 'advanced-ui/*']) }}">
          <a href="#" class="nav-link">
            <i class="link-icon" data-lucide="feather"></i>
            <span class="menu-title">UI Kit</span>
            <i class="link-arrow"></i>
          </a>
          <div class="submenu">
            <div class="col-group-wrapper row">
              <div class="col-group col-md-9">
                <div class="row">
                  <div class="col-12">
                    <p class="category-heading">Basic</p>
                    <div class="submenu-item">
                      <div class="row">
                        <div class="col-md-4">
                          <ul>
                            <li class="nav-item"><a href="{{ url('/ui-components/accordion') }}" class="nav-link {{ active_class(['ui-components/accordion']) }}">Accordion</a></li>
                            <li class="nav-item"><a href="{{ url('/ui-components/alerts') }}" class="nav-link {{ active_class(['ui-components/alerts']) }}">Alerts</a></li>
                            <li class="nav-item"><a href="{{ url('/ui-components/badges') }}" class="nav-link {{ active_class(['ui-components/badges']) }}">Badges</a></li>
                            <li class="nav-item"><a href="{{ url('/ui-components/breadcrumbs') }}" class="nav-link {{ active_class(['ui-components/breadcrumbs']) }}">Breadcrumbs</a></li>
                            <li class="nav-item"><a href="{{ url('/ui-components/buttons') }}" class="nav-link {{ active_class(['ui-components/buttons']) }}">Buttons</a></li>
                            <li class="nav-item"><a href="{{ url('/ui-components/button-group') }}" class="nav-link {{ active_class(['ui-components/button-group']) }}">Buttn Group</a></li>
                            <li class="nav-item"><a href="{{ url('/ui-components/cards') }}" class="nav-link {{ active_class(['ui-components/cards']) }}">Cards</a></li>
                            <li class="nav-item"><a href="{{ url('/ui-components/carousel') }}" class="nav-link {{ active_class(['ui-components/carousel']) }}">Carousel</a></li>
                            <li class="nav-item"><a href="{{ url('/ui-components/collapse') }}" class="nav-link {{ active_class(['ui-components/collapse']) }}">Collapse</a></li>
                          </ul>
                        </div>
                        <div class="col-md-4">
                          <ul>
                            <li class="nav-item"><a href="{{ url('/ui-components/dropdowns') }}" class="nav-link {{ active_class(['ui-components/dropdowns']) }}">Dropdowns</a></li>
                            <li class="nav-item"><a href="{{ url('/ui-components/list-group') }}" class="nav-link {{ active_class(['ui-components/list-group']) }}">List group</a></li>
                            <li class="nav-item"><a href="{{ url('/ui-components/media-object') }}" class="nav-link {{ active_class(['ui-components/media-object']) }}">Media object</a></li>
                            <li class="nav-item"><a href="{{ url('/ui-components/modal') }}" class="nav-link {{ active_class(['ui-components/modal']) }}">Modal</a></li>
                            <li class="nav-item"><a href="{{ url('/ui-components/navs') }}" class="nav-link {{ active_class(['ui-components/navs']) }}">Navs</a></li>
                            <li class="nav-item"><a href="{{ url('/ui-components/offcanvas') }}" class="nav-link {{ active_class(['ui-components/offcanvas']) }}">Offcanvas</a></li>
                            <li class="nav-item"><a href="{{ url('/ui-components/pagination') }}" class="nav-link {{ active_class(['ui-components/pagination']) }}">Pagination</a></li>
                            <li class="nav-item"><a href="{{ url('/ui-components/placeholders') }}" class="nav-link {{ active_class(['ui-components/placeholders']) }}">Placeholders</a></li>
                            <li class="nav-item"><a href="{{ url('/ui-components/popovers') }}" class="nav-link {{ active_class(['ui-components/popovers']) }}">Popovers</a></li>
                          </ul>
                        </div>
                        <div class="col-md-4">
                          <ul>
                            <li class="nav-item"><a href="{{ url('/ui-components/progress') }}" class="nav-link {{ active_class(['ui-components/progress']) }}">Progress</a></li>
                            <li class="nav-item"><a href="{{ url('/ui-components/scrollbar') }}" class="nav-link {{ active_class(['ui-components/scrollbar']) }}">Scrollbar</a></li>
                            <li class="nav-item"><a href="{{ url('/ui-components/scrollspy') }}" class="nav-link {{ active_class(['ui-components/scrollspy']) }}">Scrollspy</a></li>
                            <li class="nav-item"><a href="{{ url('/ui-components/spinners') }}" class="nav-link {{ active_class(['ui-components/spinners']) }}">Spinners</a></li>
                            <li class="nav-item"><a href="{{ url('/ui-components/tabs') }}" class="nav-link {{ active_class(['ui-components/tabs']) }}">Tabs</a></li>
                            <li class="nav-item"><a href="{{ url('/ui-components/toasts') }}" class="nav-link {{ active_class(['ui-components/toasts']) }}">Toasts</a></li>
                            <li class="nav-item"><a href="{{ url('/ui-components/tooltips') }}" class="nav-link {{ active_class(['ui-components/tooltips']) }}">Tooltips</a></li>
                          </ul>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-group col-md-3">
                <div class="row">
                  <div class="col-12">
                    <p class="category-heading">Advanced</p>
                    <div class="submenu-item">
                      <div class="row">
                        <div class="col-md-12">
                          <ul>
                            <li class="nav-item"><a href="{{ url('/advanced-ui/cropper') }}" class="nav-link {{ active_class(['advanced-ui/cropper']) }}">Cropper</a></li>
                            <li class="nav-item"><a href="{{ url('/advanced-ui/owl-carousel') }}" class="nav-link {{ active_class(['advanced-ui/owl-carousel']) }}">Owl carousel</a></li>
                            <li class="nav-item"><a href="{{ url('/advanced-ui/sortablejs') }}" class="nav-link {{ active_class(['advanced-ui/sortablejs']) }}">SortableJs</a></li>
                            <li class="nav-item"><a href="{{ url('/advanced-ui/sweet-alert') }}" class="nav-link {{ active_class(['advanced-ui/sweet-alert']) }}">Sweetalert</a></li>
                          </ul>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </li>
        <li class="nav-item {{ active_class(['forms/*']) }}">
          <a href="#" class="nav-link">
            <i class="link-icon" data-lucide="inbox"></i>
            <span class="menu-title">Forms</span>
            <i class="link-arrow"></i>
          </a>
          <div class="submenu">
            <ul class="submenu-item">
              <li class="nav-item"><a href="{{ url('/forms/basic-elements') }}" class="nav-link {{ active_class(['forms/basic-elements']) }}">Basic Elements</a></li>
              <li class="nav-item"><a href="{{ url('/forms/advanced-elements') }}" class="nav-link {{ active_class(['forms/advanced-elements']) }}">Advanced Elements</a></li>
              <li class="nav-item"><a href="{{ url('/forms/editors') }}" class="nav-link {{ active_class(['forms/editors']) }}">Editors</a></li>
              <li class="nav-item"><a href="{{ url('/forms/wizard') }}" class="nav-link {{ active_class(['forms/wizard']) }}">Wizard</a></li>
            </ul>
          </div>
        </li>
        <li class="nav-item {{ active_class(['charts/*', 'tables/*']) }}">
          <a href="#" class="nav-link">
            <i class="link-icon" data-lucide="pie-chart"></i>
            <span class="menu-title">Data</span>
            <i class="link-arrow"></i>
          </a>
          <div class="submenu">
            <div class="row">
              <div class="col-md-6">
                <ul class="submenu-item pe-lg-0">
                  <li class="category-heading">Charts</li>
                  <li class="nav-item"><a href="{{ url('/charts/apex') }}" class="nav-link {{ active_class(['charts/apex']) }}">Apex</a></li>
                  <li class="nav-item"><a href="{{ url('/charts/chartjs') }}" class="nav-link {{ active_class(['charts/chartjs']) }}">ChartJs</a></li>
                  <li class="nav-item"><a href="{{ url('/charts/flot') }}" class="nav-link {{ active_class(['charts/flot']) }}">Float</a></li>
                  <li class="nav-item"><a href="{{ url('/charts/peity') }}" class="nav-link {{ active_class(['charts/peity']) }}">Peity</a></li>
                  <li class="nav-item"><a href="{{ url('/charts/sparkline') }}" class="nav-link {{ active_class(['charts/sparkline']) }}">Sparkline</a></li>
                </ul>
              </div>
              <div class="col-md-6">
                <ul class="submenu-item ps-lg-0">
                  <li class="category-heading">Tables</li>
                  <li class="nav-item"><a href="{{ url('/tables/basic-tables') }}" class="nav-link {{ active_class(['tables/basic-tables']) }}">Basic Tables</a></li>
                  <li class="nav-item"><a href="{{ url('/tables/data-table') }}" class="nav-link {{ active_class(['tables/data-table']) }}">Data Table</a></li>
                </ul>
              </div>
            </div>
          </div>
        </li>
        <li class="nav-item {{ active_class(['icons/*']) }}">
          <a href="#" class="nav-link">
            <i class="link-icon" data-lucide="smile"></i>
            <span class="menu-title">Icons</span>
            <i class="link-arrow"></i>
          </a>
          <div class="submenu">
            <ul class="submenu-item">
              <li class="nav-item"><a href="{{ url('/icons/lucide-icons') }}" class="nav-link {{ active_class(['icons/lucide-icons']) }}">Lucide Icons</a></li>
              <li class="nav-item"><a href="{{ url('/icons/flag-icons') }}" class="nav-link {{ active_class(['icons/flag-icons']) }}">Flag Icons</a></li>
              <li class="nav-item"><a href="{{ url('/icons/mdi-icons') }}" class="nav-link {{ active_class(['icons/mdi-icons']) }}">Mdi Icons</a></li>
            </ul>
          </div>
        </li>
        <li class="nav-item mega-menu {{ active_class(['general/*', 'error/*', 'auth/*']) }}">
          <a href="#" class="nav-link">
            <i class="link-icon" data-lucide="book"></i>
            <span class="menu-title">Sample Pages</span>
            <i class="link-arrow"></i>
          </a>
          <div class="submenu">
            <div class="col-group-wrapper row">
              <div class="col-group col-md-6">
                <p class="category-heading">Special Pages</p>
                <div class="submenu-item">
                  <div class="row">
                    <div class="col-md-6">
                      <ul>
                        <li class="nav-item"><a href="{{ url('/general/blank-page') }}" class="nav-link {{ active_class(['general/blank-page']) }}">Blank page</a></li>
                        <li class="nav-item"><a href="{{ url('/general/faq') }}" class="nav-link {{ active_class(['general/faq']) }}">Faq</a></li>
                        <li class="nav-item"><a href="{{ url('/general/invoice') }}" class="nav-link {{ active_class(['general/invoice']) }}">Invoice</a></li>
                      </ul>
                    </div>
                    <div class="col-md-6">
                      <ul>
                        <li class="nav-item"><a href="{{ url('/general/profile') }}" class="nav-link {{ active_class(['general/profile']) }}">Profile</a></li>
                        <li class="nav-item"><a href="{{ url('/general/pricing') }}" class="nav-link {{ active_class(['general/pricing']) }}">Pricing</a></li>
                        <li class="nav-item"><a href="{{ url('/general/timeline') }}" class="nav-link {{ active_class(['general/timeline']) }}">Timeline</a></li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-group col-md-3">
                <p class="category-heading">Auth Pages</p>
                <ul class="submenu-item">
                  <li class="nav-item"><a href="{{ url('/auth/login') }}" class="nav-link {{ active_class(['auth/login']) }}">Login</a></li>
                  <li class="nav-item"><a href="{{ url('/auth/register') }}" class="nav-link {{ active_class(['auth/register']) }}">Register</a></li>
                  <li class="nav-item"><a href="{{ url('/auth/forgot-password') }}" class="nav-link {{ active_class(['auth/forgot-password']) }}">Forgot Password</a></li>
                </ul>
              </div>
              <div class="col-group col-md-3">
                <p class="category-heading">Error Pages</p>
                <ul class="submenu-item">
                  <li class="nav-item"><a href="{{ url('/error/404') }}" class="nav-link {{ active_class(['error/404']) }}">404</a></li>
                  <li class="nav-item"><a href="{{ url('/error/500') }}" class="nav-link {{ active_class(['error/500']) }}">500</a></li>
                </ul>
              </div>
            </div>
          </div>
        </li>
        <li class="nav-item">
          <a href="https://nobleui.com/laravel/documentation/docs.html" target="_blank" class="nav-link">
            <i class="link-icon" data-lucide="hash"></i>
            <span class="menu-title">Documentation</span></a>
        </li>
      </ul>
    </div>
  </nav>
</div>