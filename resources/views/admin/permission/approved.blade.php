@extends('layouts.admin')

@section('content')
    <div class="admin-card">
        <div class="card-header">
            <span>{{ $heading }}</span>
            <span class="admin-badge admin-badge-success"><i class="fas fa-check"></i> Approved</span>
        </div>
        <div class="card-body">
            <table class="table table-striped table-bordered second admin-table" id="example">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Username</th>
                        <th>Tournament</th>
                        <th>Tournament Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($requests as $index => $request)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="font-weight-bold">{{ $request->user->username }}</td>
                            <td>{{ $request->tournament->name }}</td>
                            <td>{{ $request->tournament->date }}</td>
                            <td><span class="admin-badge admin-badge-success">{{ $request->status }}</span></td>
                            <td>
                                <a href="{{ route('request.reject', $request->id) }}"
                                    class="btn-admin btn-admin-sm btn-admin-danger" data-swal-confirm
                                    data-swal-title="Reject approved request?"
                                    data-swal-text="This will revoke the player's tournament access."
                                    data-swal-icon="warning"
                                    data-swal-confirm-text="Yes, reject"
                                    data-swal-confirm-color="#dc2626">
                                    <i class="fas fa-times"></i> Reject
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="admin-empty">
                                <i class="fas fa-inbox d-block"></i>
                                No approved requests.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
