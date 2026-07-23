@extends('layout')

@section('title', 'Our Menu — CoffeeShop')

@section('styles')
<style>
    .products-hero {
        background: url('/images/products-hero.jpg') center/cover no-repeat;
        min-height: 320px;
        display: flex;
        align-items: center;
        position: relative;
    }
    .products-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(28,15,7,.82) 0%, rgba(28,15,7,.5) 100%);
    }
    .products-hero .content { position: relative; z-index: 1; }

    .filter-bar {
        background: #fff;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        padding: 1.2rem 1.5rem;
        margin-bottom: 2.5rem;
    }
    .filter-bar .form-control,
    .filter-bar .form-select { border-radius: 10px; }

    .product-card {
        background: #fff;
        border-radius: var(--radius);
        overflow: hidden;
        box-shadow: var(--shadow);
        transition: var(--transition);
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .product-card:hover { transform: translateY(-5px); box-shadow: var(--shadow-hover); }
    .product-card .card-img {
        width: 100%;
        height: 165px;
        object-fit: cover;
        object-position: center;
        background: linear-gradient(135deg, #3b1f0e, #c8963e);
    }
    .product-card .card-body { padding: 1.05rem; flex: 1; display: flex; flex-direction: column; }
    .product-card .product-name { font-size: 1.05rem; font-weight: 600; color: var(--coffee-dark); margin-bottom: .3rem; }
    .product-card .product-desc { font-size: .83rem; color: var(--text-muted); line-height: 1.6; flex: 1; margin-bottom: 1rem; }
    .product-card .product-price { font-size: 1.15rem; font-weight: 700; color: var(--coffee-accent); }
    .product-card .category-badge {
        display: inline-block;
        background: rgba(200,150,62,.12);
        color: var(--coffee-accent);
        font-size: .72rem;
        font-weight: 600;
        letter-spacing: 1px;
        text-transform: uppercase;
        padding: .2rem .65rem;
        border-radius: 50px;
        margin-bottom: .6rem;
    }
    .qty-input {
        width: 64px;
        text-align: center;
        border-radius: 8px;
        border: 1.5px solid #e0d4c6;
        padding: .4rem;
    }
    .product-qty-control { display:flex;align-items:center;border:1px solid #e4d5c5;border-radius:50px;background:var(--cream);padding:3px;box-shadow:0 5px 14px rgba(40,20,8,.06); }
    .product-qty-btn { width:32px;height:32px;border:0;border-radius:50%;display:grid;place-items:center;background:#fff;color:var(--coffee-dark);font-size:.95rem;transition:var(--transition); }
    .product-qty-btn:hover:not(:disabled) { background:var(--coffee-accent);color:#fff; }
    .product-qty-btn:disabled { opacity:.38;cursor:not-allowed; }
    .product-qty-value { min-width:29px;text-align:center;font-size:.84rem;font-weight:700;color:var(--coffee-dark); }
    .category-chips { display:flex; gap:.55rem; overflow-x:auto; padding:.9rem 0 .1rem; scrollbar-width:none; }
    .category-chips::-webkit-scrollbar { display:none; }
    .category-chip { flex:0 0 auto; border:1px solid #e6d9ca; background:#fffaf4; color:var(--text-muted); border-radius:50px; padding:.42rem .8rem; font-size:.76rem; font-weight:600; transition:var(--transition); }
    .category-chip:hover,.category-chip.active { background:var(--coffee-dark); border-color:var(--coffee-dark); color:#fff; }
    #product-results { transition:opacity .2s ease,transform .2s ease; }
    #product-results.is-loading { opacity:.42; transform:translateY(5px); pointer-events:none; }
    .filter-live-status { font-size:.75rem; color:var(--text-muted); display:flex; align-items:center; gap:.4rem; }
    .filter-live-status::before { content:''; width:7px; height:7px; border-radius:50%; background:#43a047; box-shadow:0 0 0 4px rgba(67,160,71,.1); }
    @media (max-width: 767.98px) {
        .products-hero { min-height:230px; background-position:58% center !important; }
        .filter-bar { padding:1rem; margin-bottom:1.5rem; }
        .category-chips { margin-right:-1rem; padding-right:1rem; scroll-snap-type:x proximity; }
        .category-chip { min-height:40px; scroll-snap-align:start; }
        .filter-live-status { display:none; }
        .product-card .card-img { height:210px; }
        .product-card .card-body { padding:1rem; }
    }
    @media (max-width: 399.98px) {
        .product-card .card-img { height:190px; }
        .product-qty-btn { width:36px; height:36px; }
    }
</style>
@endsection

@section('content')

{{-- Hero --}}
<section class="products-hero">
    <div class="container content text-white">
        <span style="font-size:.75rem;font-weight:600;letter-spacing:3px;text-transform:uppercase;color:var(--coffee-light);">Handcrafted with care</span>
        <h1 style="font-size:clamp(2rem,5vw,3.2rem);margin-bottom:.5rem;">Our Menu</h1>
        <p style="color:rgba(255,255,255,.75);font-size:1rem;">Discover your next favorite brew.</p>
    </div>
</section>

<section style="padding: 3.5rem 0; background: var(--warm-gray);">
    <div class="container">

        {{-- Filter bar --}}
        <form method="GET" action="{{ route('products') }}" class="filter-bar" id="productFilter" autocomplete="off">
            <div class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label mb-1" for="productSearch">Search</label>
                    <div class="input-group">
                        <span class="input-group-text" style="border-color:#e0d4c6;border-radius:10px 0 0 10px;background:#fdf8f2;"><i class="bi bi-search" style="color:var(--coffee-accent);"></i></span>
                        <input id="productSearch" type="search" class="form-control" style="border-left:0;border-radius:0 10px 10px 0;" name="search" value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">Category</label>
                    <select name="category" class="form-select" id="categoryFilter">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}" @selected(mb_strtolower((string)request('category'))===mb_strtolower($category))>{{ $category }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">Sort by Price</label>
                    <select name="price" class="form-select">
                        <option value="">Default</option>
                        <option value="low" {{ request('price') == 'low' ? 'selected' : '' }}>Low → High</option>
                        <option value="high" {{ request('price') == 'high' ? 'selected' : '' }}>High → Low</option>
                    </select>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-end gap-3 mt-2">
                <div class="category-chips" aria-label="Quick category filters">
                    <button type="button" class="category-chip {{ request('category') ? '' : 'active' }}" data-category="">All</button>
                    @foreach($categories as $category)<button type="button" class="category-chip {{ mb_strtolower((string)request('category'))===mb_strtolower($category) ? 'active' : '' }}" data-category="{{ $category }}">{{ $category }}</button>@endforeach
                </div>
                <div class="filter-live-status flex-shrink-0 mb-1">Updates automatically</div>
            </div>
        </form>

        {{-- Grid --}}
        <div id="product-results" aria-live="polite">
        @if($products->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-cup-hot" style="font-size:3rem;color:var(--coffee-accent);opacity:.4;"></i>
                <p class="mt-3 text-muted">No products found. Try a different search.</p>
            </div>
        @else
        <div class="row g-4">
            @foreach ($products as $product)
            <div class="col-sm-6 col-lg-4 col-xl-3">
                <div class="product-card">
                    <img src="{{ $product->image_url }}" class="card-img" alt="{{ $product->name }}"
                         onerror="this.style.background='linear-gradient(135deg,#3b1f0e,#c8963e)';this.removeAttribute('src')">
                    <div class="card-body">
                        @if($product->category)
                            <span class="category-badge">{{ $product->category }}</span>
                        @endif
                        <div class="product-name">{{ $product->name }}</div>
                        <div class="product-desc">{{ $product->description }}</div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="product-price">${{ number_format($product->price, 2) }}</span>
                            @php($selectedQuantity = (int) ($cartQuantities[$product->id] ?? 0))
                            <div class="product-qty-control" data-product-id="{{ $product->id }}" data-add-url="{{ route('cart.add') }}" data-update-url="{{ route('cart.update',$product->id) }}" data-remove-url="{{ route('cart.remove',$product->id) }}" aria-label="Quantity of {{ $product->name }} in cart">
                                <button type="button" class="product-qty-btn product-minus" aria-label="Remove one {{ $product->name }}" @disabled($selectedQuantity < 1)><i class="bi bi-dash-lg"></i></button>
                                <span class="product-qty-value" aria-live="polite">{{ $selectedQuantity }}</span>
                                <button type="button" class="product-qty-btn product-plus" aria-label="Add one {{ $product->name }}"><i class="bi bi-plus-lg"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $products->links() }}
        </div>
        @endif
        </div>
    </div>
</section>

@endsection

@section('scripts')
<script>
const filterForm = document.getElementById('productFilter');
const searchInput = document.getElementById('productSearch');
const categoryFilter = document.getElementById('categoryFilter');
let filterTimer;
let activeFilterRequest;

async function refreshProducts(url = null) {
    const params = new URLSearchParams(new FormData(filterForm));
    for (const [key, value] of [...params.entries()]) if (!value) params.delete(key);
    const requestUrl = url || `${filterForm.action}${params.toString() ? '?' + params : ''}`;
    const results = document.getElementById('product-results');
    activeFilterRequest?.abort();
    activeFilterRequest = new AbortController();
    results.classList.add('is-loading');
    results.setAttribute('aria-busy', 'true');

    try {
        const response = await fetch(requestUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' }, signal: activeFilterRequest.signal });
        if (!response.ok) throw new Error('Unable to load products');
        const page = new DOMParser().parseFromString(await response.text(), 'text/html');
        const incoming = page.getElementById('product-results');
        if (!incoming) throw new Error('Product results missing');
        results.innerHTML = incoming.innerHTML;
        history.replaceState({}, '', requestUrl);
    } catch (error) {
        if (error.name !== 'AbortError') results.innerHTML = '<div class="alert alert-danger rounded-4">Products could not be refreshed. Please try again.</div>';
    } finally {
        results.classList.remove('is-loading');
        results.removeAttribute('aria-busy');
    }
}

filterForm.addEventListener('submit', event => { event.preventDefault(); refreshProducts(); });
filterForm.querySelectorAll('select').forEach(select => select.addEventListener('change', () => {
    if (select === categoryFilter) {
        document.querySelectorAll('.category-chip').forEach(item => item.classList.toggle('active', item.dataset.category.toLowerCase() === select.value.toLowerCase()));
    }
    refreshProducts();
}));
searchInput.addEventListener('input', () => { clearTimeout(filterTimer); filterTimer = setTimeout(() => refreshProducts(), 300); });
document.querySelectorAll('.category-chip').forEach(chip => chip.addEventListener('click', () => {
    categoryFilter.value = chip.dataset.category;
    document.querySelectorAll('.category-chip').forEach(item => item.classList.toggle('active', item === chip));
    refreshProducts();
}));
document.addEventListener('click', event => {
    const paginationLink = event.target.closest('#product-results .pagination a');
    if (!paginationLink) return;
    event.preventDefault();
    refreshProducts(paginationLink.href);
});

document.addEventListener('click', async function (event) {
    const button = event.target.closest('.product-qty-btn');
    if (!button) return;
    const control = button.closest('.product-qty-control');
    const value = control.querySelector('.product-qty-value');
    const minus = control.querySelector('.product-minus');
    const current = Number(value.textContent);
    const isAdding = button.classList.contains('product-plus');
    if (!isAdding && current < 1) return;
    control.querySelectorAll('button').forEach(item => item.disabled = true);
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    try {
        let response;
        if (isAdding) {
            const body = new FormData();
            body.append('product_id', control.dataset.productId);
            body.append('quantity', '1');
            response = await fetch(control.dataset.addUrl, { method:'POST', body, headers:{ 'X-Requested-With':'XMLHttpRequest', 'X-CSRF-TOKEN':csrfToken } });
        } else if (current === 1) {
            response = await fetch(control.dataset.removeUrl, { method:'DELETE', headers:{ 'X-Requested-With':'XMLHttpRequest', 'X-CSRF-TOKEN':csrfToken } });
        } else {
            response = await fetch(control.dataset.updateUrl, { method:'POST', body:JSON.stringify({quantity:current - 1}), headers:{ 'Content-Type':'application/json', 'X-Requested-With':'XMLHttpRequest', 'X-CSRF-TOKEN':csrfToken } });
        }
        if (!response.ok) throw new Error('Cart update failed');
        const data = await response.json();
        const next = isAdding ? current + 1 : current - 1;
        value.textContent = next;
        minus.disabled = next < 1;
        window.updateCartBadge?.(data.cartCount);
    } catch (error) {
        console.error(error);
    } finally {
        control.querySelector('.product-plus').disabled = false;
        minus.disabled = Number(value.textContent) < 1;
    }
});
</script>
@endsection
{{-- Legacy duplicate page removed from rendering.

<!-- Products Section -->
<section class="products-section py-5">
    <div class="container">
        <h2 class="text-center mb-4">Our Products</h2>

        <!-- Filter and Search -->
        <div class="row mb-4">
            <div class="col-md-6">
                <form method="GET" action="{{ route('products') }}">
                    <input type="text" class="form-control" name="search" value="{{ request('search') }}">
                </form>
            </div>
            <div class="col-md-3">
                <form method="GET" action="{{ route('products') }}">
                    <select name="category" class="form-control">
                        <option value="">Select Category</option>
                        <option value="coffee" {{ request('category') == 'coffee' ? 'selected' : '' }}>Coffee</option>
                        <option value="tea" {{ request('category') == 'tea' ? 'selected' : '' }}>Tea</option>
                    </select>
                </form>
            </div>
            <div class="col-md-3">
                <form method="GET" action="{{ route('products') }}">
                    <select name="price" class="form-control">
                        <option value="">Sort by Price</option>
                        <option value="low" {{ request('price') == 'low' ? 'selected' : '' }}>Low to High</option>
                        <option value="high" {{ request('price') == 'high' ? 'selected' : '' }}>High to Low</option>
                    </select>
                </form>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="row">
            @foreach ($products as $product)
            <div class="col-md-4 mb-4">
                <div class="product-card h-100">
                    <img src="{{ $product->image_url }}" class="img-fluid" alt="{{ $product->name }}">
                    <h4>{{ $product->name }}</h4>
                    <p>{{ $product->description }}</p>
                    <p>${{ number_format($product->price, 2) }}</p>
                    @if(Auth::check())
                    <form action="{{ route('cart.add') }}" method="POST" class="add-to-cart-form">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="number" name="quantity" class="form-control" value="1" min="1">
                        <button type="submit" class="btn btn-primary w-100 mt-2">Add to Cart</button>
                    </form>
                    @else
                    <a href="{{ route('login') }}" class="btn btn-primary w-100 mt-2">Login to Add to Cart</a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="row">
            <div class="col-md-12">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</section>

<!-- Additional CSS for Styling -->
<style>
    .product-card {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        align-items: center;
        text-align: center;
        border: 1px solid #ddd;
        border-radius: 10px;
        padding: 15px;
        background-color: #fff;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        height: 100%;
    }

    .product-card img {
        max-height: 150px;
        object-fit: cover;
        margin-bottom: 15px;
        border-radius: 10px;
    }

    .product-card h4 {
        font-size: 18px;
        margin-bottom: 10px;
    }

    .product-card p {
        font-size: 14px;
        margin-bottom: 10px;
    }

    .product-card .form-control {
        max-width: 80px;
        margin: 0 auto;
    }
</style>

<!-- JavaScript for Form Submission -->
<script>
    document.addEventListener('submit', function (event) {
        if (event.target.classList.contains('add-to-cart-form')) {
            event.preventDefault();

            const form = event.target;
            const formData = new FormData(form);
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

            if (!csrfToken) {
                alert('CSRF token is missing!');
                return;
            }

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken,
                },
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Product added to cart successfully!');
                    } else {
                        alert('Failed to add product to cart.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while adding the product to cart.');
                });
        }
    });
</script>
@endsection
--}}
