@extends('layouts.admin')
@section('content')
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card">
                <h5 class="card-header">Edit Level</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.game.levels.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{ $level->id }}">
                    <input type="hidden" name="game_id" value="{{ $level->game_id }}">
                    <div class="form-group">
                        <label for="level_name">Level Name</label>
                        <input type="text" name="level_name" id="level_name" class="form-control" value="{{ $level->level_name }}">
                    </div>
                    <div class="form-group">
                        <label for="level_description">Level Description</label>
                        <textarea name="level_description" id="level_description" class="form-control">{{ $level->level_description }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="level_image">Level Image</label>
                        <input type="file" name="level_image" id="level_image" class="form-control">
                        @if ($level->level_image)
                            <img src="{{ asset($level->level_image) }}" alt="" style="width: 150px; margin-top:10px;">
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="level_status">Level Status</label>
                        <select name="level_status" id="level_status" class="form-control">
                            <option value="active" {{ $level->level_status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $level->level_status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="level_slug">Level Slug</label>
                        <input type="text" name="level_slug" id="level_slug" class="form-control" value="{{ $level->level_slug }}">
                    </div>
                    <button type="submit" class="btn btn-primary">Update Level</button>
                </div>
            </div>
        </div>
    </div>
@endsection
