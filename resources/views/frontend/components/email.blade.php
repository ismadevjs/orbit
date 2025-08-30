<div class="email-innovation"
        style="background-image: url({{ asset('esoft/img/bg/others2-bg.png') }}); background-position: center center; background-repeat: no-repeat; background-size: cover;">
        <div class="container">
            <div class="row">
                <div class="col-lg-5">
                    <div class="headding2-w">
                        @if (getHeading('innovation'))
                            <h2 class="text-anime-style-3">{{ getHeading('innovation')->name ?? '' }}</h2>
                            <div class="space16"></div>
                            <p data-aos="fade-right" data-aos-duration="800">
                                {{ getHeading('innovation')->description ?? '' }}</p>
                            <div class="space30"></div>
                            <div class="" data-aos="fade-right" data-aos-duration="1000">
                                <a href="{{ getHeading('innovation')->button_url ?? '' }}"
                                    class="theme-btn3">{{ getHeading('innovation')->button_name ?? '' }}</a>
                            </div>
                        @endif

                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="images" data-aos="zoom-in-up" data-aos-duration="1000">
                        <div class="image1">
                            <img src="{{ asset('storage/' . optional(getHeading('innovation'))->image) }}"
                                alt="" />
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
