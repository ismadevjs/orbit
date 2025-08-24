<!-- Bottom Navigation (Visible on Small to Medium Devices) -->
<div class="fixed-bottom bg-primary shadow-lg d-block d-md-none">
    <div class="container">
        <div class="row text-center py-3">
            <!-- Left Navigation Items -->
            <div class="col">
                <a  href="{{ route('backend.index') }}" class="nav-item {{ request()->routeIs('backend.index') ? 'active' : '' }}">
                    <i class="fas fa-home icon"></i>
                    <p class="mb-0"></p>
                </a>
            </div>
            <div class="col">
                <a href="#" class="nav-item {{ request()->routeIs('search.index') ? 'active' : '' }}">
                    <i class="fas fa-search icon"></i>
                    <p class="mb-0"></p>
                </a>
            </div>
            <!-- Center Circular Button -->
            <div class="col position-relative">
                <div class="circle-btn position-absolute start-50 translate-middle">
                    <a href="#" class="d-flex justify-content-center align-items-center">
                        <i class="fas fa-wallet fs-4 icon-center"></i>
                    </a>
                </div>
            </div>
            <!-- Right Navigation Items -->
            <div class="col">
                <a  href="{{ route('notifications.index') }}" class="nav-item {{ request()->routeIs('notifications.index') ? 'active' : '' }}">
                    <i class="fas fa-bell icon"></i>
                    <p class="mb-0"></p>
                </a>
            </div>
            <div class="col">
                <a  href="{{ route('profile.show') }}" class="nav-item {{ request()->routeIs('profile.show') ? 'active' : '' }}">
                    <i class="fas fa-user-circle icon"></i>
                    <p class="mb-0"></p>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Styles -->
<style>
    .fixed-bottom {
        z-index: 9999;
    }

    /* Active navigation item (highlight) */
    .nav-item.active {
        color: #ff4500;
        transform: translateY(-6px);
        opacity: 1;
    }
    .nav-item.active .icon {
        color: #ff4500;
        transform: translateY(-5px);
    }
    /* Background and Shadow with New Gradient */
    .bg-light {
        background: linear-gradient(45deg, #343434, #a9a9a9);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    /* Center Circular Button Style */
    .circle-btn {
        width: 80px;
        height: 80px;
        background-color: #ff6347; /* Tomato */
        border-radius: 50%;
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.25);
        transition: transform 0.3s ease, box-shadow 0.3s ease, background-color 0.3s ease;
        display: flex;
        justify-content: center;
        align-items: center;
        transform: translateY(-30px); /* Elevation effect */
    }

    .circle-btn:hover {
        background-color: #ff4500;
        transform: translateY(-50px);
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4);
    }

    /* Center Icon Style */
    .icon-center {
        font-size: 2.5rem;
        color: #fff;
        transition: transform 0.4s ease, color 0.3s ease;
        animation: rotateIcon 1s ease-in-out infinite alternate; /* Continuous rotation */
    }

    @keyframes rotateIcon {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }

    /* Hover Effect on Navigation Items */
    .nav-item {
        text-decoration: none;
        color: #fff;
        transition: transform 0.3s ease, color 0.3s ease, opacity 0.3s ease;
        opacity: 0.9;
    }

    .nav-item:hover {
        color: #ff4500;
        transform: translateY(-6px);
        opacity: 1;
    }

    /* Active State Hover Behavior */
    .nav-item.active:hover {
        color: #ff4500; /* Same color as hover */
        transform: translateY(-6px); /* Same transform as hover */
        opacity: 1; /* Keep full opacity */
    }

    /* Icon Styling */
    .icon {
        font-size: 1.75rem;
        color: #fff;
        transition: transform 0.3s ease, color 0.3s ease;
    }

    /* Hover effect on icons */
    .nav-item:hover .icon {
        color: #ff4500;
        transform: translateY(-5px);
    }

    /* Navbar icons' spacing */
    .row .col p {
        font-size: 0.75rem;
        margin-top: 5px;
        color: #fff;
        transition: color 0.3s ease;
    }

    /* Media Query for Tablets and Smaller Devices */
    @media (max-width: 991px) {
        .circle-btn {
            width: 70px;
            height: 70px;
        }

        .circle-btn i {
            font-size: 2.2rem;
        }

        .icon {
            font-size: 1.5rem;
        }

        .nav-item p {
            font-size: 0.65rem;
        }
    }

    /* Hide navigation bar on large devices (larger than 992px) */
    @media (min-width: 992px) {
        .d-block.d-md-none {
            display: none !important;
        }
    }
</style>
