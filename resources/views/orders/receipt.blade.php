@extends('layout')

@section('title', 'Order confirmed — CoffeeShop')

@section('styles')
<style>
    .receipt-page{min-height:80vh;padding:3.5rem 0 5rem;background:var(--warm-gray)}
    .confirmation-wrap{max-width:880px;margin:auto}
    .confirmation-head{text-align:center;margin-bottom:2rem}
    .confirmation-icon{width:68px;height:68px;margin:0 auto 1rem;border-radius:50%;display:grid;place-items:center;background:#e8f5ec;color:#287943;font-size:2rem}
    .confirmation-head h1{font:700 clamp(2rem,5vw,3rem) 'Playfair Display',serif;color:var(--coffee-dark)}
    .confirmation-head p{color:#786b61}
    .receipt-card{background:#fff;border:1px solid #eadfd3;border-radius:24px;box-shadow:0 18px 60px rgba(52,31,18,.09);overflow:hidden}
    .receipt-top{display:flex;justify-content:space-between;gap:1.5rem;padding:2rem;border-bottom:1px solid #eee4da}
    .receipt-number{font-weight:800;letter-spacing:.05em;color:var(--coffee-dark)}
    .status-chip{display:inline-flex;align-items:center;gap:.45rem;padding:.45rem .8rem;border-radius:999px;background:#e8f5ec;color:#287943;font-size:.78rem;font-weight:800;text-transform:uppercase}
    .receipt-details{display:grid;grid-template-columns:repeat(2,1fr);gap:1.25rem;padding:1.5rem 2rem;background:#fdfaf6}
    .detail-label{font-size:.68rem;text-transform:uppercase;letter-spacing:.12em;color:#9a8879;font-weight:800;margin-bottom:.35rem}
    .detail-value{font-size:.9rem;color:#38291f}
    .receipt-items{padding:1rem 2rem 1.5rem}
    .receipt-row{display:grid;grid-template-columns:1fr 70px 110px;gap:1rem;align-items:center;padding:1rem 0;border-bottom:1px solid #f1e9e1}
    .item-name{font-weight:700;color:#302118}.item-meta{font-size:.8rem;color:#988678}
    .receipt-total{display:flex;justify-content:flex-end;gap:3rem;padding:1.5rem 2rem;font:700 1.25rem 'Playfair Display',serif}
    .receipt-total strong{color:var(--coffee-accent)}
    .receipt-actions{display:flex;flex-wrap:wrap;justify-content:center;gap:.75rem;margin-top:1.4rem}
    .email-note{border-radius:16px;padding:1rem 1.2rem;margin-bottom:1.2rem;font-size:.9rem}
    .email-note.success{background:#e8f5ec;color:#236b3a}.email-note.info{background:#fff5df;color:#765018}
    @media(max-width:700px){.receipt-page{padding:2rem 0 3rem}.confirmation-head{margin-bottom:1.25rem}.receipt-details{grid-template-columns:1fr;padding:1.15rem}.receipt-top{flex-direction:column;padding:1.15rem}.receipt-items{padding:.5rem 1.15rem 1rem}.receipt-row{grid-template-columns:1fr auto;gap:.65rem}.receipt-row .item-qty{display:none}.receipt-total{justify-content:space-between;gap:1rem;padding:1.15rem}.receipt-card{border-radius:18px}.receipt-actions{display:grid;grid-template-columns:1fr}.receipt-actions .btn{width:100%}.email-note{font-size:.82rem}}
    @media print{header,footer,.receipt-actions,.email-note{display:none!important}.receipt-page{padding:0;background:#fff}.receipt-card{box-shadow:none;border:0}}
</style>
@endsection

@section('content')
<main class="receipt-page">
    <div class="container confirmation-wrap">
        <div class="confirmation-head">
            <div class="confirmation-icon"><i class="bi bi-check-lg"></i></div>
            <span class="section-label">Order confirmed</span>
            <h1>Thank you, {{ $order->user->name }}.</h1>
            <p>Your order is confirmed and your receipt is ready.</p>
        </div>

        @if(session('order_confirmed'))
            @if(session('email_sent'))
                <div class="email-note success"><i class="bi bi-envelope-check me-2"></i>A confirmation receipt was sent to <strong>{{ $order->user->email }}</strong>.</div>
            @elseif(!session('email_delivery_configured'))
                <div class="email-note info"><i class="bi bi-info-circle me-2"></i>Your receipt is ready. Email delivery is in local mode, so use the download button below until SMTP is configured.</div>
            @else
                <div class="email-note info"><i class="bi bi-info-circle me-2"></i>Your order is safe, but the email could not be sent. You can download the receipt below.</div>
            @endif
        @endif

        <article class="receipt-card">
            <div class="receipt-top">
                <div><div class="detail-label">Receipt</div><div class="receipt-number">{{ $order->receipt_number }}</div></div>
                <div class="status-chip"><i class="bi bi-check-circle-fill"></i>{{ $order->status }}</div>
            </div>
            <div class="receipt-details">
                <div><div class="detail-label">Date</div><div class="detail-value">{{ $order->created_at->format('F j, Y · g:i A') }}</div></div>
                <div><div class="detail-label">Payment</div><div class="detail-value">{{ $order->payment_method === 'cash' ? 'Cash on delivery' : 'Card on pickup' }}</div></div>
                <div><div class="detail-label">Contact number</div><div class="detail-value">{{ $order->phone_number ?: 'Not provided' }}</div></div>
                <div>
                    <div class="detail-label">Deliver to</div>
                    <div class="detail-value">{{ $order->shipping_address }}@if($order->delivery_city), {{ $order->delivery_city }}@endif</div>
                    @if($order->delivery_notes)<div class="item-meta mt-1">Note: {{ $order->delivery_notes }}</div>@endif
                </div>
            </div>
            <div class="receipt-items">
                @foreach($order->items as $item)
                    <div class="receipt-row">
                        <div><div class="item-name">{{ $item->product->name }}</div><div class="item-meta">${{ number_format($item->price, 2) }} each</div></div>
                        <div class="item-qty">× {{ $item->quantity }}</div>
                        <strong class="text-end">${{ number_format($item->price * $item->quantity, 2) }}</strong>
                    </div>
                @endforeach
            </div>
            <div class="receipt-total"><span>Total</span><strong>${{ number_format($order->total(), 2) }}</strong></div>
        </article>

        <div class="receipt-actions">
            <a class="btn btn-coffee" href="{{ route('orders.receipt.download', $order) }}"><i class="bi bi-download me-2"></i>Download receipt</a>
            <button class="btn btn-outline-dark rounded-pill px-4" type="button" onclick="window.print()"><i class="bi bi-printer me-2"></i>Print</button>
            <a class="btn btn-outline-dark rounded-pill px-4" href="{{ route('products') }}">Back to menu</a>
        </div>
    </div>
</main>
@endsection
