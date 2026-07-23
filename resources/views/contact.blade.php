@extends('layout')

@section('title', 'Contact Us — CoffeeShop')

@section('styles')
<style>
    .contact-hero {
        background: url('/images/contact-hero.jpg') center/cover no-repeat;
        min-height: 300px;
        display: flex; align-items: center;
        position: relative;
    }
    .contact-hero::before { content:''; position:absolute; inset:0; background:linear-gradient(135deg,rgba(28,15,7,.82) 0%,rgba(28,15,7,.5) 100%); }
    .contact-hero .content { position:relative; z-index:1; }

    .contact-form-card {
        background: #fff;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        padding: 2.5rem;
    }
    .info-card {
        background: linear-gradient(135deg, var(--coffee-mid), var(--coffee-dark));
        border-radius: var(--radius);
        padding: 2.5rem;
        color: #fff;
        height: 100%;
    }
    .info-item {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.8rem;
        align-items: flex-start;
    }
    .info-icon {
        width: 44px; height: 44px;
        background: rgba(200,150,62,.2);
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem;
        color: var(--coffee-accent);
        flex-shrink: 0;
    }
    .info-label { font-size: .75rem; color: rgba(255,255,255,.5); text-transform: uppercase; letter-spacing: 1px; margin-bottom: .2rem; }
    .info-value { font-size: .95rem; font-weight: 500; }
    @media(max-width:767.98px) {
        .contact-hero { min-height:230px; }
        .contact-form-card, .info-card { padding:1.35rem; }
        .info-item { gap:.8rem; margin-bottom:1.3rem; }
        .info-icon { width:40px; height:40px; }
        iframe { min-height:300px; }
    }
</style>
@endsection

@section('content')

{{-- Hero --}}
<section class="contact-hero">
    <div class="container content text-white">
        <span style="font-size:.75rem;font-weight:600;letter-spacing:3px;text-transform:uppercase;color:var(--coffee-light);">We're here</span>
        <h1 style="font-size:clamp(2rem,5vw,3.2rem);margin-bottom:.5rem;">Get in Touch</h1>
        <p style="color:rgba(255,255,255,.75);font-size:1rem;">We'd love to hear from you.</p>
    </div>
</section>

<section style="padding:4.5rem 0; background:var(--warm-gray);">
    <div class="container">

        @if(session('success'))
        <div class="alert rounded-3 mb-4" style="background:#e8f5e9;border:none;color:#2e7d32;font-size:.9rem;">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        </div>
        @endif

        <div class="row g-4 align-items-stretch">

            {{-- Info panel --}}
            <div class="col-lg-4">
                <div class="info-card">
                    <h4 style="color:#fff;margin-bottom:.5rem;">Contact Info</h4>
                    <p style="color:rgba(255,255,255,.6);font-size:.88rem;margin-bottom:2rem;">Reach out through any channel and we'll respond promptly.</p>

                    <div class="info-item">
                        <div class="info-icon"><i class="bi bi-geo-alt"></i></div>
                        <div>
                            <div class="info-label">Address</div>
                            <div class="info-value">123 Coffee St, Bean City, CA</div>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-icon"><i class="bi bi-telephone"></i></div>
                        <div>
                            <div class="info-label">Phone</div>
                            <div class="info-value">+1 (123) 456-7890</div>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-icon"><i class="bi bi-envelope"></i></div>
                        <div>
                            <div class="info-label">Email</div>
                            <div class="info-value">support@coffeeshop.com</div>
                        </div>
                    </div>
                    <div class="info-item" style="margin-bottom:0;">
                        <div class="info-icon"><i class="bi bi-clock"></i></div>
                        <div>
                            <div class="info-label">Hours</div>
                            <div class="info-value">Mon–Fri: 7am – 9pm<br>Sat–Sun: 8am – 10pm</div>
                        </div>
                    </div>

                    <div class="d-flex gap-3 mt-4" style="font-size:1.2rem;">
                        <a href="#" style="color:rgba(255,255,255,.5);transition:var(--transition);" onmouseover="this.style.color='#c8963e'" onmouseout="this.style.color='rgba(255,255,255,.5)'"><i class="bi bi-instagram"></i></a>
                        <a href="#" style="color:rgba(255,255,255,.5);transition:var(--transition);" onmouseover="this.style.color='#c8963e'" onmouseout="this.style.color='rgba(255,255,255,.5)'"><i class="bi bi-facebook"></i></a>
                        <a href="#" style="color:rgba(255,255,255,.5);transition:var(--transition);" onmouseover="this.style.color='#c8963e'" onmouseout="this.style.color='rgba(255,255,255,.5)'"><i class="bi bi-twitter-x"></i></a>
                    </div>
                </div>
            </div>

            {{-- Form --}}
            <div class="col-lg-8">
                <div class="contact-form-card">
                    <h4 style="margin-bottom:.4rem;">Send a Message</h4>
                    <p style="color:var(--text-muted);font-size:.88rem;margin-bottom:1.8rem;">Fill out the form and we'll get back to you within 24 hours.</p>
                    <form method="POST" action="/contact/send">
                        @csrf
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label class="form-label">Your Name</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label">Your Email</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Subject</label>
                                <input type="text" class="form-control" name="subject" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Message</label>
                                <textarea class="form-control" name="message" rows="5" required style="resize:vertical;"></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-coffee">Send Message <i class="bi bi-send ms-1"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>

        {{-- Map --}}
        <div class="mt-4 rounded-3 overflow-hidden" style="box-shadow:var(--shadow);">
            <div class="ratio ratio-21x9">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3151.8354345095144!2d144.9537353153168!3d-37.81627937975162!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6ad642af0f11fd81%3A0xf577d3b396f44c0!2sCoffee%20Shop!5e0!3m2!1sen!2sus!4v1633040922177!5m2!1sen!2sus"
                    allowfullscreen="" loading="lazy" style="border:0;">
                </iframe>
            </div>
        </div>

    </div>
</section>

@endsection
{{-- Legacy duplicate page removed from rendering.
    <div class="container">
        <h1 class="display-4">Get in Touch</h1>
        <p class="lead">We'd love to hear from you. Whether it's feedback, questions, or just a hello!</p>
    </div>
</section>

<!-- Contact Form Section -->
<section id="contact-form" class="py-5">
    <div class="container">
        <h2 class="text-center">Contact Us</h2>
        <p class="text-center mb-4">Fill out the form below and we’ll get back to you as soon as possible.</p>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <form method="POST" action="/contact/send">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Your Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Your Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="subject" class="form-label">Subject</label>
                        <input type="text" class="form-control" id="subject" name="subject" required>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Message</label>
                        <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-dark w-100">Send Message</button>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Contact Information Section -->
<section id="contact-info" class="py-5 bg-light">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-4">
                <i class="fas fa-map-marker-alt fa-2x mb-3"></i>
                <h5>Visit Us</h5>
                <p>123 Coffee St, Bean City, CA 98765</p>
            </div>
            <div class="col-md-4">
                <i class="fas fa-phone fa-2x mb-3"></i>
                <h5>Call Us</h5>
                <p>+1 (123) 456-7890</p>
            </div>
            <div class="col-md-4">
                <i class="fas fa-envelope fa-2x mb-3"></i>
                <h5>Email Us</h5>
                <p>support@coffeeshop.com</p>
            </div>
        </div>
    </div>
</section>

<!-- Map Section -->
<section id="map" class="py-5">
    <div class="container">
        <h2 class="text-center mb-4">Find Us</h2>
        <div class="ratio ratio-16x9">
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3151.8354345095144!2d144.9537353153168!3d-37.81627937975162!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6ad642af0f11fd81%3A0xf577d3b396f44c0!2sCoffee%20Shop!5e0!3m2!1sen!2sus!4v1633040922177!5m2!1sen!2sus" 
                allowfullscreen="" 
                loading="lazy" 
                style="border:0;">
            </iframe>
        </div>
    </div>
</section>

<!-- Call to Action Section -->
<section class="cta-section text-white py-5" style="background: #343a40;">
    <div class="container text-center">
        <h2>Let’s Stay Connected</h2>
        <p>Follow us on social media for the latest updates and promotions!</p>
        <div class="d-flex justify-content-center">
            <a href="#" class="btn btn-light mx-2"><i class="fab fa-facebook-f"></i></a>
            <a href="#" class="btn btn-light mx-2"><i class="fab fa-twitter"></i></a>
            <a href="#" class="btn btn-light mx-2"><i class="fab fa-instagram"></i></a>
            <a href="#" class="btn btn-light mx-2"><i class="fab fa-linkedin-in"></i></a>
        </div>
    </div>
</section>
@endsection
--}}
