@extends('layouts.admin.app')

@section('content')

  <div class="card">
    <div class="card-body">

      <a href="{{ route('app.products.edit', $product->id) }}" class="btn btn-info btn-sm mb-3">Edit</a>

      <div class="row">
        <div class="col-md-6">
          <table class="table">
              <tr>
                  <th>Nama</th>
                  <td>: {{ $product->name }}</td>
              </tr>
              <tr>
              <th>Harga</th>
              <td>: {{ $product->price }}</td>
            </tr>
            <tr>
              <th>Harga Capital</th>
              <td>: {{ $product->price_capital }}</td>
            </tr>
              <tr>
              <th>Penjualan</th>
              <td>: {{ $product->sell }}</td>
            </tr>
              <tr>
              <th>Deskripsi</th>
              <td>: {{ $product->description }}</td>
            </tr>
          </table>
        </div>
      </div>

    </div>
  </div>

@endsection
