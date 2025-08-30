<!-- =====HEADER START======= -->
<header>
  <div class="header-area header-area2 header-area-all d-none d-lg-block" id="header">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <div class="header-elements">
            <div class="site-logo home1-site-logo">
                <a href="{{ url('index') }}">
                    <img src="{{ asset('storage/' . getSettingValue('logo')) }}" alt="" />
                </a>
            </div>

            <div class="header2-buttons">
              <a href="account" class="theme-btn2">Activate your eSim</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</header>
<!-- =====HEADER END======= -->

<!-- =====Mobile header start======= -->
<div class="mobile-header d-block d-lg-none">
  <div class="container-fluid">
    <div class="col-12">
      <div class="mobile-header-elements">
        <div class="mobile-logo">
          <a href="{{ url('index') }}">
            <img src="{{ asset('storage/' . getSettingValue('logo')) }}" alt="" />
          </a>
        </div>
        <div class="mobile-nav-icon" id="mobile-menu-toggle">
          <i class="fa-duotone fa-bars-staggered"></i>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Mobile Sidebar Overlay -->
<div class="mobile-sidebar-overlay" id="mobile-sidebar-overlay"></div>

<div class="mobile-sidebar d-block d-lg-none" id="mobile-sidebar">
  <div class="logo-m">
    <a href="index"><img src="{{asset('storage/' . getSettingValue('logo_white')) }}" width="80" height="80" alt="" /></a>
  </div>
  <div class="menu-close" id="mobile-menu-close">
    <i class="fa-solid fa-xmark"></i>
  </div>
  <div class="mobile-nav">
    <ul>
      <li class="has-dropdown">
        <a href="#" class="dropdown-toggle">Home</a>
        <ul class="sub-menu">
          <li class="has-dropdown has-dropdown1">
            <a href="index4" class="dropdown-toggle">Multipage</a>
            <ul class="sub-menu">
              <li><a href="index">Home 1</a></li>
              <li><a href="index2">Home 2</a></li>
              <li><a href="index3">Home 3</a></li>
              <li><a href="index4">Home 4</a></li>
              <li><a href="index5">Home 5</a></li>
              <li><a href="index6">Home 6</a></li>
              <li><a href="index7">Home 7</a></li>
              <li><a href="index8">Home 8</a></li>
              <li><a href="index9">Home 9</a></li>
              <li><a href="rtl">RTL</a></li>
            </ul>
          </li>
          <li class="has-dropdown has-dropdown1">
            <a href="index4" class="dropdown-toggle">Landing Page</a>
            <ul class="sub-menu">
              <li><a href="single-index1">Home 1</a></li>
              <li><a href="single-index2">Home 2</a></li>
              <li><a href="single-index3">Home 3</a></li>
              <li><a href="single-index4">Home 4</a></li>
              <li><a href="single-index5">Home 5</a></li>
              <li><a href="single-index6">Home 6</a></li>
              <li><a href="single-index7">Home 7</a></li>
              <li><a href="single-index8">Home 8</a></li>
              <li><a href="single-index9">Home 9</a></li>
              <li><a href="single-rtl">RTL</a></li>
            </ul>
          </li>
        </ul>
      </li>
      <li>
        <a href="about">About Us</a>
      </li>
      <li class="has-dropdown">
        <a href="#" class="dropdown-toggle">Pages</a>
        <ul class="sub-menu">
          <li><a href="contact">Contact Us</a></li>
          <li><a href="features">Features</a></li>
          <li><a href="testimonial">Testimonial</a></li>
          <li><a href="pricing">Pricing</a></li>
          <li><a href="download">Download</a></li>
          <li><a href="error">404</a></li>
        </ul>
      </li>

      <li class="has-dropdown">
        <a href="#" class="dropdown-toggle">Blog</a>
        <ul class="sub-menu">
          <li><a href="blog">Blog</a></li>
          <li><a href="blog-details-sidebar-left">Details Left</a></li>
          <li><a href="blog-details-sidebar-right">Details Right</a></li>
          <li><a href="blog-details">Blog Details</a></li>
        </ul>
      </li>

      <li class="has-dropdown">
        <a href="#" class="dropdown-toggle">Account</a>
        <ul class="sub-menu">
          <li><a href="account">Create Account</a></li>
          <li><a href="login">Login</a></li>
          <li><a href="forgot">Forgot</a></li>
          <li><a href="reset">Reset</a></li>
          <li><a href="verify">Verify Email</a></li>
          <li><a href="form-success">Success</a></li>
        </ul>
      </li>
    </ul>
    <a href="account" class="theme-btn3">Sign Up For Free</a>

    <div class="contact-infos">
      <h3>Contact Info</h3>
      <div class="box">
        <div class="icon">
          <span><i class="fa-regular fa-phone"></i></span>
        </div>
        <div class="pera">
          <a href="tel:921-888-0022">921-888-0022</a>
        </div>
      </div>
      <div class="box">
        <div class="icon">
          <span><i class="fa-regular fa-envelope"></i></span>
        </div>
        <div class="pera">
          <a href="mailto:example@visafast.com">example@visafast.com</a>
        </div>
      </div>
    </div>
    <div class="contact-infos">
      <h3>Our Location</h3>
      <div class="box">
        <div class="icon">
          <span><i class="fa-regular fa-location-dot"></i></span>
        </div>
        <div class="pera">
          <a href="#">
            55 East Birchwood Ave.Brooklyn, <br />
            New York 11201,United States
          </a>
        </div>
      </div>
    </div>

    <div class="contact-infos">
      <h3>Follow Us</h3>
      <ul class="icon-list">
        <li><a href="#"><i class="fa-brands fa-linkedin-in"></i></a></li>
        <li><a href="#"><i class="fa-brands fa-x-twitter"></i></a></li>
        <li><a href="#"><i class="fa-brands fa-youtube"></i></a></li>
        <li><a href="#"><i class="fa-brands fa-instagram"></i></a></li>
      </ul>
    </div>
  </div>
</div>
<!-- =====Mobile header end======= -->

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get elements
    const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
    const mobileSidebar = document.getElementById('mobile-sidebar');
    const mobileOverlay = document.getElementById('mobile-sidebar-overlay');
    const menuClose = document.getElementById('mobile-menu-close');
    const body = document.body;

    // Toggle mobile menu
    function toggleMobileMenu() {
        mobileSidebar.classList.toggle('active');
        mobileOverlay.classList.toggle('active');
        body.classList.toggle('mobile-menu-open');
    }

    // Close mobile menu
    function closeMobileMenu() {
        mobileSidebar.classList.remove('active');
        mobileOverlay.classList.remove('active');
        body.classList.remove('mobile-menu-open');
    }

    // Event listeners for menu toggle
    if (mobileMenuToggle) {
        mobileMenuToggle.addEventListener('click', toggleMobileMenu);
    }

    if (menuClose) {
        menuClose.addEventListener('click', closeMobileMenu);
    }

    if (mobileOverlay) {
        mobileOverlay.addEventListener('click', closeMobileMenu);
    }

    // Handle dropdown toggles
    const dropdownToggles = document.querySelectorAll('.mobile-nav .dropdown-toggle');
    dropdownToggles.forEach(function(toggle) {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            const parent = this.parentElement;
            const isActive = parent.classList.contains('active');

            // Close all other dropdowns at the same level
            const siblings = parent.parentElement.children;
            for (let sibling of siblings) {
                if (sibling !== parent) {
                    sibling.classList.remove('active');
                }
            }

            // Toggle current dropdown
            parent.classList.toggle('active');
        });
    });

    // Close menu when clicking regular links (not dropdowns)
    const mobileLinks = document.querySelectorAll('.mobile-nav a:not(.dropdown-toggle)');
    mobileLinks.forEach(function(link) {
        link.addEventListener('click', function() {
            setTimeout(closeMobileMenu, 150);
        });
    });

    // Close menu on window resize if screen gets larger
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 992) {
            closeMobileMenu();
        }
    });

    // Prevent default click on # links
    const hashLinks = document.querySelectorAll('a[href="#"]');
    hashLinks.forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
        });
    });
});
</script>

<style>
/* Add this CSS to your existing stylesheet */

/* Mobile sidebar overlay */
.mobile-sidebar-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 999;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
}

.mobile-sidebar-overlay.active {
    opacity: 1;
    visibility: visible;
}

/* Fix mobile sidebar positioning */
.mobile-sidebar {
    position: fixed;
    top: 0;
    left: -100%;
    width: 300px;
    height: 100vh;
    background: #2c3e50;
    z-index: 1000;
    overflow-y: auto;
    transition: left 0.3s ease;
    padding: 20px;
    box-sizing: border-box;
}

.mobile-sidebar.active {
    left: 0;
}

/* Prevent body scroll when mobile menu is open */
body.mobile-menu-open {
    overflow: hidden;
}

/* Fix mobile header positioning */
.mobile-header {
    position: relative;
    z-index: 100;
    background: #fff;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* Mobile nav icon cursor */
.mobile-nav-icon {
    cursor: pointer;
    padding: 10px;
    transition: color 0.3s ease;
}

.mobile-nav-icon:hover {
    color: #007bff;
}

/* Menu close button */
.menu-close {
    cursor: pointer;
    position: absolute;
    top: 20px;
    right: 20px;
    color: #fff;
    font-size: 20px;
    padding: 5px;
    transition: color 0.3s ease;
}

.menu-close:hover {
    color: #3498db;
}

/* Mobile nav dropdown styles */
.mobile-nav .has-dropdown > a::after {
    content: '\f107';
    font-family: 'Font Awesome 6 Free';
    font-weight: 900;
    float: right;
    transition: transform 0.3s ease;
}

.mobile-nav .has-dropdown.active > a::after {
    transform: rotate(180deg);
}

.mobile-nav .sub-menu {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease;
    background: rgba(0,0,0,0.2);
    margin-left: 15px;
    border-radius: 4px;
}

.mobile-nav .has-dropdown.active .sub-menu {
    max-height: 500px;
    padding: 10px 0;
}

.mobile-nav .sub-menu a {
    padding: 8px 15px;
    font-size: 14px;
    color: rgba(255,255,255,0.8);
    display: block;
}

.mobile-nav .sub-menu a:hover {
    color: #fff;
    background: rgba(255,255,255,0.1);
}

/* Fix nested dropdowns */
.mobile-nav .has-dropdown1 .sub-menu {
    margin-left: 30px;
}

/* Ensure proper stacking order */
.header-area {
    position: relative;
    z-index: 10;
}

/* Contact info styling */
.contact-infos h3 {
    color: #3498db;
    margin-bottom: 15px;
    font-size: 16px;
}

.contact-infos .box {
    display: flex;
    align-items: flex-start;
    margin-bottom: 12px;
}

.contact-infos .icon {
    margin-right: 10px;
    color: #3498db;
    margin-top: 2px;
}

.contact-infos .pera a {
    color: #bdc3c7;
    text-decoration: none;
    font-size: 14px;
}

.contact-infos .pera a:hover {
    color: #fff;
}

/* Social icons */
.icon-list {
    display: flex;
    gap: 10px;
    list-style: none;
    padding: 0;
    margin: 15px 0 0 0;
}

.icon-list a {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 35px;
    height: 35px;
    background: #3498db;
    color: #fff;
    border-radius: 50%;
    text-decoration: none;
    font-size: 14px;
    transition: background 0.3s ease;
}

.icon-list a:hover {
    background: #2980b9;
}

/* Fix button styling */
.theme-btn3 {
    display: inline-block;
    background: #3498db;
    color: #fff;
    padding: 12px 20px;
    text-decoration: none;
    border-radius: 5px;
    text-align: center;
    margin: 20px 0;
    transition: background 0.3s ease;
}

.theme-btn3:hover {
    background: #2980b9;
}

/* Mobile responsive adjustments */
@media (max-width: 991px) {
    .mobile-sidebar {
        width: 280px;
    }
}

@media (max-width: 480px) {
    .mobile-sidebar {
        width: 100%;
        left: -100%;
    }

    .mobile-sidebar.active {
        left: 0;
    }
}
</style>
