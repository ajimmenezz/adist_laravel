<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3>{{ $title ?? '' }}</h3>
            @if (isset($subtitle) && $subtitle != '')
                <p class="text-subtitle text-muted">{{ $subtitle }}</p>
            @endif
        </div>
        @if (isset($breadcrumb) && count($breadcrumb) > 0)
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        @foreach ($breadcrumb as $k => $b)
                            <li class="breadcrumb-item {{ $k == count($breadcrumb) ? 'active' : '' }}">
                                @if (isset($b['url']) && $b['url'] != '')
                                    <a href="{{ $b['url'] }}">{{ $b['label'] }}</a>
                                @else
                                    {{ $b['label'] }}
                                @endif
                            </li>
                        @endforeach
                    </ol>
                </nav>
            </div>
        @endif
    </div>
</div>
