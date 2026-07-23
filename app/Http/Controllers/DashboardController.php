<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\FeaturedSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\ActivityLog;

class DashboardController extends Controller
{
    public function index()
    {
        // Fetching the last 7 days of sales
        $salesData = $this->getSalesData();

        // Fetching product popularity data (most ordered products)
        $productData = $this->getProductPopularityData();

        // Fetch all orders for display
        $orders = Order::with(['user', 'items.product'])->latest()->get();
        $products = Product::latest()->get();
        // A dashboard GET must remain read-only. Persist these defaults only
        // when an administrator explicitly submits the featured-section form.
        $featuredSection = FeaturedSection::first()
            ?? new FeaturedSection(FeaturedSection::defaults());
        $users = User::withCount('orders')->latest()->get();
        $activityLogs = ActivityLog::with('actor')->latest()->limit(100)->get();
        $userStats = [
            'total' => $users->count(),
            'customers' => $users->where('role', 'user')->count(),
            'managers' => $users->where('role', 'manager')->count(),
            'active' => $users->where('is_active', true)->count(),
        ];

        return view('dashboard', compact('orders', 'salesData', 'productData', 'products', 'featuredSection', 'users', 'activityLogs', 'userStats'));
    }

    public function updateFeatured(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string', 'max:180'],
            'background_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'title_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'ticker_text' => ['required', 'string', 'max:160'],
            'ticker_text_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'ticker_background_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'ticker_speed' => ['required', 'integer', 'min:6', 'max:60'],
            'product_one_id' => ['nullable', 'distinct', 'exists:products,id'],
            'product_two_id' => ['nullable', 'distinct', 'exists:products,id'],
            'product_three_id' => ['nullable', 'distinct', 'exists:products,id'],
        ]);

        FeaturedSection::firstOrCreate([], FeaturedSection::defaults())->update($data);
        ActivityLog::record('homepage.featured_updated', 'Homepage featured section settings were updated');

        return back()->with('success', 'Homepage featured section updated.');
    }

    private function getSalesData()
    {
        // Get sales data for the last 7 days
        $sales = DB::table('orders')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->selectRaw('DATE(orders.created_at) as date, SUM(order_items.price * order_items.quantity) as total')
            ->where('orders.created_at', '>=', now()->subDays(7))
            ->groupByRaw('DATE(orders.created_at)')
            ->orderByRaw('DATE(orders.created_at)')
            ->get();

        $dates = $sales->pluck('date');
        $totals = $sales->pluck('total');

        return [
            'dates' => $dates->all(),
            'totals' => $totals->map(fn ($total) => (float) $total)->all()
        ];
    }

    private function getProductPopularityData()
    {
        // Get product popularity data (most sold products)
        $products = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select('products.name', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        $names = $products->pluck('name');
        $quantities = $products->pluck('total_sold');

        return [
            'names' => $names->all(),
            'quantities' => $quantities->map(fn ($quantity) => (int) $quantity)->all()
        ];
    }
}
