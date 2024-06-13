<?php

namespace App\Http\Controllers\account;

use App\Hutang;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CatatanHutangController extends Controller
{
    /**
     * CatatanhutangController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('account.catatan_hutang.index');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Validation\ValidationException
     */
    public function check(Request $request)
    {
        //set validasi required
        $this->validate($request, [
            'tanggal_awal'     => 'required',
            'tanggal_akhir'    => 'required',
        ],
            //set message validation
            [
                'tanggal_awal.required'  => 'Silahkan Pilih Tanggal Awal!',
                'tanggal_akhir.required' => 'Silahkan Pilih Tanggal Akhir!',
            ]
        );

        $tanggal_awal  = $request->input('tanggal_awal');
        $tanggal_akhir = $request->input('tanggal_akhir');

        $hutang = hutang::select('hutang.id', 'hutang.category_id', 'hutang.user_id', 'hutang.nominal', 'hutang.hutang_date', 'hutang.description', 'categories_hutang.id as id_category', 'categories_hutang.name')
            ->join('categories_hutang', 'hutang.category_id', '=', 'categories_hutang.id', 'LEFT')
            ->whereDate('hutang.hutang_date', '>=', $tanggal_awal)
            ->whereDate('hutang.hutang_date', '<=', $tanggal_akhir)
            ->paginate(10)
            ->appends(request()->except('page'));

        return view('account.catatan_hutang.index', compact('hutang', 'tanggal_awal', 'tanggal_akhir'));
    }
}
