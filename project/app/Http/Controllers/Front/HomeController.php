<?php

namespace App\Http\Controllers\Front;

use App\Shop\Categories\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Shop\Products\Repositories\Interfaces\ProductRepositoryInterface;
use App\Shop\Products\Product;
use App\Shop\Products\Transformations\ProductTransformable;

class HomeController
{
    use ProductTransformable;

    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepo;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepo;

    /**
     * HomeController constructor.
     * @param CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(CategoryRepositoryInterface $categoryRepository, ProductRepositoryInterface $productRepository)
    {
        $this->categoryRepo = $categoryRepository;
        $this->productRepo = $productRepository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $cat1 = $this->categoryRepo->findCategoryById(2);
        $cat1->products = $cat1->products->map(function (Product $item) {
            return $this->transformProduct($item);
        });

        $cat2 = $this->categoryRepo->findCategoryById(3);
        $cat2->products = $cat2->products->map(function (Product $item) {
            return $this->transformProduct($item);
        });

        $recentProducts = $this->productRepo->listProducts('created_at', 'desc')->take(8)->map(function (Product $item) {
            return $this->transformProduct($item);
        });

        return view('front.index', compact('cat1', 'cat2', 'recentProducts'));
    }
}
