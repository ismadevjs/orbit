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
                                                <h3>{{ $feature->name ?? '' }}</h3>
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
