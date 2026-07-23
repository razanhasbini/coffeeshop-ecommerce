<!doctype html>
<html lang="en">
<body style="margin:0;background:#f5efe8;font-family:Arial,sans-serif;color:#302118">
<div style="max-width:640px;margin:0 auto;padding:32px 16px">
    <div style="background:#2b190f;color:#fff;padding:24px 28px;border-radius:18px 18px 0 0">
        <div style="color:#e3a44c;font-size:13px;font-weight:bold;letter-spacing:1px;text-transform:uppercase">CoffeeShop</div>
        <h1 style="font-family:Georgia,serif;margin:8px 0 0">Your order is confirmed</h1>
    </div>
    <div style="background:#fff;padding:28px;border-radius:0 0 18px 18px">
        <p>Hi {{ $order->user->name }},</p>
        <p>Thanks for your order. Here is your itemized receipt.</p>
        <p style="background:#faf5ef;padding:14px;border-radius:10px"><strong>{{ $order->receipt_number }}</strong><br><span style="color:#806f62">{{ $order->created_at->format('F j, Y · g:i A') }}</span></p>
        @foreach($order->items as $item)
            <div style="display:flex;justify-content:space-between;gap:16px;padding:12px 0;border-bottom:1px solid #eee4da">
                <span><strong>{{ $item->product->name }}</strong><br><small style="color:#806f62">{{ $item->quantity }} × ${{ number_format($item->price, 2) }}</small></span>
                <strong>${{ number_format($item->price * $item->quantity, 2) }}</strong>
            </div>
        @endforeach
        <p style="font-size:20px;text-align:right"><strong>Total: ${{ number_format($order->total(), 2) }}</strong></p>
        <p style="text-align:center;margin:28px 0">
            <a href="{{ route('orders.receipt.show', $order) }}" style="display:inline-block;background:#c9842f;color:#fff;text-decoration:none;padding:13px 22px;border-radius:999px;font-weight:bold">View &amp; download receipt</a>
        </p>
        <p style="color:#806f62;font-size:13px">
            Phone: {{ $order->phone_number ?: 'Not provided' }}<br>
            Delivery to: {{ $order->shipping_address }}@if($order->delivery_city), {{ $order->delivery_city }}@endif
            @if($order->delivery_notes)<br>Delivery note: {{ $order->delivery_notes }}@endif
        </p>
    </div>
</div>
</body>
</html>
