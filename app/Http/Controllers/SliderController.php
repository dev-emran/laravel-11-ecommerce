<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\Laravel\Facades\Image;

class SliderController extends Controller
{
    public function slider()
    {
        $slides = Slider::orderBy('id', 'DESC')->paginate(12);
        return view('admin.slide.slide', compact('slides'));
    }

    public function sliderCreate()
    {
        return view('admin.slide.create');
    }

    public function sliderStore(Request $request)
    {

        $validatedAttr = $request->validate([
            "title" => 'required|min:3',
            "tagline" => 'required|min:3',
            "subtitle" => 'required|min:3',
            "link" => 'required|url',
            "image" => 'image|required|mimes:png,jpg,jpeg,webp|max:2048',
            "status" => 'required|in:active,inactive',
        ]);

        $slider = new Slider();
        $slider->title = $validatedAttr['title'];
        $slider->tagline = $validatedAttr['tagline'];
        $slider->subtitle = $validatedAttr['subtitle'];
        $slider->link = $validatedAttr['link'];
        $slider->status = $validatedAttr['status'];

        if ($request->hasFile('image')) {
            $slideImage = $request->file('image');
            $imgName = 'slide_img' . time() . '.' . $slideImage->extension();
            $this->slideImageHandelar($slideImage, $imgName);
            $slider->image = $imgName;
        }

        $slider->save();
        flash()->success('Slide has been added');
        return redirect()->route('admin.slide');
    }

    public function sliderEdit($id)
    {
        $slide = Slider::find($id);
        return view('admin.slide.edit', compact('slide'));
    }

    public function sliderUpdate(Request $request)
    {
        $validatedAttr = $request->validate([
            "title" => 'required|min:3',
            "slide_id" => 'required|numeric',
            "tagline" => 'required|min:3',
            "subtitle" => 'required|min:3',
            "link" => 'required|url',
            "image" => 'image|mimes:png,jpg,jpeg,webp|max:2048',
            "status" => 'required|in:active,inactive',
        ]);

        $slider = Slider::find($validatedAttr['slide_id']);
        if (!$slider) {
            flash()->error('Oh! somethings went wrong!');
            return redirect()->route('admin.slide');
        }
        $slider->title = $validatedAttr['title'];
        $slider->tagline = $validatedAttr['tagline'];
        $slider->subtitle = $validatedAttr['subtitle'];
        $slider->link = $validatedAttr['link'];
        $slider->status = $validatedAttr['status'];

        if ($request->hasFile('image')) {
            $oldImage = public_path('uploads/slider/' . $slider->image);
            if (File::exists($oldImage)) {
                File::delete($oldImage);
            }
            $slideImage = $request->file('image');
            $imgName = 'slide_img' . time() . '.' . $slideImage->extension();
            $this->slideImageHandelar($slideImage, $imgName);
            $slider->image = $imgName;
        }

        $slider->save();
        flash()->success('Slide has been updated');
        return redirect()->route('admin.slide');
    }

    public function deleteSlider(Request $request)
    {
        $validatedAttr = $request->validate([
            'slide_id' => 'required|numeric',
        ]);

        $slider = Slider::find($validatedAttr['slide_id']);
        if (!$slider) {
            flash()->error('Oh! somethings went wrong!');
            return redirect()->route('admin.slide');
        }

        $oldImagePath = public_path('uploads/slider/' . $slider->image);
        if (File::exists($oldImagePath)) {
            File::delete($oldImagePath);
        }
        $slider->delete();
        flash()->success('Slider has been deleted');
        return redirect()->route('admin.slide');
    }

    protected function slideImageHandelar($image, $name)
    {
        $path = public_path('uploads/slider');
        $img = Image::read($image->path());
        $img->cover(400, 689, 'top');

        $img->resize(400, 689, function ($constraint) {
            $constraint->aspectRatio();
        })->save($path . '/' . $name);

    }
}
