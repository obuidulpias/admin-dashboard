<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('home') }}" class="brand-link text-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-bottom: 3px solid #5a67d8;">
        <img src="{{ asset('admin/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo"
            class="brand-image img-circle elevation-3" style="opacity: 1; border: 3px solid #fff;">
        <span class="brand-text font-weight-bold" style="color: #fff; font-size: 1.2rem; letter-spacing: 1px;">Admin Panel</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar" style="background: linear-gradient(180deg, #2d3748 0%, #1a202c 100%);">
        
        <!-- SidebarSearch Form -->
        <div class="sidebar-search-container px-3" style="margin-top: 10px; margin-bottom: 5px;">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" 
                       type="search" 
                       placeholder="Search menu..." 
                       aria-label="Search" 
                       style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: #fff; border-radius: 10px; height: 34px;"/>
            </div>
        </div>
        <!-- Sidebar Menu -->
        <nav style="margin-top: 0;">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" 
                data-widget="treeview" 
                role="menu" 
                data-accordion="false">

                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" 
                        style="border-radius: 10px; margin: 5px 10px; transition: all 0.3s ease;">
                        <i class="nav-icon fas fa-tachometer-alt" style="color: #667eea;"></i>
                        <p style="font-weight: 500;">
                            Dashboard
                        </p>
                    </a>
                </li>

                <!-- MAIN MENU Section Header -->
                <li class="nav-header" style="color: #a0aec0; font-weight: 600; font-size: 0.75rem; letter-spacing: 1px; padding: 8px 15px 5px 15px;">
                    <i class="fas fa-grip-horizontal mr-2"></i> MAIN MENU
                </li>

                <!-- User Management -->
                @can('show-user-list')
                <li class="nav-item">
                    <a href="{{ route('users.index') }}" 
                        class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" 
                                        style="border-radius: 10px; margin: 1px 10px; transition: all 0.3s ease;">
                        <i class="nav-icon fas fa-users" style="color: #48bb78;"></i>
                        <p style="font-weight: 500;">
                            Users
                            <span class="badge badge-success right" style="border-radius: 10px;">Manage</span>
                        </p>
                    </a>
                </li>
                @endcan

                <!-- Access Control Section -->
                @can('user-role-permission')
                <li class="nav-item {{ request()->routeIs('roles.*') || request()->routeIs('permissions.*') ? 'menu-open' : '' }}" 
                    style="margin: 2px 0;">
                    <a href="#" class="nav-link {{ request()->routeIs('roles.*') || request()->routeIs('permissions.*') ? 'active' : '' }}" 
                        style="border-radius: 10px; margin: 0 10px; transition: all 0.3s ease;">
                        <i class="nav-icon fas fa-user-shield" style="color: #f56565;"></i>
                        <p style="font-weight: 500;">
                            Access Control
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="padding-left: 10px;">
                        <li class="nav-item">
                            <a href="{{ route('roles.index') }}" 
                                class="nav-link {{ request()->routeIs('roles.*') ? 'active' : '' }}" 
                                style="border-radius: 10px; margin: 1px 10px; transition: all 0.3s ease;">
                                <i class="far fa-dot-circle nav-icon" style="color: #ed8936;"></i>
                                <p>Roles</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('permissions.index') }}" 
                                class="nav-link {{ request()->routeIs('permissions.*') ? 'active' : '' }}" 
                                style="border-radius: 10px; margin: 1px 10px; transition: all 0.3s ease;">
                                <i class="far fa-dot-circle nav-icon" style="color: #ed8936;"></i>
                                <p>Permissions</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endcan

                <!-- SYSTEM Section Header -->
                <li class="nav-header" style="color: #a0aec0; font-weight: 600; font-size: 0.75rem; letter-spacing: 1px; padding: 8px 15px 5px 15px;">
                    <i class="fas fa-cog mr-2"></i> SYSTEM
                </li>

                @can('audit-log')
                <!-- Audit Log -->
                <li class="nav-item">
                    <a href="{{ route('audit-logs.index') }}" 
                        class="nav-link {{ request()->routeIs('audit-logs.*') ? 'active' : '' }}"
                                        style="border-radius: 10px; margin: 1px 10px; transition: all 0.3s ease;">
                        <i class="nav-icon fas fa-history" style="color: #9f7aea;"></i>
                        <p style="font-weight: 500;">
                            Audit Log
                        </p>
                    </a>
                </li>
                @endcan

                <!-- Log Viewer -->
                <li class="nav-item">
                    <a href="{{ route('log-viewer.index') }}" 
                        class="nav-link {{ request()->routeIs('log-viewer.*') ? 'active' : '' }}"
                        style="border-radius: 10px; margin: 5px 10px; transition: all 0.3s ease;">
                        <i class="nav-icon fas fa-file-alt" style="color: #4299e1;"></i>
                        <p style="font-weight: 500;">
                            Log Viewer
                        </p>
                    </a>
                </li>

                <!-- Settings -->
                <li class="nav-item">
                    <a href="#" class="nav-link" 
                                        style="border-radius: 10px; margin: 1px 10px; transition: all 0.3s ease;">
                        <i class="nav-icon fas fa-cogs" style="color: #a0aec0;"></i>
                        <p style="font-weight: 500;">
                            Settings
                        </p>
                    </a>
                </li>

                <!-- Reports -->
                <li class="nav-item">
                    <a href="#" class="nav-link" 
                                        style="border-radius: 10px; margin: 1px 10px; transition: all 0.3s ease;">
                        <i class="nav-icon fas fa-chart-line" style="color: #4299e1;"></i>
                        <p style="font-weight: 500;">
                            Reports
                        </p>
                    </a>
                </li>

                <!-- CONTENT MANAGEMENT Section Header -->
                <li class="nav-header" style="color: #a0aec0; font-weight: 600; font-size: 0.75rem; letter-spacing: 1px; padding: 8px 15px 5px 15px;">
                    <i class="fas fa-layer-group mr-2"></i> CONTENT MANAGEMENT
                </li>

                <!-- Multi-Layer Content Menu -->
                <li class="nav-item" style="margin: 5px 0;">
                    <a href="#" class="nav-link" 
                        style="border-radius: 10px; margin: 0 10px; transition: all 0.3s ease;">
                        <i class="nav-icon fas fa-folder-open" style="color: #ecc94b;"></i>
                        <p style="font-weight: 500;">
                            Content
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="padding-left: 10px;">
                        <!-- Posts with Submenu -->
                        <li class="nav-item">
                            <a href="#" class="nav-link" 
                                style="border-radius: 10px; margin: 1px 10px; transition: all 0.3s ease;">
                                <i class="far fa-circle nav-icon" style="color: #ed8936;"></i>
                                <p>
                                    Posts
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview" style="padding-left: 10px;">
                                <li class="nav-item">
                                    <a href="#" class="nav-link" 
                                        style="border-radius: 10px; margin: 1px 10px; transition: all 0.3s ease;">
                                        <i class="far fa-dot-circle nav-icon" style="color: #48bb78; font-size: 0.7rem;"></i>
                                        <p>All Posts</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link" 
                                        style="border-radius: 10px; margin: 1px 10px; transition: all 0.3s ease;">
                                        <i class="far fa-dot-circle nav-icon" style="color: #48bb78; font-size: 0.7rem;"></i>
                                        <p>Add New</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link" 
                                        style="border-radius: 10px; margin: 1px 10px; transition: all 0.3s ease;">
                                        <i class="far fa-dot-circle nav-icon" style="color: #48bb78; font-size: 0.7rem;"></i>
                                        <p>Categories</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link" 
                                        style="border-radius: 10px; margin: 1px 10px; transition: all 0.3s ease;">
                                        <i class="far fa-dot-circle nav-icon" style="color: #48bb78; font-size: 0.7rem;"></i>
                                        <p>Tags</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- Pages with Submenu -->
                        <li class="nav-item">
                            <a href="#" class="nav-link" 
                                style="border-radius: 10px; margin: 1px 10px; transition: all 0.3s ease;">
                                <i class="far fa-circle nav-icon" style="color: #ed8936;"></i>
                                <p>
                                    Pages
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview" style="padding-left: 10px;">
                                <li class="nav-item">
                                    <a href="#" class="nav-link" 
                                        style="border-radius: 10px; margin: 1px 10px; transition: all 0.3s ease;">
                                        <i class="far fa-dot-circle nav-icon" style="color: #48bb78; font-size: 0.7rem;"></i>
                                        <p>All Pages</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link" 
                                        style="border-radius: 10px; margin: 1px 10px; transition: all 0.3s ease;">
                                        <i class="far fa-dot-circle nav-icon" style="color: #48bb78; font-size: 0.7rem;"></i>
                                        <p>Add New</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- Media -->
                        <li class="nav-item">
                            <a href="#" class="nav-link" 
                                style="border-radius: 10px; margin: 1px 10px; transition: all 0.3s ease;">
                                <i class="far fa-circle nav-icon" style="color: #ed8936;"></i>
                                <p>Media Library</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- E-commerce Multi-Layer Menu -->
                <li class="nav-item" style="margin: 5px 0;">
                    <a href="#" class="nav-link" 
                        style="border-radius: 10px; margin: 0 10px; transition: all 0.3s ease;">
                        <i class="nav-icon fas fa-shopping-cart" style="color: #48bb78;"></i>
                        <p style="font-weight: 500;">
                            E-commerce
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="padding-left: 10px;">
                        <!-- Products with Deep Nesting -->
                        <li class="nav-item">
                            <a href="#" class="nav-link" 
                                style="border-radius: 10px; margin: 1px 10px; transition: all 0.3s ease;">
                                <i class="far fa-circle nav-icon" style="color: #ed8936;"></i>
                                <p>
                                    Products
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview" style="padding-left: 10px;">
                                <li class="nav-item">
                                    <a href="#" class="nav-link" 
                                        style="border-radius: 10px; margin: 1px 10px; transition: all 0.3s ease;">
                                        <i class="far fa-dot-circle nav-icon" style="color: #48bb78; font-size: 0.7rem;"></i>
                                        <p>All Products</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link" 
                                        style="border-radius: 10px; margin: 1px 10px; transition: all 0.3s ease;">
                                        <i class="far fa-dot-circle nav-icon" style="color: #48bb78; font-size: 0.7rem;"></i>
                                        <p>Add Product</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link" 
                                        style="border-radius: 10px; margin: 1px 10px; transition: all 0.3s ease;">
                                        <i class="far fa-dot-circle nav-icon" style="color: #48bb78; font-size: 0.7rem;"></i>
                                        <p>
                                            Categories
                                            <i class="fas fa-angle-left right"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview" style="padding-left: 10px;">
                                        <li class="nav-item">
                                            <a href="#" class="nav-link" 
                                                style="border-radius: 10px; margin: 1px 10px; transition: all 0.3s ease;">
                                                <i class="fas fa-caret-right nav-icon" style="color: #4299e1; font-size: 0.6rem;"></i>
                                                <p>Electronics</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#" class="nav-link" 
                                                style="border-radius: 10px; margin: 1px 10px; transition: all 0.3s ease;">
                                                <i class="fas fa-caret-right nav-icon" style="color: #4299e1; font-size: 0.6rem;"></i>
                                                <p>Clothing</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#" class="nav-link" 
                                                style="border-radius: 10px; margin: 1px 10px; transition: all 0.3s ease;">
                                                <i class="fas fa-caret-right nav-icon" style="color: #4299e1; font-size: 0.6rem;"></i>
                                                <p>Home & Garden</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>

                        <!-- Orders -->
                        <li class="nav-item">
                            <a href="#" class="nav-link" 
                                style="border-radius: 10px; margin: 1px 10px; transition: all 0.3s ease;">
                                <i class="far fa-circle nav-icon" style="color: #ed8936;"></i>
                                <p>Orders</p>
                            </a>
                        </li>

                        <!-- Customers -->
                        <li class="nav-item">
                            <a href="#" class="nav-link" 
                                style="border-radius: 10px; margin: 1px 10px; transition: all 0.3s ease;">
                                <i class="far fa-circle nav-icon" style="color: #ed8936;"></i>
                                <p>Customers</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Advanced Reports Multi-Layer -->
                <li class="nav-item" style="margin: 5px 0;">
                    <a href="#" class="nav-link" 
                        style="border-radius: 10px; margin: 0 10px; transition: all 0.3s ease;">
                        <i class="nav-icon fas fa-chart-bar" style="color: #4299e1;"></i>
                        <p style="font-weight: 500;">
                            Reports
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="padding-left: 10px;">
                        <li class="nav-item">
                            <a href="#" class="nav-link" 
                                style="border-radius: 10px; margin: 1px 10px; transition: all 0.3s ease;">
                                <i class="far fa-circle nav-icon" style="color: #ed8936;"></i>
                                <p>
                                    Sales Reports
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview" style="padding-left: 10px;">
                                <li class="nav-item">
                                    <a href="#" class="nav-link" 
                                        style="border-radius: 10px; margin: 1px 10px; transition: all 0.3s ease;">
                                        <i class="far fa-dot-circle nav-icon" style="color: #48bb78; font-size: 0.7rem;"></i>
                                        <p>Daily Sales</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link" 
                                        style="border-radius: 10px; margin: 1px 10px; transition: all 0.3s ease;">
                                        <i class="far fa-dot-circle nav-icon" style="color: #48bb78; font-size: 0.7rem;"></i>
                                        <p>Monthly Sales</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link" 
                                        style="border-radius: 10px; margin: 1px 10px; transition: all 0.3s ease;">
                                        <i class="far fa-dot-circle nav-icon" style="color: #48bb78; font-size: 0.7rem;"></i>
                                        <p>Yearly Summary</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link" 
                                style="border-radius: 10px; margin: 1px 10px; transition: all 0.3s ease;">
                                <i class="far fa-circle nav-icon" style="color: #ed8936;"></i>
                                <p>User Analytics</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- SUPPORT Section Header -->
                <li class="nav-header" style="color: #a0aec0; font-weight: 600; font-size: 0.75rem; letter-spacing: 1px; padding: 8px 15px 5px 15px;">
                    <i class="fas fa-info-circle mr-2"></i> SUPPORT
                </li>

                <!-- Documentation -->
                <li class="nav-item">
                    <a href="https://adminlte.io/docs/3.1/" target="_blank" class="nav-link" 
                                        style="border-radius: 10px; margin: 1px 10px; transition: all 0.3s ease;">
                        <i class="nav-icon fas fa-book" style="color: #ecc94b;"></i>
                        <p style="font-weight: 500;">
                            Documentation
                            <i class="fas fa-external-link-alt right" style="font-size: 0.7rem;"></i>
                        </p>
                    </a>
                </li>

                <!-- Help Center -->
                <li class="nav-item">
                    <a href="#" class="nav-link" 
                                        style="border-radius: 10px; margin: 1px 10px; transition: all 0.3s ease;">
                        <i class="nav-icon fas fa-question-circle" style="color: #38b2ac;"></i>
                        <p style="font-weight: 500;">
                            Help Center
                        </p>
                    </a>
                </li>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>

<style>
    /* Enhanced Menu Styles */
    .main-sidebar {
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.3) !important;
    }

    .sidebar .nav-link {
        color: #cbd5e0 !important;
    }

    .sidebar .nav-link:hover {
        background: rgba(102, 126, 234, 0.2) !important;
        color: #fff !important;
        transform: translateX(5px);
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
    }

    .sidebar .nav-link.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        color: #fff !important;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }

    .sidebar .nav-link.active i {
        color: #fff !important;
    }

    .sidebar .nav-treeview .nav-link {
        padding-left: 2rem !important;
    }

    .sidebar .nav-treeview .nav-link:hover {
        background: rgba(237, 137, 54, 0.15) !important;
        transform: translateX(3px) !important;
    }

    .sidebar .nav-treeview .nav-link.active {
        background: rgba(237, 137, 54, 0.3) !important;
        border-left: 3px solid #ed8936 !important;
    }

    .nav-header {
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        margin-top: 0;
    }

    .brand-link:hover {
        opacity: 0.95;
        transform: scale(1.02);
        transition: all 0.3s ease;
    }

    .user-panel .image img {
        transition: all 0.3s ease;
    }

    .user-panel .image img:hover {
        transform: scale(1.1);
        box-shadow: 0 0 15px rgba(102, 126, 234, 0.5);
    }

    .form-control-sidebar:focus {
        background: rgba(255, 255, 255, 0.15) !important;
        border-color: #667eea !important;
        color: #fff !important;
        box-shadow: 0 0 10px rgba(102, 126, 234, 0.3);
    }

    .form-control-sidebar::placeholder {
        color: #a0aec0;
    }

    /* Badge Styles */
    .badge {
        font-size: 0.65rem;
        padding: 3px 8px;
        font-weight: 600;
    }

    /* Scrollbar Styling */
    .sidebar::-webkit-scrollbar {
        width: 6px;
    }

    .sidebar::-webkit-scrollbar-track {
        background: rgba(0, 0, 0, 0.2);
    }

    .sidebar::-webkit-scrollbar-thumb {
        background: rgba(102, 126, 234, 0.5);
        border-radius: 10px;
    }

    .sidebar::-webkit-scrollbar-thumb:hover {
        background: rgba(102, 126, 234, 0.8);
    }

    /* Dropdown/TreeView Styles - Using max-height for smooth animations */
    .sidebar .nav-sidebar .nav-treeview {
        list-style: none !important;
        padding-left: 0 !important;
        margin: 0 !important;
        margin-left: 15px !important;
        border-left: 2px solid rgba(102, 126, 234, 0.2) !important;
        overflow: hidden !important;
        max-height: 0 !important;
        opacity: 0 !important;
        transition: max-height 0.4s ease, opacity 0.3s ease, margin 0.3s ease !important;
        display: block !important;
    }

    /* Show submenu when parent has menu-open class */
    .sidebar .nav-sidebar .nav-item.menu-open > .nav-treeview {
        max-height: 3000px !important;
        opacity: 1 !important;
        margin-top: 2px !important;
        margin-bottom: 2px !important;
    }

    /* Multi-level nested menu styles */
    .sidebar .nav-sidebar .nav-treeview .nav-treeview {
        border-left-color: rgba(237, 137, 54, 0.3) !important;
        margin-left: 10px !important;
        max-height: 0 !important;
        opacity: 0 !important;
    }

    .sidebar .nav-sidebar .nav-treeview .nav-item.menu-open > .nav-treeview {
        max-height: 2500px !important;
        opacity: 1 !important;
    }

    .sidebar .nav-sidebar .nav-treeview .nav-treeview .nav-treeview {
        border-left-color: rgba(72, 187, 120, 0.3) !important;
        margin-left: 8px !important;
        max-height: 0 !important;
        opacity: 0 !important;
    }

    .sidebar .nav-sidebar .nav-treeview .nav-treeview .nav-item.menu-open > .nav-treeview {
        max-height: 2000px !important;
        opacity: 1 !important;
    }

    .sidebar .nav-sidebar .nav-treeview .nav-treeview .nav-treeview .nav-treeview {
        border-left-color: rgba(66, 153, 225, 0.3) !important;
        margin-left: 6px !important;
        max-height: 0 !important;
        opacity: 0 !important;
    }

    .sidebar .nav-sidebar .nav-treeview .nav-treeview .nav-treeview .nav-item.menu-open > .nav-treeview {
        max-height: 1500px !important;
        opacity: 1 !important;
    }

    /* Rotate arrow icon when menu is open */
    .nav-item.menu-open > .nav-link > p > .right {
        transform: rotate(-90deg);
        transition: transform 0.3s ease;
    }

    .nav-link > p > .right {
        transition: transform 0.3s ease;
    }

    /* Level indicators for deep nesting */
    .sidebar .nav-sidebar .nav-treeview .nav-link {
        padding-left: 1.5rem !important;
    }

    .sidebar .nav-sidebar .nav-treeview .nav-treeview .nav-link {
        padding-left: 2rem !important;
        font-size: 0.95rem !important;
    }

    .sidebar .nav-sidebar .nav-treeview .nav-treeview .nav-treeview .nav-link {
        padding-left: 2.5rem !important;
        font-size: 0.9rem !important;
    }

    .sidebar .nav-sidebar .nav-treeview .nav-treeview .nav-treeview .nav-treeview .nav-link {
        padding-left: 3rem !important;
        font-size: 0.85rem !important;
    }

    /* Animation for menu items */
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .nav-item {
        animation: slideIn 0.3s ease forwards;
    }

    .nav-item:nth-child(1) { animation-delay: 0.05s; }
    .nav-item:nth-child(2) { animation-delay: 0.1s; }
    .nav-item:nth-child(3) { animation-delay: 0.15s; }
    .nav-item:nth-child(4) { animation-delay: 0.2s; }
    .nav-item:nth-child(5) { animation-delay: 0.25s; }

    /* Multi-layer menu visual enhancements */
    .nav-treeview .nav-item .nav-link:before {
        content: '';
        position: absolute;
        left: 5px;
        top: 50%;
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: rgba(102, 126, 234, 0.3);
        transform: translateY(-50%);
        transition: all 0.3s ease;
    }

    .nav-treeview .nav-item .nav-link:hover:before {
        background: rgba(102, 126, 234, 0.8);
        transform: translateY(-50%) scale(1.5);
    }

    .nav-treeview .nav-item .nav-link.active:before {
        background: #667eea;
        box-shadow: 0 0 10px rgba(102, 126, 234, 0.5);
    }

    /* Different colors for different levels */
    .nav-treeview .nav-treeview .nav-item .nav-link:before {
        background: rgba(237, 137, 54, 0.3);
    }

    .nav-treeview .nav-treeview .nav-item .nav-link:hover:before {
        background: rgba(237, 137, 54, 0.8);
    }

    .nav-treeview .nav-treeview .nav-treeview .nav-item .nav-link:before {
        background: rgba(72, 187, 120, 0.3);
    }

    .nav-treeview .nav-treeview .nav-treeview .nav-item .nav-link:hover:before {
        background: rgba(72, 187, 120, 0.8);
    }

    /* Indented border line effect */
    .nav-treeview:after {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 2px;
        background: linear-gradient(180deg, 
            rgba(102, 126, 234, 0.3) 0%, 
            rgba(102, 126, 234, 0.1) 50%,
            rgba(102, 126, 234, 0.3) 100%);
        border-radius: 2px;
    }

    /* Smooth expand/collapse animation */
    .nav-treeview {
        overflow: hidden;
    }

    /* Level badges for visual hierarchy */
    .nav-treeview .nav-treeview .nav-treeview .nav-link {
        background: rgba(255, 255, 255, 0.02);
    }

    .nav-treeview .nav-treeview .nav-treeview .nav-treeview .nav-link {
        background: rgba(255, 255, 255, 0.03);
    }
</style>

@push('menu-scripts')
<script>
    // Enhanced multi-layer menu interaction - runs after jQuery and AdminLTE are loaded
    $(document).ready(function() {
        // Wait for AdminLTE to fully initialize, then override its treeview behavior
        setTimeout(function() {
            // Remove AdminLTE's default treeview behavior
            const $sidebar = $('[data-widget="treeview"]');
            $sidebar.off('click.lte.treeview');
            
            // Custom multi-level menu handler - using direct event binding
            $('.nav-sidebar').off('click', '.nav-link').on('click', '.nav-link', function(e) {
                const $link = $(this);
                const $parentItem = $link.parent('.nav-item');
                const $subMenu = $link.next('.nav-treeview');
                
                // Only handle items that have submenus
                if ($subMenu.length > 0) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const isCurrentlyOpen = $parentItem.hasClass('menu-open');
                    
                    // Close sibling menus at the same level (accordion behavior)
                    $parentItem.siblings('.nav-item.menu-open').removeClass('menu-open');
                    
                    // Toggle current menu
                    if (isCurrentlyOpen) {
                        // Close this menu and all its children
                        $parentItem.removeClass('menu-open');
                        $subMenu.find('.nav-item.menu-open').removeClass('menu-open');
                    } else {
                        // Open this menu
                        $parentItem.addClass('menu-open');
                    }
                    
                    return false;
                }
            });
        }, 500);
        
        // Add enhanced ripple effect on all menu clicks
        $(document).on('click', '.nav-link', function(e) {
            const $this = $(this);
            
            // Only add ripple if element is visible
            if (!$this.is(':visible')) return;
            
            const offset = $this.offset();
            const relativeX = e.pageX - offset.left;
            const relativeY = e.pageY - offset.top;
            
            // Remove any existing ripples on this element
            $this.find('.ripple-effect').remove();
            
            // Create new ripple
            const $ripple = $('<span class="ripple-effect"></span>');
            $ripple.css({
                position: 'absolute',
                borderRadius: '50%',
                background: 'rgba(255, 255, 255, 0.3)',
                width: '100px',
                height: '100px',
                left: relativeX - 50 + 'px',
                top: relativeY - 50 + 'px',
                pointerEvents: 'none',
                animation: 'ripple 0.6s linear',
                zIndex: 0
            });
            
            $this.append($ripple);
            
            // Remove ripple after animation
            setTimeout(() => $ripple.remove(), 600);
        });

        // Ensure initially open menus are visible on page load
        setTimeout(function() {
            $('.nav-item.menu-open').each(function() {
                const $item = $(this);
                $item.children('.nav-treeview').css('display', 'block');
                // Also ensure all parent menus are open
                $item.parents('.nav-item').addClass('menu-open').children('.nav-treeview').css('display', 'block');
            });
        }, 600);
        
        // Smooth scroll in sidebar
        $('.sidebar').css({
            'scroll-behavior': 'smooth'
        });

        // Add visual feedback for nested items
        $('.nav-treeview .nav-item').hover(
            function() {
                const $link = $(this).children('.nav-link');
                const currentPadding = parseInt($link.css('padding-left')) || 0;
                $link.css('padding-left', (currentPadding + 3) + 'px');
            },
            function() {
                const $link = $(this).children('.nav-link');
                const currentPadding = parseInt($link.css('padding-left')) || 0;
                $link.css('padding-left', (currentPadding - 3) + 'px');
            }
        );
    });

    // Add ripple animation styles
    if (!document.getElementById('ripple-animation-styles')) {
        const style = document.createElement('style');
        style.id = 'ripple-animation-styles';
        style.textContent = `
            @keyframes ripple {
                0% {
                    transform: scale(0);
                    opacity: 1;
                }
                100% {
                    transform: scale(2.5);
                    opacity: 0;
                }
            }
            .nav-link {
                position: relative;
                overflow: hidden;
            }
        `;
        document.head.appendChild(style);
    }
</script>
@endpush
