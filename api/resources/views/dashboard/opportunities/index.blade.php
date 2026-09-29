@extends('layouts.admin')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Opportunities</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Opportunities</li>
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
                <h5 class="mb-2">Info</h5>
                <div class="row">

                    @foreach ($statuses as $status)
                        <div class="col-md-3 col-sm-6 col-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="far fa-window-restore"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{ $status->name }}</span>
                                    <a href="{{ route('opportunities.index', ['status' => $status->id]) }}" class="info-box-number">
                                        @php
                                            foreach ($opportunitiesByStatus as $key => $opp) {
                                                if ($status->id == $key) {
                                                    $count = count($opp);
                                                    break;
                                                } else {
                                                    $count = 0;
                                                }
                                            }
                                        @endphp
                                        <span class="text-green text-xl" style="line-height: 1;">{{ $count ?? 0 }}</span>
                                        <span class="text-dark">Opportunities</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>

            <div class="col-12">
                <h5 class="mb-2">Opportunities Table</h5>

                <div class="card">

                    <div class="card-header">
                        <h3 class="card-title">{{ isset($selectedStatus->name) ? $selectedStatus->name.' Table' : 'Opportunities Table' }}</h3>
                        <div class="card-tools">
                            <div>
                                <a href="{{ route('opportunities.create') }}" class="btn btn-block btn-primary">Add Opportunity</a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <table class="table stripped">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Name</th>
                                    <th>Product</th>
                                    <th>Count</th>
                                    <th>Status</th>
                                    <th style="width: 200px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($opportunities))
                                    @foreach ($opportunities as $opportunity)
                                        <tr>
                                            <td>{{ $loop->iteration }}.</td>
                                            <td>{{ $opportunity->customer->name }}</td>
                                            <td>{{ $opportunity->product->name }}</td>
                                            <td>{{ $opportunity->count }}</td>
                                            <td>{{ $opportunity->status->name }}</td>
                                            <td>
                                                <span><a href="{{ route('opportunities.show', $opportunity->id) }}" class="btn btn-info">View</a></span>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                {{ $opportunities->links() }}
            </div>

        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection
