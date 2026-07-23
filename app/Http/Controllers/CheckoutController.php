<?php

namespace App\Http\Controllers;

use App\Mail\OrderReceiptMail;
use App\Models\ActivityLog;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    public function showCheckout()
    {
        $cartItems = Cart::with('product')->where('user_id', auth()->id())->get();
        $totalPrice = $cartItems->sum(fn ($item) => $item->quantity * $item->product->price);

        return view('checkout', compact('cartItems', 'totalPrice'));
    }

    public function processCheckout(Request $request)
    {
        $validated = $request->validate([
            'phone_number' => ['required', 'string', 'min:7', 'max:25', 'regex:/^[0-9+()\s.-]+$/'],
            'shipping_address' => 'required|string|min:5|max:255',
            'delivery_city' => 'required|string|min:2|max:100',
            'delivery_notes' => 'nullable|string|max:500',
            'payment_method' => 'required|in:cash,card',
        ], [
            'phone_number.regex' => 'Enter a valid phone number.',
            'shipping_address.required' => 'Enter your street, building, and floor details.',
            'delivery_city.required' => 'Enter your city or area.',
        ]);

        try {
            $cartItems = Cart::with('product')->where('user_id', auth()->id())->get();

            if ($cartItems->isEmpty()) {
                return back()->with('error', 'Your cart is empty.');
            }

            $order = DB::transaction(function () use ($cartItems, $validated) {
                $order = Order::create([
                    'user_id' => auth()->id(),
                    'phone_number' => $validated['phone_number'],
                    'shipping_address' => $validated['shipping_address'],
                    'delivery_city' => $validated['delivery_city'],
                    'delivery_notes' => $validated['delivery_notes'] ?? null,
                    'payment_method' => $validated['payment_method'],
                    'status' => 'confirmed',
                ]);

                $order->update([
                    'receipt_number' => 'CS-'.now()->format('Ymd').'-'.str_pad((string) $order->id, 6, '0', STR_PAD_LEFT),
                ]);

                foreach ($cartItems as $item) {
                    $order->items()->create([
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity,
                        'price' => $item->product->price,
                    ]);
                }

                Cart::where('user_id', auth()->id())->delete();

                return $order->load(['user', 'items.product']);
            });

            ActivityLog::record('order.placed', $order->receipt_number.' was placed', $order, [
                'items' => $cartItems->sum('quantity'),
                'total' => $cartItems->sum(fn ($item) => $item->product->price * $item->quantity),
            ]);

            $emailSent = false;
            $emailDeliveryConfigured = ! in_array(config('mail.default'), ['log', 'array'], true);
            try {
                Mail::to($order->user->email)->send(new OrderReceiptMail($order));
                $order->update(['confirmation_email_sent_at' => now()]);
                $emailSent = $emailDeliveryConfigured;
            } catch (\Throwable $mailException) {
                Log::error('Order confirmation email failed.', [
                    'order_id' => $order->id,
                    'message' => $mailException->getMessage(),
                ]);
            }

            return redirect()
                ->route('orders.receipt.show', $order)
                ->with('order_confirmed', true)
                ->with('email_sent', $emailSent)
                ->with('email_delivery_configured', $emailDeliveryConfigured);
        } catch (\Throwable $exception) {
            Log::error('Checkout failed.', [
                'user_id' => auth()->id(),
                'message' => $exception->getMessage(),
            ]);

            return redirect()
                ->route('checkout.index')
                ->withInput()
                ->with('error', 'We could not place your order. Please try again.');
        }
    }

    public function showReceipt(Order $order)
    {
        $this->authorizeOrder($order);

        return view('orders.receipt', [
            'order' => $order->load(['user', 'items.product']),
        ]);
    }

    public function downloadReceipt(Order $order)
    {
        $this->authorizeOrder($order);
        $order->load(['user', 'items.product']);

        return response(view('orders.receipt-download', compact('order')))
            ->header('Content-Type', 'text/html; charset=UTF-8')
            ->header(
                'Content-Disposition',
                'attachment; filename="receipt-'.strtolower($order->receipt_number).'.html"'
            );
    }

    private function authorizeOrder(Order $order): void
    {
        abort_unless((int) $order->user_id === (int) auth()->id(), 403);
    }
}
