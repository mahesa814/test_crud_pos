<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private ProductService $product_service;

    public function __construct()
    {
        $this->product_service = new ProductService;
    }
    public function get(Request $request)
    {
        $products = $this->product_service->get_list_paged($request);
        $count = $this->product_service->get_list_count($request);

        $data = [];
        $no = $request->start;
        $user = auth()->user();

        foreach ($products as $product) {
            $no++;
            $row = [];
            $row[] = $no;
            $row[] = $product->name;
            $row[] = $product->price;
            $row[] = $product->price_capital;
            $row[] = $product->sell;
            $row[] = $product->description;
            // Tombol Detail
            $detailButton = "<a href='" . route("app.products.show", $product->id) . "' class='btn btn-info btn-sm me-3'>Detail</a>";

            // Form untuk Penghapusan
            $deleteForm = form_delete("formUser$product->id", route("app.products.destroy", $product->id));

            // Menggabungkan keduanya menjadi satu string
            $combinedString = "<div class='d-flex align-center'>" . $detailButton . ' ' . $deleteForm . "</div>";

            $row[] = $combinedString;
            $data[] = $row;
        }
        $output = [
            "draw" => $request->draw,
            "recordsTotal" => $count,
            "recordsFiltered" => $count,
            "data" => $data
        ];

        return \response()->json($output, 200);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->view_admin("admin.products.index", "Product Management", [], TRUE);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [
            "roles" => Role::orderBy("role_name", "ASC")->get()
        ];
        return $this->view_admin("admin.products.create", "Tambah Product", $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        $response = $this->product_service->store($request);
        return \response_json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $data = [
            "product" => $product
        ];
        return $this->view_admin("admin.products.show", "Detail Product", $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $data = [
            "product" => $product,
        ];

        return $this->view_admin("admin.products.edit", "Edit Product", $data);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, Product $product)
    {
        $response = $this->product_service->update($request, $product);
        return \response_json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();
        $response = \response_success_default("Berhasil hapus product!", FALSE, \route("app.products.index"));
        return \response_json($response);
    }
}
