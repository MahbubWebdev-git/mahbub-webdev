@php
    $homeSlides = App\Models\HomeSlide::find(1) ?? new App\Models\HomeSlide();
@endphp


<section class="banner">
    <div class="container custom-container">
        <div class="row align-items-center justify-content-center justify-content-lg-between">
            <div class="col-lg-6 order-0 order-lg-2">
                <div class="text-center text-xxl-end">
                    <img src="{{ asset($homeSlides->home_slide ?? 'frontend/assets/img/banner/banner_img.png') }}" alt="{{ 'home slider' }}" class="banner__img">
                </div>
            </div>
            <div class="col-xl-5 col-lg-6">
                <div class="banner__content">
                    <h2 class="title wow fadeInUp" data-wow-delay=".2s"><span>{{ $homeSlides->title }}</span> <br>
                    </h2>
                    <p class="wow fadeInUp" data-wow-delay=".4s">{{ $homeSlides->short_title }}</p>
                    <a href="about.html" class="btn banner__btn wow fadeInUp" data-wow-delay=".6s">more about me</a>
                </div>
            </div>
        </div>
    </div>
    <div class="scroll__down">
        <a href="#aboutSection" class="scroll__link">Scroll down</a>
    </div>
    <div class="banner__video">
        <a href="{{ $homeSlides->video_url ?? '#' }}" class="popup-video"><i class="fas fa-play"></i></a>
    </div>
</section>