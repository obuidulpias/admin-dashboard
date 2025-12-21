<footer class="main-footer" style="background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%); border-top: 3px solid #667eea; box-shadow: 0 -2px 15px rgba(0, 0, 0, 0.05); padding: 12px 20px !important;">
    <div class="d-flex flex-wrap justify-content-between align-items-center">
        <!-- Left Side - Copyright -->
        <div class="text-center text-md-left mb-2 mb-md-0">
            <strong style="color: #2d3748; font-weight: 600; font-size: 0.9rem;">
                &copy; {{ date('Y') }} 
                <a href="{{ route('home') }}" class="footer-brand-link">
                    <i class="fas fa-shield-alt"></i> Admin Panel
                </a>
            </strong>
            <span style="color: #718096; font-size: 0.85rem; margin-left: 5px;">All rights reserved.</span>
        </div>
        
        <!-- Right Side - Compact Info -->
        <div class="d-flex align-items-center flex-wrap justify-content-center">
            <!-- Social Links - Compact -->
            <div class="mr-3">
                <a href="#" class="footer-social-icon" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="footer-social-icon" title="Twitter"><i class="fab fa-twitter"></i></a>
                <a href="#" class="footer-social-icon" title="Instagram"><i class="fab fa-instagram"></i></a>
                <a href="#" class="footer-social-icon" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
            </div>
            
            <!-- Quick Links - Compact -->
            <div class="mr-3 d-none d-sm-flex">
                <a href="#" class="footer-link-compact"><i class="fas fa-life-ring"></i> Support</a>
                <a href="#" class="footer-link-compact"><i class="fas fa-shield-alt"></i> Privacy</a>
            </div>
            
            <!-- Version Badge - Compact -->
            <span class="badge" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; padding: 4px 10px; font-size: 0.7rem; border-radius: 12px; font-weight: 600;">
                <i class="fas fa-code-branch"></i> v1.0.0
            </span>
        </div>
    </div>
</footer>

<style>
    /* Compact Footer Styles */
    .main-footer {
        margin-left: 250px !important;
        transition: all 0.3s ease;
        min-height: auto !important;
    }
    
    /* When sidebar is collapsed */
    .sidebar-collapse .main-footer {
        margin-left: 0 !important;
    }
    
    /* Footer Brand Link */
    .footer-brand-link {
        color: #667eea !important;
        text-decoration: none !important;
        font-weight: 700;
        transition: all 0.3s ease;
        position: relative;
    }
    
    .footer-brand-link:hover {
        color: #764ba2 !important;
    }
    
    .footer-brand-link::after {
        content: '';
        position: absolute;
        width: 0;
        height: 2px;
        bottom: -2px;
        left: 0;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        transition: width 0.3s ease;
    }
    
    .footer-brand-link:hover::after {
        width: 100%;
    }
    
    /* Compact Social Icons */
    .footer-social-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: #fff;
        color: #667eea !important;
        text-decoration: none !important;
        margin: 0 3px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.08);
        font-size: 0.75rem;
    }
    
    .footer-social-icon:hover {
        transform: translateY(-2px) scale(1.05);
        box-shadow: 0 4px 8px rgba(102, 126, 234, 0.3);
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff !important;
    }
    
    /* Compact Footer Links */
    .footer-link-compact {
        color: #4a5568 !important;
        text-decoration: none !important;
        font-size: 0.8rem;
        font-weight: 500;
        padding: 4px 8px;
        border-radius: 6px;
        transition: all 0.3s ease;
        display: inline-block;
        margin: 0 2px;
    }
    
    .footer-link-compact:hover {
        color: #667eea !important;
        background: rgba(102, 126, 234, 0.1);
        transform: translateY(-1px);
    }
    
    .footer-link-compact i {
        font-size: 0.7rem;
        margin-right: 3px;
    }
    
    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .main-footer {
            margin-left: 0 !important;
            padding: 10px 15px !important;
        }
        
        .main-footer > div {
            justify-content: center !important;
        }
        
        .footer-social-icon {
            width: 26px;
            height: 26px;
            font-size: 0.7rem;
            margin: 0 2px;
        }
    }
    
    /* Smooth Transitions */
    .main-footer * {
        transition: all 0.3s ease;
    }
</style>
