@props(['align' => 'right', 'width' => '48', 'contentClasses' => ''])

@php
$alignmentClasses = match ($align) {
    'left' => '',
    default => 'dropdown-menu-end',
};
@endphp

<div class="dropdown d-inline-block" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
    <div @click="open = ! open">
        {{ $trigger }}
    </div>

    <div x-show="open"
            class="dropdown-menu show {{ $alignmentClasses }} {{ $contentClasses }}"
            style="display: none;"
            @click="open = false">
        {{ $content }}
    </div>
</div>
