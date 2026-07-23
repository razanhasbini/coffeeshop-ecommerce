@extends('layout')

@section('title', 'Home — CoffeeShop')

@section('styles')
<style>
    /* Hero */
    .hero {
        position: relative;
        min-height: 100vh;
        overflow: hidden;
        background: url('/images/coffee.jpg') center center/cover no-repeat;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
    }
    .hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(160deg, rgba(28,15,7,.78) 0%, rgba(28,15,7,.45) 100%);
    }
    .hero-content { position: relative; z-index: 2; max-width: 680px; padding: 0 1.5rem; }
    .hero-eyebrow {
        display: inline-block;
        background: rgba(200,150,62,.18);
        border: 1px solid rgba(200,150,62,.4);
        color: var(--coffee-light);
        font-size: .78rem;
        font-weight: 600;
        letter-spacing: 3px;
        text-transform: uppercase;
        padding: .35rem 1.1rem;
        border-radius: 50px;
        margin-bottom: 1.4rem;
    }
    .hero h1 {
        font-size: clamp(2.8rem, 7vw, 5rem);
        font-weight: 700;
        color: #fff;
        line-height: 1.1;
        margin-bottom: 1.2rem;
    }
    .hero h1 em { color: var(--coffee-accent); font-style: normal; }
    .hero p {
        color: rgba(255,255,255,.8);
        font-size: 1.1rem;
        line-height: 1.7;
        margin-bottom: 2rem;
    }
    .hero-scroll {
        position: absolute;
        bottom: 2.5rem;
        left: 50%;
        transform: translateX(-50%);
        color: rgba(255,255,255,.5);
        font-size: .75rem;
        letter-spacing: 2px;
        text-transform: uppercase;
        animation: bounce 2s infinite;
        z-index: 2;
    }
    @keyframes bounce { 0%,100%{transform:translateX(-50%) translateY(0)} 50%{transform:translateX(-50%) translateY(6px)} }
    @media (prefers-reduced-motion: reduce) {
        .hero-scroll { animation: none; }
    }

    /* Stats bar */
    .stats-bar {
        background: var(--coffee-dark);
        padding: 1.4rem 0;
    }
    .stat-item { text-align: center; }
    .stat-number {
        font-family: 'Playfair Display', serif;
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--coffee-accent);
    }
    .stat-label { font-size: .78rem; color: rgba(255,255,255,.55); letter-spacing: 1.5px; text-transform: uppercase; }

    /* Cards */
    .product-card-home {
        background: #fff;
        border-radius: var(--radius);
        overflow: hidden;
        box-shadow: var(--shadow);
        transition: var(--transition);
        height: 100%;
    }
    .product-card-home:hover { transform: translateY(-6px); box-shadow: var(--shadow-hover); }
    .product-card-home img { width: 100%; height: 220px; object-fit: cover; }
    .product-card-home .card-body { padding: 1.4rem; }
    .product-card-home .price { font-size: 1.1rem; font-weight: 700; color: var(--coffee-accent); }
    .featured-marquee {
        width: 100%; overflow: hidden; padding: .85rem 0;
        background: var(--ticker-bg); color: var(--ticker-color);
        border-top: 1px solid rgba(255,255,255,.08);
        border-bottom: 1px solid rgba(255,255,255,.08);
    }
    .featured-marquee-track {
        display: flex; width: max-content; will-change: transform;
        animation: featured-marquee-scroll var(--ticker-speed) linear infinite;
    }
    .featured-marquee-group { display:flex; align-items:center; flex-shrink:0; }
    .featured-marquee-item {
        display:flex; align-items:center; gap:1.5rem; padding:0 1.5rem;
        white-space:nowrap; font-size:.76rem; font-weight:700;
        letter-spacing:2.6px; text-transform:uppercase;
    }
    .featured-marquee-item::after { content:'✦'; font-size:.7rem; opacity:.7; }
    @keyframes featured-marquee-scroll { to { transform:translateX(-50%); } }
    .featured-heading { max-width:620px; margin-bottom:2rem; }
    .featured-heading .section-title { font-size:clamp(1.8rem,3.5vw,2.65rem); margin-bottom:.55rem; }
    .featured-heading p { color:var(--text-muted); font-size:.9rem; line-height:1.65; margin:0; }
    @media (prefers-reduced-motion: reduce) {
        .featured-marquee-track { animation:none; }
        .featured-marquee-group:last-child { display:none; }
    }

    /* Feature icons */
    .feature-icon {
        width: 56px;
        height: 56px;
        background: rgba(200,150,62,.12);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        color: var(--coffee-accent);
        margin-bottom: 1rem;
    }

    /* Testimonials */
    .testimonial-card {
        background: #fff;
        border-radius: var(--radius);
        padding: 2rem;
        box-shadow: var(--shadow);
        position: relative;
    }
    .testimonial-card::before {
        content: '"';
        font-family: 'Playfair Display', serif;
        font-size: 4rem;
        color: var(--coffee-accent);
        position: absolute;
        top: .5rem;
        left: 1.5rem;
        line-height: 1;
        opacity: .4;
    }
    .testimonial-card p { font-size: .95rem; line-height: 1.75; color: #555; padding-top: 1.5rem; }
    .testimonial-card .author { font-weight: 600; font-size: .88rem; color: var(--coffee-dark); }
    .testimonial-card .role { font-size: .8rem; color: var(--text-muted); }

    /* CTA band */
    .cta-band {
        background: linear-gradient(135deg, var(--coffee-mid) 0%, var(--coffee-dark) 100%);
        padding: 5rem 0;
        position: relative;
        overflow: hidden;
    }
    .cta-band::before {
        content: '☕';
        position: absolute;
        font-size: 18rem;
        opacity: .04;
        right: -2rem;
        top: 50%;
        transform: translateY(-50%);
    }
    @media (max-width: 767.98px) {
        .hero { min-height:calc(100svh - 58px); padding:4.5rem 0 5.5rem; background-position:58% center; }
        .hero-content { padding:0 1rem; }
        .hero h1 { font-size:clamp(2.35rem,13vw,3.35rem); }
        .hero p { font-size:.97rem; line-height:1.6; }
        .hero-eyebrow { max-width:100%; letter-spacing:2px; }
        .hero-scroll { bottom:1.5rem; }
        .stats-bar { padding:1.25rem 0; }
        .stat-item { padding:.65rem .25rem; }
        .stat-number { font-size:1.5rem; }
        .stat-label { font-size:.66rem; letter-spacing:.8px; }
        .featured-heading { margin-bottom:1.4rem; }
        .featured-marquee { margin-bottom:2rem !important; }
        .featured-marquee-item { gap:1rem; padding:0 1rem; letter-spacing:1.7px; }
        .product-card-home img { height:210px; }
        .product-card-home .card-body, .testimonial-card { padding:1.2rem; }
        .feature-icon { margin-inline:auto; }
        .cta-band { padding:3.5rem 0; text-align:center; }
        .cta-band p { margin-inline:auto; }
        section:not(.hero):not(.cta-band) { scroll-margin-top:65px; }
        section[style*="padding: 5rem"] { padding:3.5rem 0 !important; }
    }
</style>
@endsection

@section('content')

{{-- ── HERO ── --}}
<section class="hero">
    <div class="hero-content">
        <span class="hero-eyebrow">Freshly brewed daily</span>
        <h1>Coffee That <em>Warms</em> Your Soul</h1>
        <p>Ethically sourced, expertly roasted, and brewed with love. Your perfect cup is waiting.</p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="/products" class="btn btn-coffee btn-lg">Explore Menu</a>
            <a href="/about" class="btn btn-outline-light btn-lg" style="border-radius:50px;padding:.65rem 1.8rem;">Our Story</a>
        </div>
    </div>
    <div class="hero-scroll"><i class="bi bi-chevron-down d-block mb-1"></i>Scroll</div>
</section>

{{-- ── STATS ── --}}
<div class="stats-bar">
    <div class="container">
        <div class="row g-3 justify-content-center">
            <div class="col-6 col-md-3 stat-item">
                <div class="stat-number">15+</div>
                <div class="stat-label">Years of craft</div>
            </div>
            <div class="col-6 col-md-3 stat-item">
                <div class="stat-number">40+</div>
                <div class="stat-label">Menu items</div>
            </div>
            <div class="col-6 col-md-3 stat-item">
                <div class="stat-number">10k+</div>
                <div class="stat-label">Happy customers</div>
            </div>
            <div class="col-6 col-md-3 stat-item">
                <div class="stat-number">100%</div>
                <div class="stat-label">Ethically sourced</div>
            </div>
        </div>
    </div>
</div>

{{-- ── ADMIN-CURATED FEATURED PRODUCTS ── --}}
<section class="py-6" style="padding: 3.25rem 0; background: {{ $featuredSection->background_color ?? '#f5eee5' }};">
    <div class="featured-marquee" style="--ticker-bg:{{ $featuredSection->ticker_background_color ?? '#1c0f07' }};--ticker-color:{{ $featuredSection->ticker_text_color ?? '#f2cb83' }};--ticker-speed:{{ $featuredSection->ticker_speed ?? 22 }}s; margin-top:-3.25rem; margin-bottom:2.75rem;" aria-label="{{ $featuredSection->ticker_text ?? 'Freshly roasted, thoughtfully sourced, made with love' }}">
        <div class="featured-marquee-track" aria-hidden="true">
            @for($group = 0; $group < 2; $group++)
            <div class="featured-marquee-group">
                @for($repeat = 0; $repeat < 4; $repeat++)
                    <span class="featured-marquee-item">{{ $featuredSection->ticker_text ?? 'Freshly roasted • Thoughtfully sourced • Made with love' }}</span>
                @endfor
            </div>
            @endfor
        </div>
    </div>
    <div class="container">
        <div class="featured-heading text-start">
            <span class="section-label">Customer favorites</span>
            <h2 class="section-title" style="color:{{ $featuredSection->title_color ?? '#1c0f07' }};">{{ $featuredSection->title ?? 'Featured Favorites' }}</h2>
            <p>{{ $featuredSection->description ?? 'A small selection of drinks we think you’ll love.' }}</p>
        </div>
        <div class="row g-4">
            @foreach($featuredProducts as $product)
            <div class="col-md-4">
                <div class="product-card-home">
                    <img src="{{ $product->image_url ?: '/images/coffee.jpg' }}" alt="{{ $product->name }}" onerror="this.src='/images/coffee.jpg'">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="mb-0">{{ $product->name }}</h5>
                            <span class="price">${{ number_format($product->price, 2) }}</span>
                        </div>
                        <p class="text-muted mb-3" style="font-size:.88rem;">{{ $product->description ?: 'Carefully crafted and ready to become your new favorite.' }}</p>
                        <a href="/products" class="btn btn-coffee w-100">Order Now</a>
                    </div>
                </div>
            </div>
            @endforeach
            @for($slot = $featuredProducts->count(); $slot < 3; $slot++)
            <div class="col-md-4">
                <div class="product-card-home" style="border:2px dashed rgba(122,102,82,.22)!important;background:rgba(255,255,255,.55);">
                    <div style="height:220px;display:grid;place-items:center;background:rgba(255,255,255,.35);color:var(--text-muted);"><i class="bi bi-image" style="font-size:2.5rem;opacity:.35;"></i></div>
                    <div class="card-body text-center">
                        <h5>Featured spot available</h5>
                        <p class="text-muted mb-0" style="font-size:.88rem;">Choose a product from the admin dashboard.</p>
                    </div>
                </div>
            </div>
            @endfor
        </div>
        <div class="text-center mt-4">
            <a href="/products" class="btn btn-outline-coffee">View Full Menu</a>
        </div>
    </div>
</section>

{{-- ── WHY US ── --}}
<section style="padding: 5rem 0; background: #fff;">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-5">
                <img src="/images/about.jpg" alt="About" class="img-fluid rounded-3 w-100" style="max-height:420px;object-fit:cover;" onerror="this.style.background='linear-gradient(135deg,#3b1f0e,#c8963e)';this.style.height='420px';this.removeAttribute('src')">
            </div>
            <div class="col-lg-7">
                <span class="section-label">Why choose us</span>
                <h2 class="section-title mb-4">More Than Just Coffee</h2>
                <div class="row g-4">
                    <div class="col-sm-6">
                        <div class="feature-icon"><i class="bi bi-patch-check"></i></div>
                        <h6 style="font-weight:600;">Premium Quality</h6>
                        <p style="font-size:.88rem;color:var(--text-muted);">Beans sourced from top farms, roasted in-house weekly.</p>
                    </div>
                    <div class="col-sm-6">
                        <div class="feature-icon"><i class="bi bi-heart"></i></div>
                        <h6 style="font-weight:600;">Made with Love</h6>
                        <p style="font-size:.88rem;color:var(--text-muted);">Every cup crafted by our passionate baristas.</p>
                    </div>
                    <div class="col-sm-6">
                        <div class="feature-icon"><i class="bi bi-tree"></i></div>
                        <h6 style="font-weight:600;">Ethically Sourced</h6>
                        <p style="font-size:.88rem;color:var(--text-muted);">We support farmers and sustainable practices.</p>
                    </div>
                    <div class="col-sm-6">
                        <div class="feature-icon"><i class="bi bi-lightning"></i></div>
                        <h6 style="font-weight:600;">Fast & Fresh</h6>
                        <p style="font-size:.88rem;color:var(--text-muted);">Order online, pick up in minutes.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── TESTIMONIALS ── --}}
<section style="padding: 5rem 0; background: var(--warm-gray);">
    <div class="container">
        <div class="text-center mb-5">
            <span class="section-label">What people say</span>
            <h2 class="section-title">Customer Love</h2>
            <div class="section-divider mx-auto"></div>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="testimonial-card">
                    <p>"Amazing coffee and great service! This is my daily ritual — I can't start my morning without it."</p>
                    <div class="d-flex align-items-center gap-2 mt-3">
                        <div style="width:36px;height:36px;border-radius:50%;background:var(--coffee-accent);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:600;font-size:.85rem;">J</div>
                        <div><div class="author">John Doe</div><div class="role">Regular Customer</div></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="testimonial-card">
                    <p>"The best coffee shop in town, hands down. The atmosphere is cozy and the staff are incredibly friendly."</p>
                    <div class="d-flex align-items-center gap-2 mt-3">
                        <div style="width:36px;height:36px;border-radius:50%;background:var(--coffee-mid);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:600;font-size:.85rem;">J</div>
                        <div><div class="author">Jane Smith</div><div class="role">Food Blogger</div></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="testimonial-card">
                    <p>"A must-visit for all coffee lovers. Their latte art is Instagram-worthy and the taste is unmatched."</p>
                    <div class="d-flex align-items-center gap-2 mt-3">
                        <div style="width:36px;height:36px;border-radius:50%;background:#7a6652;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:600;font-size:.85rem;">E</div>
                        <div><div class="author">Emily Johnson</div><div class="role">Coffee Enthusiast</div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── CTA BAND ── --}}
<section class="cta-band">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-8 text-white">
                <span class="section-label" style="color:var(--coffee-light);">Members get more</span>
                <h2 class="section-title" style="color:#fff;">Join Our Loyalty Program</h2>
                <p style="color:rgba(255,255,255,.7);font-size:1rem;max-width:500px;">Earn points on every purchase, unlock exclusive discounts, and get early access to new menu items.</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="/login" class="btn btn-coffee btn-lg">Get Started Free</a>
            </div>
        </div>
    </div>
</section>

@endsection
