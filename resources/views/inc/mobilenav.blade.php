
<main-nav logo="{{ asset('img/logo2.png') }}"></main-nav>
@auth
<user-nav dlink="{{route('deposit.index')}}" user_id="{{auth()->user()->id}}" user="{{auth()->user()->name}}" balance="{{auth()->user()->balance}}"></user-nav>
<div id="expandNav" class="nav-slide">
  <div class="inner">
    <div class="user-account-info">
        <div class="user-account">
            <i class="fas fa-user"></i>
            <span class="user-name">{{auth()->user()->name}}</span>
            <div id="expandClose"><i class="fas fa-chevron-left"></i></div>
        </div>
        <div class="user-balance">
          <span class="balance-amount">
              <div class="sm-txt">Balance</div>
              <div class="balance-amount-val">${{auth()->user()->balance}}</div>
          </span>
        </div>
    </div>
    <div class="list-group">
        <a href="{{route('deposit.index')}}" class="deposit list-group-item list-group-item-action"><i class="fas fa-credit-card"></i>Deposit</a>
        <a href="{{route('user.dashboard')}}" class="list-group-item list-group-item-action">Dashboard</a>
        <a href="{{route('races.index')}}" class="list-group-item list-group-item-action">Races</a>
        <a href="#" class="list-group-item list-group-item-action">Settings</a>
        <a href="{{route('user.logout')}}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="list-group-item list-group-item-action">Logout</a>
        
          <form id="logout-form" action="{{ route('user.logout') }}" method="POST" style="display: none;">
              @csrf
          </form>
      </div>
  </div>
</div>
@endauth