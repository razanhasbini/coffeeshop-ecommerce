@extends('layout')

@section('title', 'Dashboard — CoffeeShop')

@section('styles')
<style>
    .dashboard-page { background: var(--warm-gray); min-height: 90vh; padding: 2.5rem 0 4rem; }
    .dash-header { margin-bottom: 2rem; }
    .stat-card {
        background: #fff;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        padding: 1.5rem 1.8rem;
        display: flex;
        align-items: center;
        gap: 1.2rem;
        transition: var(--transition);
    }
    .stat-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-hover); }
    .stat-icon {
        width: 52px; height: 52px;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem;
        flex-shrink: 0;
    }
    .stat-card .value { font-family: 'Playfair Display',serif; font-size: 1.7rem; font-weight: 700; line-height: 1; }
    .stat-card .label { font-size: .78rem; color: var(--text-muted); margin-top: .2rem; font-weight: 500; letter-spacing: .5px; text-transform: uppercase; }

    .panel {
        background: #fff;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        overflow: hidden;
    }
    .panel-header {
        padding: 1.2rem 1.6rem;
        border-bottom: 1px solid #f0e8dd;
        display: flex;
        align-items: center;
        gap: .6rem;
    }
    .panel-header h6 { margin: 0; font-size: .95rem; font-weight: 700; }
    .panel-body { padding: 1.5rem; }

    .orders-table { width: 100%; border-collapse: collapse; font-size: .87rem; }
    .orders-table th {
        font-size: .72rem; font-weight: 600; letter-spacing: 1.5px;
        text-transform: uppercase; color: var(--text-muted);
        padding: .75rem 1rem; border-bottom: 1px solid #f0e8dd;
        background: var(--warm-gray);
    }
    .orders-table td { padding: .85rem 1rem; border-bottom: 1px solid #f7f0e8; vertical-align: middle; }
    .orders-table tr:last-child td { border-bottom: none; }
    .orders-table tr:hover td { background: #fdf9f5; }

    .badge-status {
        font-size: .72rem; font-weight: 600; padding: .28rem .75rem;
        border-radius: 50px; display: inline-block;
    }
    .badge-completed { background: #e8f5e9; color: #2e7d32; }
    .badge-pending   { background: #fff8e1; color: #f57f17; }
    .admin-grid { display:grid; grid-template-columns:minmax(300px,.8fr) minmax(0,1.2fr); gap:1rem; }
    .admin-form-grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:1rem; }
    .admin-form-grid .full { grid-column:1/-1; }
    .product-admin-item { display:grid; grid-template-columns:76px minmax(0,1fr) auto; gap:1rem; align-items:center; padding:1rem 0; border-bottom:1px solid #f0e8dd; }
    .product-admin-item:last-child { border-bottom:0; }
    .product-admin-image { width:76px; height:76px; object-fit:cover; border-radius:14px; background:var(--warm-gray); }
    .product-admin-item details { grid-column:1/-1; }
    .product-admin-item summary { cursor:pointer; color:var(--coffee-accent); font-size:.84rem; font-weight:700; list-style:none; }
    .product-admin-item summary::-webkit-details-marker { display:none; }
    .color-field { display:flex; align-items:center; gap:.7rem; }
    .color-field input[type=color] { width:50px; height:44px; padding:3px; border:1px solid #e0d4c6; border-radius:10px; background:#fff; }
    .admin-nav { position:sticky; top:70px; z-index:20; display:flex; gap:.45rem; overflow-x:auto; padding:.55rem; margin-bottom:1.5rem; border:1px solid rgba(80,43,20,.08); border-radius:16px; background:rgba(255,255,255,.88); backdrop-filter:blur(18px); box-shadow:var(--shadow); scrollbar-width:none; }
    .admin-nav::-webkit-scrollbar { display:none; }
    .admin-nav a { flex:0 0 auto; color:var(--text-muted); text-decoration:none; font-size:.8rem; font-weight:700; padding:.58rem .82rem; border-radius:10px; transition:var(--transition); }
    .admin-nav a:hover { color:var(--coffee-dark); background:var(--warm-gray); }
    .admin-section { scroll-margin-top:145px; }
    .role-badge { display:inline-flex; align-items:center; padding:.25rem .6rem; border-radius:50px; font-size:.68rem; font-weight:700; letter-spacing:.5px; text-transform:uppercase; }
    .role-super_admin { background:#f3e5f5;color:#7b1fa2; }.role-manager { background:#fff3e0;color:#bf6516; }.role-user { background:#e3f2fd;color:#1565c0; }
    .status-dot { width:8px;height:8px;border-radius:50%;display:inline-block;margin-right:.35rem; }.status-active{background:#43a047}.status-suspended{background:#e53935}
    .log-action { font-family:ui-monospace,SFMono-Regular,Menlo,monospace; font-size:.72rem; color:var(--coffee-accent); background:rgba(201,138,56,.09); padding:.24rem .5rem; border-radius:7px; }
    @media(max-width:991px) { .admin-grid { grid-template-columns:1fr; } }
    @media(max-width:575px) { .admin-form-grid { grid-template-columns:1fr; } .admin-form-grid .full { grid-column:auto; } }
</style>
@endsection

@section('content')
<div class="dashboard-page">
    <div class="container">

        <div class="dash-header">
            <span class="section-label">Admin Panel</span>
            <div class="d-flex align-items-end justify-content-between gap-3 flex-wrap">
                <h2 class="section-title mb-0">Dashboard</h2>
                <div class="text-end"><span class="role-badge role-{{ auth()->user()->role }}">{{ str_replace('_',' ',auth()->user()->role) }}</span><div class="text-muted mt-1" style="font-size:.78rem;">Signed in as {{ auth()->user()->username }}</div></div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success border-0 rounded-4 mb-4"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger border-0 rounded-4 mb-4">
                @foreach($errors->all() as $error)<div><i class="bi bi-exclamation-circle me-2"></i>{{ $error }}</div>@endforeach
            </div>
        @endif

        <nav class="admin-nav" aria-label="Dashboard sections">
            <a href="#analytics"><i class="bi bi-graph-up me-1"></i>Analytics</a>
            <a href="#products-admin"><i class="bi bi-cup-hot me-1"></i>Products</a>
            <a href="#homepage-admin"><i class="bi bi-window me-1"></i>Homepage</a>
            <a href="#users-admin"><i class="bi bi-people me-1"></i>Users & Roles</a>
            <a href="#orders-admin"><i class="bi bi-receipt me-1"></i>Orders</a>
            <a href="#logs-admin"><i class="bi bi-clock-history me-1"></i>Activity Logs</a>
        </nav>

        {{-- Stat cards --}}
        <div class="row g-3 mb-4 admin-section" id="analytics">
            <div class="col-sm-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-icon" style="background:rgba(200,150,62,.12);color:var(--coffee-accent);"><i class="bi bi-receipt"></i></div>
                    <div>
                        <div class="value">{{ count($orders) }}</div>
                        <div class="label">Total Orders</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-icon" style="background:rgba(46,125,50,.1);color:#2e7d32;"><i class="bi bi-graph-up-arrow"></i></div>
                    <div>
                        <div class="value">${{ number_format(array_sum($salesData['totals']), 0) }}</div>
                        <div class="label">7-Day Revenue</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-icon" style="background:rgba(21,101,192,.1);color:#1565c0;"><i class="bi bi-cup-hot"></i></div>
                    <div>
                        <div class="value">{{ array_sum($productData['quantities']) }}</div>
                        <div class="label">Units Sold</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-icon" style="background:rgba(136,14,79,.1);color:#880e4f;"><i class="bi bi-people"></i></div>
                    <div>
                        <div class="value">{{ $userStats['customers'] }}</div>
                        <div class="label">Customers</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Charts --}}
        <div class="row g-3 mb-4">
            <div class="col-lg-7">
                <div class="panel">
                    <div class="panel-header">
                        <i class="bi bi-bar-chart-line" style="color:var(--coffee-accent);"></i>
                        <h6>Sales Trends (Last 7 Days)</h6>
                    </div>
                    <div class="panel-body">
                        <canvas id="salesTrendChart" height="110"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="panel">
                    <div class="panel-header">
                        <i class="bi bi-pie-chart" style="color:var(--coffee-accent);"></i>
                        <h6>Product Popularity</h6>
                    </div>
                    <div class="panel-body">
                        <canvas id="productPopularityChart" height="140"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Product and homepage management --}}
        <div class="admin-grid mb-4">
            <div class="panel admin-section" id="products-admin">
                <div class="panel-header"><i class="bi bi-plus-circle" style="color:var(--coffee-accent);"></i><h6>Add a Product</h6></div>
                <div class="panel-body">
                    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="admin-form-grid">
                        @csrf
                        <div><label class="form-label">Product name</label><input class="form-control" name="name" value="{{ old('name') }}" required></div>
                        <div><label class="form-label">Price ($)</label><input class="form-control" type="number" name="price" min="0" step="0.01" value="{{ old('price') }}" required></div>
                        <div class="full"><label class="form-label">Description</label><textarea class="form-control" name="description" rows="3" maxlength="1000">{{ old('description') }}</textarea></div>
                        <div><label class="form-label">Category</label><select class="form-select category-select" name="category" data-custom-target="newCustomCategory"><option value="">Choose category</option>@foreach(\App\Models\Product::CATEGORIES as $category)<option value="{{ $category }}" @selected(old('category')===$category)>{{ $category }}</option>@endforeach<option value="Other" @selected(old('category')==='Other')>Other…</option></select></div>
                        <div id="newCustomCategory" class="{{ old('category')==='Other' ? '' : 'd-none' }}"><label class="form-label">Custom category</label><input class="form-control" name="custom_category" value="{{ old('custom_category') }}"></div>
                        <div><label class="form-label">Product photo</label><input class="form-control" type="file" name="image" accept="image/jpeg,image/png,image/webp" required></div>
                        <div class="full"><button class="btn btn-coffee w-100" type="submit"><i class="bi bi-plus-lg me-1"></i>Add Product</button></div>
                    </form>
                </div>
            </div>

            <div class="panel admin-section" id="homepage-admin">
                <div class="panel-header"><i class="bi bi-stars" style="color:var(--coffee-accent);"></i><h6>Homepage Featured Section</h6></div>
                <div class="panel-body">
                    <p class="text-muted" style="font-size:.85rem;">Customize the section and choose exactly three products to display on the homepage.</p>
                    <form action="{{ route('admin.featured.update') }}" method="POST" class="admin-form-grid">
                        @csrf @method('PUT')
                        <div class="full"><label class="form-label">Section title</label><input class="form-control" name="title" value="{{ old('title', $featuredSection->title) }}" required></div>
                        <div class="full"><label class="form-label">Short description</label><input class="form-control" name="description" maxlength="180" value="{{ old('description', $featuredSection->description) }}" required></div>
                        <div><label class="form-label">Background</label><div class="color-field"><input type="color" name="background_color" value="{{ $featuredSection->background_color }}"><span>{{ $featuredSection->background_color }}</span></div></div>
                        <div><label class="form-label">Title color</label><div class="color-field"><input type="color" name="title_color" value="{{ $featuredSection->title_color }}"><span>{{ $featuredSection->title_color }}</span></div></div>
                        <div class="full"><label class="form-label">Moving line text</label><input class="form-control" name="ticker_text" maxlength="160" value="{{ old('ticker_text', $featuredSection->ticker_text) }}" required><div class="form-text">This text repeats continuously above the featured section.</div></div>
                        <div><label class="form-label">Line background</label><div class="color-field"><input type="color" name="ticker_background_color" value="{{ $featuredSection->ticker_background_color }}"><span>{{ $featuredSection->ticker_background_color }}</span></div></div>
                        <div><label class="form-label">Line text color</label><div class="color-field"><input type="color" name="ticker_text_color" value="{{ $featuredSection->ticker_text_color }}"><span>{{ $featuredSection->ticker_text_color }}</span></div></div>
                        <div class="full"><label class="form-label">Loop speed: <strong><span id="tickerSpeedValue">{{ $featuredSection->ticker_speed }}</span> seconds</strong></label><input class="form-range" id="tickerSpeed" type="range" name="ticker_speed" min="6" max="60" value="{{ $featuredSection->ticker_speed }}"><div class="d-flex justify-content-between form-text"><span>Fast</span><span>Slow</span></div></div>
                        @foreach(['one','two','three'] as $index => $slot)
                        <div class="{{ $index === 2 ? 'full' : '' }}">
                            <label class="form-label">Featured item {{ $index + 1 }}</label>
                            <select class="form-select" name="product_{{ $slot }}_id">
                                <option value="">Choose a product</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" @selected($featuredSection->{'product_'.$slot.'_id'} == $product->id)>{{ $product->name }} — ${{ number_format($product->price,2) }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endforeach
                        <div class="full"><button class="btn btn-coffee w-100" type="submit"><i class="bi bi-palette me-1"></i>Update Homepage</button></div>
                    </form>
                </div>
            </div>
        </div>

        <div class="panel mb-4 admin-section">
            <div class="panel-header"><i class="bi bi-cup-hot" style="color:var(--coffee-accent);"></i><h6>Manage Products ({{ $products->count() }})</h6></div>
            <div class="panel-body">
                @forelse($products as $product)
                <div class="product-admin-item">
                    <img class="product-admin-image" src="{{ $product->image_url ?: '/images/coffee.jpg' }}" alt="{{ $product->name }}">
                    <div><strong>{{ $product->name }}</strong><div class="text-muted" style="font-size:.82rem;">{{ $product->category ?: 'Uncategorized' }} · ${{ number_format($product->price,2) }}</div></div>
                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Delete this product?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" type="submit" aria-label="Delete {{ $product->name }}"><i class="bi bi-trash"></i></button></form>
                    <details>
                        <summary><i class="bi bi-pencil me-1"></i>Edit product</summary>
                        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="admin-form-grid mt-3">
                            @csrf @method('PUT')
                            <div><label class="form-label">Name</label><input class="form-control" name="name" value="{{ $product->name }}" required></div>
                            <div><label class="form-label">Price</label><input class="form-control" type="number" name="price" min="0" step="0.01" value="{{ $product->price }}" required></div>
                            <div class="full"><label class="form-label">Description</label><textarea class="form-control" name="description" rows="2">{{ $product->description }}</textarea></div>
                            @php($isCustomCategory = $product->category && !collect(\App\Models\Product::CATEGORIES)->contains(fn($category) => mb_strtolower($category) === mb_strtolower($product->category)))
                            <div><label class="form-label">Category</label><select class="form-select category-select" name="category" data-custom-target="customCategory{{ $product->id }}"><option value="">Choose category</option>@foreach(\App\Models\Product::CATEGORIES as $category)<option value="{{ $category }}" @selected(!$isCustomCategory && mb_strtolower((string)$product->category)===mb_strtolower($category))>{{ $category }}</option>@endforeach<option value="Other" @selected($isCustomCategory)>Other…</option></select></div>
                            <div id="customCategory{{ $product->id }}" class="{{ $isCustomCategory ? '' : 'd-none' }}"><label class="form-label">Custom category</label><input class="form-control" name="custom_category" value="{{ $isCustomCategory ? $product->category : '' }}"></div>
                            <div><label class="form-label">Replace photo (optional)</label><input class="form-control" type="file" name="image" accept="image/jpeg,image/png,image/webp"></div>
                            <div class="full"><button class="btn btn-outline-coffee" type="submit">Save Changes</button></div>
                        </form>
                    </details>
                </div>
                @empty
                    <div class="text-center text-muted py-4"><i class="bi bi-cup d-block mb-2" style="font-size:2rem;"></i>No products yet. Add your first one above.</div>
                @endforelse
            </div>
        </div>

        {{-- Users and roles --}}
        <div class="panel mb-4 admin-section" id="users-admin">
            <div class="panel-header d-flex justify-content-between flex-wrap">
                <div class="d-flex align-items-center gap-2"><i class="bi bi-people" style="color:var(--coffee-accent);"></i><h6>Users & Roles</h6></div>
                <div class="text-muted" style="font-size:.78rem;">{{ $userStats['active'] }} active · {{ $userStats['managers'] }} managers · {{ $userStats['total'] }} total</div>
            </div>
            @if(auth()->user()->isSuperAdmin())
            <div class="panel-body" style="background:var(--warm-gray);border-bottom:1px solid #f0e8dd;">
                <details>
                    <summary style="cursor:pointer;font-weight:700;color:var(--coffee-accent);"><i class="bi bi-person-plus me-1"></i>Create managerial account</summary>
                    <form action="{{ route('admin.managers.store') }}" method="POST" class="admin-form-grid mt-3">
                        @csrf
                        <div><label class="form-label">Manager name</label><input class="form-control" name="username" required></div>
                        <div><label class="form-label">Email</label><input class="form-control" type="email" name="email" required></div>
                        <div><label class="form-label">Password</label><input class="form-control" type="password" name="password" minlength="8" required></div>
                        <div><label class="form-label">Confirm password</label><input class="form-control" type="password" name="password_confirmation" minlength="8" required></div>
                        <div class="full"><button class="btn btn-coffee" type="submit"><i class="bi bi-shield-plus me-1"></i>Create Manager</button></div>
                    </form>
                </details>
            </div>
            @endif
            <div class="table-responsive">
                <table class="orders-table">
                    <thead><tr><th>User</th><th>Role</th><th>Status</th><th>Orders</th><th>Joined</th><th>Last login</th>@if(auth()->user()->isSuperAdmin())<th>Manage</th>@endif</tr></thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td><strong>{{ $user->username }}</strong><div class="text-muted" style="font-size:.76rem;">{{ $user->email }}</div></td>
                            <td><span class="role-badge role-{{ $user->role }}">{{ str_replace('_',' ',$user->role) }}</span></td>
                            <td><span class="status-dot {{ $user->is_active ? 'status-active' : 'status-suspended' }}"></span>{{ $user->is_active ? 'Active' : 'Suspended' }}</td>
                            <td>{{ $user->orders_count }}</td>
                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                            <td>{{ $user->last_login_at?->diffForHumans() ?? 'Never' }}</td>
                            @if(auth()->user()->isSuperAdmin())
                            <td>
                                @if(!$user->isSuperAdmin())
                                <form action="{{ route('admin.users.update',$user) }}" method="POST" class="d-flex gap-2 align-items-center flex-wrap">
                                    @csrf @method('PUT')
                                    <select class="form-select form-select-sm" name="role" style="width:105px;"><option value="user" @selected($user->role==='user')>User</option><option value="manager" @selected($user->role==='manager')>Manager</option></select>
                                    <select class="form-select form-select-sm" name="is_active" style="width:112px;"><option value="1" @selected($user->is_active)>Active</option><option value="0" @selected(!$user->is_active)>Suspended</option></select>
                                    <button class="btn btn-sm btn-outline-coffee" type="submit">Save</button>
                                </form>
                                @else<span class="text-muted" style="font-size:.76rem;"><i class="bi bi-lock me-1"></i>Protected</span>@endif
                            </td>
                            @endif
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Orders table --}}
        <div class="panel mb-4 admin-section" id="orders-admin">
            <div class="panel-header">
                <i class="bi bi-list-ul" style="color:var(--coffee-accent);"></i>
                <h6>All Orders</h6>
            </div>
            <div class="table-responsive">
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Customer</th>
                            <th>Product</th>
                            <th>Qty</th>
                            <th>Total</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                        <tr>
                            <td style="color:var(--text-muted);">{{ $order->id }}</td>
                            <td style="font-weight:600;">{{ $order->user->username ?? 'Guest' }}</td>
                            <td>{{ $order->items->pluck('product.name')->filter()->join(', ') ?: '—' }}</td>
                            <td>{{ $order->items->sum('quantity') }}</td>
                            <td style="font-weight:600;color:var(--coffee-accent);">${{ number_format($order->items->sum(fn ($item) => $item->price * $item->quantity), 2) }}</td>
                            <td style="color:var(--text-muted);">{{ $order->created_at->format('M d, Y') }}</td>
                            <td>
                                <span class="badge-status badge-completed">Placed</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted" style="font-size:.88rem;">No orders yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Audit log --}}
        <div class="panel admin-section" id="logs-admin">
            <div class="panel-header"><i class="bi bi-clock-history" style="color:var(--coffee-accent);"></i><h6>Activity Logs</h6><span class="ms-auto text-muted" style="font-size:.76rem;">Latest 100 events</span></div>
            <div class="table-responsive">
                <table class="orders-table">
                    <thead><tr><th>Time</th><th>Actor</th><th>Action</th><th>Description</th><th>IP address</th></tr></thead>
                    <tbody>
                    @forelse($activityLogs as $log)
                        <tr>
                            <td style="white-space:nowrap;color:var(--text-muted);">{{ $log->created_at->format('M d, H:i') }}</td>
                            <td><strong>{{ $log->actor->username ?? 'System / Guest' }}</strong><div class="text-muted" style="font-size:.74rem;">{{ $log->actor ? str_replace('_',' ',$log->actor->role) : 'unauthenticated' }}</div></td>
                            <td><span class="log-action">{{ $log->action }}</span></td>
                            <td>{{ $log->description }}</td>
                            <td style="font-family:ui-monospace,monospace;font-size:.75rem;color:var(--text-muted);">{{ $log->ip_address ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">No activity has been recorded yet.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const accentColor = '#c8963e';
const tickerSpeed = document.getElementById('tickerSpeed');
const tickerSpeedValue = document.getElementById('tickerSpeedValue');
tickerSpeed?.addEventListener('input', () => tickerSpeedValue.textContent = tickerSpeed.value);
document.querySelectorAll('.category-select').forEach(select => {
    const customField = document.getElementById(select.dataset.customTarget);
    const syncCustomCategory = () => {
        const isOther = select.value === 'Other';
        customField?.classList.toggle('d-none', !isOther);
        const input = customField?.querySelector('input');
        if (input) input.required = isOther;
    };
    select.addEventListener('change', syncCustomCategory);
    syncCustomCategory();
});

// Sales Trend
new Chart(document.getElementById('salesTrendChart').getContext('2d'), {
    type: 'line',
    data: {
        labels: {!! json_encode($salesData['dates']) !!},
        datasets: [{
            label: 'Revenue ($)',
            data: {!! json_encode($salesData['totals']) !!},
            borderColor: accentColor,
            backgroundColor: 'rgba(200,150,62,.08)',
            borderWidth: 2.5,
            fill: true,
            tension: .4,
            pointBackgroundColor: accentColor,
            pointRadius: 4,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            x: { grid: { display: false }, ticks: { font: { size: 11 } } },
            y: { grid: { color: '#f0e8dd' }, ticks: { font: { size: 11 } } }
        }
    }
});

// Product Popularity
new Chart(document.getElementById('productPopularityChart').getContext('2d'), {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($productData['names']) !!},
        datasets: [{
            data: {!! json_encode($productData['quantities']) !!},
            backgroundColor: ['#c8963e','#3b1f0e','#e8c27a','#7a6652','#f4efe8'],
            borderWidth: 0,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom', labels: { font: { size: 11 }, padding: 12 } }
        },
        cutout: '62%',
    }
});
</script>
@endsection
{{-- Legacy duplicate page removed from rendering.

        <!-- Orders Overview -->
        <div class="row mb-4">
            <div class="col-md-12">
                <h4>All Orders</h4>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>Order ID</th>
                                <th>Customer Name</th>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Total Price</th>
                                <th>Order Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->customer_name }}</td>
                                <td>{{ $order->product_name }}</td>
                                <td>{{ $order->quantity }}</td>
                                <td>${{ $order->total_price }}</td>
                                <td>{{ $order->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <span class="badge {{ $order->status === 'Completed' ? 'bg-success' : 'bg-warning' }}">
                                        {{ $order->status }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="row">
            <!-- Sales Trend Chart -->
            <div class="col-md-6">
                <h4>Sales Trends</h4>
                <canvas id="salesTrendChart"></canvas>
            </div>

            <!-- Product Popularity Chart -->
            <div class="col-md-6">
                <h4>Product Popularity</h4>
                <canvas id="productPopularityChart"></canvas>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Sales Trend Chart
    const salesTrendCtx = document.getElementById('salesTrendChart').getContext('2d');
    const salesTrendChart = new Chart(salesTrendCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($salesData['dates']) !!}, // Pass dates array from controller
            datasets: [{
                label: 'Sales ($)',
                data: {!! json_encode($salesData['totals']) !!}, // Pass sales totals array from controller
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2,
                fill: false,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
            },
        },
    });

    // Product Popularity Chart
    const productPopularityCtx = document.getElementById('productPopularityChart').getContext('2d');
    const productPopularityChart = new Chart(productPopularityCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($productData['names']) !!}, // Pass product names array from controller
            datasets: [{
                label: 'Units Sold',
                data: {!! json_encode($productData['quantities']) !!}, // Pass product quantities array from controller
                backgroundColor: 'rgba(153, 102, 255, 0.6)',
                borderColor: 'rgba(153, 102, 255, 1)',
                borderWidth: 1,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
            },
        },
    });
});
</script>

@endsection
--}}
