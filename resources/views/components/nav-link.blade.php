@props(['active' => false, 'href' => '#'])

@php
$classes = ($active ?? false)
    ? 'nav-link active'
    : 'nav-link';
@endphp

<li class="nav-item">
    <a {{ $attributes->merge(['href' => $href, 'class' => $classes]) }}>
        {{ $slot }}
    </a>
</li>
