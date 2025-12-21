<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TrendingController extends Controller
{
    /**
     * Display trending products
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function trending(Request $request)
    {
        $period = $request->get('period', 'week'); // week, month, all
        
        // Determine date range based on period
        $dateFrom = $this->getDateFromPeriod($period);
        
        // Main Query using Eloquent
        $query = \App\Models\Produk::select('produk.*')
            ->selectSub(function ($query) use ($dateFrom) {
                $query->from('order_items')
                    ->join('orders', 'order_items.order_id', '=', 'orders.order_id')
                    ->whereColumn('order_items.produk_id', 'produk.produk_id')
                    ->where('orders.status', 'selesai'); // Only completed orders
                
                if ($dateFrom) {
                    $query->where('orders.created_at', '>=', $dateFrom);
                }
                
                $query->selectRaw('COALESCE(SUM(quantity), 0)');
            }, 'sold_period')
            ->selectSub(function ($query) use ($dateFrom) {
                $query->from('produk_views')
                    ->whereColumn('produk_views.produk_id', 'produk.produk_id');
                
                if ($dateFrom) {
                    $query->where('viewed_at', '>=', $dateFrom);
                }
                
                $query->selectRaw('COALESCE(SUM(view_count), 0)');
            }, 'views')
            ->with(['kategori']) // Eager load relationships
            ->where('is_active', true)
            ->where('stok', '>', 0)
            ->whereHas('orderItems', function ($q) use ($dateFrom) {
                $q->whereHas('order', function ($oq) use ($dateFrom) {
                    $oq->where('status', 'selesai');
                    if ($dateFrom) {
                        $oq->where('created_at', '>=', $dateFrom);
                    }
                });
            })
            ->orderByDesc('sold_period')
            ->orderByDesc('views');
        
        // Paginate results
        $trendingProduk = $query->paginate(24)->withQueryString();
        
        // Calculate statistics
        $stats = $this->calculateStats($period);
        
        return view('trending.index', [
            'trendingProduk' => $trendingProduk,
            'totalTrending' => $trendingProduk->total(),
            'totalViews' => $stats['totalViews'],
            'avgRating' => $stats['avgRating'],
            'currentPeriod' => $period
        ]);
    }
    
    /**
     * Get date from based on period
     *
     * @param string $period
     * @return string|null
     */
    private function getDateFromPeriod($period)
    {
        switch ($period) {
            case 'week':
                return Carbon::now()->subDays(7)->toDateString();
            case 'month':
                return Carbon::now()->subDays(30)->toDateString();
            case 'all':
            default:
                return null; // No date filter for 'all'
        }
    }
    
    /**
     * Calculate trending statistics
     *
     * @param string $period
     * @return array
     */
    private function calculateStats($period)
    {
        $dateFrom = $this->getDateFromPeriod($period);
        
        // Total views
        $viewsQuery = DB::table('produk_views');
        
        if ($dateFrom) {
            $viewsQuery->where('viewed_at', '>=', $dateFrom);
        }
        
        $totalViews = $viewsQuery->sum('view_count');
        
        // Average rating of TRENDING products (those that have sales in the period)
        $avgRatingQuery = DB::table('produk')
            ->join('order_items', 'produk.produk_id', '=', 'order_items.produk_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.order_id')
            ->where('produk.is_active', true)
            ->where('produk.stok', '>', 0)
            ->where('orders.status', 'selesai');

        if ($dateFrom) {
            $avgRatingQuery->where('orders.created_at', '>=', $dateFrom);
        }

        // Calculate average of the rating column for these specific products
        // Distinct because a product might appear multiple times in order_items
        $productIds = $avgRatingQuery->distinct()->pluck('produk.produk_id');
        $avgRating = DB::table('produk')->whereIn('produk_id', $productIds)->avg('rating') ?? 0;
        
        return [
            'totalViews' => $totalViews,
            'avgRating' => round($avgRating, 1)
        ];
    }
    
    /**
     * Record product view (AJAX)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function recordView(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produk,produk_id'
        ]);
        
        $produkId = $request->produk_id;
        $today = Carbon::now()->toDateString();
        
        // Check if view already exists for today
        $existingView = DB::table('produk_views')
            ->where('produk_id', $produkId)
            ->where('viewed_at', $today)
            ->first();
        
        if ($existingView) {
            // Increment view count
            DB::table('produk_views')
                ->where('produk_id', $produkId)
                ->where('viewed_at', $today)
                ->increment('view_count');
        } else {
            // Create new view record
            DB::table('produk_views')->insert([
                'produk_id' => $produkId,
                'view_count' => 1,
                'viewed_at' => $today,
                'created_at' => now()
            ]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'View recorded'
        ]);
    }
}