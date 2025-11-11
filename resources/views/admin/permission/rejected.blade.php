@extends('layouts.admin')
@section('content')
  <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card">
                <h5 class="card-header">{{ $heading }}</h5>
                <div class="card-body">

                    <table class="table table-striped table-bordered second"  id="example">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Username</th>
                                <th>Tournament</th>
                                <th>Tournament Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($requests as $index => $request)
                                <tr>
                                    <td>{{ $index }}</td>
                                    <td>{{ $request->user->username  }}</td>
                                    <td>{{ $request->tournament->name  }}</td>
                                    <td>{{ $request->tournament->date  }}</td>
                                    <td>{{ $request->status }}</td>
                                    <td><a href="{{ route('request.approve', $request->id) }}" class="btn btn-primary">Approve</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
