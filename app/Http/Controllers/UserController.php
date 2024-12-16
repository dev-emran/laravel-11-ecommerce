<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\ProfilePhoto;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Laravel\Facades\Image;
use Surfsidemedia\Shoppingcart\Facades\Cart;

class UserController extends Controller
{

    // public function __construct()
    // {
    //     // $this->middleware('auth');
    //     // $this->middleware('admin-role')->only(['indexAdmin', 'adminSetting', 'updateSetting']);
    // }
    /**
     * user account view
     */
    public function index()
    {
        return view('user.index');
    }

    /**
     * admin panel user view
     */

    public function indexAdmin()
    {
        $users = User::orderBy('created_at', 'DESC')->paginate(8);
        return view('user.admin.users', compact('users'));
    }

    public function adminSetting()
    {

        $user = Auth::user();
        if ($user->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }
        return view('user.admin.setting', compact('user'));
    }

    public function updateSetting(Request $request)
    {
        $rules = [
            'user_id' => 'required|numeric',
            'name' => 'required|string',
            'mobile' => 'required|digits:11',
            'email' => 'email|required',
            'profile_photo' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:1024',
            'old_password' => 'nullable|required_with:password',
            'password' => 'nullable|confirmed|min:8',

        ];

        $validatedAttr = $request->validate($rules);
        $user = User::find($validatedAttr['user_id']);

        if (!$user) {
            return redirect()->back()->withErrors('User not found');
        }

        if ($request->filled('old_password')) {
            if (!Hash::check($validatedAttr['old_password'], $user->password)) {
                return redirect()->back()->with('passErr', 'The old password is incorrect');
            }
            $user->password = Hash::make($validatedAttr['password']);
        }

        DB::transaction(function () use ($user, $validatedAttr, $request) {
            $user->name = $validatedAttr['name'];
            $user->email = $validatedAttr['email'];
            $user->mobile = $validatedAttr['mobile'];
            $user->save();

            if ($request->hasFile('profile_photo')) {
                $oldImage = ProfilePhoto::where('user_id', $validatedAttr['user_id'])->first();
                if ($oldImage) {
                    $oldImagePath = public_path('uploads/profile/' . $oldImage->image);
                    File::delete($oldImagePath);
                    $oldImage->delete();
                }
                $name = "profile_" . time() . '.' . $request->file('profile_photo')->extension();
                ProfilePhoto::create([
                    'user_id' => $validatedAttr['user_id'],
                    'image' => $name,
                ]);
                $this->profileImageHandelar($validatedAttr['profile_photo'], $name);
            }
        });

        flash()->success('Profile has been updated');
        return redirect()->back();

    }

    //customer
    public function customerAccountSetting()
    {
        $customer = Auth::user();
        return view('user.account', compact('customer'));
    }

    public function customerAccountUpdate(Request $request)
    {
        $rules = [
            'name' => 'required|string',
            'mobile' => 'required|digits:11',
            'profile_photo' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:1024',
            'old_password' => 'nullable|required_with:password',
            'password' => 'nullable|min:8|confirmed',
        ];
        $validatedAttr = $request->validate($rules);
        $customer = User::find(Auth::user()->id);
        if ($request->filled('old_password')) {
            if (!Hash::check($validatedAttr['old_password'], $customer->password)) {
                return redirect()->back()->with('error', 'The old password is incorrect');
            }
            $customer->password = Hash::make($validatedAttr['password']);
        }
        DB::transaction(function () use ($customer, $validatedAttr, $request) {
            $customer->name = $validatedAttr['name'];
            $customer->mobile = $validatedAttr['mobile'];
            $customer->save();

            if ($request->hasFile('profile_photo')) {
                $oldImage = ProfilePhoto::where('user_id', $customer->id)->first();
                if ($oldImage) {
                    $oldImagePath = public_path('uploads/profile/' . $oldImage->image);
                    File::delete($oldImagePath);
                    $oldImage->delete();
                }
                $imgName = 'profile_' . time() . '.' . $request->file('profile_photo')->extension();
                ProfilePhoto::create([
                    'user_id' => $customer->id,
                    'image' => $imgName,
                ]);
                $this->profileImageHandelar($validatedAttr['profile_photo'], $imgName);
            }
        });

        return redirect()->back()->with('success', 'Profile has been updated');

    }

    public function customerAddress()
    {
        $address = Address::where('user_id', Auth::user()->id)->first();
        return view('user.address', compact('address'));
    }

    public function createNewAddress()
    {
        return view('user.create-address');
    }

    public function storeAddress(Request $request)
    {
        $validatedAttr = $request->validate([
            "name" => 'required|string',
            "phone" => 'required|numeric|digits:11',
            "zip" => 'required',
            "state" => 'required',
            "city" => 'required',
            "address" => 'required',
            "locality" => 'required',
            "landmark" => 'required',
            "isdefault" => 'in:1',
            "user_id" => 'required|numeric',
        ],
            [
                'name.required' => 'Full name is required',
                'phone.required' => 'Phone no is required',
                'phone.digits' => 'Phone no must be 11 digits',
                'zip.required' => 'Postal code is required',
                'state.required' => 'Division is required',
                'city.required' => 'District is required',
                'address.required' => 'Address is required',
                'locality.required' => 'Thana/upzilla is required',
                'landmark.required' => 'Post is required',

            ]

        );

        if ($validatedAttr['user_id'] == Auth::user()->id) {
            $address = Address::where('user_id', $validatedAttr['user_id'])->first();
            if ($address) {
                $address->delete();
            }
            Address::create([
                "name" => $validatedAttr["name"],
                "phone" => $validatedAttr["phone"],
                "zip" => $validatedAttr["zip"],
                "state" => $validatedAttr["state"],
                "city" => $validatedAttr["city"],
                "address" => $validatedAttr["address"],
                "locality" => $validatedAttr["locality"],
                "landmark" => $validatedAttr["landmark"],
                "country" => 'Bangladesh',
                "isdefault" => 1,
                "user_id" => $validatedAttr["user_id"],
            ]);
            flash()->success('Address has been created');
            return redirect()->route('customer.address');
        }

        flash()->error('Somethings went wrong');
        return redirect()->back();

    }

    public function editAddress($id)
    {
        $address = Address::where('id', $id)->where('user_id', Auth::user()->id)->first();
        if (!$address) {
            flash()->error('Somethings went wrong');
            return redirect()->back();
        }

        return view('user.edit-address', compact('address'));
    }

    public function updateAddress(Request $request)
    {
        $validatedAttr = $request->validate([
            "name" => 'required|string',
            "phone" => 'required|numeric|digits:11',
            "zip" => 'required',
            "state" => 'required',
            "city" => 'required',
            "address" => 'required',
            "locality" => 'required',
            "landmark" => 'required',
            "isdefault" => 'in:1',
            "user_id" => 'required|numeric',
        ],
            [
                'name.required' => 'Full name is required',
                'phone.required' => 'Phone no is required',
                'phone.digits' => 'Phone no must be 11 digits',
                'zip.required' => 'Postal code is required',
                'state.required' => 'Division is required',
                'city.required' => 'District is required',
                'address.required' => 'Address is required',
                'locality.required' => 'Thana/upzilla is required',
                'landmark.required' => 'Post is required',

            ]

        );

        if ($validatedAttr['user_id'] == Auth::user()->id) {
            $address = Address::where('user_id', $validatedAttr['user_id'])->first();
            if ($address) {
                $address->update([
                    "name" => $validatedAttr["name"],
                    "phone" => $validatedAttr["phone"],
                    "zip" => $validatedAttr["zip"],
                    "state" => $validatedAttr["state"],
                    "city" => $validatedAttr["city"],
                    "address" => $validatedAttr["address"],
                    "locality" => $validatedAttr["locality"],
                    "landmark" => $validatedAttr["landmark"],
                    "country" => 'Bangladesh',
                    "isdefault" => 1,
                    "user_id" => $validatedAttr["user_id"],
                ]);
                flash()->success('Address has been updated');
                return redirect()->route('customer.address');
            }
        }

        flash()->error('Somethings went wrong');
        return redirect()->back();
    }


    public function customerWishlist()
    {
        $items = Cart::instance('wishlist')->content();
        return view('user.wishlist', compact('items'));
    }

    protected function profileImageHandelar($image, $name)
    {
        $path = public_path('uploads/profile');
        $img = Image::read($image->path());
        $img->resize(300, 300, function ($constraint) {
            $constraint->upsize();
        })->save($path . '/' . $name);
    }

    protected function validateUserAccount(Request $request)
    {
        return $request->validate([
            'user_id' => 'nullable|numeric',
            'name' => 'required|string|max:255',
            'mobile' => 'required|digits:11',
            'email' => 'nullable|email|max:255',
            'profile_photo' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:1024',
            'old_password' => 'nullable|required_with:password',
            'password' => 'nullable|confirmed|min:8',
        ]);
    }

    protected function validateAddress(Request $request)
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|digits:11',
            'zip' => 'required',
            'state' => 'required',
            'city' => 'required',
            'address' => 'required',
            'locality' => 'required',
            'landmark' => 'required',
            'isdefault' => 'in:1',
            'user_id' => 'required|numeric',
        ],
            [
                'name.required' => 'Full name is required',
                'phone.required' => 'Phone no is required',
                'phone.digits' => 'Phone no must be 11 digits',
                'zip.required' => 'Postal code is required',
                'state.required' => 'Division is required',
                'city.required' => 'District is required',
                'address.required' => 'Address is required',
                'locality.required' => 'Thana/upzilla is required',
                'landmark.required' => 'Post is required',
            ]

        );
    }

    protected function saveAddress(array $validatedAttr)
    {
        Address::updateOrCreate(
            ['user_id' => $validatedAttr['user_id']],
            array_merge($validatedAttr, ['country' => 'Bangladesh', 'isdefault' => 1])
        );
    }
}
