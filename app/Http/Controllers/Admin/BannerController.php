<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BannerController extends Controller
{
    function index()
    {
        $data['banners'] = Banner::get();

        return view('admin.banner.index', $data);
    }

    function create()
    {
        return view('admin.banner.create');
    }

    function store(Request $request)
    {

        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $file = uploadFile($request->file('photo'), "banner", "do_spaces");
        }

        Banner::create([
            'file_url' => $file,
        ]);

        return redirect()->route('admin.banner.index')->with('success', 'Ads Banner created successfully');
    }

    function show(Banner $banner)
    {
        $data['banner'] = $banner;

        return view('admin.banner.edit', $data);
    }

    function enableBanner(Banner $banner)
    {
        $banner->update([
            'enabled' => true
        ]);

        return response()->json(['success' => true, 'message' => 'Enabled successfully.']);
    }

    function disableBanner(Banner $banner)
    {
        $banner->update([
            'enabled' => false
        ]);

        return response()->json(['success' => true, 'message' => 'Disabled successfully.']);
    }

    function update(Request $request, Banner $banner)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $file = uploadFile($request->file('photo'), "banner", "do_spaces");
        }

        $banner->update([
            'file_url' => $file,
        ]);

        return redirect()->route('admin.banner.index')->with('success', 'Ads Banner updated successfully');
    }

    function destroy(Banner $banner)
    {
        $banner->delete();

        return response()->json(['success' => true, 'message' => 'Ads Banner deleted successfully']);
    }
}
