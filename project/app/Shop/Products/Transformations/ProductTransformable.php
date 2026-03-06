<?php

namespace App\Shop\Products\Transformations;

use App\Shop\Products\Product;

trait ProductTransformable
{
    /**
     * Transform the product
     *
     * @param Product $product
     * @return Product
     */
    protected function transformProduct(Product $product)
    {
        $product->cover = $this->rewriteExitsImagePath($product->cover);
        $product->weight = (float) $product->weight;
        $product->id = (int) $product->id;
        $product->brand_id = (int) $product->brand_id;
        
        return $product;
    }

    /**
     * it checks the image path which registered to DB and it exists whether on storage. 
     * if exist, return original path add asset. 
     * if not exist, return path for No Data.png
     * 
     * @param string $path
     * @return string $existsPath
     */
    private function rewriteExitsImagePath($path)
    {
        if ($path == null) {
            return $path;
        }
        // Check using Laravel's storage path - works on all systems
        if (file_exists(storage_path("app/public/" . $path))) {
            return asset("storage/$path");
        }
        // Also check if it's already a valid URL or asset path
        if (strpos($path, 'http') === 0 || strpos($path, 'asset') === 0) {
            return $path;
        }
        return asset("images/NoData.png");
    }
}
