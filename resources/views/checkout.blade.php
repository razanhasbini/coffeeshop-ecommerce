@extends('layout')

@section('title', 'Checkout — CoffeeShop')

@section('styles')
<style>
    .checkout-page{min-height:80vh;padding:4rem 0;background:var(--warm-gray)}
    .checkout-card{background:#fff;border-radius:var(--radius);box-shadow:var(--shadow);padding:clamp(1.5rem,4vw,2.5rem)}
    .checkout-item{display:flex;justify-content:space-between;gap:1rem;padding:.9rem 0;border-bottom:1px solid #f0e8dd;font-size:.9rem}
    .checkout-total{display:flex;justify-content:space-between;font:700 1.25rem 'Playfair Display',serif;padding-top:1.2rem}
    .field-hint{display:block;color:#8c7b6d;font-size:.78rem;margin-top:.4rem}
    .delivery-fields{padding:1.15rem;border:1px solid #eee3d8;border-radius:16px;background:#fdfaf6;margin-bottom:1rem}
    .delivery-fields h5{font-size:.88rem;text-transform:uppercase;letter-spacing:.08em;margin-bottom:1rem;color:var(--coffee-dark)}
    @media(max-width:767.98px){
        .checkout-page{padding:2rem 0 3rem}
        .checkout-card{padding:1.15rem}
        .delivery-fields{padding:1rem}
        .checkout-item{align-items:flex-start}
        .checkout-item span{min-width:0}
        .checkout-item strong{flex:0 0 auto}
    }
</style>
@endsection

@section('content')
<div class="checkout-page">
    <div class="container">
        <div class="mb-4"><span class="section-label">Secure checkout</span><h1 class="section-title">Complete your order</h1></div>
        @if(session('error'))<div class="alert alert-danger border-0 rounded-4">{{ session('error') }}</div>@endif
        @if($errors->any())
            <div class="alert alert-danger border-0 rounded-4">
                <strong>Please check your details.</strong>
                <ul class="mb-0 mt-2">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif
        <div class="row g-4">
            <div class="col-lg-7">
                <div class="checkout-card">
                    <h4 class="mb-4">Delivery details</h4>
                    <form method="POST" action="{{ route('checkout.confirm') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="phone_number">Phone number</label>
                            <input class="form-control @error('phone_number') is-invalid @enderror" id="phone_number" type="tel" name="phone_number" value="{{ old('phone_number') }}" autocomplete="tel" required>
                            <span class="field-hint">We’ll only use this if we need to reach you about delivery.</span>
                            @error('phone_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="delivery-fields">
                            <h5><i class="bi bi-geo-alt me-1"></i> Delivery address</h5>
                            <div class="mb-3">
                                <label class="form-label" for="shipping_address">Street, building &amp; floor</label>
                                <input class="form-control @error('shipping_address') is-invalid @enderror" id="shipping_address" name="shipping_address" value="{{ old('shipping_address') }}" autocomplete="street-address" required>
                                @error('shipping_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="delivery_city">City or area</label>
                                <input class="form-control @error('delivery_city') is-invalid @enderror" id="delivery_city" name="delivery_city" value="{{ old('delivery_city') }}" autocomplete="address-level2" required>
                                @error('delivery_city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div>
                                <label class="form-label" for="delivery_notes">Delivery notes <span class="text-muted">(optional)</span></label>
                                <textarea class="form-control @error('delivery_notes') is-invalid @enderror" id="delivery_notes" name="delivery_notes" rows="2">{{ old('delivery_notes') }}</textarea>
                                @error('delivery_notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="mb-4"><label class="form-label">Payment method</label><select class="form-select" name="payment_method" required><option value="">Choose payment method</option><option value="cash">Cash on delivery</option><option value="card">Card on pickup</option></select></div>
                        <button class="btn btn-coffee w-100" type="submit" @disabled($cartItems->isEmpty())>Confirm &amp; place order <i class="bi bi-lock ms-1"></i></button>
                    </form>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="checkout-card">
                    <h4>Order summary</h4>
                    @forelse($cartItems as $item)
                    <div class="checkout-item"><span>{{ $item->product->name }} <span class="text-muted">× {{ $item->quantity }}</span></span><strong>${{ number_format($item->product->price*$item->quantity,2) }}</strong></div>
                    @empty<div class="text-muted py-4">Your cart is empty.</div>@endforelse
                    <div class="checkout-total"><span>Total</span><span style="color:var(--coffee-accent);">${{ number_format($totalPrice,2) }}</span></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
