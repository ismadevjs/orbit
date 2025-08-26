<!-- ===== CTA AREA END ======= -->
<div  style="padding : 80px 0px 77px 0px;background-image: url({{asset('esoft/img/bg/cta2-bg.png')}}); background-position: center; background-repeat: no-repeat; background-size: cover;">
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

