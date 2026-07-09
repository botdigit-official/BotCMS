<?php

namespace Plugins\ProductCatalog\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Post;

class Product extends Model
{
    protected $table = 'plugin_products';

    protected $fillable = [
        'post_id',
        'sku',
        'price',
        'stock_quantity',
        'is_featured',
    ];

    /**
     * Get the associated Post model (representing the product content type).
     */
    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }
}
