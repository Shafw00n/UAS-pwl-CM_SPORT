<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Kategori extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'kategori';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'kategori_id';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_kategori',
        'slug',
        'deskripsi',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Boot method to handle model events
     */
    protected static function boot()
    {
        parent::boot();

        // Automatically generate slug when creating
        static::creating(function ($kategori) {
            if (empty($kategori->slug)) {
                $kategori->slug = Str::slug($kategori->nama_kategori);
            }
        });

        // Update slug when updating nama_kategori
        static::updating(function ($kategori) {
            if ($kategori->isDirty('nama_kategori')) {
                $kategori->slug = Str::slug($kategori->nama_kategori);
            }
        });
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Relationship: Kategori has many Produk
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function produk()
    {
        return $this->hasMany(Produk::class, 'kategori_id', 'kategori_id');
    }

    /**
     * Relationship: Get active products only
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function produkAktif()
    {
        return $this->hasMany(Produk::class, 'kategori_id', 'kategori_id')
                    ->where('is_active', true)
                    ->where('stok', '>', 0);
    }

    /**
     * Scope: Get only categories with active products
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithActiveProducts($query)
    {
        return $query->whereHas('produk', function ($q) {
            $q->where('is_active', true)->where('stok', '>', 0);
        });
    }

    /**
     * Scope: Search by name or description
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('nama_kategori', 'LIKE', "%{$search}%")
                     ->orWhere('deskripsi', 'LIKE', "%{$search}%");
    }

    /**
     * Get count of products in this category
     *
     * @return int
     */
    public function getProductCountAttribute()
    {
        return $this->produk()->count();
    }

    /**
     * Get count of active products in this category
     *
     * @return int
     */
    public function getActiveProductCountAttribute()
    {
        return $this->produkAktif()->count();
    }

    /**
     * Get the icon URL
     *
     * @return string|null
     */
    public function getIconUrlAttribute()
    {
        if ($this->icon) {
            return asset('storage/' . $this->icon);
        }
        return asset('images/default-category-icon.svg');
    }

    /**
     * Get category URL
     *
     * @return string
     */
    public function getUrlAttribute()
    {
        return route('produk.kategori', $this->slug);
    }

    /**
     * Check if category has products
     *
     * @return bool
     */
    public function hasProducts()
    {
        return $this->produk()->exists();
    }

    /**
     * Check if category has active products
     *
     * @return bool
     */
    public function hasActiveProducts()
    {
        return $this->produkAktif()->exists();
    }

    /**
     * Get featured products from this category
     *
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getFeaturedProducts($limit = 8)
    {
        return $this->produk()
                    ->where('is_featured', true)
                    ->where('is_active', true)
                    ->where('stok', '>', 0)
                    ->orderBy('created_at', 'DESC')
                    ->limit($limit)
                    ->get();
    }

    /**
     * Get new products from this category
     *
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getNewProducts($limit = 8)
    {
        return $this->produk()
                    ->where('is_new', true)
                    ->where('is_active', true)
                    ->where('stok', '>', 0)
                    ->orderBy('created_at', 'DESC')
                    ->limit($limit)
                    ->get();
    }

    /**
     * Get best selling products from this category
     *
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getBestSellingProducts($limit = 8)
    {
        return $this->produk()
                    ->where('is_active', true)
                    ->where('stok', '>', 0)
                    ->orderBy('terjual', 'DESC')
                    ->limit($limit)
                    ->get();
    }

    /**
     * Get discounted products from this category
     *
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getDiscountedProducts($limit = 8)
    {
        return $this->produk()
                    ->where('discount', '>', 0)
                    ->where('is_active', true)
                    ->where('stok', '>', 0)
                    ->orderBy('discount', 'DESC')
                    ->limit($limit)
                    ->get();
    }

    /**
     * Get price range for products in this category
     *
     * @return array
     */
    public function getPriceRange()
    {
        $minPrice = $this->produk()
                         ->where('is_active', true)
                         ->where('stok', '>', 0)
                         ->min('harga');

        $maxPrice = $this->produk()
                         ->where('is_active', true)
                         ->where('stok', '>', 0)
                         ->max('harga');

        return [
            'min' => $minPrice ?? 0,
            'max' => $maxPrice ?? 0
        ];
    }

    /**
     * Get average rating for products in this category
     *
     * @return float
     */
    public function getAverageRating()
    {
        return $this->produk()
                    ->where('is_active', true)
                    ->avg('rating') ?? 0;
    }

    /**
     * Static method: Get all categories with product counts
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getAllWithCounts()
    {
        return static::withCount(['produk', 'produkAktif'])
                     ->orderBy('nama_kategori')
                     ->get();
    }

    /**
     * Static method: Get popular categories (with most products)
     *
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getPopular($limit = 5)
    {
        return static::withCount('produkAktif')
                     ->having('produk_aktif_count', '>', 0)
                     ->orderBy('produk_aktif_count', 'DESC')
                     ->limit($limit)
                     ->get();
    }

    /**
     * Convert to array for JSON response
     *
     * @return array
     */
    public function toArray()
    {
        $array = parent::toArray();
        
        // Add computed attributes
        $array['product_count'] = $this->product_count;
        $array['active_product_count'] = $this->active_product_count;
        $array['icon_url'] = $this->icon_url;
        $array['url'] = $this->url;
        
        return $array;
    }
}
