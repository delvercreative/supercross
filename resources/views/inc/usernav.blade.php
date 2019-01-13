{{-- <user-nav href="/deposit" user_id="{{auth()->user()->id}}" user="{{auth()->user()->name}}" balance="{{auth()->user()->balance}}"></user-nav> --}}

<div id="userHeader">
    <div class="nav-inner">
      <div class="user-account">
          <i class="fas fa-user"></i>
          <span class="user-name">{{auth()->user()->name}}</span>
      </div>
      <div class="user-balance">
        <span class="balance-amount">
          <div class="sm-txt">Balance</div>
          <div class="balance-amount-val">${{auth()->user()->balance}}</div>
        </span>
      <a href="{{route('deposit.index')}}" class="deposit-link">
        <span class="add-funds"><i class="fas fa-plus"></i></span>
        </a>
      </div>
    </div>
</div>