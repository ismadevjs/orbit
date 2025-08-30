    <div class="hero-area2">
        <div class="container">
            <div class="row align-items-center hero2-content">
                <div class="col-lg-6">
                    <div class="main-headding">
                      <span class="span">
                            {{ $carousel ? __('messages.' . $carousel->subtitle) : '' }}
                        </span>


                        <h1 class="text-anime-style-3">{{ $carousel->title ?? '' }}</h1>
                        <p data-aos="fade-right" data-aos-duration="800">
                            {{ $carousel->body ?? '' }}
                        </p>

                        <div class="space30"></div>
                        <div class="hero-btns row" data-aos="fade-right" data-aos-duration="1100">

                            @if ($buttonCount > 0)
                                @foreach ($buttons as $btn)
                                    <div class="{{ $buttonCount == 2 ? 'col-6' : 'col-4' }} mb-2">
                                        <a href="{{ $btn->link ?? '#' }}"
                                        class="theme-btn2 w-100 text-center {{ $loop->index == 1 ? 'theme-btn3' : '' }}">
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
