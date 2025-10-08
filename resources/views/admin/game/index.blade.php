@extends('layouts.admin')
@section('content')
    <div class="row">
        <!-- ============================================================== -->
        <!-- data table  -->
        <!-- ============================================================== -->
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example" class="table table-striped table-bordered second" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Image</th>
                                    <th>Rules</th>
                                    <th>Description</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($games as $game)
                                    <tr>
                                        <td>{{ $game->title }}</td>
                                        <td><img src="{{ asset($game->image) }}" alt="" style="width: 250px"></td>
                                        <td>
                                            @php
                                                $rules = json_decode($game->rules, true);
                                            @endphp
                                            @if (!empty($rules))
                                                <ul>
                                                    @foreach ($rules as $rule)
                                                        <li>{{ $rule }}</li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <span class="text-muted">No rules added</span>
                                            @endif
                                        </td>
                                        <td>{{ $game->description }}</td>
                                        <td>
                                            <a href="{{ route('admin.game.edit', $game->id) }}"
                                                class="btn btn-sm btn-primary">Edit</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- end data table  -->
        <!-- ============================================================== -->
    </div>
@endsection
