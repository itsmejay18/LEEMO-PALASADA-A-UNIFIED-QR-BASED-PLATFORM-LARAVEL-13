@props([
    'type',
    'icon',
    'label',
    'empty' => 'No items yet.',
])

@php
    $items = ($headerItems ?? collect())->get($type, collect());
    $unreadCount = $items->whereNull('read_at')->count();
@endphp

<li class="nav-item dropdown">
    <button class="btn btn-link nav-link position-relative px-2" type="button" data-coreui-toggle="dropdown" aria-expanded="false" aria-label="{{ $label }}">
        <i class="icon icon-lg {{ $icon }}"></i>
        @if ($unreadCount > 0)
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill text-bg-danger">
                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                <span class="visually-hidden">unread {{ strtolower($label) }}</span>
            </span>
        @endif
    </button>
    <div class="dropdown-menu dropdown-menu-end pt-0 shadow-sm" style="width: min(22rem, calc(100vw - 2rem));">
        <div class="dropdown-header bg-body-tertiary text-body-secondary rounded-top">
            <div class="fw-semibold">{{ $label }}</div>
            <div class="small">{{ $unreadCount }} unread</div>
        </div>
        <div class="list-group list-group-flush">
            @forelse ($items as $item)
                <div class="list-group-item">
                    <div class="d-flex justify-content-between gap-3">
                        <div>
                            <div class="fw-semibold">{{ $item->title }}</div>
                            @if ($item->body)
                                <div class="small text-body-secondary">{{ $item->body }}</div>
                            @endif
                            @if ($item->action_url)
                                <a class="small text-link" href="{{ $item->action_url }}">{{ $item->action_label ?? 'Open' }}</a>
                            @endif
                        </div>
                        @if (! $item->read_at)
                            <form method="POST" action="{{ route('header-items.read', $item) }}">
                                @csrf
                                @method('PATCH')
                                <button class="btn btn-sm btn-outline-secondary" type="submit">Mark</button>
                            </form>
                        @else
                            <span class="badge text-bg-light align-self-start">Read</span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="dropdown-item-text text-body-secondary">{{ $empty }}</div>
            @endforelse
        </div>
    </div>
</li>
