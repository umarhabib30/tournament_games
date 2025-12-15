@extends('layouts.admin')
@section('content')
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card">
                <h5 class="card-header">{{ $heading }}</h5>
                <div class="card-body">

                    @if ($tournaments->count())
                        <table class="table table-striped table-bordered second" id="example">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Type</th>
                                    <th>Elimination</th>
                                    {{-- <th>Status</th> --}}
                                    <th>Rounds</th>
                                    <th>URL</th>
                                    <th>Action</th>
                                    <th>Action</th>
                                    <th>Action</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tournaments as $index => $tournament)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $tournament->name }}</td>
                                        <td>{{ $tournament->date }}</td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($tournament->start_time)->format('h:i A') }}
                                            -
                                            {{ \Carbon\Carbon::parse($tournament->end_time)->format('h:i A') }}
                                        </td>

                                        <td>
                                            {{ $tournament->time_or_free == 'time' ? 'Knock Out' : 'Free Form' }}
                                        </td>
                                        <td>
                                            @if ($tournament->elimination_type == 'percentage')
                                                {{ $tournament->elimination_percent }}%
                                            @else
                                                Play till end
                                            @endif
                                        </td>
                                        {{-- <td>
                                            @if ($tournament->status == 'active')
                                                <span class="badge badge-success">Active</span>
                                            @elseif($tournament->status == 'inactive')
                                                <span class="badge badge-secondary">Inactive</span>
                                            @elseif($tournament->status == 'inprogress')
                                                <span class="badge badge-warning">In Progress</span>
                                            @elseif($tournament->status == 'completed')
                                                <span class="badge badge-dark">Completed</span>
                                            @endif
                                        </td> --}}
                                        <td>{{ $tournament->rounds }}</td>
                                        <td><button class="btn btn-primary"
                                                url="{{ url('waiting-area', $tournament->id) }}">Copy</button></td>
                                        <td>
                                            <a href="{{ route('admin.tournament.details', $tournament->id) }}"
                                                class="btn btn-sm btn-info">View</a>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.tournament.results', $tournament->id) }}"
                                                class="btn btn-sm btn-info">Results</a>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.tournament.edit', $tournament->id) }}"
                                                class="btn btn-sm btn-primary">Edit</a>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-danger delete_tournament"
                                                tournament_id={{ $tournament->id }}>
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-muted">No tournaments found.</p>
                    @endif

                    <a href="{{ route('admin.tournament.create') }}" class="btn btn-success mt-3">+ Add Tournament</a>

                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {

            $('body').on('click', 'button[url]', function() {
                var url = $(this).attr('url');

                // Copy to clipboard
                navigator.clipboard.writeText(url).then(function() {
                    Swal.fire(
                        'Copied!',
                        'Tournament URL has been copied to clipboard.',
                        'success'
                    );
                }, function(err) {
                    Swal.fire(
                        'Error!',
                        'Failed to copy the URL.',
                        'error'
                    );
                });
            });

            $('body').on('click', '.delete_tournament', function(e) {
                e.preventDefault();

                var id = $(this).attr('tournament_id');
                var url = "{{ route('admin.tournament.delete', ':id') }}".replace(':id', id);

                Swal.fire({
                    title: 'Are you sure?',
                    text: "This action cannot be undone!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: "GET",
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                Swal.fire(
                                    'Deleted!',
                                    'Tournament has been deleted.',
                                    'success'
                                ).then(() => {
                                    // Reload page OR remove row/card
                                    location.reload();
                                });
                            },
                            error: function(xhr) {
                                Swal.fire(
                                    'Error!',
                                    'Something went wrong while deleting.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
