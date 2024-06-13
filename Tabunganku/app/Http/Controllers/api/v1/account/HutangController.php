<?php

namespace App\Http\Controllers\api\v1\account;

use App\Hutang;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class HutangController extends Controller
{
    /**
     * HutangController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $hutang = DB::table('hutang')
            ->select('hutang.id', 'hutang.category_id', 'hutang.user_id', 'hutang.nominal', 'hutang.hutang_date', 'hutang.description', 'categories_hutang.id as id_category', 'categories_hutang.name as category_name')
            ->join('categories_hutang', 'hutang.category_id', '=', 'categories_hutang.id', 'LEFT')
            ->where('hutang.user_id', Auth::user()->id)
            ->orderBy('hutang.created_at', 'DESC')
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $hutang
        ],200);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'nominal'        => 'required',
            'hutang_date'     => 'required',
            'category_id'    => 'required',
            'description'    => 'required',

        ],
            [
                'nominal.required' => 'Masukkan Nominal hutang!',
                'hutang_date.required' => 'Silahkan Pilih Tanggal!',
                'category_id.required' => 'Silahkan Pilih Kategori!',
                'description.required' => 'Masukkan Keterangan!',
            ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'data'    => $validator->errors()
            ],401);

        } else {

            Hutang::create([
                'user_id'       => Auth::user()->id,
                'hutang_date'   => $request->input('hutang_date'),
                'category_id'   => $request->input('category_id'),
                'nominal'       => str_replace(",", "", $request->input('nominal')),
                'description'   => $request->input('description'),
            ]);

            return response()->json([
                'success' => true,
                'data'    => 'Data Berhasil Disimpan !'
            ],200);

        }
    }

}
