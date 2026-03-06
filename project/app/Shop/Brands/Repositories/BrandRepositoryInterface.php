<?php

namespace App\Shop\Brands\Repositories;

use Jsdecena\Baserepo\BaseRepositoryInterface;
use App\Shop\Brands\Brand;
use App\Shop\Products\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface BrandRepositoryInterface extends BaseRepositoryInterface
{
    public function createBrand(array $data): Brand;

    public function findBrandById(int $id) : Brand;

    public function updateBrand(array $data) : bool;

    public function deleteBrand() : bool;

    public function listBrands($columns = array('*'), string $orderBy = 'id', string $sortBy = 'asc') : Collection;

    public function listBrandsPaginated($columns = array('*'), string $orderBy = 'id', string $sortBy = 'asc', int $perPage = 10) : LengthAwarePaginator;

    public function saveProduct(Product $product);
}
