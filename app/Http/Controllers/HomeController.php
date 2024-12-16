<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Slider;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $sliders = Slider::orderBy('id', 'DESC')
            ->where('status', 'active')
            ->get()
            ->take(3);

        $categories = Category::orderBy('name')
            ->get();
        $popularCategories = Category::where('slug', 't-shirt')->orWhere('slug', 'jacket')->get();

        $saleProducts = Product::whereNotNull('sale_price')
            ->where('sale_price', '<>', '')
            ->inRandomOrder()
            ->get()
            ->take(8);

        $featuredProducts = Product::where('featured', 1)->paginate(8);
        return view('index', compact('sliders', 'categories', 'saleProducts', 'featuredProducts', 'popularCategories'));
    }

    public function contact()
    {
        return view('contact');
    }

    public function about()
    {
        return view('about');
    }

    public function search(Request $request)
    {
        $query = $request->input('search_key');
        $results = Product::where('title', 'LIKE', "%{$query}%")->get()->take(8);
        return response()->json($results);
    }
}