<div class="vertical-menu mm-active">

    <div data-simplebar class="h-100 mm-show">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                @forelse (App\Services\Navigation::adminRoutes() as $key => $subNavigation)
                    @if (!isset($subNavigation->subMenu))
                        {{-- <li class="menu-title" data-key="t-menu">Menu</li> --}}
                        <li style="display: {{ $subNavigation->hasPermission ? 'block' : 'none' }}">
                            <a href="{{ !empty($subNavigation->route) ? route($subNavigation->route) : '' }}"
                                class="{{ Route::currentRouteNamed($subNavigation->route) ? 'active' : '' }}">
                                <i data-feather="{{ $subNavigation->icon }}"></i>
                                <span data-key="t-dashboard">{{ $subNavigation->name }}</span>
                            </a>
                        </li>
                    @else
                        <li style="display: {{ $subNavigation->hasPermission ? 'block' : 'none' }}">
                            <a href="#" class="has-arrow {{ in_array(Route::currentRouteName(), $subNavigation->routes) ? 'mm-active' : '' }}">
                                <i data-feather="{{ $subNavigation->icon }}"></i>
                                <span data-key="t-apps">
                                    {{ $subNavigation->name }}
                                </span>
                            </a>
                            <ul class="sub-menu {{ in_array(Route::currentRouteName(), $subNavigation->routes) ? 'mm-show' : '' }}" aria-expanded="false">
                                @foreach ($subNavigation->subMenu as $sub)
                                    <li style="display: {{ $sub->hasPermission ? 'block' : 'none' }}">
                                        <a href="{{ !empty($sub->route) ? route($sub->route) : '' }}" class="{{ Route::currentRouteNamed($sub->route) ? 'active' : '' }}">
                                            <span data-key="t-calendar">{{ $sub->name }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @endif
                @empty
                    <li class="menu-title">No menu item found.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
