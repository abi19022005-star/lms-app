@props(['href' => '#'])

<a {{ $attributes->merge(['href' => $href, 'class' => 'dropdown-item']) }}>
    {{ $slot }}
</a>
