<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Order;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;


class AdminController extends Controller
{
    public function index()
    {
        $orders = Order::orderBy('created_at', 'DESC')->get()->take(5);
        $dashboardsDatas = DB::table('orders')->selectRaw("
            SUM(total) as TotalAmount,
            SUM(IF(status = 'ordered', total, 0)) as TotalOrderedAmount,
            SUM(IF(status = 'delivered', total, 0)) as TotalDeliveredAmount,
            SUM(IF(status = 'canceled', total, 0)) as TotalCanceledAmount,

            COUNT(IF(status = 'ordered', 1, NULL)) as TotalOrdered,
            COUNT(IF(status = 'delivered', 1, NULL)) as TotalDelivered,
            COUNT(IF(status = 'canceled', 1, NULL)) as TotalCanceled
        ")->first();

        $monthlyDatas = DB::select("
            SELECT
                M.id AS MonthNo,
                M.name AS MonthName,
                IFNULL(D.TotalAmount, 0) AS TotalAmount,
                IFNULL(D.TotalOrderedAmount, 0) AS TotalOrderedAmount,
                IFNULL(D.TotalDeliveredAmount, 0) AS TotalDeliveredAmount,
                IFNULL(D.TotalCanceledAmount, 0) AS TotalCanceledAmount
            FROM
                months M
            LEFT JOIN (
                SELECT
                    MONTH(created_at) AS MonthNo,
                    SUM(total) AS TotalAmount,
                    SUM(IF(status = 'ordered', total, 0)) AS TotalOrderedAmount,
                    SUM(IF(status = 'delivered', total, 0)) AS TotalDeliveredAmount,
                    SUM(IF(status = 'canceled', total, 0)) AS TotalCanceledAmount
                FROM
                    orders
                WHERE
                    YEAR(created_at) = YEAR(NOW())
                GROUP BY
                    MONTH(created_at)
            ) D ON D.MonthNo = M.id
            ORDER BY
                M.id
        ");

        $AmountM = implode(',', collect($monthlyDatas)->pluck('TotalAmount')->toArray());
        $OrderedAmountM = implode(',', collect($monthlyDatas)->pluck('TotalOrderedAmount')->toArray());
        $DeliveredAmountM = implode(',', collect($monthlyDatas)->pluck('TotalDeliveredAmount')->toArray());
        $CanceledAmountM = implode(',', collect($monthlyDatas)->pluck('TotalCanceledAmount')->toArray());

        $TotalAmount = collect($monthlyDatas)->sum('TotalAmount');
        $TotalOrderedAmount = collect($monthlyDatas)->sum('TotalOrderedAmount');
        $TotalDeliveredAmount = collect($monthlyDatas)->sum('TotalDeliveredAmount');
        $TotalCanceledAmount = collect($monthlyDatas)->sum("TotalCanceledAmount");

        
        return view('admin.index', compact('orders', 'dashboardsDatas', 'AmountM','OrderedAmountM','DeliveredAmountM','CanceledAmountM','TotalAmount','TotalOrderedAmount','TotalDeliveredAmount', 'TotalCanceledAmount'));
    }

    public function brands()
    {
        $brands = Brand::orderBy('id', 'DESC')->paginate(10);
        return view('admin.brand.index', compact('brands'));
    }

    public function create()
    {
        return view('admin.brand.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:brands,slug',
            'image' => 'image|max:1024|mimes:png,jpg,webp|nullable',
        ]);

        $brand = new Brand();
        $brand->name = $validatedData['name'];
        $brand->slug = $validatedData['slug'] ?? Str::slug($validatedData['name']);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imgExtention = $image->extension();
            $name = "brand_img" . time() . '.' . $imgExtention;
            // $name = strtolower(str_replace(' ', '_', $name));
            $this->brandImageHandelar($image, $name);
            $brand->image = $name;
        }
        $brand->save();
        flash()->success('Brand has been added Successfull');
        return redirect()->route('brand.index');

    }

    public function editBrand($id)
    {
        try {
            $brand = Brand::findOrFail($id);
            return view('admin.brand.edit', compact('brand'));
        } catch (ModelNotFoundException $e) {
            flash()->error('Something went wrong!');
            return redirect()->route('brand.index');
        }
    }

    public function updateBrand(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'brand_id' => 'required|numeric',
            'slug' => 'required|unique:brands,slug,' . $request->brand_id,
            'image' => 'image|max:1024|mimes:png,jpg,webp|nullable',
        ]);

        $brand = Brand::findOrFail($validatedData['brand_id']);
        $brand->name = $validatedData['name'];
        $brand->slug = $validatedData['slug'] ?? Str::slug($validatedData['name']);

        if ($request->hasFile('image')) {
            $oldImagePath = public_path('uploads/brands/' . $brand->image);
            if (File::exists($oldImagePath)) {
                File::delete($oldImagePath);
            }
            $image = $request->file('image');
            $imgExtention = $image->extension();
            $name = "brand_img" . time() . '.' . $imgExtention;
            $this->brandImageHandelar($image, $name);
            $brand->image = $name;
        }
        $brand->save();
        flash()->success('Brand has been updated Successfull');
        return redirect()->route('brand.index');

    }

    public function brandDestroy(Request $request)
    {
        try {
            $brand = Brand::findOrFail($request->id);
            $oldImagePath = public_path('uploads/brands/' . $brand->image);
            if (File::exists($oldImagePath)) {
                File::delete($oldImagePath);
            }
            $brand->delete();
            flash()->warning('Brand Successfully Deleted');
            return redirect()->route('brand.index');
        } catch (ModelNotFoundException $e) {
            flash()->error('Something went wrong!');
            return redirect()->route('brand.index');
        }
    }

    protected function brandImageHandelar($image, $name)
    {
        $path = public_path('uploads/brands');
        $img = Image::read($image->path())
            ->cover(124, 124, )
            ->resize(124, 124, function ($constraint) {
                $constraint->aspectRatio();
            })->save($path . '/' . $name);
    }
}
