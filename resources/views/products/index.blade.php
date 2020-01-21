@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        Products
                        <span class="float-right">
                            <a class="btn btn-sm btn-primary" href="{{ route('products.create') }}"> NEW </a>
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <tr>
                                    <td>Name</td>
                                    <td>Type</td>
                                    <td>Price</td>
                                    <td>Updated</td>
                                    <td> -</td>
                                </tr>
                                @forelse($products as $product)
                                    <tr>
                                        <td>{{ $product->name }}</td>
                                        <td>
                                            <span class="badge text-uppercase text-light bg-success">{{ $product->type }}</span>
                                        </td>
                                        <td>{{ $product->price }}</td>
                                        <td>
                                            {{ optional($product->updated_at)->format('d-m-Y') }}
                                            <small>{{ optional($product->updated_at)->format('H:i') }}</small>
                                        </td>
                                        <td>
                                            <a class="btn btn-sm btn-outline-primary"
                                               href="{{ route('products.edit', $product->id) }}"> EDIT </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4"> EMPTY SET</td>
                                    </tr>
                                @endforelse
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
