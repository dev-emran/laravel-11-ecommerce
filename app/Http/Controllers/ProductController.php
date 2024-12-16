<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('created_at', 'DESC')->paginate(10);
        return view('admin.product.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::select('id', 'name')->orderBy('name')->get();
        $brands = Brand::select('id', 'name')->orderBy('name')->get();
        return view('admin.product.create', compact(['categories', 'brands']));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validatedAttr = $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:products,slug',
            "brand_id" => 'required|numeric',
            'category_id' => 'required|numeric',
            'short_description' => 'required',
            'description' => 'required',
            'regular_price' => 'required',
            'sale_price' => 'required',
            'SKU' => 'required',
            'quantity' => 'required|',
            'stock_status' => 'required',
            'featured' => 'required',
            'image' => 'required|mimes:png,jpeg,jpg,webp|max:1024',
            'images.*' => 'image|mimes:png,jpeg,jpg,webp|max:1024',

        ]);

        $product = new Product();
        $product->title = $validatedAttr['name'];
        $product->slug = $validatedAttr['slug'] ?? Str::slug($validatedAttr['name']);
        $product->brand_id = $validatedAttr['brand_id'];
        $product->category_id = $validatedAttr['category_id'];
        $product->short_description = $validatedAttr['short_description'];
        $product->description = $validatedAttr['description'];
        $product->regular_price = $validatedAttr['regular_price'];
        $product->sale_price = $validatedAttr['sale_price'];
        $product->SKU = $validatedAttr['SKU'];
        $product->quantity = $validatedAttr['quantity'];
        $product->stock_status = $validatedAttr['stock_status'];
        $product->featured = $validatedAttr['featured'];
        if ($request->hasFile('image')) {
            $productImage = $request->file('image');
            $name = "product_img" . time() . '.' . $productImage->extension();
            $product->image = $validatedAttr['image'];
            $this->ProductImageHandelar($productImage, $name);
            $product->image = $name;
        }

        $product->save();

        if ($request->hasFile('images')) {
            $count = 1;
            $images = $request->file('images');
            foreach ($images as $image) {
                $name = 'g_img_' . $count . time() . '.' . $image->extension();
                $this->ProductGalleryImageHandelar($image, $name);
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $name,

                ]);
                $count++;
            }
        }
        flash()->success('Product has been added successfull');
        return redirect()->route('product.index');
    }

    public function editProduct($id)
    {
        try {
            $categories = Category::select('id', 'name')->orderBy('name')->get();
            $brands = Brand::select('id', 'name')->orderBy('name')->get();
            $gallery_images = ProductImage::select('id', 'image_path')->where('product_id', $id)->get();
            $product = Product::findOrFail($id);
            return view('admin.product.edit', compact(['product', 'categories', 'brands', 'gallery_images']));
        } catch (ModelNotFoundException $e) {
            flash()->error('Somethings went error');
            return redirect()->route('product.index');
        }
    }

    public function updateProduct(Request $request)
    {
        $validatedAttr = $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:products,slug,' . $request->product_id,
            "brand_id" => 'required|numeric',
            "product_id" => 'required|numeric',
            'category_id' => 'required|numeric',
            'short_description' => 'required',
            'description' => 'required',
            'regular_price' => 'required',
            'sale_price' => 'required',
            'SKU' => 'required',
            'quantity' => 'required|',
            'stock_status' => 'required',
            'featured' => 'required',
            'image' => 'nullable|mimes:png,jpeg,jpg,webp|max:1024',
            'images.*' => 'image|mimes:png,jpeg,jpg,webp|max:1024',

        ]);

        $product = Product::findOrFail($validatedAttr['product_id']);
        $product->title = $validatedAttr['name'];
        $product->slug = $validatedAttr['slug'] ?? Str::slug($validatedAttr['name']);
        $product->brand_id = $validatedAttr['brand_id'];
        $product->category_id = $validatedAttr['category_id'];
        $product->short_description = $validatedAttr['short_description'];
        $product->description = $validatedAttr['description'];
        $product->regular_price = $validatedAttr['regular_price'];
        $product->sale_price = $validatedAttr['sale_price'];
        $product->SKU = $validatedAttr['SKU'];
        $product->quantity = $validatedAttr['quantity'];
        $product->stock_status = $validatedAttr['stock_status'];
        $product->featured = $validatedAttr['featured'];

        if ($request->hasFile('image')) {
            $oldImagePath = public_path('uploads/products/thumbnails/' . $product->image);
            $oldImagePath2 = public_path('uploads/products/' . $product->image);
            if (File::exists($oldImagePath)) {
                File::delete($oldImagePath);
            }

            if (File::exists($oldImagePath2)) {
                File::delete($oldImagePath2);
            }

            $productImage = $request->file('image');
            $name = "product_img" . time() . '.' . $productImage->extension();
            $product->image = $validatedAttr['image'];
            $this->ProductImageHandelar($productImage, $name);
            $product->image = $name;
        }

        $product->save();

        if ($request->hasFile('images')) {
            $oldImages = ProductImage::where('product_id', $product->id)->get();

            foreach ($oldImages as $oldImage) {
                $oldGalleryImagePath = public_path('uploads/products/gallery/' . $oldImage->image_path);
                if (File::exists($oldGalleryImagePath)) {
                    File::delete($oldGalleryImagePath);
                    $oldImage->delete();
                }
            }

            $images = $request->file('images');

            foreach ($images as $index => $image) {
                $name = 'g_img_' . $index . time() . '.' . $image->extension();
                $this->ProductGalleryImageHandelar($image, $name);
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $name,

                ]);
            }
        }
        flash()->success('Product has been Updated successfull');
        return redirect()->route('product.index');

    }

    public function deleteProduct(Request $request)
    {
        try {
            $product = Product::findOrFail($request->product_id);
            if ($product) {
                $oldImagePath = public_path('uploads/products/thumbnails/' . $product->image);
                $oldImagePath2 = public_path('uploads/products/' . $product->image);

                if (File::exists($oldImagePath)) {
                    File::delete($oldImagePath);
                }
                if (File::exists($oldImagePath2)) {
                    File::delete($oldImagePath2);
                }
                $galleryImages = $product->galleryImages;
                foreach ($galleryImages as $galleryImage) {
                    $oldGalleryImage = public_path('uploads/products/gallery/' . $galleryImage->image_path);
                    if (File::exists($oldGalleryImage)) {
                        File::delete($oldGalleryImage);
                    }
                }
                
                $product->delete();
            }

            flash()->success('Product has been deleted');
            return redirect()->route('product.index');

        } catch (ModelNotFoundException $e) {
            flash()->error('Somethings went wrong');
            return redirect()->back();
        }

    }

    public function productDetails($slug)
    {
        $product = Product::where('slug', $slug)->first();
        $relatedProducts = Product::where('category_id', $product->category_id )
                                    ->where('slug', '<>', $product->slug )
                                    ->get();
        return view('shop.detail', compact('product', 'relatedProducts'));
    }

    protected function ProductImageHandelar($image, $name)
    {
        $path = public_path('uploads/products/thumbnails');
        $destPath = public_path('uploads/products');
        $img = Image::read($image->path());
        $img->cover(540, 689, 'top');

        $img->resize(540, 689, function ($constraint) {
            $constraint->aspectRatio();
        })->save($path . '/' . $name);

        $img->resize(104, 104, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destPath . '/' . $name);
    }

    protected function ProductGalleryImageHandelar($image, $name)
    {
        $path = public_path('uploads/products/gallery');
        $img = Image::read($image->path());
        $img->cover(540, 689, 'top');

        $img->resize(540, 689, function ($constraint) {
            $constraint->aspectRatio();
        })->save($path . '/' . $name);

    }
}
