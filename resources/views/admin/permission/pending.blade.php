@extends('layouts.admin')

@section('content')
    <div class="admin-card">
        <div class="card-header">
            <span>{{ $heading }}</span>
            <span class="admin-badge admin-badge-warning"><i class="fas fa-clock"></i> Pending</span>
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
                            <td><span class="admin-badge admin-badge-warning">{{ $request->status }}</span></td>
                            <td>
                                <div class="admin-actions">
                                    <a href="{{ route('request.approve', $request->id) }}"
                                        class="btn-admin btn-admin-sm btn-admin-success" data-swal-confirm
                                        data-swal-title="Approve this request?"
                                        data-swal-text="The player will be allowed to enter the tournament."
                                        data-swal-icon="question"
                                        data-swal-confirm-text="Yes, approve"
                                        data-swal-confirm-color="#16a34a">
                                        <i class="fas fa-check"></i> Approve
                                    </a>
                                    <a href="{{ route('request.reject', $request->id) }}"
                                        class="btn-admin btn-admin-sm btn-admin-danger" data-swal-confirm
                                        data-swal-title="Reject this request?"
                                        data-swal-text="The player will not be able to enter the tournament."
                                        data-swal-icon="warning"
                                        data-swal-confirm-text="Yes, reject"
                                        data-swal-confirm-color="#dc2626">
                                        <i class="fas fa-times"></i> Reject
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="admin-empty">
                                <i class="fas fa-inbox d-block"></i>
                                No pending requests.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
