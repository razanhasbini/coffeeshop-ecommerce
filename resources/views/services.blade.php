@extends('layout')

@section('title', 'Our Services — CoffeeShop')

@section('styles')
<style>
    .services-hero {
        min-height: 520px; display:flex; align-items:center; position:relative; overflow:hidden;
        background:url('/images/services-hero.jpg') center/cover no-repeat;
    }
    .services-hero::before { content:''; position:absolute; inset:0; background:linear-gradient(105deg,rgba(20,8,3,.9),rgba(28,15,7,.6) 55%,rgba(28,15,7,.28)); }
    .services-hero::after { content:''; position:absolute; width:520px; height:520px; right:-180px; bottom:-310px; border:1px solid rgba(242,203,131,.25); border-radius:50%; box-shadow:0 0 0 55px rgba(242,203,131,.035),0 0 0 110px rgba(242,203,131,.025); }
    .services-hero .content { position:relative; z-index:1; max-width:710px; }
    .services-hero h1 { color:#fff; font-size:clamp(3rem,7vw,5.7rem); line-height:.98; margin:.8rem 0 1.2rem; }
    .services-hero p { color:rgba(255,255,255,.72); font-size:1.08rem; line-height:1.8; max-width:570px; }
    .hero-kicker { color:var(--coffee-light); font-size:.75rem; letter-spacing:3.5px; text-transform:uppercase; font-weight:700; }
    .service-card { height:100%; padding:2rem; border-radius:var(--radius); background:rgba(255,255,255,.88); box-shadow:var(--shadow); transition:var(--transition); position:relative; overflow:hidden; }
    .service-card:hover { transform:translateY(-8px); box-shadow:var(--shadow-hover); }
    .service-card::after { content:''; position:absolute; width:95px; height:95px; right:-48px; top:-48px; border-radius:50%; background:rgba(201,138,56,.08); transition:var(--transition); }
    .service-card:hover::after { transform:scale(1.7); }
    .service-icon { width:58px; height:58px; display:grid; place-items:center; border-radius:17px; margin-bottom:1.4rem; color:#fff; font-size:1.35rem; background:linear-gradient(145deg,var(--coffee-accent),#8d4e19); box-shadow:0 10px 24px rgba(201,138,56,.25); }
    .service-card h4 { font-size:1.32rem; margin-bottom:.7rem; }
    .service-card p { color:var(--text-muted); font-size:.9rem; line-height:1.75; margin:0; }
    .experience-panel { border-radius:28px; overflow:hidden; background:var(--coffee-dark); box-shadow:var(--shadow-hover); }
    .experience-panel img { width:100%; height:100%; min-height:470px; object-fit:cover; }
    .experience-copy { padding:clamp(2rem,5vw,4.5rem); color:#fff; }
    .experience-copy h2 { color:#fff; font-size:clamp(2.2rem,4vw,3.5rem); }
    .experience-copy p { color:rgba(255,255,255,.66); line-height:1.8; }
    .quality-item { display:flex; gap:1rem; padding:1rem 0; border-bottom:1px solid rgba(255,255,255,.09); }
    .quality-item:last-child { border:0; }
    .quality-number { color:var(--coffee-light); font-family:'Playfair Display',serif; font-size:1.25rem; }
    .services-cta { position:relative; overflow:hidden; padding:6rem 0; background:linear-gradient(120deg,#3b1f0e,#1c0f07); }
    .services-cta::before { content:'COFFEE'; position:absolute; inset:auto 0 -3rem; text-align:center; font:700 clamp(7rem,18vw,17rem)/1 'Playfair Display',serif; color:rgba(255,255,255,.025); letter-spacing:.08em; }
</style>
@endsection

@section('content')
<section class="services-hero">
    <div class="container content">
        <span class="hero-kicker">Beyond the perfect cup</span>
        <h1>Crafted moments,<br><em style="color:var(--coffee-light);">made for you.</em></h1>
        <p>From precision brewing to private gatherings, every service is designed around exceptional coffee, thoughtful hospitality, and memorable detail.</p>
        <div class="d-flex gap-3 flex-wrap mt-4">
            <a href="#services-grid" class="btn btn-coffee btn-lg">Explore Services</a>
            <a href="/contact" class="btn btn-outline-light btn-lg" style="border-radius:50px;padding:.7rem 1.7rem;">Plan an Event</a>
        </div>
    </div>
</section>

<section id="services-grid" style="padding:6rem 0;background:var(--warm-gray);">
    <div class="container">
        <div class="row align-items-end mb-5 g-3">
            <div class="col-lg-7">
                <span class="section-label">What we create</span>
                <h2 class="section-title mb-0">An experience for every occasion</h2>
            </div>
            <div class="col-lg-5"><p class="mb-0" style="color:var(--text-muted);line-height:1.75;">Thoughtfully designed services, delivered by people who care about every detail.</p></div>
        </div>
        <div class="row g-4">
            @foreach ([
                ['bi-cup-hot-fill','Specialty Coffee','Single-origin beans, dialed-in daily and handcrafted by experienced baristas.'],
                ['bi-basket2-fill','Fresh Pairings','Small-batch pastries and seasonal bites selected to complement every brew.'],
                ['bi-laptop','Work & Create','Fast Wi-Fi, comfortable seating, quiet corners, and coffee that keeps ideas moving.'],
                ['bi-stars','Private Events','A warm, character-rich setting for launches, celebrations, and intimate gatherings.'],
                ['bi-building','Corporate Catering','Complete coffee bars and curated refreshments brought directly to your team.'],
                ['bi-bicycle','Pickup & Delivery','Your favorites prepared fresh and ready exactly when and where you need them.']
            ] as [$icon,$title,$copy])
            <div class="col-md-6 col-lg-4">
                <article class="service-card">
                    <div class="service-icon"><i class="bi {{ $icon }}"></i></div>
                    <h4>{{ $title }}</h4>
                    <p>{{ $copy }}</p>
                </article>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section style="padding:6rem 0;background:var(--cream);">
    <div class="container">
        <div class="experience-panel">
            <div class="row g-0 align-items-stretch">
                <div class="col-lg-6"><img src="/images/why-choose-us.jpg" alt="Barista preparing specialty coffee"></div>
                <div class="col-lg-6 d-flex align-items-center">
                    <div class="experience-copy">
                        <span class="section-label" style="color:var(--coffee-light);">The CoffeeShop standard</span>
                        <h2>Hospitality you can feel.</h2>
                        <p>Great service should feel effortless. Behind it is obsessive preparation, careful sourcing, and a team empowered to make every visit special.</p>
                        <div class="mt-4">
                            <div class="quality-item"><span class="quality-number">01</span><div><strong>Quality without compromise</strong><div style="color:rgba(255,255,255,.55);font-size:.85rem;margin-top:.2rem;">Premium ingredients and precise preparation.</div></div></div>
                            <div class="quality-item"><span class="quality-number">02</span><div><strong>Genuinely personal service</strong><div style="color:rgba(255,255,255,.55);font-size:.85rem;margin-top:.2rem;">Warm, attentive, and never scripted.</div></div></div>
                            <div class="quality-item"><span class="quality-number">03</span><div><strong>Conscious by design</strong><div style="color:rgba(255,255,255,.55);font-size:.85rem;margin-top:.2rem;">Ethical sourcing and lower-waste operations.</div></div></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="services-cta text-center text-white">
    <div class="container position-relative" style="z-index:1;">
        <span class="section-label" style="color:var(--coffee-light);">Your table is waiting</span>
        <h2 class="section-title mx-auto" style="color:#fff;max-width:680px;">Let’s make your next coffee moment exceptional.</h2>
        <p class="mx-auto mt-3 mb-4" style="max-width:550px;color:rgba(255,255,255,.64);">Visit us, order online, or talk to our team about a tailored coffee experience.</p>
        <a href="/products" class="btn btn-coffee btn-lg me-2">Browse the Menu</a>
        <a href="/contact" class="btn btn-outline-light btn-lg" style="border-radius:50px;">Contact Us</a>
    </div>
</section>
@endsection
