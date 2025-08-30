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
