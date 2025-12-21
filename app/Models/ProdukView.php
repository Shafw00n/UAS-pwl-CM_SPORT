<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ProductView extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product_views';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'produk_id',
        'view_count',
        'viewed_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'view_count' => 'integer',
        'viewed_at' => 'date',
        'created_at' => 'datetime',
    ];

    /**
     * Relationship: ProductView belongs to Produk
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id', 'produk_id');
    }

    /**
     * Record a view for a product
     *
     * @param int $productId
     * @return void
     */
    public static function recordView($productId)
    {
        $today = Carbon::now()->toDateString();

        $view = static::where('produk_id', $productId)
                     ->where('viewed_at', $today)
                     ->first();

        if ($view) {
            $view->increment('view_count');
        } else {
            static::create([
                'produk_id' => $productId,
                'view_count' => 1,
                'viewed_at' => $today,
                'created_at' => now()
            ]);
        }
    }

    /**
     * Get total views for a product
     *
     * @param int $productId
     * @param int|null $days
     * @return int
     */
    public static function getTotalViews($productId, $days = null)
    {
        $query = static::where('produk_id', $productId);

        if ($days) {
            $dateFrom = Carbon::now()->subDays($days)->toDateString();
            $query->where('viewed_at', '>=', $dateFrom);
        }

        return $query->sum('view_count');
    }

    /**
     * Get trending products
     *
     * @param int $limit
     * @param int $days
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getTrendingProducts($limit = 10, $days = 7)
    {
        $dateFrom = Carbon::now()->subDays($days)->toDateString();

        return static::select('produk_id')
                    ->selectRaw('SUM(view_count) as total_views')
                    ->where('viewed_at', '>=', $dateFrom)
                    ->groupBy('produk_id')
                    ->orderByDesc('total_views')
                    ->limit($limit)
                    ->with('produk')
                    ->get();
    }

    /**
     * Clean old view records (older than X days)
     *
     * @param int $days
     * @return int
     */
    public static function cleanOldRecords($days = 90)
    {
        $dateLimit = Carbon::now()->subDays($days)->toDateString();
        
        return static::where('viewed_at', '<', $dateLimit)->delete();
    }

    /**
     * Scope: Filter by date range
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $from
     * @param string|null $to
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDateRange($query, $from, $to = null)
    {
        $query->where('viewed_at', '>=', $from);

        if ($to) {
            $query->where('viewed_at', '<=', $to);
        }

        return $query;
    }

    /**
     * Scope: Last X days
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $days
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLastDays($query, $days)
    {
        $dateFrom = Carbon::now()->subDays($days)->toDateString();
        return $query->where('viewed_at', '>=', $dateFrom);
    }
}