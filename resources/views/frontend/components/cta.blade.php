<!-- ===== CTA AREA END ======= -->
<div class="cta2-area" style="background-image: url({{asset('esoft/img/bg/cta2-bg.png')}}); background-position: center; background-repeat: no-repeat; background-size: cover;">
  <div class="container">
    <div class="row">
      <div class="col-lg-6 m-auto text-center">
        <div class="headding2-w pbmit-heading-subheading animation-style2">
          <h2 class="pbmit-title">{{getHeading('join')->title ?? ''}}</h2>
          <div class="space16"></div>
          <p data-aos="fade-up" data-aos-duration="800">{{getHeading('join')->description ?? ''}}</p>
          <div class="space30"></div>
          <div class="" data-aos="fade-up" data-aos-duration="1000">
            <a href="{{getHeading('join')->button_url ?? ''}}" class="theme-btn3">{{getHeading('join')->button_name ?? ''}}</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- ===== CTA AREA END ======= -->

<!-- ===== CTA IMAGE AREA START ======= -->
<div class="cta2-main-image">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="cta2-images">
          <div class="img1">
            <img src="{{asset('storage/'.getHeading('join')->image ?? '')}}" alt="" />
          </div>
          <div class="shape1">
            <img src="{{asset('esoft/img/shapes/cta2-shape2.png')}}" alt="" />
          </div>
          <div class="shape2">
            <img src="{{asset('esoft/img/shapes/cta2-shape1.png')}}" alt="" />
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- ===== CTA IMAGE AREA END ======= -->
