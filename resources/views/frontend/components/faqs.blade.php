  <div class="faq5 sp" id="faq">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 m-auto text-center">
                    <div class="heading5">
                        <p class="title aos-init aos-animate" data-aos="zoom-in-left" data-aos-duration="700">
                            <span class="span">
                                <img src="assets/img/icons/heading5-span.png" alt=""> FAQ
                            </span>
                        </p>
                        <h2 class="text-anime-style-3" style="perspective: 400px;">
                            <div class="split-line" style="display: block; text-align: center; position: relative;">
                                <div style="position:relative;display:inline-block;">
                                    <div style="position: relative; display: inline-block;">
                                        Frequently Asked Questions
                                    </div>
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
                            @foreach (getTables('faqs') as $index => $fq)
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading{{ $index }}">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}"
                                            aria-expanded="false" aria-controls="collapse{{ $index }}">
                                            {{ $fq->question ?? '' }}
                                        </button>
                                    </h2>
                                    <div id="collapse{{ $index }}" class="accordion-collapse collapse"
                                        aria-labelledby="heading{{ $index }}" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            {{ $fq->answer ?? '' }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <img src="assets/img/shapes/faq5-shape1.png" alt="" class="shape1">
            <img src="assets/img/shapes/faq5-shape2.png" alt="" class="shape2">
        </div>
    </div>
