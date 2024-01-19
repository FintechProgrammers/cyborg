<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    function index()
    {
        $data['news'] = News::get();

        return view('admin.news.index', $data);
    }

    function create()
    {
        return view('admin.news.create');
    }

    function store(Request $request)
    {
        $request->validate([
            'title'         => 'required',
            'news_body'     => 'required',
            'photo'         => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $image = uploadFile($request->file('photo'), "news", "do_spaces");
        }

        News::create([
            'title'     => $request->title,
            'content'   => $request->news_body,
            'image'     =>  $image,
            'status'    => 'draft'
        ]);

        return redirect()->route('admin.news.index')->with('success', 'News created successfully.');
    }

    function show(News $news)
    {

        $data['news'] = $news;

        return view('admin.news.edit', $data);
    }

    function publish(News $news)
    {
        $news->update([
            'status'  => 'published'
        ]);

        return response()->json(['success' => true, 'message' => 'News published successfully.']);
    }

    function unpublish(News $news)
    {
        $news->update([
            'status'  => 'draft'
        ]);

        return response()->json(['success' => true, 'message' => 'News unpublished successfully.']);
    }


    function update(Request $request, News $news)
    {
        $request->validate([
            'title'         => 'required',
            'news_body'     => 'required',
            'photo'         => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $image = $news->image;

        if ($request->hasFile('photo')) {
            $image = uploadFile($request->file('photo'), "news", "do_spaces");
        }

        $news->update([
            'title'     => $request->title,
            'content'   => $request->news_body,
            'image'     =>  $image,
        ]);

        return redirect()->route('admin.news.index')->with('success', 'News created successfully.');
    }

    function destroy(News $news)
    {
        $news->delete();

        return response()->json(['success' => true, 'message' => 'News deleted successfully']);
    }
}
