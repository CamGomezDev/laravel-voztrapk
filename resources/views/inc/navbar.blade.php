<nav class="navbar navbar-default navbar-static-top">
    <div class="container">
        <div class="navbar-header">

            <!-- Collapsed Hamburger -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Branding Image -->
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'Laravel') }}
            </a>
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->
            <?php $id = Auth::user()->id_rol ?>
            <ul class="nav navbar-nav">
                @if($id == 1 || $id == 3 || $id == 4)
                <li class="{{(Request::segment(1) == 'Mapa' || Request::segment(1) == '') ? 'active' : ''}}"><a href="/Mapa">Mapa</a></li>
                @endif
                @if($id == 1 || $id == 3)
                <li class="{{(Request::segment(1) == 'Administracion') ? 'active' : ''}}"><a href="/Administracion">Administración</a></li>
                @endif
                @if($id == 1 || $id == 2)
                <li class="{{(Request::segment(1) == 'Ajustes') ? 'active' : ''}}"><a href="/Ajustes">Ajustes</a></li>
                @endif
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
                <!-- Authentication Links -->
                @if (Auth::guest())
                    <li><a href="{{ route('login') }}">Login</a></li>
                    {{--  <li><a href="{{ route('register') }}">Register</a></li>  --}}
                @else
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                              document.getElementById('logout-form').submit();">
                                    Logout
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>