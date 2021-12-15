<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $category = $request->query('category');
        $search = $request->query('search');

        $query = Product::query();

        if (!empty($category)) {
            $query->where('category_id', $category);
        }

        if (!empty($search)) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('price', 'like', '%' . $search . '%')
                ->orWhere('sku', 'like', '%' . $search . '%')
                ;
        }

        // $products = Product::paginate(3);
        $products = $query->paginate(3);
        $categories = Category::pluck('name', 'id');
        return view('admin.product.index', [
            'products'=>$products,
            'categories' => $categories,
            'filterSearch' => $search,
            'filterCategory' => $categories
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::pluck('name', 'id');
        return view('admin.product.create', ['categories' => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $inputs = $request->all();
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required',
            'price' => 'required|numeric',
            'sku' => 'required',
            'status' => 'required|in:active,inactiv',
            'image' => 'nullable|image',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');

            if ($image->isValid()) {
                $imageName = time() . "." . $image->getClientOriginalExtension();
                $image->storeAs('public/product', $imageName);
                $inputs['image'] = $imageName;
            } else {
            unset($inputs['image']);
            }
        } else {
            unset($inputs['image']);
        }

        
        $result = Product::create($inputs);

        if ($result) {
            return redirect()->route('product.index')->with('success', 'Add data success!');
        } else {
            return redirect()->route('product.index')->with('failed', 'Add data failed!');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return view('admin.product.show', ['product' => $product]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $categories = Category::pluck('name', 'id');
        return view('admin.product.edit', ['product' => $product, 'categories' => $categories]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $inputs = $request->all();
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required',
            'price' => 'required|numeric',
            'sku' => 'required',
            'status' => 'required|in:active,inactiv',
            'image' => 'nullable|image',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');

            if ($image->isValid()) {

                // hapus gambar
                if (!empty($product->image) && Storage::exists('public/product/' . $product->image)) {
                    Storage::delete('public/product/' . $product->image);
                }



                $imageName = time() . "." . $image->getClientOriginalExtension();
                $image->storeAs('public/product', $imageName);
                $inputs['image'] = $imageName;
            } else {
            unset($inputs['image']);
            }
        } else {
            unset($inputs['image']);
        }

        
        $result = $product->update($inputs);

        if ($result) {
            return redirect()->route('product.index')->with('success', 'Update data success!');
        } else {
            return redirect()->route('product.index')->with('failed', 'Update data failed!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        // hapus gambar
        if (!empty($product->image) && Storage::exists('public/product/' . $product->image)) {
            Storage::delete('public/product/' . $product->image);
        }

        $result = $product->delete();

        if ($result) {
            return redirect()->route('product.index')->with('success', 'Delete data success!');
        } else {
            return redirect()->route('product.index')->with('failed', 'Delete data failed!');
        }
    }
}
