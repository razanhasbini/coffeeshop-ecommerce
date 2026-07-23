<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Receipt {{ $order->receipt_number }}</title>
    <style>
        body{font-family:Arial,sans-serif;color:#2e2119;max-width:760px;margin:40px auto;padding:0 24px}header{display:flex;justify-content:space-between;border-bottom:2px solid #c9842f;padding-bottom:18px}h1{margin:0;font-family:Georgia,serif}.muted{color:#806f62}.meta{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin:28px 0;padding:20px;background:#faf5ef}.label{font-size:11px;text-transform:uppercase;letter-spacing:1px;color:#9a8879}.item{display:grid;grid-template-columns:1fr 80px 110px;padding:14px 0;border-bottom:1px solid #eadfd3}.right{text-align:right}.total{display:flex;justify-content:flex-end;gap:60px;font-size:20px;font-weight:bold;padding:24px 0;color:#9d5c17}
    </style>
</head>
<body>
    <header><div><h1>CoffeeShop</h1><div class="muted">Order receipt</div></div><strong>{{ $order->receipt_number }}</strong></header>
    <div class="meta">
        <div><div class="label">Customer</div>{{ $order->user->name }}<br>{{ $order->user->email }}</div>
        <div><div class="label">Date</div>{{ $order->created_at->format('F j, Y · g:i A') }}</div>
        <div><div class="label">Phone number</div>{{ $order->phone_number ?: 'Not provided' }}</div>
        <div><div class="label">Delivery address</div>{{ $order->shipping_address }}@if($order->delivery_city), {{ $order->delivery_city }}@endif @if($order->delivery_notes)<br><span class="muted">Note: {{ $order->delivery_notes }}</span>@endif</div>
        <div><div class="label">Payment</div>{{ $order->payment_method === 'cash' ? 'Cash on delivery' : 'Card on pickup' }}</div>
    </div>
    @foreach($order->items as $item)
        <div class="item"><div><strong>{{ $item->product->name }}</strong><br><span class="muted">${{ number_format($item->price, 2) }} each</span></div><div>× {{ $item->quantity }}</div><strong class="right">${{ number_format($item->price * $item->quantity, 2) }}</strong></div>
    @endforeach
    <div class="total"><span>Total</span><span>${{ number_format($order->total(), 2) }}</span></div>
    <p class="muted">Thank you for your order. Keep this receipt for your records.</p>
</body>
</html>
