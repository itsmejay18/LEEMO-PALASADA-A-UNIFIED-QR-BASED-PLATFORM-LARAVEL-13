@php
    $cards = $cards ?? [];
    $columnClass = count($cards) === 3 ? 'col-sm-6 col-xl-4' : 'col-sm-6 col-xl-3';
    $defaultIcons = ['cil-people', 'cil-basket', 'cil-chart-pie', 'cil-speedometer'];
@endphp

<div class="row g-4 mb-4">
    @foreach ($cards as $index => $card)
        @php
            $color = $card['color'] ?? 'primary';
            $progress = $card['progress'] ?? 50;
            $trend = $card['trend'] ?? null;
            $trendIcon = $trend === 'down' ? 'cil-arrow-bottom' : 'cil-arrow-top';
            $icon = $card['icon'] ?? $defaultIcons[$index % count($defaultIcons)];
        @endphp

        <div class="{{ $columnClass }}">
            <div class="card text-white bg-{{ $color }} h-100">
                <div class="card-body">
                    <div class="text-white text-opacity-75 text-end">
                        <i class="icon icon-xxl {{ $icon }}" aria-hidden="true"></i>
                    </div>

                    <div class="fs-4 fw-semibold mt-2">
                        {{ $card['value'] }}
                        @if (! empty($card['change']))
                            <span class="fs-6 fw-normal">
                                ({{ $card['change'] }}
                                <i class="icon {{ $trendIcon }}"></i>)
                            </span>
                        @endif
                    </div>

                    <div class="small text-white text-opacity-75 text-uppercase fw-semibold text-truncate">
                        {{ $card['label'] }}
                    </div>

                    <div class="progress progress-white progress-thin mt-3 mb-0">
                        <div class="progress-bar" role="progressbar" style="width: {{ $progress }}%" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>

                    @if (! empty($card['href']))
                        <a class="stretched-link" href="{{ $card['href'] }}" aria-label="Open {{ $card['label'] }}"></a>
                    @elseif (! empty($card['menu']))
                        <div class="dropdown position-absolute top-0 end-0 m-3">
                            <button class="btn btn-transparent text-white p-0" type="button" data-coreui-toggle="dropdown" aria-haspopup="true" aria-expanded="false" aria-label="{{ $card['label'] }} actions">
                                <i class="icon cil-options"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                @foreach ($card['menu'] as $menuItem)
                                    <a class="dropdown-item" href="{{ $menuItem['href'] }}">{{ $menuItem['label'] }}</a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</div>
