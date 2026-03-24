{{-- HERO SLIDER START --}}
<div id="hero-slider" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3500" data-bs-pause="hover">
  <div class="carousel-inner">
    @forelse ($sliders as $index => $slider)
      <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
<img src="{{ Storage::url($slider->image_path) }}" class="d-block w-100" style="max-height: 500px; object-fit: cover;" alt="Slider {{ $index + 1 }}">
      </div>
    @empty
      <div class="carousel-item active">
<img src="{{ asset('assets/images/slider/efecte-decorative-v2-1.jpg') }}" class="d-block w-100" style="max-height: 500px; object-fit: cover;" alt="Slider">
      </div>
    @endforelse
  </div>
  <a class="carousel-control-prev" href="#hero-slider" role="button" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
  </a>
  <a class="carousel-control-next" href="#hero-slider" role="button" data-bs-slide="next" style="display: flex; align-items: center; justify-content: center; width: 5%;">
    <span class="carousel-control-next-icon" aria-hidden="true" style="display: inline-block;"></span>
  </a>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    if (typeof bootstrap !== 'undefined' && bootstrap.Carousel) {
      var myCarousel = document.getElementById('hero-slider');
      if (myCarousel) {
        new bootstrap.Carousel(myCarousel, {
          interval: 3500,
          pause: 'hover',
          ride: 'carousel'
        });
      }
    } else if (typeof $ !== 'undefined' && $('#hero-slider').length) {
      $('#hero-slider').carousel({
        interval: 3500,
        pause: 'hover'
      });
    }
  });
</script>
{{-- HERO SLIDER END --}}

