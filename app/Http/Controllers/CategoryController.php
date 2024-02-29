<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    //index
    public function index()
    {
        $categories = Category::paginate(10);
        return view('pages.categories.index', compact('categories'));
    }

    //create
    public function create()
    {
        return view('pages.categories.create');
    }

    //store
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'image' => 'required|mimes:jpeg,jpg,png,gif,svg|max:2048',
        ]);

        //store the request

        $category = new Category;
        $category->name = $request->name;
        $category->description = $request->description;


        //save image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image->storeAs('public/categories/', $category->id . '.' . $image->getClientOriginalExtension());
            $category->image = 'storage/categories' . $category->id . '.' . $image->getClientOriginalExtension();
            $category->save();
        }

        return redirect()->route('categories.index')->with('success', 'Category Created Successfully');
    }

    //show
    public function show()
    {
        return view('pages.categories.show');
    }

    //edit
    public function edit($id)
    {
        $category = Category::find($id);
        return view('pages.categories.edit', compact('category'));
    }

    //update
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
        ]);

        //update the requested
        $category = Category::find($id);
        $category->name = $request->name;
        $category->description = $request->description;
        $category->save();

        //save image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image->storeAs('public/categories/', $category->id . '.' . $image->getClientOriginalExtension());
            $category->image = 'storage/categories' . $category->id . '.' . $image->getClientOriginalExtension();
            $category->save();
        }

        return redirect()->route('categories.index')->with('success', 'Category Update Successfully');
    }

    //destroy
    public function destroy($id)
    {
        //delete the requested
        $category = Category::find($id);
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Category Deleted Successfully');
    }
}
