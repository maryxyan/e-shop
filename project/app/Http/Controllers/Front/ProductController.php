<?php

namespace App\Http\Controllers\Front;

use App\Shop\Products\Product;
use App\Shop\Products\Repositories\Interfaces\ProductRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Shop\Products\Transformations\ProductTransformable;

class ProductController extends Controller
{
    use ProductTransformable;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepo;

    /**
     * ProductController constructor.
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepo = $productRepository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function search()
    {
        $products = $this->productRepo->searchProductPaginated(request()->input('q'));

        $products->getCollection()->transform(function ($product) {
            return $this->transformProduct($product);
        });

        return view('front.products.product-search', [
            'products' => $products
        ]);
    }

    /**
     * Get the product
     *
     * @param string $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(string $slug)
    {
        $product = $this->productRepo->findProductBySlug(['slug' => $slug]);
        $product = $this->transformProduct($product);
        $images = $product->images;
        $category = $product->categories->first();
        $productAttributes = $product->attributes;

        // Get related products from the same category
        $relatedProducts = collect([]);
        if ($category) {
            $relatedProducts = $category->products()
                ->with(['images', 'brand'])
                ->where('products.status', 1)
                ->where('products.id', '!=', $product->id)
                ->limit(4)
                ->get()
                ->map(function ($item) {
                    return $this->transformProduct($item);
                });
        }

        return view('front.products.product', compact(
            'product',
            'images',
            'productAttributes',
            'category',
            'relatedProducts'
        ));
    }
}
