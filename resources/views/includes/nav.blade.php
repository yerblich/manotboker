

 <!-- Navigation-->
 <nav style="z-index:1000000" class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
   <h1> <a class="navbar-brand" href="{{ url('/')}}">משווק מנות בוקר</a></h1>
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
      <ul class="navbar-nav navbar-sidenav" id="exampleAccordion">
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="בית">
          <a class="nav-link" href="{{ url('/')}}">
            <i class="fa fa-fw fa-dashboard"></i>
            <span class="nav-link-text">בית</span>
          </a>
        </li>
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="הזמנות">
          <a class="nav-link" href="{{ url('/orders')}}">
            <i class="	fa fa-file-archive-o"></i>
            <span class="nav-link-text">הזמנות</span>
          </a>
        </li>
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="חזרות">
          <a class="nav-link" href="{{ url('/returns')}}">
            <i class="fa fa-refresh"></i>
            <span class="nav-link-text">חזרות</span>
          </a>
        </li>
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="חשבוניות">
            <a class="nav-link" href="{{ url('/invoices')}}">
              <i class="fa fa-file-pdf-o"></i>
              <span class="nav-link-text">חשבוניות</span>
            </a>
          </li>
          <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Products">
              <a class="nav-link" href="{{ url('/products')}}">
                <i class="fa fa-birthday-cake"></i>
                <span class="nav-link-text">מוצרים</span>
              </a>
            </li>
            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Clients">
                <a class="nav-link" href="{{ url('/clients')}}">
                  <i class="fa fa-group"></i>
                  <span class="nav-link-text">לקוחות</span>
                </a>
              </li>
              <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Suppliers">
                  <a class="nav-link" href="{{ url('/suppliers')}}">
                    <i class="fa fa-truck	"></i>
                    <span class="nav-link-text">ספקים</span>
                  </a>
                </li>
                <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Prices">
                  <a class="nav-link" href="{{ url('/prices')}}">
                    <i class="fa fa-truck	"></i>
                    <span class="nav-link-text">מחירים</span>
                  </a>
                </li>
                <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Options">
                    <a class="nav-link" href="{{ url('/options')}}">
                      <i class="fa fa-cog"></i>
                      <span class="nav-link-text">אפשריות</span>
                    </a>
                  </li>

      </ul>
      <ul class="navbar-nav sidenav-toggler">
        <li class="nav-item">
          <a class="nav-link text-center" id="sidenavToggler">
            <i class="fa fa-fw fa-angle-left"></i>
          </a>
        </li>
      </ul>
      <ul class="navbar-nav ml-auto">

        {{-- <li class="nav-item">
          <form class="form-inline my-2 my-lg-0 mr-lg-2">
            <div class="input-group">
              <input class="form-control" type="text" placeholder="Search for...">
              <span class="input-group-append">
                <button class="btn btn-primary" type="button">
                  <i class="fa fa-search"></i>
                </button>
              </span>
            </div>
          </form>
        </li> --}}
        {{-- <li class="nav-item">
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="{{ route('logout') }}"
                   onclick="event.preventDefault();
                                 document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
          <a  href=" {{url('logout') }}" class="nav-link" data-toggle="modal" data-target="#exampleModal">
            <i class="fa fa-fw fa-sign-out"></i>Logout</a>
        </li> --}}
      </ul>
      {{-- <div class="flex-center position-ref full-height">
        @if (Route::has('login'))
            <div class="top-right links">
                @auth
                    <a href="{{ url('/dashboard') }}">Home</a>
                @else
                    <a href="{{ route('login') }}">Login</a>
                    <a href="{{ route('register') }}">Register</a>
                @endauth
            </div>
        @endif


    </div> --}}
    <ul class="navbar-nav ml-auto">
      <!-- Authentication Links -->
      @guest
          <li class="nav-item">
              <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
          </li>
          {{-- <li class="nav-item">
              <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
          </li> --}}
      @else
          <li class="nav-item dropdown">
              <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                  {{ Auth::user()->name }} <span class="caret"></span>
              </a>

              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                  <a class="dropdown-item" href="{{ route('logout') }}"
                     onclick="event.preventDefault();
                                   document.getElementById('logout-form').submit();">
                      {{ __('Logout') }}
                  </a>

                  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                      @csrf
                  </form>
              </div>
          </li>
      @endguest
  </ul>
    </div>
  </nav>
