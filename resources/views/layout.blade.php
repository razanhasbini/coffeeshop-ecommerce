<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Coffee Shop')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,600&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --coffee-dark:   #1c0f07;
            --coffee-mid:    #3b1f0e;
            --coffee-accent: #c98a38;
            --coffee-light:  #f2cb83;
            --cream:         #fffaf4;
            --warm-gray:     #f5eee5;
            --text-dark:     #1c0f07;
            --text-muted:    #7a6652;
            --radius:        20px;
            --radius-sm:     12px;
            --shadow:        0 12px 38px rgba(40,20,8,.09), 0 2px 8px rgba(40,20,8,.04);
            --shadow-hover:  0 24px 58px rgba(40,20,8,.16), 0 5px 14px rgba(40,20,8,.07);
            --transition:    all .35s cubic-bezier(.2,.8,.2,1);
        }

        * { box-sizing: border-box; }
        html { overflow-x: clip; scroll-padding-top: 88px; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--cream);
            color: var(--text-dark);
            margin: 0;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
            background-image:
                radial-gradient(circle at 8% 12%, rgba(201,138,56,.08), transparent 28rem),
                radial-gradient(circle at 92% 45%, rgba(59,31,14,.055), transparent 32rem);
        }

        h1, h2, h3, h4, h5 { font-family: 'Playfair Display', serif; letter-spacing: -.025em; }
        p { text-wrap: pretty; }
        a { text-underline-offset: 3px; }
        ::selection { background: var(--coffee-accent); color: #fff; }
        :focus-visible { outline: 3px solid rgba(201,138,56,.55); outline-offset: 3px; }
        main { position: relative; isolation: isolate; min-width: 0; }
        img, svg, video, canvas { max-width: 100%; height: auto; }
        input, select, textarea, button { max-width: 100%; }
        td, th, p, a, span { overflow-wrap: anywhere; }

        /* ── NAVBAR ── */
        .navbar {
            background: rgba(28,15,7,.9) !important;
            backdrop-filter:blur(18px);
            -webkit-backdrop-filter:blur(18px);
            border-bottom:1px solid rgba(201,138,56,.16);
            padding:.85rem 0;
            transition: var(--transition);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .nav-shell {
            min-height:auto;
            padding-left:var(--bs-gutter-x,.75rem);
            padding-right:var(--bs-gutter-x,.75rem);
        }
        .navbar.scrolled {
            background:rgba(28,15,7,.97) !important;
            padding:.55rem 0;
            box-shadow:0 10px 30px rgba(12,5,2,.18);
        }
        .navbar.scrolled .nav-shell {
            background:transparent;
            box-shadow:none;
        }
        .navbar-brand {
            font-family: 'Playfair Display', serif;
            font-size: 1.35rem;
            font-weight: 700;
            color: var(--coffee-accent) !important;
            letter-spacing:-.2px;
        }
        .brand-mark {
            width: 38px; height: 38px; display: inline-grid; place-items: center;
            margin-right: .55rem; border-radius: 50%; font-size: 1rem;
            color: #fff; background: linear-gradient(145deg,var(--coffee-light),var(--coffee-accent));
            box-shadow: inset 0 1px 0 rgba(255,255,255,.4), 0 6px 15px rgba(201,138,56,.28);
        }
        .navbar-brand span { color:#fff; }
        .nav-link {
            color:rgba(255,255,255,.78) !important;
            font-weight: 600;
            font-size: .85rem;
            letter-spacing: .3px;
            padding: .4rem .85rem !important;
            border-radius:0;
            transition: var(--transition);
            position:relative;
        }
        .nav-link:not(.nav-cta)::after { content:''; position:absolute; left:.85rem; right:.85rem; bottom:0; height:2px; border-radius:2px; background:var(--coffee-accent); transform:scaleX(0); transition:transform .25s ease; }
        .nav-link:hover, .nav-link.active {
            color:#fff !important;
            background:transparent;
        }
        .nav-link:hover::after,.nav-link.active::after { transform:scaleX(1); }
        .nav-link.active { box-shadow:none; }
        .cart-link { position:relative; }
        .cart-count { position:absolute; top:-7px; right:-7px; min-width:19px; height:19px; padding:0 5px; display:grid; place-items:center; border-radius:50px; background:var(--coffee-accent); color:#fff; border:2px solid var(--coffee-dark); font-size:.65rem; font-weight:700; line-height:1; }
        .nav-link.nav-cta {
            background:var(--coffee-accent);
            color: #fff !important;
            padding: .52rem 1.15rem !important;
            border-radius: 50px;
        }
        .nav-link.nav-cta:hover {
            background:var(--coffee-light);
            color:var(--coffee-dark) !important;
            transform:translateY(-1px);
        }

        /* ── BUTTONS ── */
        .btn-coffee {
            background: linear-gradient(135deg, #d69a48, #b87525);
            color: #fff;
            border: none;
            border-radius: 50px;
            padding: .65rem 1.8rem;
            font-weight: 600;
            font-size: .95rem;
            letter-spacing: .3px;
            transition: var(--transition);
        }
        .btn-coffee:hover {
            background: #b07c28;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 10px 26px rgba(201,138,56,.35);
        }
        .btn-outline-coffee {
            background: transparent;
            color: var(--coffee-accent);
            border: 2px solid var(--coffee-accent);
            border-radius: 50px;
            padding: .6rem 1.7rem;
            font-weight: 600;
            font-size: .95rem;
            transition: var(--transition);
        }
        .btn-outline-coffee:hover {
            background: var(--coffee-accent);
            color: #fff;
            transform: translateY(-2px);
        }

        /* ── SECTIONS ── */
        .section-label {
            font-size: .78rem;
            font-weight: 600;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: var(--coffee-accent);
            display: block;
            margin-bottom: .4rem;
        }
        .section-title {
            font-size: clamp(2rem, 4vw, 3.15rem);
            font-weight: 700;
            color: var(--coffee-dark);
            line-height: 1.2;
        }
        .section-label::before { content:''; display:inline-block; width:22px; height:1px; background:currentColor; vertical-align:middle; margin-right:9px; }
        .section-divider {
            width: 48px;
            height: 3px;
            background: var(--coffee-accent);
            border-radius: 2px;
            margin: 1rem auto 0;
        }

        /* ── FOOTER ── */
        footer {
            background:
                radial-gradient(circle at 80% 20%, rgba(201,138,56,.12), transparent 25rem),
                linear-gradient(150deg, #251207, #120804);
            color: rgba(255,255,255,.7);
            padding: 4.5rem 0 1.5rem;
            position: relative;
        }
        footer .footer-brand {
            font-family: 'Playfair Display', serif;
            font-size: 1.4rem;
            color: var(--coffee-accent);
            font-weight: 700;
        }
        footer a { color: rgba(255,255,255,.6); text-decoration: none; transition: var(--transition); }
        footer a:hover { color: var(--coffee-accent); }
        .social-link {
            width: 38px; height: 38px; border-radius: 50%; display: grid; place-items: center;
            border: 1px solid rgba(255,255,255,.1); background: rgba(255,255,255,.04);
        }
        .social-link:hover { transform: translateY(-3px); background: rgba(201,138,56,.12); border-color: rgba(201,138,56,.35); }
        footer .footer-bottom {
            border-top: 1px solid rgba(255,255,255,.08);
            margin-top: 2rem;
            padding-top: 1.2rem;
            font-size: .82rem;
        }

        /* ── FORM CONTROLS ── */
        .form-control, .form-select {
            border: 1.5px solid #e0d4c6;
            border-radius: 12px;
            padding: .78rem 1rem;
            font-size: .92rem;
            background: #fff;
            transition: var(--transition);
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--coffee-accent);
            box-shadow: 0 0 0 3px rgba(200,150,62,.15);
        }
        .form-label { font-weight: 500; font-size: .88rem; color: var(--text-muted); margin-bottom: .35rem; }

        /* ── SCROLLBAR ── */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--warm-gray); }
        ::-webkit-scrollbar-thumb { background: var(--coffee-accent); border-radius: 3px; }

        /* Shared premium surfaces and page motion */
        .card, .auth-card, .contact-form-card, .info-card, .filter-bar,
        .product-card, .product-card-home, .testimonial-card, .value-card,
        .team-card, .cart-card, .summary-card, .stat-card, .chart-card {
            border: 1px solid rgba(80,43,20,.07) !important;
        }
        img { max-width: 100%; }
        .about-hero, .contact-hero, .products-hero, .hero-section {
            background-attachment: fixed !important;
            background-position: center !important;
        }
        .reveal-item { opacity: 0; transform: translateY(28px); transition: opacity .75s ease, transform .75s cubic-bezier(.2,.8,.2,1); }
        .reveal-item.is-visible { opacity: 1; transform: translateY(0); }
        @media (max-width: 991.98px) {
            .navbar-collapse { max-height:calc(100dvh - 78px); overflow-y:auto; margin-top:.65rem; padding:.75rem 0 .25rem; border-top:1px solid rgba(255,255,255,.09); background:transparent; }
            .navbar-nav { align-items:stretch !important; }
            .nav-link { min-height:44px; display:flex; align-items:center; padding:.7rem .85rem !important; }
            .nav-link.nav-cta { justify-content:center; margin:.35rem 0 .25rem; }
            .cart-count { top:2px; right:auto; left:55px; }
            .navbar-toggler { width:42px;height:42px;border-radius:50%;background:rgba(255,255,255,.08); }
            .navbar-toggler-icon { filter:none; }
            .about-hero, .contact-hero, .products-hero, .hero-section { background-attachment: scroll !important; }
        }
        @media (max-width: 767.98px) {
            :root { --radius:16px; --radius-sm:10px; }
            body { background-image:none; }
            .container { --bs-gutter-x:1.5rem; }
            .navbar { padding:.55rem 0; }
            .navbar-brand { font-size:1.16rem; }
            .brand-mark { width:34px; height:34px; }
            .section-title { font-size:clamp(1.75rem,9vw,2.35rem); }
            .section-label { letter-spacing:2px; font-size:.7rem; }
            .btn-coffee, .btn-outline-coffee { min-height:44px; padding:.65rem 1.25rem; }
            .form-control, .form-select { min-height:46px; font-size:16px; }
            textarea.form-control { min-height:96px; }
            footer { padding:3.2rem 0 1.25rem; text-align:center; }
            footer .social-link { margin-inline:auto; }
            footer .d-flex { justify-content:center; }
            footer .footer-bottom { display:flex; flex-wrap:wrap; justify-content:center; gap:.45rem .75rem; }
            footer .footer-bottom .mx-3 { display:none; }
            .reveal-item { transition-delay:0ms !important; }
        }
        @media (max-width: 359.98px) {
            .container { --bs-gutter-x:1rem; }
            .navbar-brand { font-size:1.03rem; }
            .brand-mark { margin-right:.4rem; }
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { scroll-behavior:auto !important; animation-duration:.01ms !important; animation-iteration-count:1 !important; transition-duration:.01ms !important; }
            .reveal-item { opacity:1; transform:none; }
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark" id="mainNav">
        <div class="container nav-shell">
            <a class="navbar-brand d-flex align-items-center" href="/" aria-label="CoffeeShop home"><span class="brand-mark"><i class="bi bi-cup-hot-fill"></i></span><span>Coffee</span>Shop</a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Open navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center gap-1">
                    <li class="nav-item"><a class="nav-link {{ request()->is('about') ? 'active' : '' }}" href="/about">About</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->is('products') ? 'active' : '' }}" href="/products">Menu</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->is('services') ? 'active' : '' }}" href="/services">Services</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->is('contact') ? 'active' : '' }}" href="/contact">Contact</a></li>
                    <li class="nav-item"><a class="nav-link cart-link {{ request()->is('cart') ? 'active' : '' }}" href="/cart"><i class="bi bi-bag me-1"></i>Cart<span class="cart-count {{ $navCartCount > 0 ? '' : 'd-none' }}" id="navCartCount">{{ $navCartCount }}</span></a></li>

                    @auth
                        @if(auth()->user()->canAccessAdmin())
                        <li class="nav-item"><a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" href="/dashboard"><i class="bi bi-grid me-1"></i>Admin</a></li>
                        @endif
                        <li class="nav-item"><a class="nav-link" href="/logout"><i class="bi bi-box-arrow-right me-1"></i>Logout</a></li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link nav-cta" href="/login">Sign In</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="footer-brand mb-2">☕ CoffeeShop</div>
                    <p style="font-size:.88rem;line-height:1.7;">Crafting exceptional coffee experiences since 2010. Every cup tells a story.</p>
                </div>
                <div class="col-md-2">
                    <h6 style="color:#fff;font-size:.85rem;font-weight:600;letter-spacing:1px;text-transform:uppercase;margin-bottom:1rem;">Explore</h6>
                    <ul class="list-unstyled" style="font-size:.88rem;">
                        <li class="mb-2"><a href="/about">About Us</a></li>
                        <li class="mb-2"><a href="/products">Our Menu</a></li>
                        <li class="mb-2"><a href="/services">Services</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h6 style="color:#fff;font-size:.85rem;font-weight:600;letter-spacing:1px;text-transform:uppercase;margin-bottom:1rem;">Visit Us</h6>
                    <p style="font-size:.88rem;line-height:1.8;">123 Coffee St, Bean City<br>Mon–Fri 7am–9pm<br>+1 (123) 456-7890</p>
                </div>
                <div class="col-md-3">
                    <h6 style="color:#fff;font-size:.85rem;font-weight:600;letter-spacing:1px;text-transform:uppercase;margin-bottom:1rem;">Follow Us</h6>
                    <div class="d-flex gap-2" style="font-size:1rem;">
                        <a class="social-link" href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                        <a class="social-link" href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                        <a class="social-link" href="#" aria-label="X"><i class="bi bi-twitter-x"></i></a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom text-center">
                <span>&copy; {{ date('Y') }} CoffeeShop. All rights reserved.</span>
                <span class="mx-3">·</span>
                <a href="#">Privacy Policy</a>
                <span class="mx-3">·</span>
                <a href="#">Terms of Service</a>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Shrink navbar on scroll
        window.addEventListener('scroll', () => {
            document.getElementById('mainNav').classList.toggle('scrolled', window.scrollY > 50);
        });
        window.updateCartBadge = (count) => {
            const badge = document.getElementById('navCartCount');
            if (!badge) return;
            badge.textContent = count;
            badge.classList.toggle('d-none', Number(count) < 1);
        };

        const revealTargets = document.querySelectorAll('main section:not(:first-child), .product-card, .team-card, .value-card, .stat-card, .chart-card');
        if ('IntersectionObserver' in window && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            const revealObserver = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (!entry.isIntersecting) return;
                    entry.target.classList.add('is-visible');
                    revealObserver.unobserve(entry.target);
                });
            }, { threshold: .1, rootMargin: '0px 0px -45px' });
            revealTargets.forEach((target, index) => {
                target.classList.add('reveal-item');
                target.style.transitionDelay = `${Math.min(index % 3, 2) * 70}ms`;
                revealObserver.observe(target);
            });
        }
    </script>
    @yield('scripts')
</body>
</html>
