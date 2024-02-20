<x-forms.input-grid col1="2" col2="6" label="Nama" name="name" value="{{ $product->name ?? '' }}" placeholder="Masukan nama product..."></x-forms.input-grid>
<x-forms.input-grid col1="2" col2="6" label="Harga" name="price" value="{{ $product->price ?? '' }}" placeholder="Masukan harga product..."></x-forms.input-grid>
<x-forms.input-grid col1="2" col2="6" label="Harga" name="price_capital" value="{{ $product->price_capital ?? '' }}" placeholder="Masukan harga capital product..."></x-forms.input-grid>
<x-forms.input-grid col1="2" col2="6" label="Penjualan" name="sell" value="{{ $product->sell ?? '' }}" placeholder="Masukan  penjualan product..."></x-forms.input-grid>
<x-forms.textarea-grid col1="2" col2="6" label="Harga" name="description" value="{{ $product->description ?? '' }}" placeholder="Masukan deskripsi product..."></x-forms.textarea-grid>

@push('script')
<script src="{{ asset('assets/js/apps/user.js?v=' . random_string(6)) }}"></script>
@endpush
