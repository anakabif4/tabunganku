<?php

namespace App\Http\Controllers\api\v1\account;

use App\Hutang;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CatatanHutangController extends Controller
{
    /**
     * CatatanhutangController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'tanggal_awal'     => 'required',
            'tanggal_akhir'    => 'required',

        ],
            [
                'tanggal_awal.required'  => 'Silahkan Pilih Tanggal Awal!',
                'tanggal_akhir.required' => 'Silahkan Pilih Tanggal Akhir!',
            ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'data'    => $validator->errors()
            ],401);

        } else {

            $tanggal_awal  = $request->input('tanggal_awal');
            $tanggal_akhir = $request->input('tanggal_akhir');

            $hutang = Hutang::select('hutang.id', 'hutang.category_id', 'hutang.user_id', 'hutang.nominal', 'hutang.hutang_date', 'hutang.description', 'categories_hutang.id as id_category', 'categories_hutang.name')
                ->join('categories_hutang', 'hutang.category_id', '=', 'categories_hutang.id', 'LEFT')
                ->whereDate('hutang.hutang_date', '>=', $tanggal_awal)
                ->whereDate('hutang.hutang_date', '<=', $tanggal_akhir)
                ->get();

            return response()->json([
                'success' => true,
                'data'    => $hutang
            ],401);

        }
    }

}
