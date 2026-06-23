@extends('layouts.admin')

@section('content')
    <div class="admin-card">
        <div class="card-header">
            <span>Games Library</span>
            <a href="{{ route('admin.game.create') }}" class="btn-admin btn-admin-sm btn-admin-success">
                <i class="fas fa-plus"></i> Add Game
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered second admin-table" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Image</th>
                            <th>Rules</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($games as $game)
                            <tr>
                                <td class="font-weight-bold">{{ $game->title }}</td>
                                <td>
                                    <img src="{{ asset($game->image) }}" alt="{{ $game->title }}"
                                        style="width: 120px; border-radius: 10px;">
                                </td>
                                <td>
                                    @php $rules = json_decode($game->rules, true); @endphp
                                    @if (!empty($rules))
                                        <ul class="mb-0 pl-3">
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
                                    <div class="admin-actions">
                                        <a href="{{ route('admin.game.edit', $game->id) }}"
                                            class="btn-admin btn-admin-sm btn-admin-primary">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="{{ route('admin.game.levels', $game->id) }}"
                                            class="btn-admin btn-admin-sm btn-admin-info">
                                            <i class="fas fa-layer-group"></i> Levels
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
