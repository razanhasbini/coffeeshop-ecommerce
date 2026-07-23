@extends('layout')

@section('title', 'About Us — CoffeeShop')

@section('styles')
<style>
    .about-hero {
        background: url('/images/about-hero.jpg') center/cover no-repeat;
        min-height: 360px;
        display: flex; align-items: center;
        position: relative;
    }
    .about-hero::before { content:''; position:absolute; inset:0; background:linear-gradient(135deg,rgba(28,15,7,.80) 0%,rgba(28,15,7,.45) 100%); }
    .about-hero .content { position:relative; z-index:1; }

    .team-card {
        background: #fff;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        padding: 2rem 1.5rem;
        text-align: center;
        transition: var(--transition);
    }
    .team-card:hover { transform: translateY(-5px); box-shadow: var(--shadow-hover); }
    .team-avatar {
        width: 100px; height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid var(--coffee-accent);
        margin: 0 auto 1rem;
        display: block;
        background: linear-gradient(135deg, var(--coffee-mid), var(--coffee-accent));
    }
    .team-initials {
        width: 100px; height: 100px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--coffee-mid), var(--coffee-accent));
        display: flex; align-items: center; justify-content: center;
        font-size: 2rem; font-weight: 700; color: #fff;
        margin: 0 auto 1rem;
        font-family: 'Playfair Display', serif;
    }
    .value-card {
        background: #fff;
        border-radius: var(--radius);
        padding: 1.8rem;
        box-shadow: var(--shadow);
        border-left: 4px solid var(--coffee-accent);
    }
</style>
@endsection

@section('content')

{{-- Hero --}}
<section class="about-hero">
    <div class="container content text-white">
        <span style="font-size:.75rem;font-weight:600;letter-spacing:3px;text-transform:uppercase;color:var(--coffee-light);">Our journey</span>
        <h1 style="font-size:clamp(2rem,5vw,3.5rem);margin-bottom:.5rem;">About CoffeeShop</h1>
        <p style="color:rgba(255,255,255,.75);font-size:1rem;max-width:500px;">Discover the passion, people, and principles behind every cup.</p>
    </div>
</section>

{{-- Our Story --}}
<section style="padding:5rem 0; background:#fff;">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <img src="/images/our-story.jpg" alt="Our Story" class="img-fluid rounded-3"
                     style="max-height:420px;object-fit:cover;width:100%;"
                     onerror="this.style.background='linear-gradient(135deg,#3b1f0e,#c8963e)';this.style.height='420px';this.removeAttribute('src')">
            </div>
            <div class="col-lg-6">
                <span class="section-label">Since 2010</span>
                <h2 class="section-title mb-4">Our Story</h2>
                <p style="color:var(--text-muted);line-height:1.85;">
                    CoffeeShop began with a simple idea: to bring people together over a perfect cup of coffee.
                    From a small neighborhood café, we've grown into a beloved destination for coffee lovers —
                    all while staying true to our roots of quality, community, and care.
                </p>
                <p style="color:var(--text-muted);line-height:1.85;">
                    We believe every cup tells a story, from the hands that picked the beans to the moments
                    shared by our customers. Our dedication to excellence has made us a leader in the craft coffee space.
                </p>
                <div class="d-flex gap-4 mt-4">
                    <div><div class="stat-number" style="font-family:'Playfair Display',serif;font-size:2rem;font-weight:700;color:var(--coffee-accent);">15+</div><div style="font-size:.8rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:1px;">Years</div></div>
                    <div><div class="stat-number" style="font-family:'Playfair Display',serif;font-size:2rem;font-weight:700;color:var(--coffee-accent);">10k+</div><div style="font-size:.8rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:1px;">Customers</div></div>
                    <div><div class="stat-number" style="font-family:'Playfair Display',serif;font-size:2rem;font-weight:700;color:var(--coffee-accent);">40+</div><div style="font-size:.8rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:1px;">Menu Items</div></div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Mission & Vision --}}
<section style="padding:5rem 0; background:var(--warm-gray);">
    <div class="container">
        <div class="text-center mb-5">
            <span class="section-label">What drives us</span>
            <h2 class="section-title">Our Values</h2>
            <div class="section-divider mx-auto"></div>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="value-card">
                    <div style="font-size:1.5rem;margin-bottom:.8rem;">🌱</div>
                    <h5>Sustainability</h5>
                    <p style="font-size:.88rem;color:var(--text-muted);line-height:1.75;">Ethically sourced beans, compostable packaging, and carbon-conscious operations.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="value-card">
                    <div style="font-size:1.5rem;margin-bottom:.8rem;">🤝</div>
                    <h5>Community</h5>
                    <p style="font-size:.88rem;color:var(--text-muted);line-height:1.75;">A welcoming space where everyone feels at home, from first-time visitors to daily regulars.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="value-card">
                    <div style="font-size:1.5rem;margin-bottom:.8rem;">✨</div>
                    <h5>Excellence</h5>
                    <p style="font-size:.88rem;color:var(--text-muted);line-height:1.75;">Uncompromising quality in every bean, every brew, and every customer interaction.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Team --}}
<section style="padding:5rem 0; background:#fff;">
    <div class="container">
        <div class="text-center mb-5">
            <span class="section-label">The people behind the cup</span>
            <h2 class="section-title">Meet Our Team</h2>
            <div class="section-divider mx-auto"></div>
        </div>
        <div class="row g-4 justify-content-center">
            <div class="col-sm-6 col-md-4">
                <div class="team-card">
                    <img src="/images/jad.jpg" alt="Jad" class="team-avatar"
                         onerror="this.outerHTML='<div class=\'team-initials\'>J</div>'">
                    <h5>Jad</h5>
                    <p style="font-size:.85rem;color:var(--coffee-accent);font-weight:600;margin-bottom:.5rem;">Founder & CEO</p>
                    <p style="font-size:.83rem;color:var(--text-muted);">The visionary who turned a love for coffee into a community institution.</p>
                </div>
            </div>
            <div class="col-sm-6 col-md-4">
                <div class="team-card">
                    <img src="/images/razan.jpg" alt="Razan" class="team-avatar"
                         onerror="this.outerHTML='<div class=\'team-initials\'>R</div>'">
                    <h5>Razan</h5>
                    <p style="font-size:.85rem;color:var(--coffee-accent);font-weight:600;margin-bottom:.5rem;">Head Barista</p>
                    <p style="font-size:.83rem;color:var(--text-muted);">Crafting exceptional espresso and training the next generation of baristas.</p>
                </div>
            </div>
            <div class="col-sm-6 col-md-4">
                <div class="team-card">
                    <img src="/images/mhmd.jpg" alt="Mohammad" class="team-avatar"
                         onerror="this.outerHTML='<div class=\'team-initials\'>M</div>'">
                    <h5>Mohammad</h5>
                    <p style="font-size:.85rem;color:var(--coffee-accent);font-weight:600;margin-bottom:.5rem;">Operations Manager</p>
                    <p style="font-size:.83rem;color:var(--text-muted);">Keeping everything running smoothly so every experience is flawless.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- CTA --}}
<section style="background:linear-gradient(135deg,var(--coffee-mid),var(--coffee-dark));padding:4.5rem 0;">
    <div class="container text-center text-white">
        <h2 style="color:#fff;margin-bottom:.8rem;">Ready to experience the difference?</h2>
        <p style="color:rgba(255,255,255,.7);margin-bottom:1.8rem;">Join thousands of coffee lovers who start every day with us.</p>
        <a href="{{ route('login') }}" class="btn btn-coffee btn-lg me-2">Join Now</a>
        <a href="/products" class="btn btn-outline-light btn-lg" style="border-radius:50px;">View Menu</a>
    </div>
</section>

@endsection
{{-- Legacy duplicate page removed from rendering.
    <div class="container">
        <h1 class="display-4">About Coffee Shop</h1>
        <p class="lead">Discover our journey and passion for coffee.</p>
    </div>
</section>

<!-- Our Story Section -->
<section id="our-story" class="py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h2>Our Story</h2>
                <p>
                    Coffee Shop began with a simple idea: to bring people together over a perfect cup of coffee. 
                    Founded in 2010, we’ve grown from a small neighborhood café into a beloved destination for coffee lovers. 
                    Our mission is to serve the highest quality coffee, sourced ethically from the best farms around the world.
                </p>
                <p>
                    We believe every cup tells a story, from the hands that picked the beans to the moments shared by our customers.
                    Our dedication to excellence and innovation has made us a leader in the coffee industry.
                </p>
            </div>
            <div class="col-md-6">
                <img src="/images/our-story.jpg" alt="Our Story" class="img-fluid rounded">
            </div>
        </div>
    </div>
</section>

<!-- Our Mission and Vision Section -->
<section id="mission-vision" class="py-5 bg-light">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-6">
                <h3>Our Mission</h3>
                <p>
                    To deliver an exceptional coffee experience while fostering a sense of community and sustainability. 
                    We aim to create a welcoming space where everyone feels at home.
                </p>
            </div>
            <div class="col-md-6">
                <h3>Our Vision</h3>
                <p>
                    To be recognized globally as a leader in quality coffee, sustainability, and innovation, while maintaining our commitment to ethical sourcing and community support.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Meet the Team Section -->
<section id="team" class="py-5">
    <div class="container">
        <h2 class="text-center">Meet Our Team</h2>
        <p class="text-center mb-5">The passionate people behind our success.</p>
        <div class="row">
            <div class="col-md-4 text-center">
                <img src="/images/jad.jpg" alt="Team Member" class="rounded-circle mb-0"  height="150px">
                <h5>Jad</h5>
                <p>Founder & CEO</p>
            </div>
            <div class="col-md-4 text-center">
                <img src="/images/razan.jpg" alt="Team Member" class="rounded-circle mb-3" width="150">
                <h5>Razan</h5>
                <p>Head Barista</p>
            </div>
            <div class="col-md-4 text-center">
                <img src="/images/mhmd.jpg" alt="Team Member" class="rounded-circle mb-3" width="150">
                <h5>Mohammad</h5>
                <p>Operations Manager</p>
            </div>
        </div>
    </div>
</section>

<!-- Sustainability Section -->
<section id="sustainability" class="py-5 bg-light">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h2>Our Commitment to Sustainability</h2>
                <p>
                    At Coffee Shop, we take sustainability seriously. From sourcing our beans from environmentally friendly farms 
                    to using compostable cups and packaging, we are committed to reducing our carbon footprint.
                </p>
                <p>
                    We believe in giving back to the planet and supporting the communities that make our coffee possible.
                    Join us in making a positive impact, one cup at a time.
                </p>
            </div>
            <div class="col-md-6">
                <img src="/images/sustainability.jpg" alt="Sustainability" class="img-fluid rounded">
            </div>
        </div>
    </div>
</section>

<!-- Call to Action Section -->
<section id="cta" class="text-white py-5" style="background: #343a40;">
    <div class="container text-center">
        <h2>Join Our Coffee Lovers Club</h2>
        <p>Get exclusive updates, discounts, and rewards by signing up for our newsletter.</p>
        <a href="{{ route('login') }}" class="btn btn-primary btn-lg">Sign Up Now</a>
    </div>
</section>

@endsection
--}}
