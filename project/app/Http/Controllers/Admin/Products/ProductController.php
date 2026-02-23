<?php

namespace App\Http\Controllers\Admin\Products;

use App\Shop\Attributes\Repositories\AttributeRepositoryInterface;
use App\Shop\AttributeValues\Repositories\AttributeValueRepositoryInterface;
use App\Shop\Brands\Repositories\BrandRepositoryInterface;
use App\Shop\Categories\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Shop\Categories\Category;
use App\Shop\ProductAttributes\ProductAttribute;
use App\Shop\Products\Exceptions\ProductUpdateErrorException;
use App\Shop\Products\Product;
use App\Shop\Products\Repositories\Interfaces\ProductRepositoryInterface;
use App\Shop\Products\Repositories\ProductRepository;
use App\Shop\Products\Requests\CreateProductRequest;
use App\Shop\Products\Requests\UpdateProductRequest;
use App\Http\Controllers\Controller;
use App\Shop\Products\Transformations\ProductTransformable;
use App\Shop\Tools\UploadableTrait;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    use ProductTransformable, UploadableTrait;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepo;

    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepo;

    /**
     * @var AttributeRepositoryInterface
     */
    private $attributeRepo;

    /**
     * @var AttributeValueRepositoryInterface
     */
    private $attributeValueRepository;

    /**
     * @var ProductAttribute
     */
    private $productAttribute;

    /**
     * @var BrandRepositoryInterface
     */
    private $brandRepo;

    /**
     * ProductController constructor.
     *
     * @param ProductRepositoryInterface $productRepository
     * @param CategoryRepositoryInterface $categoryRepository
     * @param AttributeRepositoryInterface $attributeRepository
     * @param AttributeValueRepositoryInterface $attributeValueRepository
     * @param ProductAttribute $productAttribute
     * @param BrandRepositoryInterface $brandRepository
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        CategoryRepositoryInterface $categoryRepository,
        AttributeRepositoryInterface $attributeRepository,
        AttributeValueRepositoryInterface $attributeValueRepository,
        ProductAttribute $productAttribute,
        BrandRepositoryInterface $brandRepository
    ) {
        $this->productRepo = $productRepository;
        $this->categoryRepo = $categoryRepository;
        $this->attributeRepo = $attributeRepository;
        $this->attributeValueRepository = $attributeValueRepository;
        $this->productAttribute = $productAttribute;
        $this->brandRepo = $brandRepository;

        $this->middleware(['permission:create-product, guard:employee'], ['only' => ['create', 'store']]);
        $this->middleware(['permission:update-product, guard:employee'], ['only' => ['edit', 'update']]);
        $this->middleware(['permission:delete-product, guard:employee'], ['only' => ['destroy']]);
        $this->middleware(['permission:view-product, guard:employee'], ['only' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        $list = $this->productRepo->listProducts('id');

        if (request()->has('q') && request()->input('q') != '') {
            $list = $this->productRepo->searchProduct(request()->input('q'));
        }

        $products = $list->map(function (Product $item) {
            return $this->transformProduct($item);
        })->all();

        return view('admin.products.list', [
            'products' => $this->productRepo->paginateArrayResults($products, 25)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create()
    {
        $categories = $this->categoryRepo->listCategories('name', 'asc')->toTree();

        return view('admin.products.create', [
            'categories' => $categories,
            'brands' => $this->brandRepo->listBrands(['*'], 'name', 'asc'),
            'default_weight' => env('SHOP_WEIGHT'),
            'weight_units' => Product::MASS_UNIT,
            'product' => new Product
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateProductRequest $request
     * @return RedirectResponse
     */
    public function store(CreateProductRequest $request)
    {
        $data = $request->except('_token', '_method');
        $data['slug'] = Str::slug($request->input('name'));

        if ($request->hasFile('cover') && $request->file('cover') instanceof UploadedFile) {
            $data['cover'] = $this->productRepo->saveCoverImage($request->file('cover'));
        }

        $product = $this->productRepo->createProduct($data);

        $productRepo = new ProductRepository($product);

        if ($request->hasFile('image')) {
            $productRepo->saveProductImages(collect($request->file('image')));
        }

        if ($request->has('categories')) {
            $productRepo->syncCategories($request->input('categories'));
        } else {
            $productRepo->detachCategories();
        }

        return redirect()->route('admin.products.edit', $product->id)->with('message', 'Create successful');
    }

    /**
     * Display the specified resource.
     *
     * @param int|string $id
     * @return Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show($id)
    {
        $product = $this->productRepo->findProductById((int)$id);
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|RedirectResponse
     */
    public function edit(int $id)
    {
        $product = $this->productRepo->findProductById($id);
        $productAttributes = $product->attributes()->get();

        $qty = $productAttributes->map(function ($item) {
            return $item->quantity;
        })->sum();

        if (request()->has('delete') && request()->has('pa')) {
            $pa = $productAttributes->where('id', request()->input('pa'))->first();
            $pa->attributesValues()->detach();
            $pa->delete();

            request()->session()->flash('message', 'Delete successful');
            return redirect()->route('admin.products.edit', [$product->id, 'combination' => 1]);
        }

        $categories = $this->categoryRepo->listCategories('name', 'asc')->toTree();
	
        return view('admin.products.edit', [
            'product' => $product,
            'images' => $product->images()->get(['src']),
            'categories' => $categories,
            'selectedIds' => $product->categories()->pluck('category_id')->all(),
            'attributes' => $this->attributeRepo->listAttributes(),
            'productAttributes' => $productAttributes,
            'qty' => $qty,
            'brands' => $this->brandRepo->listBrands(['*'], 'name', 'asc'),
            'weight' => $product->weight,
            'default_weight' => $product->mass_unit,
            'weight_units' => Product::MASS_UNIT
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateProductRequest $request
     * @param int $id
     *
     * @return RedirectResponse
     * @throws ProductUpdateErrorException
     */
    public function update(UpdateProductRequest $request, int $id)
    {
        $product = $this->productRepo->findProductById($id);
        $productRepo = new ProductRepository($product);

        if ($request->has('attributeValue')) {
            $this->saveProductCombinations($request, $product);
            return redirect()->route('admin.products.edit', [$id, 'combination' => 1])
                ->with('message', 'Attribute combination created successful');
        }

        $data = $request->except(
            'categories',
            '_token',
            '_method',
            'default',
            'image',
            'productAttributeQuantity',
            'productAttributePrice',
            'attributeValue',
            'combination'
        );

        $data['slug'] = Str::slug($request->input('name'));

        if ($request->hasFile('cover')) {
            $data['cover'] = $productRepo->saveCoverImage($request->file('cover'));
        }

        if ($request->hasFile('image')) {
            $productRepo->saveProductImages(collect($request->file('image')));
        }

        if ($request->has('categories')) {
            $productRepo->syncCategories($request->input('categories'));
        } else {
            $productRepo->detachCategories();
        }

        $productRepo->updateProduct($data);

        return redirect()->route('admin.products.edit', $id)
            ->with('message', 'Update successful');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @throws Exception
     */
    public function destroy($id): RedirectResponse
    {
        $product = $this->productRepo->findProductById($id);
        $product->categories()->sync([]);
        $productAttr = $product->attributes();

        $productAttr->each(function ($pa) {
            DB::table('attribute_value_product_attribute')->where('product_attribute_id', $pa->id)->delete();
        });

        $productAttr->where('product_id', $product->id)->delete();

        $productRepo = new ProductRepository($product);
        $productRepo->removeProduct();

        return redirect()->route('admin.products.index')->with('message', 'Delete successful');
    }

    /**
     * Show the batch upload form.
     */
    public function batchUpload()
    {
        $previewRows = Product::orderBy('id', 'desc')
            ->take(10)
            ->get()
            ->map(function (Product $product) {
                $firstImage = $product->images()->first();
                $secondImage = $product->images()->skip(1)->first();
                $category = $product->categories()->whereNull('parent_id')->first();
                $subcategory = $product->categories()->whereNotNull('parent_id')->first();

                return [
                    'name' => $product->name,
                    'price' => $product->price,
                    'image_url' => $firstImage ? $firstImage->src : null,
                    'image_url_2' => $secondImage ? $secondImage->src : null,
                    'category' => $category ? $category->name : null,
                    'subcategory' => $subcategory ? $subcategory->name : null,
                    'description' => $product->description,
                    'specificatii_produs' => null,
                ];
            });

        return view('admin.batchupload.show', [
            'previewRows' => $previewRows,
        ]);
    }

    /**
     * Process the batch upload file.
     */
    public function processBatchUpload(Request $request)
    {
        $request->validate([
            'products_file' => 'required|file|mimes:csv,txt,xlsx',
        ]);

        $file = $request->file('products_file');
        $path = $file->getRealPath();

        if (($handle = fopen($path, 'r')) === false) {
            return redirect()->route('admin.products.batch-upload')
                ->withErrors(['products_file' => 'Could not open uploaded file.']);
        }

        $header = fgetcsv($handle, 0, ',');

        if ($header === false) {
            fclose($handle);
            return redirect()->route('admin.products.batch-upload')
                ->withErrors(['products_file' => 'Uploaded file is empty.']);
        }

        $normalizedHeader = array_map(function ($value) {
            return strtolower(trim($value));
        }, $header);

        $requiredColumns = [
            'name',
            'price',
            'image_url',
            'image_url_2',
            'category',
            'subcategory',
            'description',
            'specificatii_produs',
        ];

        $missing = array_diff($requiredColumns, $normalizedHeader);

        if (!empty($missing)) {
            fclose($handle);
            return redirect()->route('admin.products.batch-upload')
                ->withErrors(['products_file' => 'Missing required columns: ' . implode(', ', $missing)]);
        }

        $columnIndexes = array_flip($normalizedHeader);

        DB::beginTransaction();

        try {
            while (($row = fgetcsv($handle, 0, ',')) !== false) {
                if (count(array_filter($row, function ($value) {
                    return trim((string)$value) !== '';
                })) === 0) {
                    continue;
                }

                $rowData = [];
                foreach ($requiredColumns as $column) {
                    $index = $columnIndexes[$column];
                    $rowData[$column] = isset($row[$index]) ? trim($row[$index]) : null;
                }

                // Normalize price (e.g. "74 23 lei" -> 74.23)
                $rawPrice = (string)($rowData['price'] ?? '');
                // Replace comma with dot for decimal, remove currency text and spaces
                $normalizedPrice = str_replace(',', '.', $rawPrice);
                $normalizedPrice = preg_replace('/[^0-9.\-]/', '', $normalizedPrice);

                if ($normalizedPrice === '' || !is_numeric($normalizedPrice)) {
                    fclose($handle);
                    DB::rollBack();
                    return redirect()->route('admin.products.batch-upload')
                        ->withErrors(['products_file' => 'Invalid price value found: "' . $rowData['price'] . '"']);
                }

                $sku = strtoupper(Str::slug($rowData['name'])) . '-' . substr(uniqid(), -6);

                $productData = [
                    'sku' => $sku,
                    'name' => $rowData['name'],
                    'price' => $normalizedPrice,
                    'quantity' => 0,
                    'description' => $rowData['description'] ?? '',
                    'slug' => Str::slug($rowData['name']),
                    'status' => 1,
                ];

                /** @var Product $product */
                $product = $this->productRepo->createProduct($productData);
                $productRepo = new ProductRepository($product);

                if (!empty($rowData['specificatii_produs'])) {
                    $product->description = trim($product->description . "\n\n" . $rowData['specificatii_produs']);
                    $product->save();
                }

                $categoryIds = [];

                if (!empty($rowData['category'])) {
                    $category = Category::firstOrCreate(
                        ['name' => $rowData['category'], 'parent_id' => null],
                        [
                            'slug' => Str::slug($rowData['category']),
                            'status' => 1,
                        ]
                    );
                    $categoryIds[] = $category->id;

                    if (!empty($rowData['subcategory'])) {
                        $subcategory = Category::firstOrCreate(
                            ['name' => $rowData['subcategory'], 'parent_id' => $category->id],
                            [
                                'slug' => Str::slug($rowData['subcategory']),
                                'status' => 1,
                                'parent_id' => $category->id,
                            ]
                        );
                        $categoryIds[] = $subcategory->id;
                    }
                }

                if (!empty($categoryIds)) {
                    $productRepo->syncCategories($categoryIds);
                }

                if (!empty($rowData['image_url'])) {
                    $product->images()->create([
                        'src' => $rowData['image_url'],
                    ]);
                }

                if (!empty($rowData['image_url_2'])) {
                    $product->images()->create([
                        'src' => $rowData['image_url_2'],
                    ]);
                }
            }

            fclose($handle);
            DB::commit();
        } catch (\Throwable $e) {
            fclose($handle);
            DB::rollBack();
            throw $e;
        }

        return redirect()->route('admin.products.batch-upload')->with('success', 'Batch upload processed!');
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function removeImage(Request $request)
    {
        $product = $this->productRepo->findOneOrFail($request->input('product_id'));
        $product->cover = null;
        $product->save();

        return redirect()->back()->with('message', 'Image delete successful');
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function removeThumbnail(Request $request)
    {
        $this->productRepo->deleteThumb($request->input('src'));
        return redirect()->back()->with('message', 'Image delete successful');
    }

    /**
     * @param Request $request
     * @param Product $product
     * @return RedirectResponse|bool
     */
    private function saveProductCombinations(Request $request, Product $product): bool
    {
        $fields = $request->only(
            'productAttributeQuantity',
            'productAttributePrice',
            'salePrice',
            'default'
        );

        if ($errors = $this->validateFields($fields)) {
            return redirect()->route('admin.products.edit', [$product->id, 'combination' => 1])
                ->withErrors($errors);
        }

        $quantity = $fields['productAttributeQuantity'];
        $price = $fields['productAttributePrice'];

        $sale_price = null;
        if (isset($fields['salePrice'])) {
            $sale_price = $fields['salePrice'];
        }

        $attributeValues = $request->input('attributeValue');
        $productRepo = new ProductRepository($product);

        $hasDefault = $productRepo->listProductAttributes()->where('default', 1)->count();

        $default = 0;
        if ($request->has('default')) {
            $default = $fields['default'];
        }

        if ($default == 1 && $hasDefault > 0) {
            $default = 0;
        }

        $productAttribute = $productRepo->saveProductAttributes(
            new ProductAttribute(compact('quantity', 'price', 'sale_price', 'default'))
        );

        // save the combinations
        return collect($attributeValues)->each(function ($attributeValueId) use ($productRepo, $productAttribute) {
            $attribute = $this->attributeValueRepository->find($attributeValueId);
            return $productRepo->saveCombination($productAttribute, $attribute);
        })->count();
    }

    /**
     * @param array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator|void
     */
    private function validateFields(array $data)
    {
        $validator = Validator::make($data, [
            'productAttributeQuantity' => 'required'
        ]);

        if ($validator->fails()) {
            return $validator;
        }
    }
}
