@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body mx-auto">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <a href="{{ route('products.index') }}" class="btn text-center btn-lg btn-light btn-outline-success btn-block">{{ __('Products') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
