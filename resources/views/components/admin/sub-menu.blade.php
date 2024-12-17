@props(['active'=>false])
<li class="sub-menu-item">
    <a 
        class= "{{ $active ? 'active' : '' }}"
        aria-current="{{ $active ? 'page' : 'false' }}" 
        {{ $attributes }}
    >
        <div class="text">{{ $slot }}</div>
    </a>
</li>
