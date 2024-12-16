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
use Intervention\Image\Facades\Image;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin')->only(['indexAdmin', 'adminSetting', 'updateSetting']);
    }

    /**
     * User account view
     */
    public function index()
    {
        return view('user.index');
    }

    /**
     * Admin panel user view
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
        $validatedAttr = $this->validateUserAccount($request);

        $user = User::find($validatedAttr['user_id']);
        if (!$user) {
            return redirect()->back()->withErrors('User not found');
        }

        $this->updateAccount($user, $validatedAttr, $request);

        flash()->success('Profile has been updated');
        return redirect()->back();
    }

    public function customerAccountSetting()
    {
        $customer = Auth::user();
        return view('user.account', compact('customer'));
    }

    public function customerAccountUpdate(Request $request)
    {
        $validatedAttr = $this->validateUserAccount($request);

        $customer = Auth::user();
        $this->updateAccount($customer, $validatedAttr, $request);

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
        $validatedAttr = $this->validateAddress($request);

        if ($validatedAttr['user_id'] == Auth::user()->id) {
            $this->saveAddress($validatedAttr);
            flash()->success('Address has been created');
            return redirect()->route('customer.address');
        }

        flash()->error('Something went wrong');
        return redirect()->back();
    }

    public function editAddress($id)
    {
        $address = Address::where('id', $id)->where('user_id', Auth::user()->id)->firstOrFail();
        return view('user.edit-address', compact('address'));
    }

    public function updateAddress(Request $request)
    {
        $validatedAttr = $this->validateAddress($request);

        $this->saveAddress($validatedAttr);

        flash()->success('Address has been updated');
        return redirect()->route('customer.address');
    }

    protected function updateProfilePhoto($userId, $image)
    {
        $oldImage = ProfilePhoto::where('user_id', $userId)->first();
        if ($oldImage) {
            $oldImagePath = public_path('uploads/profile/' . $oldImage->image);
            if (File::exists($oldImagePath)) {
                File::delete($oldImagePath);
            }
            $oldImage->delete();
        }

        $name = "profile_" . time() . '.' . $image->extension();
        $this->profileImageHandler($image, $name);

        ProfilePhoto::create([
            'user_id' => $userId,
            'image' => $name,
        ]);
    }

    protected function profileImageHandler($image, $name)
    {
        $path = public_path('uploads/profile');
        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }

        $img = Image::make($image->path());
        $img->fit(300, 300, function ($constraint) {
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

    protected function updateAccount($user, $validatedAttr, $request)
    {
        if ($request->filled('old_password')) {
            if (!Hash::check($validatedAttr['old_password'], $user->password)) {
                return redirect()->back()->with('error', 'The old password is incorrect');
            }
            $user->password = Hash::make($validatedAttr['password']);
        }

        DB::transaction(function () use ($user, $validatedAttr, $request) {
            $user->name = $validatedAttr['name'];
            $user->email = $validatedAttr['email'] ?? $user->email;
            $user->mobile = $validatedAttr['mobile'];
            $user->save();

            if ($request->hasFile('profile_photo')) {
                $this->updateProfilePhoto($user->id, $request->file('profile_photo'));
            }
        });
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
        ]);
    }

    protected function saveAddress(array $validatedAttr)
    {
        Address::updateOrCreate(
            ['user_id' => $validatedAttr['user_id']],
            array_merge($validatedAttr, ['country' => 'Bangladesh', 'isdefault' => 1])
        );
    }
}
