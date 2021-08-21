  <div class="wrapper ">
    <div class="sidebar" data-color="green" data-background-color="white" style="background-color: white;">
      <!--
        Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

        Tip 2: you can also add an image using data-image tag
    -->
      <div class="logo" style="background-color: white;"><a href="{{route('admin.dashboard')}}" class="simple-text logo-normal">
        <img src="{{ asset('images/logo/logo.png') }}" alt="image" width="160px" height="60px">
        </a>
      </div>
      <div class="sidebar-wrapper" style="background-color: white;">
        <ul class="nav">
          @if(Auth::guard('admin')->check())
            <li class="nav-item" id="nav-dashboard">
              <a class="nav-link" href="{{route('admin.dashboard')}}">
                <i class="material-icons">dashboard</i>
                <p>Inicio</p>
              </a>
            </li>
            <li class="nav-item" id="nav-players">
              <a class="nav-link" href="{{route('admin.listPlayers')}}">
                <i class="material-icons">manage_accounts</i>
                <p>Becados</p>
              </a>
            </li>
            <li class="nav-item" id="nav-gameHistory">
              <a class="nav-link" href="{{route('admin.listDaily')}}">
                <i class="material-icons">description</i>
                <p>Historial Axie Infinity</p>
              </a>
            </li>
            @if(Auth::guard('admin')->id() == 1)
              <li class="nav-item" id="nav-rates">
                <a class="nav-link" href="{{route('admin.rates')}}">
                  <i class="material-icons">attach_money</i>
                  <p>Tasas</p>
                </a>
              </li>
              <li class="nav-item" id="nav-groups">
                <a class="nav-link" href="#">
                  <i class="material-icons">groups</i>
                  Grupos
                </a>
              </li>
            @else
            <li class="nav-item" id="nav-rate">
              <a class="nav-link" href="{{route('admin.rate')}}">
                <i class="material-icons">attach_money</i>
                <p>Tasa</p>
              </a>
            </li>
            @endif
            <li class="nav-item" id="nav-deposits">
              <a class="nav-link" href="#">
                <i class="material-icons">account_balance</i>
                Historial Reclamos
              </a>
            </li>
          @else
            <li class="nav-item" id="nav-dashboard">
              <a class="nav-link" href="{{route('player.dashboard')}}">
                <i class="material-icons">dashboard</i>
                <p>Inicio</p>
              </a>
            </li>
            <li class="nav-item" id="nav-profile">
              <a class="nav-link" href="{{route('player.profile')}}">
                <i class="material-icons">person</i>
                <p>Perfil</p>
              </a>
            </li>
            <li class="nav-item" id="nav-gameHistory">
              <a class="nav-link" href="{{route('player.listDaily')}}">
                <i class="material-icons">description</i>
                <p>Historial Axie Infinity</p>
              </a>
            </li>
            <li class="nav-item" id="nav-depositHistory">
              <a class="nav-link" href="#">
                <i class="material-icons">description</i>
                <p>Historial Reclamos</p>
              </a>
            </li>
          @endif
          <li class="nav-item">
            <a class="nav-link" href="{{route('logout')}}">
            <i class="material-icons">login</i>
              <p>Salir</p>
            </a>
          </li>
        </ul>
      </div>
    </div>
    <style>
      .ajs-message {
        color: white !important;
      }
      button.ajs-button.ajs-ok {
          border-radius: 60px !important;
          border: 2px solid #00cc5f !important;
          background-color: #00cc5f !important;
          color: white !important;
      }

      button.ajs-button.ajs-ok:hover {
          border-radius: 60px !important;
          background-color: white !important;
          border: 2px solid #00cc5f !important;
          color: #00cc5f !important;
      }

      button.ajs-button.ajs-cancel {
          border-radius: 60px !important;
          border: 2px solid #ffffff80 !important;
          color: black !important;
      }

      button.ajs-button.ajs-cancel:hover {
          border-radius: 60px !important;
          background-color: #ffffff80 !important;
          border: 2px solid #ffffff80 !important;
          color: black !important;
      }
    </style>