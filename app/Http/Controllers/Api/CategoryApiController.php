<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryApiController extends Controller
{
      /**
      *   @OA\Get(
      *        path="/api/category/all",
      *        operationId="get-all-category",
      *        summary="Get all category",
      *        description="get all category",
      *        tags={"Category"},
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
        $Categories = Category::all();

        return response()->json([
            'success' => true,
            'message' => 'Get data success',
            'data' => $Categories,
            ]);
    }

      /**
      *   @OA\Post(
      *        path="/api/category",
      *        operationId="create-category",
      *        summary="Create Category",
      *        description="Create Category",
      *        tags={"Category"},
      *        security={
      *             {"api_key": {}}
      *        },
      *        @OA\RequestBody(
      *             @OA\MediaType(
      *                  mediaType="application/x-www-form-urlencoded",
      *                  @OA\Schema(
      *                       @OA\Property(
      *                            property="name",
      *                            description="name",
      *                            type="string",
      *                       ),
      *                       @OA\Property(
      *                            property="status",
      *                            description="Status",
      *                            type="string",
      *                            enum={"active","inactive"},
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
          $request->validate([
               'name' => 'required',
               'status' => 'required|in:active,inactive'
          ]);

          $result = Category::create($request->all());

          if ($result) {
               return response()->json([
                    'success' => true,
                    'message' => 'Create data success',
                    'data' => $result,
               ]);
          } else {
               return response()->json([
                    'success' => false,
                    'message' => 'Create data failed',
                    'data' => null,
               ]);
          }
    }

      /**
      *   @OA\Get(
      *        path="/api/category/{category_id}",
      *        operationId="show-detil-category",
      *        summary="Get detil category",
      *        description="get detil category",
      *        tags={"Category"},
      *        security={
      *             {"api_key": {}}
      *        },
      *        @OA\Parameter(
      *             name="category_id",
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
    public function show(Category $id)
    {
          $category = Category::find($id);

          if (empty($category)) {
               return response()->json([
                    'message' => 'Category not found',
                    'data' => null,
               ], 404);
          } else {
               return response()->json([
                    'success' => true,
                    'message' => 'Get data success',
                    'data' => $category,
               ]);
          }
    }

      /**
      *   @OA\Put(
      *        path="/api/category/{category_id}",
      *        operationId="update-category",
      *        summary="Update Category",
      *        description="Update Category",
      *        tags={"Category"},
      *        security={
      *             {"api_key": {}}
      *        },
      *        @OA\Parameter(
      *             name="category_id",
      *             in="path",
      *        ),
      *        @OA\RequestBody(
      *             @OA\MediaType(
      *                  mediaType="application/x-www-form-urlencoded",
      *                  @OA\Schema(
      *                       @OA\Property(
      *                            property="name",
      *                            description="name",
      *                            type="string",
      *                       ),
      *                       @OA\Property(
      *                            property="status",
      *                            description="Status",
      *                            type="string",
      *                            enum={"active","inactive"},
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
      *        @OA\Response(
      *             response=404,
      *             description="Data not found",
      *        ),
      *   )
      */
    public function update(Request $request, $id)
    {
          $request->validate([
              'name' => 'required',
              'status' => 'required|in:active,inactive'
          ], [
               'name.required' => 'Nama Harus diisi.',
               'status.required' => 'Status Harus diisi.',
               'status.in' => 'Status tidak valid.',
          ]);
          
          $category = Category::find($id);
          
          if (Empty($category)) {
               return response()->json([
                    'message' => 'Category not found',
                    'data' => null,
               ], 404);
          } else {
               $result = $category->update($request->all());
               if ($result) {
                    // success
                    return response()->json([
                         'success' => true,
                         'message' => 'Update data success',
                         'data' => $category,
                    ]);
               } else {
                    // failed
                    return response()->json([
                         'success' => false,
                         'message' => 'Update data failed',
                         'data' => $category,
                    ]);
               }
          }
    }

      /**
      *   @OA\Delete(
      *        path="/api/category/{category_id}",
      *        operationId="delete-category",
      *        summary="Delete category",
      *        description="delete category",
      *        tags={"Category"},
      *        security={
      *             {"api_key": {}}
      *        },
      *        @OA\Parameter(
      *             name="category_id",
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
          $category = Category::find($id);

          if (empty($category)) {
               return response()->json([
                    'message' => 'Category not found',
                    'data' => null,
               ], 404);
          } else {

               $products = $category->products;
               
               // hapus data product
               foreach ($products as $product) {
                    // hapus gambar
                    if (!empty($product->image) && Storage::exists('public/product/' . $product->image)) {
                         Storage::delete('public/product/' . $product->image);
                    }

                    $product->delete();
               }
          
               $result = $category->delete();

               if ($result) {
                    // success
                    return response()->json([
                         'success' => true,
                         'message' => 'Delete data success',
                         'data' => null,
                    ]);
               } else {
                    // failed
                    return response()->json([
                         'success' => false,
                         'message' => 'Delete data failed',
                         'data' => null,
                    ]);
               }
          }
     }
}