@extends('layouts.admin')

@section('style')
    <style>
        .card {
            background: #fff;
            border: 0;
        }

        .form-label {
            margin-bottom: 0.5rem;
            color: #212529;
            font-weight: 600;
        }

        .form-control,
        .form-select {
            border: 1px solid #dcdfe4;
            padding: 0.85rem 1rem;
            transition: all 0.2s ease-in-out;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.15rem rgba(13, 110, 253, 0.15);
        }

        textarea.form-control {
            resize: none;
        }

        .btn {
            font-weight: 500;
        }

        .card-header h4,
        .card-header h5 {
            margin-bottom: 0;
        }

        .rounded-4 {
            border-radius: 1rem !important;
        }

        .page-header-card {
            border-radius: 1rem;
            box-shadow: 0 0.125rem 0.75rem rgba(0, 0, 0, 0.06);
        }

        .level-form-card {
            border-radius: 1rem;
            box-shadow: 0 0.125rem 1rem rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .level-form-wrapper {
            display: none;
        }

        .level-form-wrapper.show {
            display: block;
            animation: fadeInDown 0.25s ease-in-out;
        }

        .table-card {
            border-radius: 1rem;
            box-shadow: 0 0.125rem 1rem rgba(0, 0, 0, 0.06);
            overflow: hidden;
        }

        .level-thumb {
            width: 70px;
            height: 50px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #e9ecef;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 600;
            text-transform: capitalize;
        }

        .status-active {
            background: rgba(25, 135, 84, 0.12);
            color: #198754;
        }

        .status-inactive {
            background: rgba(220, 53, 69, 0.12);
            color: #dc3545;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endsection

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <div class="card page-header-card">
                <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h3 class="mb-1">Game Levels</h3>
                        <p class="text-muted mb-0">Manage all levels for this game.</p>
                    </div>

                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <span class="badge bg-light text-dark px-3 py-2 rounded-pill">Game ID: {{ $game->id }}</span>
                        <button type="button" class="btn btn-primary rounded-3 px-4" id="toggleLevelFormBtn">
                            <i class="fa fa-plus me-1"></i> Add New Level
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Full width add new level form --}}
    <div class="row mb-4 level-form-wrapper" id="levelFormWrapper">
        <div class="col-12">
            <div class="card level-form-card">
                <div
                    class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <h4 class="fw-bold text-dark mb-1">Add New Level</h4>
                        <p class="text-muted mb-0">Create a new game level with details, image, and status.</p>
                    </div>
                    <button type="button" class="btn btn-outline-secondary rounded-3" id="closeLevelFormBtn">
                        <i class="fa fa-times me-1"></i> Close
                    </button>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('admin.game.levels.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="game_id" value="{{ $game->id }}">

                        <div class="row g-4">
                            <div class="col-md-6">
                                <label for="level_name" class="form-label">Level Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="level_name" id="level_name"
                                    class="form-control form-control-lg rounded-3" placeholder="Enter level name"
                                    value="{{ old('level_name') }}">
                                <small class="text-muted">Choose a clear and unique name for the level.</small>
                            </div>

                            <div class="col-md-6">
                                <label for="level_slug" class="form-label">Level Slug <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="level_slug" id="level_slug"
                                    class="form-control form-control-lg rounded-3" placeholder="e.g. jungle-adventure"
                                    value="{{ old('level_slug') }}">
                                <small class="text-muted">Used in URLs. Keep it lowercase and hyphen-separated.</small>
                            </div>
                            <div class="col-md-6">
                                <label for="level_image" class="form-label">Level Image</label>
                                <input type="file" name="level_image" id="level_image" class="form-control rounded-3">
                                <small class="text-muted">Upload a thumbnail or featured image for this level.</small>
                            </div>
                            {{-- <div class="col-md-6">
                                <label for="level_status" class="form-label">Level Status</label>
                                <select name="level_status" id="level_status" class="form-control form-select-lg rounded-3">
                                    <option value="active" {{ old('level_status') == 'active' ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="inactive" {{ old('level_status') == 'inactive' ? 'selected' : '' }}>
                                        Inactive</option>
                                </select>
                                <small class="text-muted">Set whether this level is visible and available.</small>
                            </div> --}}
                            <div class="col-12">
                                <label for="level_description" class="form-label">Level Description</label>
                                <textarea name="level_description" id="level_description" rows="5" class="form-control rounded-3"
                                    placeholder="Write a short description about this level...">{{ old('level_description') }}</textarea>
                            </div>

                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-end gap-2 flex-wrap">
                            <button type="button" class="btn btn-light border rounded-3 px-4" id="cancelLevelFormBtn">
                                Cancel
                            </button>
                            <button type="submit" class="btn btn-primary rounded-3 px-4 shadow-sm">
                                Save Level
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Levels table --}}
    <div class="row">
        <div class="col-12">
            <div class="card table-card">
                <div class="card-header bg-white border-0 px-4 py-3">
                    <h5 class="fw-bold">Game Levels</h5>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered align-middle" id="example">
                            <thead>
                                <tr>
                                    <th>Level Name</th>
                                    <th>Level Description</th>
                                    <th>Level Image</th>
                                    <th>Level Status</th>
                                    <th>Level Slug</th>
                                    <th width="100">Edit</th>
                                    <th width="100">Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($game->levels as $level)
                                    <tr>
                                        <td>{{ $level->level_name }}</td>
                                        <td>{{ $level->level_description }}</td>
                                        <td>
                                            @if ($level->level_image)
                                                <img src="{{ asset($level->level_image) }}" alt="{{ $level->level_name }}"
                                                    class="level-thumb">
                                            @else
                                                <span class="text-muted">No Image</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span
                                                class="status-badge {{ $level->level_status == 'active' ? 'status-active' : 'status-inactive' }}">
                                                {{ $level->level_status }}
                                            </span>
                                        </td>
                                        <td>{{ $level->level_slug }}</td>
                                        <td>
                                            <a href="{{ route('admin.game.levels.edit', $level->id) }}"
                                                class="btn btn-primary btn-sm rounded-3">
                                                Edit
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.game.levels.delete', $level->id) }}"
                                                class="btn btn-danger btn-sm rounded-3 delete-level-btn">
                                                Delete
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">No levels found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const formWrapper = document.getElementById('levelFormWrapper');
            const toggleBtn = document.getElementById('toggleLevelFormBtn');
            const closeBtn = document.getElementById('closeLevelFormBtn');
            const cancelBtn = document.getElementById('cancelLevelFormBtn');

            function openForm() {
                formWrapper.classList.add('show');
                window.scrollTo({
                    top: formWrapper.offsetTop - 20,
                    behavior: 'smooth'
                });
            }

            function closeForm() {
                formWrapper.classList.remove('show');
            }

            toggleBtn.addEventListener('click', function() {
                if (formWrapper.classList.contains('show')) {
                    closeForm();
                } else {
                    openForm();
                }
            });

            closeBtn.addEventListener('click', closeForm);
            cancelBtn.addEventListener('click', closeForm);

            @if ($errors->any() || old('level_name') || old('level_slug') || old('level_description') || old('level_status'))
                openForm();
            @endif

            document.querySelectorAll('.delete-level-btn').forEach(function(button) {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const deleteUrl = this.getAttribute('href');

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "This level will be deleted permanently.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, delete it',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = deleteUrl;
                        }
                    });
                });
            });
        });
    </script>
@endsection
