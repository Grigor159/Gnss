@extends('layouts.admin')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Products</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Products</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Products Table</h3>
                        <div class="card-tools">
                            <div>
                                <a href="{{ route('products.create') }}" class="btn btn-block btn-primary">Add Product</a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Name</th>
                                    <th style="width: 200px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                    <tr>
                                        <td>{{ $loop->iteration }}.</td>
                                        <td>{{ $product->name }}</td>
                                        <td>
                                            <span><a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning">Edit</a></span>
                                            <span>
                                                <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="submit" value="DELETE" class="btn btn-danger">
                                                </form>
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="card-footer">
                            {{ $products->withQueryString()->links() }}
                        </div>
                    </div>


                </div>

            </div>

        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection
