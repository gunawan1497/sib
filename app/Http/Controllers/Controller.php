<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="SIB",
 *      description="Sistem Informasi Barang",
 *      @OA\Contact(
 *          email="nugroho201612@gmail.com"
 *      ),     
 * ),
 */

/**
 *  @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="Url from config file"
 *  )
 */

}