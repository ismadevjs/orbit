<!-- ===== FOOTER AREA START ======= -->
<div class="footer2 _relative">
  <div class="container">

    <div class="space50"></div>
    <div class="row">
      <div class="col-lg col-md-6 col-12">
  <div class="single-footer-items">
    <h3>About Flisim</h3>
    <p>{{ getSettingValue('footer_links') ?? '' }}</p>
  </div>
</div>




      <div class="col-lg-3 col-md-6 col-7">
        <div class="single-footer-items">
          <h3>Social Links</h3>

          <ul class="social-icons">
            <li>
              <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
            </li>
            <li>
              <a href="#"><i class="fa-brands fa-x-twitter"></i></a>
            </li>
            <li>
              <a href="#"><i class="fa-brands fa-youtube"></i></a>
            </li>
            <li>
              <a href="#"><i class="fa-brands fa-instagram"></i></a>
            </li>
          </ul>
        </div>
      </div>
    </div>

    <div class="space40"></div>

    <div class="copyright-area">
      <div class="row align-items-center">
        <div class="col-md-5">
          <div class="logo">
            <a href="{{ url('index') }}">
            <img src="{{ asset('storage/' . getSettingValue('logo')) }}" alt="" />
        </a>
          </div>
        </div>
        <div class="col-12 col-md-7">
  <div class="coppyright text-md-right text-center">
    <p>{{ getSettingValue('footer_text') }}</p>
  </div>
</div>

      </div>
    </div>
  </div>

  <img class="footer-shape" src="{{asset('esoft/img/shapes/footer2-shape.png')}}" alt="" />
  <img class="footer-shape2" src="{{asset('esoft/img/shapes/footer2-shape2.png')}}" alt="" />
</div>
<!-- ===== FOOTER AREA END ======= -->
