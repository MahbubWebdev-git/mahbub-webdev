@php
    $aboutpage = App\Models\About::find(1) ?? new App\Models\About();
    $allMultiImage = App\Models\MultiImage::all();
@endphp
<section id="aboutSection" class="about">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <ul class="about__icons__wrap">
                    @foreach($allMultiImage as $item)
                    <li>
                        <img class="light" src="{{ asset($item->multi_image) }}" alt="XD">
                        <img class="dark" src="{{ asset($item->multi_image) }}" alt="XD">
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="col-lg-6">
                <div class="about__content">
                    <div class="section__title">
                        <span class="sub-title">{{ $aboutpage->short_title ?? 'About Me' }}</span>
                        <h2>{{ $aboutpage->title ?? 'Default About Title' }}</h2>
                    </div>
                    <div class="about__exp">
                        <div class="about__exp__icon">
                            <img src="{{ asset('frontend/assets/img/icons/about_icon.png') }}" alt="">
                        </div>
                        <div class="about__exp__content">
                            <p>{{ $aboutpage->short_description ?? 'Default short description' }}</p>
                        </div>
                    </div>
                    <div class="desc">{!! $aboutpage->long_description ?? 'Default long description' !!}</div>
                    <a href="about.html" class="btn">Download my resume</a>
                </div>
            </div>
        </div>
    </div>
</section>