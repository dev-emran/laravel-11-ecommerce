@props(['active'=>false, 'icon'])
<li class="menu-item">
    <a
        class="{{ $active ? 'active' : '' }}"
        aria-current="{{ $active ? 'page' : 'false' }}" 
        {{ $attributes }}
    >
        <div class="icon"><i class="{{ $icon }}"></i></div>
        <div class="text">{{ $slot }}</div>
    </a>
</li>