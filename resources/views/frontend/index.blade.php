@extends('layouts.frontend')

@section('title', 'FliSim - Activate your eSIM in 60 seconds — campus-ready')
@php
    $carousel = getTable('carousels');
    $buttons = !empty($carousel?->buttons) ? json_decode($carousel->buttons) : [];
    $buttonCount = is_array($buttons) || is_object($buttons) ? count($buttons) : 0;
@endphp
@section('content')
    <!-- ===== HERO AREA START ======= -->
    <div class="hero-area2">
        <div class="container">
            <div class="row align-items-center hero2-content">
                <div class="col-lg-6">
                    <div class="main-headding">
                        <span class="span">{{ $carousel->subtitle ?? '' }}</span>
                        <h1 class="text-anime-style-3">{{ $carousel->title ?? '' }}</h1>
                        <p data-aos="fade-right" data-aos-duration="800">
                            {{ $carousel->body ?? '' }}
                        </p>

                        <div class="space30"></div>
                        <div class="hero-btns row" data-aos="fade-right" data-aos-duration="1100">

                            @if ($buttonCount > 0)
                                @foreach ($buttons as $btn)
                                    <div class="{{ $buttonCount == 2 ? 'col-6' : 'col-4' }} mb-2">
                                        <a href="{{ $btn->link ?? '#' }}" class="theme-btn2 w-100 text-center">
                                            {{ $btn->name ?? '' }}
                                        </a>
                                    </div>
                                @endforeach
                            @endif

                            {{-- Load More button only if more than 2 plans --}}
                            @if ($buttonCount > 2)
                                <div class="col-12 mt-3 text-center">
                                    <a href="#" class="theme-btn2">Load More</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>


                <div class="col-lg-6">
                    <div class="hero2-image">
                        <div class="image1 animate1">
                            <img src="{{ asset('esoft/img/hero/hero2-image1.png') }}" alt="" />
                        </div>
                        <div class="image2">
                            <img src="storage/{{ getTable('carousels')->image ?? '' }}" alt="" />
                        </div>
                        <div class="image3">
                            <img src="{{ asset('esoft/img/hero/hero2-image3.png') }}" alt="" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ===== HERO AREA END ======= -->

    <!-- ===== CHOOSE AREA START ======= -->

    <div class="footer2 _relative" style="margin-top : -70px">
        <div class="container">
            <div class="rwo">
                <div class="footer-icon-box-all">

                    @if (getTable('brands'))
                        @foreach (getTables('brands') as $brand)
                            <div class="footer-icon-box">
                                <div class="">
                                    <img src="{{ asset('storage/' . $brand->image) }}" width="80" height="80"
                                        alt="" />
                                </div>
                                <div class="headding">
                                    <p>{{ $brand->name }}</p>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="choose2 pb120 mt-4">
        <div class="container">
            {{-- Section Heading --}}


            <div class="row">
                <div class="col-lg-8 m-auto text-center">
                    <div class="headding2 mb-5">
                        <span class="span">Popular for Students & Expats in Türkiye</span>
                        <h2 class="text-anime-style-3">Choose Your Perfect eSIM Plan</h2>
                        <p class="mt-3 text-muted">
                            Stay connected instantly with affordable and reliable eSIM plans.
                            Pick the package that suits your needs and activate in seconds.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Plan Cards --}}
            <div class="row" id="plan-container">
                @php
                    $services = getTables('services');
                    $countServices = $services ? count($services) : 0;
                @endphp

                @if ($services)
                    @foreach ($services as $key => $service)
                        <div class="
                plan-card-wrapper mb-4
                {{ $countServices == 1 ? 'col-lg-12' : ($countServices == 2 ? 'col-lg-4 col-md-6' : 'col-lg-6 col-md-6') }}
                {{ $key > 1 ? 'd-none' : '' }}
            "
                            data-aos="fade-up" data-aos-duration="{{ 700 + $key * 100 }}">

                            <div class="plan-card text-center p-4 h-100">
                                @if ($service->image)
                                    <div class="plan-img mb-3">
                                        <img src="/storage/{{ $service->image }}" alt="{{ $service->name }}"
                                            class="img-fluid">
                                    </div>
                                @endif

                                <h4 class="plan-title">{{ $service->name ?? '' }}</h4>
                                <p class="plan-desc">{{ $service->body ?? '' }}</p>
                                <a href="{{ url('checkout?plan=') }}" class="theme-btn2 mt-3 w-100">
                                    Get This Plan
                                </a>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>



            {{-- Load More Button --}}
            <div class="text-center mt-4">
                <button id="loadMoreBtn" class="theme-btn2">Load More</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let plans = document.querySelectorAll(".plan-card-wrapper.d-none");
            let loadMoreBtn = document.getElementById("loadMoreBtn");
            let shown = 2; // initially showing 2

            loadMoreBtn.addEventListener("click", function() {
                for (let i = shown; i < shown + 3 && i < plans.length + 2; i++) {
                    if (plans[i - 2]) {
                        plans[i - 2].classList.remove("d-none");
                    }
                }
                shown += 3;

                // Hide button if no more to load
                if (shown >= plans.length + 2) {
                    loadMoreBtn.style.display = "none";
                }
            });
        });
    </script>

    <style>
        /* Responsive handling */


        @media (max-width: 991px) {

            /* On tablets & below, show 2 per row */
            #plan-container .plan-card-wrapper {
                flex: 0 0 50%;
                max-width: 50%;
            }
        }

        @media (max-width: 575px) {

            /* On small screens, 1 per row */
            #plan-container .plan-card-wrapper {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }
    </style>


    {{-- Styling --}}
    <style>
        .plan-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .plan-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
        }

        .plan-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: #222;
            margin-bottom: 10px;
        }

        .plan-desc {
            font-size: 0.95rem;
            color: #666;
            margin-top: 10px;
            min-height: 70px;
        }

        .plan-img img {
            max-height: 120px;
            object-fit: contain;
        }

        .plan-card::before {
            content: "";
            position: absolute;
            top: -40px;
            right: -40px;
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #4facfe, #00f2fe);
            border-radius: 50%;
            opacity: 0.12;
        }
    </style>

    <div class="col-lg-7">
        <div class="choose-images" data-aos="zoom-in-up" data-aos-duration="900">
            <div class="image1">
                <img src="{{ $image ?? '' }}" alt="" />
            </div>
            <div class="image2">
                <img src="{{ asset('esoft/img/shapes/hero2-shape.png') }}" alt="" />
            </div>
        </div>
    </div>
    </div>

    </div>
    </div>
    <!-- ===== CHOOSE AREA END ======= -->

    <!-- ===== EMAIL INNOVATION START ======= -->
    <div class="email-innovation"
        style="background-image: url({{ asset('esoft/img/bg/others2-bg.png') }}); background-position: center center; background-repeat: no-repeat; background-size: cover;">
        <div class="container">
            <div class="row">
                <div class="col-lg-5">
                    <div class="headding2-w">
                        @if (getHeading('innovation'))


                               <h2 class="text-anime-style-3">{{getHeading('innovation')->name ?? ''}}</h2>
                                    <div class="space16"></div>
                                    <p data-aos="fade-right" data-aos-duration="800">{{getHeading('innovation')->description ?? ''}}</p>
                                    <div class="space30"></div>
                                    <div class="" data-aos="fade-right" data-aos-duration="1000">
                                        <a href="{{getHeading('innovation')->button_url ?? ''}}" class="theme-btn3">{{getHeading('innovation')->button_name ?? ''}}</a>
                                    </div>

                        @endif

                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="images" data-aos="zoom-in-up" data-aos-duration="1000">
                        <div class="image1">
                            <img src="{{ asset('storage/'.getHeading('innovation')->image ?? '') }}" alt="" />
                        </div>
                        <div class="image2">
                            <img src="{{ asset('esoft/img/shapes/others2-shape1.png') }}" alt="" />
                        </div>
                        <div class="image3">
                            {{-- <img src="{{ asset('esoft/img/shapes/others2-shape2.png') }}" alt="" /> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ===== EMAIL INNOVATION END ======= -->

    <!-- ===== WORK AREA START ======= -->
    <div class="work2 sp _relative">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 m-auto text-center">
                    <div class="headding2">
                        <span class="span">How It Works</span>
                        <h2 class="text-anime-style-3">3 Simple Steps to Get Connected</h2>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-9 m-auto text-center">
                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="tab1-tab" type="button" role="tab"
                                aria-controls="tab1" aria-selected="true">1</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tab2-tab" type="button" role="tab" aria-controls="tab2"
                                aria-selected="false">2</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tab3-tab" type="button" role="tab" aria-controls="tab3"
                                aria-selected="false">3</button>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="tab1" role="tabpanel"
                            aria-labelledby="tab1-tab">
                            <div class="row">

                                @if (getTable('features'))
                                    @foreach (getTables('features') as $feature)

                                         <div class="col-lg-4">
                                    <div class="tabs-box-item" data-aos="fade-up" data-aos-duration="800">
                                        <h3>{{$feature->name ?? ''}}</h3>
                                        <img src="{{ asset('storage/' . $feature->image) }}" alt="" />
                                    </div>
                                </div>
                                    @endforeach
                                @endif

                                {{-- <div class="col-lg-4">
                                    <div class="tabs-box-item" data-aos="fade-up" data-aos-duration="1000">
                                        <h3>Pay Securely with Visa/Mastercard</h3>
                                        <img src="{{ asset('esoft/img/work/work2-img2.png') }}" alt="" />
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="tabs-box-item" data-aos="fade-up" data-aos-duration="1100">
                                        <h3>Get Instant QR via Email</h3>
                                        <img src="{{ asset('esoft/img/work/work2-img3.png') }}" alt="" />
                                    </div>
                                </div> --}}
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="tab2-tab">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="tabs-box-item">
                                        <h3>Scan the QR Code</h3>
                                        <img src="{{ asset('esoft/img/work/work2-img1.png') }}" alt="" />
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="tabs-box-item">
                                        <h3>Turn Data Roaming ON</h3>
                                        <img src="{{ asset('esoft/img/work/work2-img2.png') }}" alt="" />
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="tabs-box-item">
                                        <h3>Connect to Turkish Networks</h3>
                                        <img src="{{ asset('esoft/img/work/work2-img3.png') }}" alt="" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tab3" role="tabpanel" aria-labelledby="tab3-tab">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="tabs-box-item">
                                        <h3>You're Online!</h3>
                                        <img src="{{ asset('esoft/img/work/work2-img1.png') }}" alt="" />
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="tabs-box-item">
                                        <h3>WhatsApp Support Available</h3>
                                        <img src="{{ asset('esoft/img/work/work2-img2.png') }}" alt="" />
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="tabs-box-item">
                                        <h3>Download Orbit App</h3>
                                        <img src="{{ asset('esoft/img/work/work2-img3.png') }}" alt="" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <img class="shape1" src="{{ asset('esoft/img/shapes/home2-element1.png') }}" alt="" />
        <img class="shape2" src="{{ asset('esoft/img/shapes/home2-element2.png') }}" alt="" />
    </div>
    <!-- ===== WORK AREA END ======= -->
   <!-- ===== TES AREA START ======= -->
    <div class="tes2">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="headding2">
                        <span class="span">Student Reviews</span>
                        <h2 class="text-anime-style-3">Loved by students getting set up fast</h2>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="testimonial-arrows" data-aos="fade-left" data-aos-duration="800">
                        <button class="testimonial-prev-arrow2"><i class="fa-regular fa-arrow-left"></i></button>
                        <button class="testimonial-next-arrow2"><i class="fa-regular fa-arrow-right"></i></button>
                    </div>
                </div>
            </div>

            <div class="space60"></div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="tes2-slider-all" data-aos="fade-up" data-aos-duration="900">

                        {{-- Testimonial 1 --}}
                        @if (getTables('services'))
                            @foreach (getTables('reviews') as $rev)
                                <div class="single-slider">
                                    <ul class="stars">
                                        @for ($i = 0; $i <= $rev->rating; $i++)
                                            <li><i class="fa-solid fa-star"></i></li>
                                        @endfor
                                    </ul>

                                    <p>{{ $rev->comment ?? '' }}</p>
                                    <div class="single-slider-bottom">
                                        <div class="headdding-area">
                                            <div class="image">
                                                <img src="storage/{{ $rev->image ?? '' }}" alt="" />
                                            </div>
                                            <div class="headding">
                                                <h5><a href="#">{{ $rev->name ?? '' }}</a></h5>
                                                <p>{{ $rev->role ?? '' }}</p>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <img class="tes2-shape" src="{{ asset('esoft/img/shapes/footer2-shape2.png') }}" alt="" />
    </div>
    <!-- ===== TES AREA END ======= -->


    <div class="faq5 sp" id="faq">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 m-auto text-center">
                    <div class="heading5">
                        <p class="title aos-init aos-animate" data-aos="zoom-in-left" data-aos-duration="700"><span
                                class="span"><img src="assets/img/icons/heading5-span.png" alt=""> FAQ</span>
                        </p>
                        <h2 class="text-anime-style-3" style="perspective: 400px;">
                            <div class="split-line" style="display: block; text-align: center; position: relative;">
                                <div style="position:relative;display:inline-block;">
                                    <div
                                        style="position: relative; display: inline-block; translate: none; rotate: none; scale: none; transform: translate(0px, 0px); opacity: 1;">
                                        Frequestly asked question</div>

                                </div>
                            </div>
                        </h2>
                    </div>
                </div>
            </div>

            <div class="space30"></div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="accordion accordion1 aos-init aos-animate" data-aos="fade-up" data-aos-duration="1000"
                        id="accordionExample">
                        @if (getTables('faqs'))
                            @foreach (getTables('faqs') as $fq)
                                <div class="accordion-item active">
                                    <h2 class="accordion-header" id="headingOne">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseOne" aria-expanded="true"
                                            aria-controls="collapseOne">
                                            {{ $fq->question ?? '' }}
                                        </button>
                                    </h2>
                                    <div id="collapseOne" class="accordion-collapse collapse show"
                                        aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            {{ $fq->answer ?? '' }}
                                        </div>
                                    </div>
                                </div>

                    </div>
                    @endforeach
                    @endif

                </div>
            </div>
            <img src="assets/img/shapes/faq5-shape1.png" alt="" class="shape1">
            <img src="assets/img/shapes/faq5-shape2.png" alt="" class="shape2">
        </div>
    </div>

    {{-- {{ LandingPageSection}} --}}
    <!-- ===== STAPES AREA START ======= -->
<div class="stapes sp">
    <div class="container">

        @if ($sections = getTables('landing_page_sections'))
            @foreach ($sections as $key => $land)
                <div class="row align-items-center {{ $key > 0 ? 'mt-5 pt-5' : '' }}">

                    {{-- Even index → text left, image right --}}
                    @if ($key % 2 == 0)
                        <div class="col-lg-5">
                            <div class="headding2">
                                <span class="span">{{ $land->title }}</span>
                                <h5 class="text-anime-style-3">{{ $land->name }}</h5>
                                <div class="space16"></div>
                                <p data-aos="fade-right" data-aos-duration="800">{{ $land->content }}</p>
                                <div class="space24"></div>
                                <div data-aos="fade-right" data-aos-duration="1000">
                                    <a href="{{ url('checkout') }}" class="theme-btn2">Get Started Now</a>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-7">
                            <div class="stapes-images1" data-aos="flip-right" data-aos-duration="900">
                                @if ($land->image)
                                    <div class="image1">
                                        <img src="/storage/{{ $land->image }}" alt="{{ $land->title }}">
                                    </div>
                                @endif
                                <div class="image2">
                                    <img src="{{ asset('esoft/img/shapes/hero2-shape.png') }}" alt="shape">
                                </div>
                            </div>
                        </div>

                    {{-- Odd index → image left, text right --}}
                    @else
                        <div class="col-lg-7">
                            <div class="stapes-images2">
                                @if ($land->image)
                                    <div class="image1" data-aos="flip-left" data-aos-duration="900">
                                        <img src="/storage/{{ $land->image }}" alt="{{ $land->title }}">
                                    </div>
                                @endif
                                <div class="image2">
                                    <img src="{{ asset('esoft/img/shapes/hero2-shape.png') }}" alt="shape">
                                </div>
                                <div class="main-shape">
                                    <img src="{{ asset('esoft/img/shapes/staps-shape.png') }}" alt="shape">
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-5">
                            <div class="headding2 pbmit-heading-subheading animation-style2">
                                <span class="span">{{ $land->title }}</span>
                                <h5 class="text-anime-style-3">{{ $land->name }}</h5>
                                <div class="space16"></div>
                                <p data-aos="fade-left" data-aos-duration="800">{{ $land->content }}</p>
                                <div class="space24"></div>
                                <div data-aos="fade-left" data-aos-duration="1000">
                                    <a href="{{ url('checkout') }}" class="theme-btn2">Get Started Now</a>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            @endforeach
        @endif

    </div>
</div>
<!-- ===== STAPES AREA END ======= -->


    <!-- ===== APPS AREA SREA ======= -->
    {{-- <div class="apps"
        style="background-image: url({{ asset('esoft/img/bg/apps-area-bg.png') }}); background-position: center center; background-repeat: no-repeat; background-size: cover;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-4">
                    <div class="apps-img1" data-aos="fade-down" data-aos-duration="800">
                        <img src="{{ asset('esoft/img/others/apps-img1.png') }}" alt="" />
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="headding2-w text-center">
                        <span class="span">Trust & Security</span>
                        <h2 class="text-anime-style-3">Trusted Payment & Activation</h2>
                        <div class="space16"></div>
                        <p data-aos="fade-up" data-aos-duration="800">Instant QR • Visa/Mastercard • WhatsApp Support •
                            24-hour Activation Guarantee</p>

                        <div class="space30"></div>
                        <div class="" data-aos="fade-up" data-aos-duration="1000">
                            <a href="{{ url('checkout') }}" class="others">Activate eSIM Now</a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="apps-img2" data-aos="fade-down" data-aos-duration="900">
                        <img src="{{ asset('esoft/img/others/apps-img2.png') }}" alt="" />
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-10 m-auto text-center">
                    <div class="bottom-logo">
                        <img src="{{ asset('esoft/img/logo/others2-bottom-logo.png') }}" alt="" />
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    <!-- ===== APPS AREA END ======= -->




    @endsection
