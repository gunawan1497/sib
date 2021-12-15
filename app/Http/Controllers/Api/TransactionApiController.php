<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;
use App\Imports\TransactionImport;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use phpDocumentor\Reflection\PseudoTypes\True_;
use PhpParser\Node\Stmt\Catch_;
use PhpParser\Node\Stmt\Return_;

class TransactionApiController extends Controller
{
     /**
      *   @OA\Get(
      *        path="/api/transaction/all",
      *        operationId="get-all-transaction",
      *        summary="Get all transaction",
      *        description="get all transaction",
      *        tags={"Transaction"},
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
        $transactions = Transaction::all();

        return response()->json([
            'success' => true,
            'message' => "Get data success",
            'data' => TransactionResource::collection($transactions)
        ]);
    }

      /**
      *   @OA\Post(
      *        path="/api/transaction/import",
      *        operationId="import-product",
      *        summary="Import product",
      *        description="import product",
      *        tags={"Transaction"},
      *        security={
      *             {"api_key": {}}
      *        },
      *        @OA\RequestBody(
      *             @OA\MediaType(
      *                  mediaType="multipart/form-data",
      *                  @OA\Schema(
      *                       @OA\Property(
      *                            property="import",
      *                            description="Import excel",
      *                            type="file",
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
    public function import(Request $request)
    {
        $request->validate([
            'import' => 'required|file|mimes:xlsx,xls',
       ]);

     try {
          Excel::import(new TransactionImport(), $request->file('import'));
               Return response()->json([
                    'success' => True,
                    'message' => 'Import data success',
                    'data' => null
               ]);
          } Catch (\Exception $e) {
               Return response()->json([
                    'success' => false,
                    'message' => 'Import data failed',
                    'data' => null
               ]);
          }
    }
}
