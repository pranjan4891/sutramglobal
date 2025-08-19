<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    @php $settings = \App\Models\Setting::find(1); @endphp
    <a href="{{ route('admin.dashboard') }}" class="brand-link">
        <img src="{{ isImage('settings', $settings->header_logo) }}" alt="Logo"
            class="brand-image">
        <!--<span class="brand-text font-weight-light">Admin</span>-->
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            @php $menu = request()->segment(2); @endphp
            @php $subMenu = request()->segment(3); @endphp

                {{-- {{ $menu }}/{{ $subMenu }} --}}

            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ $menu . '/' . $subMenu == 'dashboard/' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.products') }}" class="nav-link  {{ in_array($menu . '/' . $subMenu, ['products/', 'products/add', 'products/edit']) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-table"></i>
                        <p>
                            Products
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.orders') }}" class="nav-link {{ in_array($menu . '/' . $subMenu, ['orders/', 'orders/add', 'orders/edit']) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-pie"></i>
                        <p>
                            Orders
                        </p>
                    </a>
                </li>
                <li class="nav-item {{ $menu == 'masters' ? 'menu-is-opening menu-open' : '' }}">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ $menu == 'masters' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-edit"></i>
                        <p>
                            Masters
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.colors') }}" class="nav-link {{ $menu . '/' . $subMenu == 'masters/colors' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Colors</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.sizes') }}" class="nav-link {{ $menu . '/' . $subMenu == 'masters/sizes' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Sizes</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.masters.category') }}" class="nav-link {{ $menu . '/' . $subMenu == 'masters/categories' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Categories</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.masters.subCategory') }}" class="nav-link {{ $menu . '/' . $subMenu == 'masters/sub-categories' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Sub Categories</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.masters.slider') }}" class="nav-link {{ $menu . '/' . $subMenu == 'masters/slider' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Slider</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.pages') }}" class="nav-link {{ in_array($menu . '/' . $subMenu, ['pages/', 'pages/add', 'pages/manage']) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-book"></i>
                        <p>
                            Pages
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.coupons') }}" class="nav-link {{ $menu . '/' . $subMenu == 'coupons/' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tags"></i>
                        <p>
                            Coupons
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.wishlist') }}" class="nav-link {{ $menu . '/' . $subMenu == 'wishlist/' ? 'active' : '' }}">
                        <i class="nav-icon far fa-heart"></i>
                        <p>
                            Wishlists
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard.cartProducts') }}" class="nav-link {{ $menu . '/' . $subMenu == 'cart-products/' ? 'active' : '' }}">
                        <i class="nav-icon fa fa-shopping-cart"></i>
                        <p>
                            Cart Products
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard.contactUs') }}" class="nav-link {{ $menu . '/' . $subMenu == 'contact-us/' ? 'active' : '' }}">
                        <i class="nav-icon far fa-envelope"></i>
                        <p>
                            Contact Us
                        </p>
                    </a>
                </li>
                {{-- <li class="nav-item">
                    <a href="{{ route('admin.dashboard.newsLatter') }}" class="nav-link {{ $menu . '/' . $subMenu == 'news-letter/' ? 'active' : '' }}">
                        <i class="nav-icon far fa-envelope"></i>
                        <p>
                            Newsletter
                        </p>
                    </a>
                </li> --}}
                <li class="nav-item">
                    <a href="{{route('admin.users')}}" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Users
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.settings') }}" class="nav-link {{ $menu . '/' . $subMenu == 'settings/' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cog"></i>
                        <p>
                            Settings
                        </p>
                    </a>
                </li>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
