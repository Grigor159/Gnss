<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = ['name', 'description', 'text'];

    /**
     * guarded
     *
     * @var array
     */
    protected $guarded = ['unique_code'];

    /**
     * boot
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if (!$model->unique_code) {
                do {
                    $model->unique_code = time() . mt_rand(1000, 9999); // Generates a unique integer code
                } while (static::where('unique_code', $model->unique_code)->exists());
            }
        });
    }

    /**
     * Get all of the productTraslations for the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function translations(): HasMany
    {
        return $this->hasMany(ProductTranslations::class);
    }

    /**
     * The category that belong to the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function category(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    /**
     * The subcategory that belong to the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function subcategory(): BelongsToMany
    {
        return $this->belongsToMany(Subcategory::class);
    }

    /**
     * Get all of the images for the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProductImages::class);
    }

    /**
     * Get all of the links for the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function links(): HasMany
    {
        return $this->hasMany(Link::class);
    }

    /**
     * Get all of the files for the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function files(): HasMany
    {
        return $this->hasMany(File::class);
    }

    /**
     * Get all of the order for the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function order(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get all of the inquiries for the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function inquiries(): HasMany
    {
        return $this->hasMany(Inquiry::class);
    }

    /**
     * Get all of the opportunity for the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function opportunity(): HasMany
    {
        return $this->hasMany(Opportunity::class);
    }
}
