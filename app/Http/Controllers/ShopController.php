<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;

class ShopController extends Controller
{
    public function index()
    {
        $size = request()->query('size', 12);
        $order = request()->query('order', -1);
        $brandFilter = request()->query('brand');
        $categoryFilter = request()->query('category');
        $minPrice = request()->query('min_price') ?? 10;
        $maxPrice = request()->query('max_price') ?? 5000;

        // Determine the column and order direction based on the selected option
        $orderColumn = 'id';
        $_order = 'DESC';

        switch ($order) {
            case 1:
                $orderColumn = 'created_at';
                $_order = 'DESC';
                break;
            case 2:
                $orderColumn = 'created_at';
                $_order = 'ASC';
                break;
            case 3:
                $orderColumn = 'sale_price';
                $_order = 'ASC';
                break;
            case 4:
                $orderColumn = 'sale_price';
                $_order = 'DESC';
                break;
        }

        
        $brands = Brand::orderBy('name')->get();
        $categories = Category::orderBy('name', 'ASC')->get();

        
        $products = Product::with(['brand', 'category'])
            ->when($brandFilter, function ($query) use ($brandFilter) {
                $brandIds = explode(',', $brandFilter); // Split the brand IDs into an array
                $query->whereIn('brand_id', $brandIds); 
            })
            ->when($categoryFilter, function ($query) use ($categoryFilter) {
                $categoryIds = explode(',', $categoryFilter);
                $query->whereIn('category_id', $categoryIds);
            })
            ->when($minPrice || $maxPrice, function ($query) use ($minPrice, $maxPrice) {
                $query->where(function ($query) use ($minPrice, $maxPrice) {
                    $query->whereBetween('sale_price', [$minPrice, $maxPrice])
                        ->orWhereBetween('regular_price', [$minPrice, $maxPrice]);
                });
            })
            ->orderBy($orderColumn, $_order)
            ->paginate($size)
            ->appends(request()->query());

        return view('shop.index', compact('products', 'size', 'order', 'brands', 'brandFilter', 'categories', 'categoryFilter', 'minPrice', 'maxPrice'));
    }

}
