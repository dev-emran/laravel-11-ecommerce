<ul class="account-nav">
    <li>
        <x-customer-acc-nav.acc-nav-link href="{{ route('user.index') }}" :active="request()->routeIs('user.index')">
            Dashboard
        </x-customer-acc-nav.acc-nav-link>
    </li>
    <li>
        <x-customer-acc-nav.acc-nav-link href="{{ route('user.orders') }}" :active="request()->routeIs(['user.orders', 'user.order-details'])">
            Orders
        </x-customer-acc-nav.acc-nav-link>
    </li>

    <li>
        <x-customer-acc-nav.acc-nav-link href="{{ route('customer.address') }}" :active="request()->routeIs('customer.address')">
            Address
        </x-customer-acc-nav.acc-nav-link>
    </li>
    <li>
        <x-customer-acc-nav.acc-nav-link href="{{ route('customer.account') }}" :active="request()->routeIs('customer.account')">
            Account details
        </x-customer-acc-nav.acc-nav-link>
    </li>
    <li>
        <x-customer-acc-nav.acc-nav-link href="{{ route('customer.wishlist') }}" :active="request()->routeIs('customer.wishlist')">
            Wishlist
        </x-customer-acc-nav.acc-nav-link>
    </li>
    <li>
        <button class="menu-link menu-link_us-s btn text-uppercase" form="logoutForm">Logout</button>
        {{-- <a form="logoutForm" class="menu-link menu-link_us-s">Logout</a> --}}
        <form id="logoutForm" action="{{ route('logout') }}" method="post">
            @csrf
            {{-- <a href="{{ route('logout') }}" class="menu-link menu-link_us-s" onclick="event.preventDefault();document.getElementById('logoutForm').submit();">Logout</a> --}}
        </form>
    </li>
</ul>
