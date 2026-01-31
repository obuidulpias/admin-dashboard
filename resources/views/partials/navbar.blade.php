<nav class="main-header navbar navbar-expand navbar-dark" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-bottom: none; box-shadow: 0 2px 15px rgba(0, 0, 0, 0.2);">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button" style="color: #fff; transition: all 0.3s ease;">
                <i class="fas fa-bars" style="font-size: 1.2rem;"></i>
            </a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ route('home') }}" class="nav-link navbar-link-custom">
                <i class="fas fa-home mr-1"></i> Dashboard
            </a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="#" class="nav-link navbar-link-custom">
                <i class="fas fa-envelope mr-1"></i> Contact
            </a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        
        <!-- Quick Search -->
        <li class="nav-item" style="position: relative;">
            <a class="nav-link navbar-icon-custom" data-widget="navbar-search" href="#" role="button" title="Search">
                <i class="fas fa-search"></i>
            </a>
            <div class="navbar-search-block" style="background: #fff; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.2); position: absolute; top: 100%; right: 0; margin-top: 5px; min-width: 300px; padding: 15px; z-index: 1050; display: none;">
                <form class="form-inline">
                    <div class="input-group input-group-sm">
                        <input class="form-control form-control-navbar" type="search" placeholder="Search anything..." 
                            aria-label="Search" style="border-radius: 20px 0 0 20px; border: 2px solid #667eea; background: #fff; color: #2d3748;">
                        <div class="input-group-append">
                            <button class="btn btn-navbar" type="submit" style="background: #667eea; color: #fff; border-radius: 0; border: 2px solid #667eea; border-left: none;">
                                <i class="fas fa-search"></i>
                            </button>
                            <button class="btn btn-navbar" type="button" data-widget="navbar-search" style="background: #f56565; color: #fff; border-radius: 0 20px 20px 0; border: 2px solid #f56565;">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </li>

        <!-- Messages Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link navbar-icon-custom" data-toggle="dropdown" href="#" title="Messages">
                <i class="far fa-comments"></i>
                <span class="badge badge-danger navbar-badge pulse-animation" style="border-radius: 10px;">3</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right modern-dropdown" style="border-radius: 15px; border: none; box-shadow: 0 8px 25px rgba(0,0,0,0.15); margin-top: 10px;">
                <span class="dropdown-item dropdown-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; font-weight: 600; border-radius: 15px 15px 0 0; padding: 15px;">
                    <i class="fas fa-comments mr-2"></i> 3 New Messages
                </span>
                <div class="dropdown-divider" style="margin: 0;"></div>
                
                <a href="#" class="dropdown-item dropdown-item-hover">
                    <div class="media">
                        <img src="{{ asset('admin/dist/img/user1-128x128.jpg') }}" alt="User Avatar"
                            class="img-size-50 mr-3 img-circle" style="border: 3px solid #667eea;">
                        <div class="media-body">
                            <h3 class="dropdown-item-title" style="color: #2d3748; font-weight: 600;">
                                Brad Diesel
                                <span class="float-right text-sm" style="color: #f56565;"><i class="fas fa-star"></i></span>
                            </h3>
                            <p class="text-sm" style="color: #4a5568;">Call me whenever you can...</p>
                            <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                        </div>
                    </div>
                </a>
                
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-item-hover">
                    <div class="media">
                        <img src="{{ asset('admin/dist/img/user8-128x128.jpg') }}" alt="User Avatar"
                            class="img-size-50 img-circle mr-3" style="border: 3px solid #667eea;">
                        <div class="media-body">
                            <h3 class="dropdown-item-title" style="color: #2d3748; font-weight: 600;">
                                John Pierce
                                <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
                            </h3>
                            <p class="text-sm" style="color: #4a5568;">I got your message bro</p>
                            <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                        </div>
                    </div>
                </a>
                
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-item-hover">
                    <div class="media">
                        <img src="{{ asset('admin/dist/img/user3-128x128.jpg') }}" alt="User Avatar"
                            class="img-size-50 img-circle mr-3" style="border: 3px solid #667eea;">
                        <div class="media-body">
                            <h3 class="dropdown-item-title" style="color: #2d3748; font-weight: 600;">
                                Nora Silvester
                                <span class="float-right text-sm" style="color: #ecc94b;"><i class="fas fa-star"></i></span>
                            </h3>
                            <p class="text-sm" style="color: #4a5568;">The subject goes here</p>
                            <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                        </div>
                    </div>
                </a>
                
                <div class="dropdown-divider" style="margin: 0;"></div>
                <a href="#" class="dropdown-item dropdown-footer" style="background: #f7fafc; color: #667eea; font-weight: 600; border-radius: 0 0 15px 15px; text-align: center; padding: 12px;">
                    See All Messages <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </li>

        <!-- Notifications Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link navbar-icon-custom" data-toggle="dropdown" href="#" title="Notifications">
                <i class="far fa-bell"></i>
                <span class="badge badge-warning navbar-badge pulse-animation" style="border-radius: 10px;">5</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right modern-dropdown" style="border-radius: 15px; border: none; box-shadow: 0 8px 25px rgba(0,0,0,0.15); margin-top: 10px;">
                <span class="dropdown-item dropdown-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; font-weight: 600; border-radius: 15px 15px 0 0; padding: 15px;">
                    <i class="fas fa-bell mr-2"></i> 5 Notifications
                </span>
                <div class="dropdown-divider" style="margin: 0;"></div>
                
                <a href="#" class="dropdown-item dropdown-item-hover">
                    <i class="fas fa-envelope mr-2" style="color: #667eea;"></i> 4 new messages
                    <span class="float-right text-muted text-sm">3 mins</span>
                </a>
                
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-item-hover">
                    <i class="fas fa-users mr-2" style="color: #48bb78;"></i> 8 friend requests
                    <span class="float-right text-muted text-sm">12 hours</span>
                </a>
                
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-item-hover">
                    <i class="fas fa-file mr-2" style="color: #f56565;"></i> 3 new reports
                    <span class="float-right text-muted text-sm">2 days</span>
                </a>
                
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-item-hover">
                    <i class="fas fa-check-circle mr-2" style="color: #9f7aea;"></i> Task completed
                    <span class="float-right text-muted text-sm">1 week</span>
                </a>
                
                <div class="dropdown-divider" style="margin: 0;"></div>
                <a href="#" class="dropdown-item dropdown-footer" style="background: #f7fafc; color: #667eea; font-weight: 600; border-radius: 0 0 15px 15px; text-align: center; padding: 12px;">
                    See All Notifications <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </li>

        <!-- Fullscreen Toggle -->
        <li class="nav-item">
            <a class="nav-link navbar-icon-custom" data-widget="fullscreen" href="#" role="button" title="Fullscreen">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>

        <!-- User Profile Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link navbar-icon-custom" data-toggle="dropdown" href="#" style="padding: 5px 10px;">
                <div class="d-flex align-items-center">
                    <img src="{{ asset('admin/dist/img/user2-160x160.jpg') }}" class="img-circle" 
                        style="width: 35px; height: 35px; border: 2px solid #fff; margin-right: 8px;" alt="User Image">
                    <span class="d-none d-md-inline" style="color: #fff; font-weight: 500;">
                        {{ Auth::user()->name ?? 'Admin' }}
                    </span>
                    <i class="fas fa-angle-down ml-2" style="color: #fff; font-size: 0.9rem;"></i>
                </div>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right modern-dropdown" style="border-radius: 15px; border: none; box-shadow: 0 8px 25px rgba(0,0,0,0.15); margin-top: 10px; min-width: 280px;">
                <div class="dropdown-item dropdown-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; border-radius: 15px 15px 0 0; padding: 20px; text-align: center;">
                    <img src="{{ asset('admin/dist/img/user2-160x160.jpg') }}" class="img-circle" 
                        style="width: 60px; height: 60px; border: 3px solid #fff; margin-bottom: 10px;" alt="User Image">
                    <h5 style="margin: 0; font-weight: 600;">{{ Auth::user()->name ?? 'Admin User' }}</h5>
                    <small style="color: rgba(255,255,255,0.8);">{{ Auth::user()->email ?? 'admin@example.com' }}</small>
                </div>
                <div class="dropdown-divider" style="margin: 0;"></div>
                
                <a href="#" class="dropdown-item dropdown-item-hover" style="padding: 12px 20px;">
                    <i class="fas fa-user mr-2" style="color: #667eea; width: 20px;"></i> My Profile
                </a>
                
                <a href="#" class="dropdown-item dropdown-item-hover" style="padding: 12px 20px;">
                    <i class="fas fa-cog mr-2" style="color: #48bb78; width: 20px;"></i> Settings
                </a>
                
                <a href="#" class="dropdown-item dropdown-item-hover" style="padding: 12px 20px;">
                    <i class="fas fa-lock mr-2" style="color: #ecc94b; width: 20px;"></i> Privacy
                </a>
                
                <div class="dropdown-divider"></div>
                
                <form action="{{ route('logout') }}" method="POST" class="d-inline" style="width: 100%;">
                    @csrf
                    <button type="submit" class="dropdown-item dropdown-item-hover text-danger" style="padding: 12px 20px; font-weight: 600;">
                        <i class="fas fa-sign-out-alt mr-2" style="width: 20px;"></i> Logout
                    </button>
                </form>
            </div>
        </li>
    </ul>
</nav>

<style>
    /* Enhanced Navbar Styles */
    .main-header.navbar {
        transition: all 0.3s ease;
    }

    /* Navbar Link Hover Effects */
    .navbar-link-custom {
        color: rgba(255, 255, 255, 0.9) !important;
        font-weight: 500;
        padding: 8px 15px !important;
        border-radius: 8px;
        transition: all 0.3s ease;
        margin: 0 3px;
    }

    .navbar-link-custom:hover {
        background: rgba(255, 255, 255, 0.2) !important;
        color: #fff !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    /* Navbar Icon Styles */
    .navbar-icon-custom {
        color: rgba(255, 255, 255, 0.9) !important;
        padding: 8px 12px !important;
        border-radius: 8px;
        transition: all 0.3s ease;
        position: relative;
        margin: 0 3px;
        display: inline-flex !important;
        align-items: center;
        justify-content: center;
    }

    .navbar-icon-custom:hover {
        background: rgba(255, 255, 255, 0.2) !important;
        color: #fff !important;
        transform: scale(1.1);
    }

    .navbar-icon-custom i {
        font-size: 1.2rem !important;
        display: inline-block !important;
        color: #fff !important;
        line-height: 1;
    }
    
    /* Ensure FontAwesome icons are visible */
    .navbar-icon-custom .fa,
    .navbar-icon-custom .far,
    .navbar-icon-custom .fas {
        opacity: 1 !important;
        visibility: visible !important;
    }

    /* Pushmenu Icon Hover */
    [data-widget="pushmenu"]:hover {
        background: rgba(255, 255, 255, 0.2) !important;
        border-radius: 8px;
        transform: scale(1.1);
    }

    /* Badge Pulse Animation */
    .pulse-animation {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
            opacity: 1;
        }
        50% {
            transform: scale(1.1);
            opacity: 0.9;
        }
    }

    /* Modern Dropdown Styles */
    .modern-dropdown {
        animation: dropdownFadeIn 0.3s ease;
    }

    @keyframes dropdownFadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Dropdown Item Hover */
    .dropdown-item-hover {
        transition: all 0.3s ease;
        border-left: 3px solid transparent;
    }

    .dropdown-item-hover:hover {
        background: linear-gradient(90deg, rgba(102, 126, 234, 0.1) 0%, rgba(255, 255, 255, 0) 100%) !important;
        border-left-color: #667eea;
        padding-left: 23px !important;
        transform: translateX(3px);
    }

    /* User Avatar in Dropdown Hover */
    .dropdown-item-hover:hover .img-circle {
        transform: scale(1.05);
        transition: all 0.3s ease;
    }

    /* Navbar Badge Custom */
    .navbar-badge {
        font-size: 0.6rem !important;
        font-weight: 700 !important;
        padding: 2px 5px !important;
        position: absolute !important;
        top: 3px !important;
        right: 3px !important;
        border: 2px solid rgba(102, 126, 234, 0.3) !important;
        min-width: 18px !important;
        height: 18px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        z-index: 10 !important;
    }
    
    /* Fix badge colors to be more visible */
    .badge-danger {
        background-color: #f56565 !important;
        color: #fff !important;
    }
    
    .badge-warning {
        background-color: #ecc94b !important;
        color: #2d3748 !important;
    }

    /* Search Block Enhancement */
    .navbar-search-block {
        position: absolute !important;
        top: 100% !important;
        right: 0 !important;
        margin-top: 5px !important;
        min-width: 300px !important;
        padding: 15px !important;
        z-index: 1050 !important;
        display: none !important;
        animation: searchFadeIn 0.3s ease;
    }
    
    /* Search icon active state */
    [data-widget="navbar-search"].active {
        background: rgba(255, 255, 255, 0.3) !important;
    }
    
    /* Search input styling */
    .navbar-search-block .form-control-navbar {
        background: #fff !important;
        color: #2d3748 !important;
    }
    
    .navbar-search-block .form-control-navbar:focus {
        border-color: #667eea !important;
        box-shadow: 0 0 10px rgba(102, 126, 234, 0.3) !important;
        outline: none !important;
    }
    
    /* Make search parent position relative */
    .nav-item:has([data-widget="navbar-search"]) {
        position: relative !important;
    }

    @keyframes searchFadeIn {
        from {
            opacity: 0;
            transform: scale(0.95);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    /* Dropdown Footer Hover */
    .dropdown-footer:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        color: #fff !important;
    }

    /* Profile Image Hover in Navbar */
    .nav-link img.img-circle {
        transition: all 0.3s ease;
    }

    .nav-link:hover img.img-circle {
        transform: scale(1.1);
        box-shadow: 0 0 15px rgba(255, 255, 255, 0.5);
    }

    /* Scrollbar for Long Dropdowns */
    .dropdown-menu {
        max-height: 400px;
        overflow-y: auto;
    }

    .dropdown-menu::-webkit-scrollbar {
        width: 6px;
    }

    .dropdown-menu::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .dropdown-menu::-webkit-scrollbar-thumb {
        background: #667eea;
        border-radius: 10px;
    }

    .dropdown-menu::-webkit-scrollbar-thumb:hover {
        background: #764ba2;
    }

    /* Force icon visibility - override any conflicting styles */
    .navbar-nav .nav-item .nav-link i.fa-comments,
    .navbar-nav .nav-item .nav-link i.fa-bell,
    .navbar-nav .nav-item .nav-link i.fa-search,
    .navbar-nav .nav-item .nav-link i.fa-expand-arrows-alt,
    .navbar-nav .nav-item .nav-link i.far,
    .navbar-nav .nav-item .nav-link i.fas {
        display: inline-block !important;
        opacity: 1 !important;
        visibility: visible !important;
        font-size: 1.2rem !important;
        color: #fff !important;
        width: auto !important;
        height: auto !important;
    }
    
    /* Ensure nav items are visible */
    .navbar-nav > .nav-item {
        display: flex !important;
        align-items: center !important;
    }
    
    /* Make sure dropdown items are displayed */
    .navbar-nav > .nav-item.dropdown {
        display: flex !important;
        position: relative !important;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .navbar-icon-custom {
            padding: 8px 8px !important;
        }
        
        .navbar-link-custom {
            padding: 8px 10px !important;
        }
    }
</style>

@push('menu-scripts')
<script>
    $(document).ready(function() {
        // Ensure icons are visible
        setTimeout(function() {
            $('.navbar-icon-custom i').each(function() {
                if ($(this).is(':hidden') || $(this).css('opacity') == '0') {
                    $(this).css({
                        'display': 'inline-block',
                        'opacity': '1',
                        'visibility': 'visible',
                        'color': '#fff'
                    });
                }
            });
        }, 100);
        
        // Custom Navbar Search Toggle (override AdminLTE default)
        const $searchToggle = $('[data-widget="navbar-search"]');
        const $searchBlock = $('.navbar-search-block');
        
        // Initially hide the search block
        $searchBlock.hide();
        
        // Remove AdminLTE's default handler and add custom one
        $searchToggle.off('click.lte.navbar-search');
        
        $searchToggle.on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const isVisible = $searchBlock.is(':visible');
            
            if (isVisible) {
                // Close search
                $searchBlock.slideUp(300);
                $(this).removeClass('active');
            } else {
                // Open search
                $searchBlock.slideDown(300, function() {
                    // Focus on the search input after animation
                    $searchBlock.find('input[type="search"]').focus();
                });
                $(this).addClass('active');
            }
        });
        
        // Close search when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.navbar-search-block, [data-widget="navbar-search"]').length) {
                if ($searchBlock.is(':visible')) {
                    $searchBlock.slideUp(300);
                    $searchToggle.removeClass('active');
                }
            }
        });
        
        // Prevent search block from closing when clicking inside it
        $searchBlock.on('click', function(e) {
            e.stopPropagation();
        });
        
        // Add smooth transitions to dropdowns
        $('.dropdown-toggle').on('click', function() {
            $(this).next('.dropdown-menu').addClass('modern-dropdown');
        });
        
        // Initialize Bootstrap dropdowns explicitly
        $('.nav-item.dropdown').each(function() {
            const $dropdown = $(this);
            const $toggle = $dropdown.find('[data-toggle="dropdown"]');
            
            $toggle.on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                // Close search if open
                if ($searchBlock.is(':visible')) {
                    $searchBlock.slideUp(300);
                    $searchToggle.removeClass('active');
                }
                
                // Close other dropdowns
                $('.nav-item.dropdown').not($dropdown).removeClass('show')
                    .find('.dropdown-menu').removeClass('show');
                
                // Toggle current dropdown
                $dropdown.toggleClass('show');
                $dropdown.find('.dropdown-menu').toggleClass('show');
            });
        });
        
        // Close dropdowns when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.nav-item.dropdown').length) {
                $('.nav-item.dropdown').removeClass('show')
                    .find('.dropdown-menu').removeClass('show');
            }
        });

        // Update fullscreen icon
        $('[data-widget="fullscreen"]').on('click', function() {
            const icon = $(this).find('i');
            if (icon.hasClass('fa-expand-arrows-alt')) {
                icon.removeClass('fa-expand-arrows-alt').addClass('fa-compress-arrows-alt');
            } else {
                icon.removeClass('fa-compress-arrows-alt').addClass('fa-expand-arrows-alt');
            }
        });

        // Add notification read functionality (optional)
        $('.dropdown-item-hover').on('click', function(e) {
            if ($(this).closest('.dropdown-menu').find('.badge').length) {
                // Could add AJAX call here to mark as read
            }
        });
    });
</script>
@endpush
