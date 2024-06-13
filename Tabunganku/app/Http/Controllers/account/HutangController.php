<?php

namespace App\Http\Controllers\account;

use App\CategoriesHutang;
use App\Hutang;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HutangController extends Controller
{
    /**
     * HutangController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $hutang = DB::table('hutang')
            ->select('hutang.id', 'hutang.category_id', 'hutang.user_id', 'hutang.nominal', 'hutang.hutang_date', 'hutang.description', 'categories_hutang.id as id_category', 'categories_hutang.name')
            ->join('categories_hutang', 'hutang.category_id', '=', 'categories_hutang.id', 'LEFT')
            ->where('hutang.user_id', Auth::user()->id)
            ->orderBy('hutang.created_at', 'DESC')
            ->paginate(10);
        return view('account.hutang.index', compact('hutang'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function search(Request $request)
    {
        $search = $request->get('q');
        $hutang = DB::table('hutang')
            ->select('hutang.id', 'hutang.category_id', 'hutang.user_id', 'hutang.nominal', 'hutang.hutang_date', 'hutang.description', 'categories_hutang.id as id_category', 'categories_hutang.name')
            ->join('categories_hutang', 'hutang.category_id', '=', 'categories_hutang.id', 'LEFT')
            ->where('hutang.user_id', Auth::user()->id)
            ->where('hutang.description', 'LIKE', '%' .$search. '%')
            ->orderBy('hutang.created_at', 'DESC')
            ->paginate(10);
        return view('account.hutang.index', compact('hutang'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Categorieshutang::where('user_id', Auth::user()->id)
        ->get();
        return view('account.hutang.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //set validasi required
        $this->validate($request, [
            'nominal'       => 'required',
            'hutang_date'    => 'required',
            'category_id'   => 'required',
            'description'   => 'required'
        ],
            //set message validation
            [
                'nominal.required' => 'Masukkan Nominal hutang / Pemasukan!',
                'hutang_date.required' => 'Silahkan Pilih Tanggal!',
                'category_id.required' => 'Silahkan Pilih Kategori!',
                'description.required' => 'Masukkan Keterangan!',
            ]
        );

        //Eloquent simpan data
        $save = Hutang::create([
            'user_id'       => Auth::user()->id,
            'hutang_date'   => $request->input('hutang_date'),
            'category_id'   => $request->input('category_id'),
            'nominal'       => str_replace(",", "", $request->input('nominal')),
            'description'   => $request->input('description'),
        ]);
        //cek apakah data berhasil disimpan
        if($save){
            //redirect dengan pesan sukses
            return redirect()->route('account.hutang.index')->with(['success' => 'Data Berhasil Disimpan!']);
        }else{
            //redirect dengan pesan error
            return redirect()->route('account.hutang.index')->with(['error' => 'Data Gagal Disimpan!']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Hutang $hutang)
    {
        $categories = CategoriesHutang::where('user_id', Auth::user()->id)
            ->get();
        return  view('account.hutang.edit', compact('hutang', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Hutang $hutang)
    {
        //set validasi required
        $this->validate($request, [
            'nominal'       => 'required',
            'hutang_date'    => 'required',
            'category_id'   => 'required',
            'description'   => 'required'
        ],
            //set message validation
            [
                'nominal.required' => 'Masukkan Nominal hutang / Pemasukan!',
                'hutang_date.required' => 'Silahkan Pilih Tanggal!',
                'category_id.required' => 'Silahkan Pilih Kategori!',
                'description.required' => 'Masukkan Keterangan!',
            ]
        );

        //Eloquent simpan data
        $update = Hutang::whereId($hutang->id)->update([
            'user_id'       => Auth::user()->id,
            'category_id'   => $request->input('category_id'),
            'hutang_date'    => $request->input('hutang_date'),
            'nominal'       => str_replace(",", "", $request->input('nominal')),
            'description'   => $request->input('description'),
        ]);
        //cek apakah data berhasil disimpan
        if($update){
            //redirect dengan pesan sukses
            return redirect()->route('account.hutang.index')->with(['success' => 'Data Berhasil Diupdate!']);
        }else{
            //redirect dengan pesan error
            return redirect()->route('account.hutang.index')->with(['error' => 'Data Gagal Diupdate!']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = Hutang::find($id)->delete($id);

        if($delete){
            return response()->json([
                'status' => 'success'
            ]);
        }else{
            return response()->json([
                'status' => 'error'
            ]);
        }
    }
}
