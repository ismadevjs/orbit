 <div class="tes2">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="headding2">
                        <span class="span">{{ getHeading('testimonials')->title ?? '' }}</span>
                        <h2 class="text-anime-style-3">{{ getHeading('testimonials')->name ?? '' }}</h2>
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
