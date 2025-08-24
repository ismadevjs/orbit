@extends('layouts.frontend')

@section('title', 'FliSim - Activate your eSIM in 60 seconds — campus-ready')

@section('content')
    <!-- ===== HERO AREA START ======= -->
    <div class="hero-area2">
        <div class="container">
            <div class="row align-items-center hero2-content">
                <div class="col-lg-6">
                    <div class="main-headding">
                        <span class="span">60-Second eSIM Activation</span>
                        <h1 class="text-anime-style-3">Activate your eSIM in 60 seconds — campus-ready</h1>
                        <p data-aos="fade-right" data-aos-duration="800">
                            WhatsApp support in Turkish, Arabic, and English. <br />
                            Perfect for students and expats in Türkiye. Instant activation, <br />
                            secure payment, and multilingual support available 24/7.
                        </p>

                        <div class="space30"></div>
                        <div class="hero-btns" data-aos="fade-right" data-aos-duration="1100">
                            <a href="{{ url('checkout') }}" class="theme-btn2">Activate eSIM Now</a>
                            <a href="#compatibility" class="theme-btn3">Is my phone compatible?</a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="hero2-image">
                        <div class="image1 animate1">
                            <img src="{{ asset('esoft/img/hero/hero2-image1.png') }}" alt="" />
                        </div>
                        <div class="image2">
                            <img src="{{ asset('esoft/img/hero/hero2-image2.png') }}" alt="" />
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
    <div class="space30"></div>

    <div class="choose2 pb120">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 m-auto text-center">
                    <div class="headding2">
                        <span class="span">Popular for students & expats in Türkiye</span>
                        <h2 class="text-anime-style-3">Choose Your Perfect <br /> eSIM Plan</h2>
                    </div>
                </div>
            </div>

            <div class="space30"></div>
            <div class="row align-items-center">
                <div class="col-lg-5">
                    <div class="accordion accordion1" id="accordionExample">
                        <div class="accordion-item active" data-aos="fade-right" data-aos-duration="800">
                            <h2 class="accordion-header" id="headingOne"><button class="accordion-button" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true"
                                    aria-controls="collapseOne">Türkiye 10GB - Most chosen</button></h2>
                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                                data-bs-parent="#accordionExample">
                                <div class="accordion-body">Instant email QR • Works on most modern phones • No store visit • Perfect for students and expats.</div>
                            </div>
                        </div>
                        <div class="accordion-item" data-aos="fade-right" data-aos-duration="1000">
                            <h2 class="accordion-header" id="headingTwo"><button class="accordion-button collapsed"
                                    type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo"
                                    aria-expanded="false" aria-controls="collapseTwo">Türkiye 5GB - Light users</button></h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                                data-bs-parent="#accordionExample">
                                <div class="accordion-body">Quick trips & light users • Instant email QR • Works on most modern phones • No store visit needed.</div>
                            </div>
                        </div>
                        <div class="accordion-item" data-aos="fade-right" data-aos-duration="1200">
                            <h2 class="accordion-header" id="headingThree"><button class="accordion-button collapsed"
                                    type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree"
                                    aria-expanded="false" aria-controls="collapseThree">24-hour Activation Guarantee</button></h2>
                            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree"
                                data-bs-parent="#accordionExample">
                                <div class="accordion-body">We'll help you get online fast with our 24-hour activation guarantee and WhatsApp support in TR/AR/EN.</div>
                            </div>
                        </div>
                    </div>

                    <div class="space30"></div>

                    <div class="" data-aos="fade-right" data-aos-duration="900">
                        <a href="{{ url('checkout?plan=turkey-10gb') }}" class="theme-btn2">Get 10GB Now</a>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="choose-images" data-aos="zoom-in-up" data-aos-duration="900">
                        <div class="image1">
                            <img src="{{ asset('esoft/img/choose/choose2-img.png') }}" alt="" />
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
                        <h2 class="text-anime-style-3">eSIM Innovation: Connect Instantly With Success</h2>
                        <div class="space16"></div>
                        <p data-aos="fade-right" data-aos-duration="800">Your mobile connectivity journey transforms into a streamlined, powerful experience. Our cutting-edge eSIM platform equips you with instant activation, multilingual support</p>
                        <div class="space30"></div>
                        <div class="" data-aos="fade-right" data-aos-duration="1000">
                            <a href="{{ url('checkout') }}" class="theme-btn3">Start For Free Now</a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="images" data-aos="zoom-in-up" data-aos-duration="1000">
                        <div class="image1">
                            <img src="{{ asset('esoft/img/others/others2-image.png') }}" alt="" />
                        </div>
                        <div class="image2">
                            <img src="{{ asset('esoft/img/shapes/others2-shape1.png') }}" alt="" />
                        </div>
                        <div class="image3">
                            <img src="{{ asset('esoft/img/shapes/others2-shape2.png') }}" alt="" />
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
                            <button class="nav-link active" id="tab1-tab" data-bs-toggle="pill" data-bs-target="#tab1"
                                type="button" role="tab" aria-controls="tab1" aria-selected="true">1</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tab2-tab" data-bs-toggle="pill" data-bs-target="#tab2"
                                type="button" role="tab" aria-controls="tab2" aria-selected="false">2</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tab3-tab" data-bs-toggle="pill" data-bs-target="#tab3"
                                type="button" role="tab" aria-controls="tab3" aria-selected="false">3</button>
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
                                <div class="col-lg-4">
                                    <div class="tabs-box-item" data-aos="fade-up" data-aos-duration="800">
                                        <h3>Choose Your Türkiye Plan</h3>
                                        <img src="{{ asset('esoft/img/work/work2-img1.png') }}" alt="" />
                                    </div>
                                </div>
                                <div class="col-lg-4">
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
                                </div>
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

    <!-- ===== STAPES AREA START ======= -->
    <div class="stapes sp">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-5">
                    <div class="headding2">
                        <span class="span">eSIM Benefits</span>
                        <h5 class="text-anime-style-3">Usage Meter & 1-Tap Recharge</h5>
                        <div class="space16"></div>
                        <p data-aos="fade-right" data-aos-duration="800">Experience peace of mind with the Orbit app featuring usage meter, 1-tap recharge, and the ability to switch country on eligible plans, ensuring seamless connectivity wherever you go.</p>
                        <div class="space24"></div>
                        <div class="" data-aos="fade-right" data-aos-duration="1000">
                            <a href="{{ url('checkout') }}" class="theme-btn2">Get Started Now</a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="stapes-images1" data-aos="flip-right" data-aos-duration="900">
                        <div class="image1">
                            <img src="{{ asset('esoft/img/work/staps-1.png') }}" alt="" />
                        </div>
                        <div class="image2">
                            <img src="{{ asset('esoft/img/shapes/hero2-shape.png') }}" alt="" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="space120"></div>
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <div class="stapes-images2">
                        <div class="image1" data-aos="flip-left" data-aos-duration="900">
                            <img src="{{ asset('esoft/img/work/staps-1.png') }}" alt="" />
                        </div>
                        <div class="image2">
                            <img src="{{ asset('esoft/img/shapes/hero2-shape.png') }}" alt="" />
                        </div>

                        <div class="main-shape">
                            <img src="{{ asset('esoft/img/shapes/staps-shape.png') }}" alt="" />
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="headding2 pbmit-heading-subheading animation-style2">
                        <span class="span">WhatsApp Support</span>
                        <h5 class="text-anime-style-3">Turkish, Arabic & English Support</h5>
                        <div class="space16"></div>
                        <p data-aos="fade-left" data-aos-duration="800">Get instant help with our multilingual WhatsApp support available 10:00–01:00 KSA. Our team speaks Turkish, Arabic, and English to assist students and expats in Türkiye.</p>
                        <div class="space24"></div>
                        <div class="" data-aos="fade-left" data-aos-duration="1000">
                            <a href="https://wa.me/your-number" class="theme-btn2" target="_blank">Chat on WhatsApp</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ===== STAPES AREA END ======= -->

    <!-- ===== APPS AREA SREA ======= -->
    <div class="apps"
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
                        <p data-aos="fade-up" data-aos-duration="800">Instant QR • Visa/Mastercard • WhatsApp Support • 24-hour Activation Guarantee</p>

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
    </div>
    <!-- ===== APPS AREA END ======= -->

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
                    <div class="single-slider">
                        <ul class="stars">
                            <li><i class="fa-solid fa-star"></i></li>
                            <li><i class="fa-solid fa-star"></i></li>
                            <li><i class="fa-solid fa-star"></i></li>
                            <li><i class="fa-solid fa-star"></i></li>
                            <li><i class="fa-solid fa-star"></i></li>
                        </ul>
                        <div class="icon">
                            <img src="{{ asset('esoft/img/icons/tes2-icon.png') }}" alt="" />
                        </div>
                        <p>"Perfect for my semester in Istanbul! Setup in minutes and WhatsApp support in Arabic was incredibly helpful when I had questions."</p>
                        <div class="single-slider-bottom">
                            <div class="headdding-area">
                                <div class="image">
                                    <img src="{{ asset('esoft/img/testimonial/tes2-img1.png') }}" alt="" />
                                </div>
                                <div class="headding">
                                    <h5><a href="#">Ahmed K.</a></h5>
                                    <p>Student in Ankara</p>
                                </div>
                            </div>
                            <div class="logo">
                                <img src="{{ asset('esoft/img/logo/tes2-logo1.png') }}" alt="" />
                            </div>
                        </div>
                    </div>

                    {{-- Testimonial 2 --}}
                    <div class="single-slider">
                        <ul class="stars">
                            <li><i class="fa-solid fa-star"></i></li>
                            <li><i class="fa-solid fa-star"></i></li>
                            <li><i class="fa-solid fa-star"></i></li>
                            <li><i class="fa-solid fa-star"></i></li>
                            <li><i class="fa-solid fa-star"></i></li>
                        </ul>
                        <div class="icon">
                            <img src="{{ asset('esoft/img/icons/tes2-icon.png') }}" alt="" />
                        </div>
                        <p>"Cheaper than roaming and works everywhere in Turkey. The QR code arrived instantly and activation was super smooth for my exchange program."</p>
                        <div class="single-slider-bottom">
                            <div class="headdding-area">
                                <div class="image">
                                    <img src="{{ asset('esoft/img/testimonial/tes2-img2.png') }}" alt="" />
                                </div>
                                <div class="headding">
                                    <h5><a href="#">Sarah M.</a></h5>
                                    <p>Exchange Student Istanbul</p>
                                </div>
                            </div>
                            <div class="logo">
                                <img src="{{ asset('esoft/img/logo/tes2-logo2.png') }}" alt="" />
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <img class="tes2-shape" src="{{ asset('esoft/img/shapes/footer2-shape2.png') }}" alt="" />
</div>
<!-- ===== TES AREA END ======= -->


<!-- ===== BLOG AREA START ======= -->
<div class="blog2 sp">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 m-auto text-center">
                <div class="headding2">
                    <span class="span">FAQ</span>
                    <h2 class="text-anime-style-3">Frequently Asked Questions</h2>
                </div>
            </div>
        </div>

        <div class="space30"></div>

        <div class="row">
            <div class="col-lg-6">
                <div class="blog-box" data-aos="zoom-in-up" data-aos-duration="800">
                    <div class="image">
                        <img src="{{ asset('esoft/img/blog/blog2-img1.png') }}" alt="" />
                    </div>
                    <div class="headding">
                        <div class="tags">
                            <a href="#"><img src="{{ asset('esoft/img/icons/blog2-icon1.png') }}" alt="" /> FAQ</a>
                            <a href="#"><img src="{{ asset('esoft/img/icons/blog2-icon2.png') }}" alt="" /> eSIM Guide</a>
                        </div>
                        <h4><a href="#faq">Is my phone compatible with eSIM?</a></h4>
                        <p>Most modern iPhone/Android models support eSIM. Message us on WhatsApp if you're unsure about compatibility.</p>
                        <a href="https://wa.me/your-number" class="learn">Ask on WhatsApp <span><i class="fa-regular fa-arrow-right"></i></span></a>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="blog-box" data-aos="zoom-in-up" data-aos-duration="1000">
                    <div class="image">
                        <img src="{{ asset('esoft/img/blog/blog2-img2.png') }}" alt="" />
                    </div>
                    <div class="headding">
                        <div class="tags">
                            <a href="#"><img src="{{ asset('esoft/img/icons/blog2-icon1.png') }}" alt="" /> Support</a>
                            <a href="#"><img src="{{ asset('esoft/img/icons/blog2-icon2.png') }}" alt="" /> Activation</a>
                        </div>
                        <h4><a href="#support">Need help with activation?</a></h4>
                        <p>We'll help you get online fast with 24-hour activation guarantee and multilingual WhatsApp support.</p>
                        <a href="https://wa.me/your-number" class="learn">Get Help Now <span><i class="fa-regular fa-arrow-right"></i></span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ===== BLOG AREA END ======= -->

@endsection
