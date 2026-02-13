{{-- HERO SLIDER START --}}
<div id="hero-slider" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3500" data-bs-pause="hover">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="{{ asset('assets/images/slider/efecte-decorative-v2-1.jpg') }}" class="d-block w-100" alt="Efecte Decorative">
    </div>
    <div class="carousel-item">
      <img src="{{ asset('assets/images/slider/mobila-la-comanda_slider.jpg.png') }}" class="d-block w-100" alt="Mobila la Comanda">
    </div>
    <div class="carousel-item">
      <img src="{{ asset('assets/images/slider/profile-decorative-v2.jpg') }}" class="d-block w-100" alt="Profile Decorative">
    </div>
    <div class="carousel-item">
      <img src="{{ asset('assets/images/slider/slider-tencuieli-decorative-v2.jpg') }}" class="d-block w-100" alt="Tencuieli Decorative">
    </div>
    <div class="carousel-item">
      <img src="{{ asset('assets/images/slider/slider-tenc-pluta-dec-e.jpg') }}" class="d-block w-100" alt="Tenc Pluta">
    </div>
    <div class="carousel-item">
      <img src="{{ asset('assets/images/slider/slider-tenc-pers-bet-e-1.jpg') }}" class="d-block w-100" alt="Tenc Pers Bet">
    </div>
    <div class="carousel-item">
      <img src="{{ asset('assets/images/slider/slider-tapet-e.jpg') }}" class="d-block w-100" alt="Tapet">
    </div>
    <div class="carousel-item">
      <img src="{{ asset('assets/images/slider/slider-rest-fatade-e.jpg') }}" class="d-block w-100" alt="Rest Fatade">
    </div>
    <div class="carousel-item">
      <img src="{{ asset('assets/images/slider/slider-profile-decorative-exterior-v2.jpg') }}" class="d-block w-100" alt="Profile Decorative Exterior">
    </div>
  </div>
  <a class="carousel-control-prev" href="#hero-slider" role="button" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="carousel-control-next" href="#hero-slider" role="button" data-bs-slide="next" style="display: flex; align-items: center; justify-content: center; width: 5%;">
    <span class="carousel-control-next-icon" aria-hidden="true" style="display: inline-block;"></span>
    <span class="sr-only">Next</span>
  </a>
</div>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    if (typeof bootstrap !== 'undefined' && bootstrap.Carousel) {
      var myCarousel = document.getElementById('hero-slider');
      var carousel = new bootstrap.Carousel(myCarousel, {
        interval: 3500,
        pause: 'hover',
        ride: 'carousel'
      });
    } else if (typeof $ !== 'undefined' && $('#hero-slider').carousel) {
      $('#hero-slider').carousel({
        interval: 3500,
        pause: 'hover',
        ride: 'carousel'
      });
    }
  });
</script>
{{-- HERO SLIDER END --}}