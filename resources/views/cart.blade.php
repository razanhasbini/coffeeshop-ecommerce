@extends('layout')

@section('title', 'Your Cart — CoffeeShop')

@section('styles')
<style>
    .cart-page { background: var(--warm-gray); min-height: 80vh; padding: 3.5rem 0; }
    .cart-card {
        background: #fff;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        overflow: hidden;
    }
    .cart-card-header {
        padding: 1.3rem 1.8rem;
        border-bottom: 1px solid #f0e8dd;
        display: flex;
        align-items: center;
        gap: .75rem;
    }
    .cart-card-header h5 { margin: 0; font-size: 1.05rem; }
    .cart-table { width: 100%; border-collapse: collapse; }
    .cart-table th {
        font-size: .75rem;
        font-weight: 600;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: var(--text-muted);
        padding: .85rem 1.3rem;
        border-bottom: 1px solid #f0e8dd;
        background: var(--warm-gray);
    }
    .cart-table td {
        padding: 1rem 1.3rem;
        border-bottom: 1px solid #f7f0e8;
        vertical-align: middle;
        font-size: .92rem;
    }
    .cart-table tr:last-child td { border-bottom: none; }
    .cart-table tr:hover td { background: #fdf9f5; }
    .qty-control {
        display: flex;
        align-items: center;
        gap: .4rem;
        width: fit-content;
    }
    .qty-btn {
        width: 28px; height: 28px;
        border-radius: 50%;
        border: 1.5px solid #e0d4c6;
        background: #fff;
        cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        font-size: .85rem;
        transition: var(--transition);
        color: var(--coffee-dark);
    }
    .qty-btn:hover { border-color: var(--coffee-accent); color: var(--coffee-accent); }
    .qty-display {
        width: 38px;
        text-align: center;
        font-weight: 600;
        font-size: .92rem;
    }
    .remove-btn {
        background: none;
        border: none;
        color: #ccc;
        font-size: 1rem;
        cursor: pointer;
        padding: .3rem;
        border-radius: 6px;
        transition: var(--transition);
    }
    .remove-btn:hover { color: #e53935; background: #fdecea; }
    .summary-card {
        background: #fff;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        padding: 1.8rem;
        position: sticky;
        top: 90px;
    }
    .summary-row {
        display: flex;
        justify-content: space-between;
        font-size: .9rem;
        padding: .5rem 0;
        color: var(--text-muted);
        border-bottom: 1px solid #f0e8dd;
    }
    .summary-total {
        display: flex;
        justify-content: space-between;
        font-size: 1.15rem;
        font-weight: 700;
        color: var(--coffee-dark);
        padding-top: 1rem;
        margin-top: .5rem;
    }
    .empty-cart {
        text-align: center;
        padding: 5rem 2rem;
    }
    .empty-cart i { font-size: 4rem; color: var(--coffee-accent); opacity: .3; }
</style>
@endsection

@section('content')
<div class="cart-page">
    <div class="container">
        <div class="mb-4">
            <span class="section-label">Your selection</span>
            <h2 class="section-title">Shopping Cart</h2>
        </div>

        @if($cartItems->isEmpty())
        <div class="cart-card">
            <div class="empty-cart">
                <i class="bi bi-bag d-block mb-3"></i>
                <h5 style="color:var(--coffee-dark);">Your cart is empty</h5>
                <p class="text-muted mb-4">Looks like you haven't added anything yet.</p>
                <a href="{{ route('products') }}" class="btn btn-coffee">Browse Menu</a>
            </div>
        </div>
        @else
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="cart-card">
                    <div class="cart-card-header">
                        <i class="bi bi-bag" style="color:var(--coffee-accent);font-size:1.1rem;"></i>
                        <h5>Cart Items</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="cart-table" id="cartItems">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cartItems as $item)
                                <tr data-id="{{ $item->product_id }}">
                                    <td>
                                        <div style="font-weight:600;color:var(--coffee-dark);">{{ $item->product->name }}</div>
                                        @if($item->product->category)
                                        <div style="font-size:.76rem;color:var(--text-muted);margin-top:.2rem;">{{ ucfirst($item->product->category) }}</div>
                                        @endif
                                    </td>
                                    <td style="color:var(--coffee-accent);font-weight:600;">${{ number_format($item->product->price, 2) }}</td>
                                    <td>
                                        <div class="qty-control">
                                            <button class="qty-btn qty-minus" type="button">−</button>
                                            <div class="qty-display quantity-value">{{ $item->quantity }}</div>
                                            <button class="qty-btn qty-plus" type="button">+</button>
                                        </div>
                                    </td>
                                    <td class="item-subtotal" style="font-weight:600;">${{ number_format($item->product->price * $item->quantity, 2) }}</td>
                                    <td>
                                        <button class="remove-btn remove-item" title="Remove"><i class="bi bi-trash3"></i></button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="summary-card">
                    <h6 style="font-weight:700;font-size:1rem;margin-bottom:1.2rem;">Order Summary</h6>
                    <div class="summary-row"><span>Subtotal</span><span>$<span id="cartTotal">{{ number_format($totalPrice, 2) }}</span></span></div>
                    <div class="summary-row"><span>Delivery</span><span style="color:#2e7d32;">Free</span></div>
                    <div class="summary-total">
                        <span>Total</span>
                        <span style="color:var(--coffee-accent);">$<span id="cartTotalBottom">{{ number_format($totalPrice, 2) }}</span></span>
                    </div>
                    <a href="{{ route('checkout.index') }}" class="btn btn-coffee w-100 mt-3">{{ auth()->check() ? 'Proceed to Checkout' : 'Sign In to Checkout' }} <i class="bi bi-arrow-right ms-1"></i></a>
                    @guest<p class="text-center text-muted mt-2 mb-0" style="font-size:.76rem;">Your cart will be kept while you sign in.</p>@endguest
                    <a href="{{ route('products') }}" class="btn btn-outline-coffee w-100 mt-2">Continue Shopping</a>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
const csrf = document.querySelector('meta[name="csrf-token"]').content;

function updateTotals(newTotal) {
    document.getElementById('cartTotal').textContent      = parseFloat(newTotal).toFixed(2);
    document.getElementById('cartTotalBottom').textContent = parseFloat(newTotal).toFixed(2);
}

// +/- buttons
document.addEventListener('click', async (e) => {
    const row  = e.target.closest('tr[data-id]');
    if (!row) return;
    const id   = row.dataset.id;
    const disp = row.querySelector('.quantity-value');
    let qty    = parseInt(disp.textContent);

    if (e.target.closest('.qty-plus'))  qty = Math.max(1, qty + 1);
    else if (e.target.closest('.qty-minus')) qty = Math.max(1, qty - 1);
    else return;

    disp.textContent = qty;
    try {
        const res  = await fetch(`/cart/update/${id}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
            body: JSON.stringify({ quantity: qty })
        });
        const data = await res.json();
        const price = parseFloat(data.totalPrice) / qty * qty; // item subtotal not returned, so approximate
        updateTotals(data.totalPrice);
        window.updateCartBadge?.(data.cartCount);
        // Recompute this row's subtotal via the price in the 2nd cell
        const unitPrice = parseFloat(row.cells[1].textContent.replace('$',''));
        row.querySelector('.item-subtotal').textContent = '$' + (unitPrice * qty).toFixed(2);
    } catch(err) { console.error(err); }
});

// Remove
document.addEventListener('click', async (e) => {
    if (!e.target.closest('.remove-item')) return;
    const row = e.target.closest('tr[data-id]');
    const id  = row.dataset.id;
    try {
        const res  = await fetch(`/cart/remove/${id}`, {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf }
        });
        const data = await res.json();
        if (data.success) {
            row.style.opacity = '0';
            row.style.transition = 'opacity .3s';
            setTimeout(() => row.remove(), 300);
            updateTotals(data.totalPrice);
            window.updateCartBadge?.(data.cartCount);
        }
    } catch(err) { console.error(err); }
});
</script>
@endsection
{{-- Legacy duplicate page removed from rendering.
        
        @if($cartItems->isEmpty())
            <p class="text-center">Your cart is empty. <a href="{{ route('products') }}">Shop now!</a></p>
        @else
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                            <th>Remove</th>
                        </tr>
                    </thead>
                    <tbody id="cartItems">
                        @foreach ($cartItems as $item)
                        <tr data-id="{{ $item->id }}">
                            <td>{{ $item->product->name }}</td>
                            <td>${{ number_format($item->product->price, 2) }}</td>
                            <td>
                                <input type="number" class="form-control quantity-input" value="{{ $item->quantity }}" min="1">
                            </td>
                            <td class="item-subtotal">${{ number_format($item->product->price * $item->quantity, 2) }}</td>
                            <td>
                                <button class="btn btn-danger btn-sm remove-item yellow">Remove</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="text-end mt-3">
                <h4>Total: $<span id="cartTotal">{{ number_format($totalPrice, 2) }}</span></h4>
                <button class="btn btn-dark mt-2">Proceed to Checkout</button>
            </div>
        @endif
    </div>
</section>

<script>
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('change', async (e) => {
            const row = e.target.closest('tr');
            const itemId = row.dataset.id;
            const newQuantity = e.target.value;

            try {
                const response = await fetch(`/cart/update/${itemId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ quantity: newQuantity })
                });
                const data = await response.json();
                row.querySelector('.item-subtotal').innerText = `$${data.totalPrice.toFixed(2)}`;
                document.getElementById('cartTotal').innerText = `$${data.totalPrice.toFixed(2)}`;
            } catch (error) {
                console.error('Error updating cart:', error);
            }
        });
    });

    document.querySelectorAll('.remove-item').forEach(button => {
        button.addEventListener('click', async (e) => {
            const row = e.target.closest('tr');
            const itemId = row.dataset.id;

            try {
                const response = await fetch(`/cart/remove/${itemId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                const data = await response.json();

                if (data.success) {
                    row.remove();
                    document.getElementById('cartTotal').innerText = `$${data.totalPrice.toFixed(2)}`;
                }
            } catch (error) {
                console.error('Error removing item:', error);
            }
        });
    });
</script>
@endsection
--}}
