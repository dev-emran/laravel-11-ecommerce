@props(['active' => false])
<a class="navigation__link {{ $active ? 'navigation__link_active' : '' }}"
    aria-current="{{ $active ? 'page' : 'false' }}" {{ $attributes }}>{{ $slot }}
</a>
