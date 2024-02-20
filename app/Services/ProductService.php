<?php

namespace App\Services;

use App\Http\Requests\ProductRequest;
use App\Http\Requests\UserRequest;
use App\Models\Product;
use App\Models\SessionToken;
use App\Models\User;
use App\Services\Cores\BaseService;
use App\Services\Cores\ErrorService;
use App\Validations\UserValidation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProductService extends BaseService
{
  /**
   * Generate query index page
   *
   * @param Request $request
   */
  private function generate_query_get(Request $request)
  {
    $column_search = ["products.name", "products.price",'products.price_capital'];
    $column_order = [NULL, "products.name", "products.price","products.price_capital"];
    $order = ["id" => "DESC"];

    $results = Product::query()
      ->where(function ($query) use ($request, $column_search) {
        $i = 1;
        if (isset($request->search)) {
          foreach ($column_search as $column) {
            if ($request->search["value"]) {
              if ($i == 1) {
                $query->where($column, "LIKE", "%{$request->search["value"]}%");
              } else {
                $query->orWhere($column, "LIKE", "%{$request->search["value"]}%");
              }
            }
            $i++;
          }
        }
      });

    if (isset($request->order) && !empty($request->order)) {
      $results = $results->orderBy($column_order[$request->order["0"]["column"]], $request->order["0"]["dir"]);
    } else {
      $results = $results->orderBy(key($order), $order[key($order)]);
    }

    if (auth()->user()->role_id != 1) {
      $results->where("role_id", "!=", 1);
    }

    return $results;
  }

  public function get_list_paged(Request $request)
  {
    $results = $this->generate_query_get($request);
    if ($request->length != -1) {
      $limit = $results->offset($request->start)->limit($request->length);
      return $limit->get();
    }
  }

  public function get_list_count(Request $request)
  {
    return $this->generate_query_get($request)->count();
  }

  /**
   * Store new user
   *
   * @param Request $request
   */
  public function store(ProductRequest $request)
  {
    try {
        $values = $request->validated();

        $product = Product::create($values);

        $response = \response_success_default("Berhasil menambahkan Product!", $product->id, route("app.products.show", $product->id));
    } catch (\Exception $e) {
      ErrorService::error($e, "Gagal store user!");
      $response = \response_errors_default();
    }

    return $response;
  }

  /**
   * Update new user
   *
   * @param Request $request
   * @param Product $user
   */
  public function update(ProductRequest $request, Product $product)
  {
    try {
      $product_id = $product->id;

      $values = $request->validated();

      $product->update($values);

      $response = \response_success_default("Berhasil update data Product!", $product_id, route("app.products.show", $product->id));
    } catch (\Exception $e) {
      ErrorService::error($e, "Gagal update user!");
      $response = \response_errors_default();
    }

    return $response;
  }
}
