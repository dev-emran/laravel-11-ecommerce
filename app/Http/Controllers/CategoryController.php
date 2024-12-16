<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('id', 'DESC')->paginate(10);
        return view('admin.category.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.category.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:categories,slug',
            'image' => 'image|max:1024|mimes:png,jpg,webp|nullable',
        ]);

        $cate = new Category();
        $cate->name = $validatedData['name'];
        $cate->slug = $validatedData['slug'] ?? Str::slug($validatedData['name']);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imgExtention = $image->extension();
            $name = "cate_img" . time() . '.' . $imgExtention;
            $this->cateImageHandelar($image, $name);
            $cate->image = $name;
        }

        $cate->save();
        flash()->success('Category has been added Successfull');
        return redirect()->route('category.index');
    }

    public function editCategory($id)
    {
        try {
            $category = Category::findOrFail($id);
            return view('admin.category.edit', compact('category'));
        } catch (ModelNotFoundException $e) {
            flash()->error('Somethings went wrong');
            return redirect()->route('category.index');
        }
    }

    public function updateCategory(Request $request)
    {

        $validatedData = $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,' . $request->cate_id,
            'image' => 'image|max:1024|mimes:png,jpg,webp|nullable',
            'cate_id' => 'required|numeric',
        ]);

        $cate = Category::findOrFail($validatedData["cate_id"]);
        $cate->name = $validatedData['name'];
        $cate->slug = $validatedData['slug'] ?? Str::slug($validatedData['name']);

        if ($request->hasFile('image')) {

            $oldPath = public_path('uploads/category/' . $cate->image);

            if (File::exists($oldPath)) {
                File::delete($oldPath);
            }

            $cateThumb = $request->file('image');
            $extention = $cateThumb->extension();
            $name = "cate_img" . time() . "." . $extention;
            $this->cateImageHandelar($cateThumb, $name);
            $cate->image = $name;
        }

        $cate->save();
        flash()->success('Category Updated Successfully');
        return redirect()->route('category.index');
    }

    public function categoryDestroy(Request $request)
    {
        try {
            $cate = Category::findOrFail($request->id);
            $oldPath = public_path('uploads/category/' . $cate->image);
            if (File::exists($oldPath)) {
                File::delete($oldPath);
            }
            $cate->delete();
            flash()->warning('Category Successfully Deleted');
            return redirect()->route('category.index');
        } catch (ModelNotFoundException $e) {
            flash()->error('Somethings went wrong');
            return redirect()->back();
        }
    }

    protected function cateImageHandelar($image, $name)
    {
        $path = public_path('uploads/category');
        $img = Image::read($image->path())
            ->cover(350, 350, )
            ->resize(350, 350, function ($constraint) {
                $constraint->aspectRatio();
            })->save($path . '/' . $name);
    }
}
