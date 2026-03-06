<?php

namespace App\Shop\Orders\Repositories\Interfaces;

use Jsdecena\Baserepo\BaseRepositoryInterface;
use App\Shop\Orders\Order;
use App\Shop\Products\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface OrderRepositoryInterface extends BaseRepositoryInterface
{
    public function createOrder(array $data) : Order;

    public function updateOrder(array $params) : bool;

    public function findOrderById(int $id) : Order;

    public function listOrders(string $order = 'id', string $sort = 'desc', array $columns = ['*']) : Collection;
    
    public function listOrdersPaginated(string $order = 'id', string $sort = 'desc', int $perPage = 10) : LengthAwarePaginator;

    public function findProducts(Order $order) : Collection;

    public function associateProduct(Product $product, int $quantity = 1, array $data = []);

    public function searchOrder(string $text) : Collection;
    
    public function searchOrderPaginated(string $text, int $perPage = 10) : LengthAwarePaginator;

    public function listOrderedProducts() : Collection;

    public function buildOrderDetails(Collection $items);

    public function getAddresses() : Collection;

    public function getCouriers() : Collection;
}
