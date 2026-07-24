@php
    $allfooter = App\Models\Footer::find(1) ?? new App\Models\Footer();
@endphp


<footer class="footer">
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-lg-4">
                <div class="footer__widget">
                    <div class="fw-title">
                        <h5 class="sub-title">Contact us</h5>
                        <h4 class="title">{{ $allfooter->number ?? '+123 456 7890' }}</h4>
                    </div>
                    <div class="footer__widget__text">
                        <p>{{ $allfooter->short_description ?? 'Default short description' }}</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-sm-6">
                <div class="footer__widget">
                    <div class="fw-title">
                        <h5 class="sub-title">my address</h5>
                        <h4 class="title">Bangladesh</h4> 
                    </div>
                    <div class="footer__widget__address">
                        <p>{{ $allfooter->address ?? 'Default address' }}</p>
                        <a href="mailto:{{ $allfooter->email ?? 'default@example.com' }}" class="mail">{{ $allfooter->email ?? 'default@example.com' }}</a>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-sm-6">
                <div class="footer__widget">
                    <div class="fw-title">
                        <h5 class="sub-title">Follow me</h5>
                        <h4 class="title">socially connect</h4>
                    </div>
                    <div class="footer__widget__social">
                        <p>Lorem ipsum dolor sit amet enim. <br> Etiam ullamcorper.</p>
                        <ul class="footer__social__list">
                            <li><a href="{{ $allfooter->facebook }}" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
                            <li><a href="{{ $allfooter->twitter }}" target="_blank"><i class="fab fa-twitter"></i></a></li>
                            <li><a href="{{ $allfooter->behance }}" target="_blank"><i class="fab fa-behance"></i></a></li>
                            <li><a href="{{ $allfooter->linkedin }}" target="_blank"><i class="fab fa-linkedin-in"></i></a></li>
                            <li><a href="{{ $allfooter->instagram }}" target="_blank"><i class="fab fa-instagram"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="copyright__wrap">
            <div class="row">
                <div class="col-12">
                    <div class="copyright__text text-center">
                        <p>{{ $allfooter->copyright }}</p> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>