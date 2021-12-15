<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResourse;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductApiController extends Controller
{

     /**
      *   @OA\Get(
      *        path="/api/product/all",
      *        operationId="get-all-product",
      *        summary="Get all product",
      *        description="get all product",
      *        tags={"Product"},
      *        security={
      *             {"api_key": {}}
      *        },
      *        @OA\Response(
      *             response=200,
      *             description="Success",
      *             @OA\MediaType(
      *                  mediaType="application/json"
      *             ),
      *        ),
      *        @OA\Response(
      *             response=401,
      *             description="Unauthenticated",
      *        ),
      *   )
      */
    public function all()
    {
        $products = Product::all();

        return response()->json([
            'success' => false,
            'message' => 'Delete data failed',
            'data' => ProductResourse::collection($products),
       ]);
    }

      /**
      *   @OA\Get(
      *        path="/api/product/{product_id}",
      *        operationId="get-detail-product",
      *        summary="Get detail product",
      *        description="get detail product",
      *        tags={"Product"},
      *        security={
      *             {"api_key": {}}
      *        },
      *        @OA\Parameter(
      *             name="product_id",
      *             in="path",
      *        ),
      *        @OA\Response(
      *             response=200,
      *             description="Success",
      *             @OA\MediaType(
      *                  mediaType="application/json"
      *             ),
      *        ),
      *        @OA\Response(
      *             response=401,
      *             description="Unauthenticated",
      *        ),
      *   )
      */
    public function show($id)
    {
        $product = Product::find($id);

        if (empty($product)) {
            return response()->json([
                    'message' => 'Product not found',
                    'data' => null,
            ], 404);
        } else {
            return response()->json([
                'success' => true,
                'message' => 'Get data success',
                'data' => new ProductResourse($product),
           ]);
        }
    }

      /**
      *   @OA\Post(
      *        path="/api/product",
      *        operationId="create-product",
      *        summary="Create product",
      *        description="Create product",
      *        tags={"Product"},
      *        security={
      *             {"api_key": {}}
      *        },
      *        @OA\RequestBody(
      *             @OA\MediaType(
      *                  mediaType="multipart/form-data",
      *                  @OA\Schema(
      *                       @OA\Property(
      *                            property="category_id",
      *                            description="Category id",
      *                            type="integer",
      *                       ),
      *                       @OA\Property(
      *                            property="name",
      *                            description="Name",
      *                            type="string",
      *                       ),
      *                       @OA\Property(
      *                            property="price",
      *                            description="Price",
      *                            type="integer",
      *                       ),
      *                       @OA\Property(
      *                            property="sku",
      *                            description="SKU",
      *                            type="string",
      *                       ),
      *                       @OA\Property(
      *                            property="status",
      *                            description="Status",
      *                            type="string",
      *                            enum={"active","inactive"},
      *                       ),
      *                       @OA\Property(
      *                            property="image",
      *                            description="Image",
      *                            type="file",
      *                       ),
      *                       @OA\Property(
      *                            property="description",
      *                            description="Description",
      *                            type="string",
      *                       ),
      *                  ),
      *             ),
      *        ),
      *        
      *        @OA\Response(
      *             response=200,
      *             description="Success",
      *             @OA\MediaType(
      *                  mediaType="application/json"
      *             ),
      *        ),
      *        @OA\Response(
      *             response=422,
      *             description="Validation error",
      *        ),
      *        @OA\Response(
      *             response=401,
      *             description="Unauthenticated",
      *        ),
      *   )
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

        if (empty($result)) {
            return response()->json([
                    'success' => false,
                    'message' => 'Create data failed',
                    'data' => null,
            ]);
        } else {
            return response()->json([
                'success' => true,
                'message' => 'Create data success',
                'data' => new ProductResourse($result),
           ]);
        }
    }

      /**
      *   @OA\Post(
      *        path="/api/product/update/{product_id}",
      *        operationId="update-product",
      *        summary="Update product",
      *        description="update product",
      *        tags={"Product"},
      *        security={
      *             {"api_key": {}}
      *        },
      *        @OA\Parameter(
      *             name="product_id",
      *             in="path",
      *        ),
      *        @OA\RequestBody(
      *             @OA\MediaType(
      *                  mediaType="multipart/form-data",
      *                  @OA\Schema(
      *                       @OA\Property(
      *                            property="category_id",
      *                            description="Category id",
      *                            type="integer",
      *                       ),
      *                       @OA\Property(
      *                            property="name",
      *                            description="Name",
      *                            type="string",
      *                       ),
      *                       @OA\Property(
      *                            property="price",
      *                            description="Price",
      *                            type="integer",
      *                       ),
      *                       @OA\Property(
      *                            property="sku",
      *                            description="SKU",
      *                            type="string",
      *                       ),
      *                       @OA\Property(
      *                            property="status",
      *                            description="Status",
      *                            type="string",
      *                            enum={"active","inactive"},
      *                       ),
      *                       @OA\Property(
      *                            property="image",
      *                            description="Image",
      *                            type="file",
      *                       ),
      *                       @OA\Property(
      *                            property="description",
      *                            description="Description",
      *                            type="string",
      *                       ),
      *                  ),
      *             ),
      *        ),
      *        
      *        @OA\Response(
      *             response=200,
      *             description="Success",
      *             @OA\MediaType(
      *                  mediaType="application/json"
      *             ),
      *        ),
      *        @OA\Response(
      *             response=422,
      *             description="Validation error",
      *        ),
      *        @OA\Response(
      *             response=401,
      *             description="Unauthenticated",
      *        ),
      *   )
      */
    public function update(Request $request, $id)
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

        $product = Product::find($id);

        if (empty($product)) {
            // product empty
            return response()->json([
                    'message' => 'Product not found',
                    'data' => null,
            ], 404);
        } else {
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
        }

        $result = $product->update($inputs);

        if (!$result) {
            return response()->json([
                    'success' => false,
                    'message' => 'Update data failed',
                    'data' => null,
            ]);
        } else {
            return response()->json([
                'success' => true,
                'message' => 'Update data success',
                'data' => new ProductResourse($product),
           ]);
        }
    }


     /**
      *   @OA\Delete(
      *        path="/api/product/{product_id}",
      *        operationId="delete-product",
      *        summary="Delete product",
      *        description="delete product",
      *        tags={"Product"},
      *        security={
      *             {"api_key": {}}
      *        },
      *        @OA\Parameter(
      *             name="product_id",
      *             in="path",
      *        ),
      *        @OA\Response(
      *             response=200,
      *             description="Success",
      *             @OA\MediaType(
      *                  mediaType="application/json"
      *             ),
      *        ),
      *        @OA\Response(
      *             response=401,
      *             description="Unauthenticated",
      *        ),
      *        @OA\Response(
      *             response=404,
      *             description="Data not found",
      *        ),
      *   )
      */
    public function destroy($id)
    {
        $product = Product::find($id);

        if (empty($product)) {
            // product empty
            return response()->json([
                'message' => 'Product not found',
                'data' => null,
            ], 404);
        } else {   
            // hapus gambar
            if (!empty($product->image) && Storage::exists('public/product/' . $product->image)) {
                Storage::delete('public/product/' . $product->image);
            }
            $result = $product->delete();

            if (!$result) {
                return response()->json([
                        'success' => false,
                        'message' => 'Delete data failed',
                        'data' => null,
                ]);
            } else {
                return response()->json([
                    'success' => true,
                    'message' => 'Delete data success',
                    'data' => null,
                ]);
            }
        }          
    }
}
