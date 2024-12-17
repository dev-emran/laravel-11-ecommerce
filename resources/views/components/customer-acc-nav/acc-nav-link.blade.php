@props(['active'=>false])
<a 
 class="menu-link menu-link_us-s {{ $active ? 'customer-acc-nav-active' : '' }}" 
 aria-current="{{ $active ? 'page' : 'false' }}" 
 {{ $attributes }} >{{ $slot }} 
 
 </a