<ul class="menu">
    {{-- <li class="sidebar-title"></li> --}}

    {{-- <li class="sidebar-item  ">
        <a href="index.html" class='sidebar-link'>
            <i class="bi bi-grid-fill"></i>
            <span>Dashboard</span>
        </a>
    </li> --}}

    @foreach ($menu as $m)
        <li
            class="sidebar-item {{ $m['active'] ? 'active' : '' }} {{ isset($m['children']) && count($m['children']) > 0 ? 'has-sub' : '' }}">
            @if (isset($m['url']) && $m['url'] != '')
                <a href="{{ $m['url'] }}" class='sidebar-link'>
                    <i class="{{ $m['icon'] }}"></i>
                    <span>{{ $m['label'] }}</span>
                </a>
            @else
                <a href="#" class='sidebar-link'>
                    <i class="{{ $m['icon'] }}"></i>
                    <span>{{ $m['label'] }}</span>
                </a>
            @endif

            @if (isset($m['children']) && count($m['children']))
                <ul class="submenu {{ $m['active'] ? 'active' : '' }}">
                    @foreach ($m['children'] as $s)
                        <li class="submenu-item {{ $s['active'] ? 'active' : '' }}">
                            <a href="{{ $s['url'] }}">{{ $s['label'] }}</a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </li>
    @endforeach

</ul>
