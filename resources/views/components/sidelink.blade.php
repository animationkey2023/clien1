@props(['active' => false])

<a {{ $attributes->merge([
        'class' => $active ? 'nav-link active' : 'nav-link'
    ]) }}>
    {{ $slot }}
</a>
