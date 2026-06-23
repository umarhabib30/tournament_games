@extends('layouts.admin')

@section('content')
    <div class="admin-card">
        <div class="card-header">
            <span>{{ $heading }}</span>
            <span class="admin-badge admin-badge-danger"><i class="fas fa-ban"></i> Rejected</span>
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
                            <td><span class="admin-badge admin-badge-danger">{{ $request->status }}</span></td>
                            <td>
                                <a href="{{ route('request.approve', $request->id) }}"
                                    class="btn-admin btn-admin-sm btn-admin-success" data-swal-confirm
                                    data-swal-title="Approve this request?"
                                    data-swal-text="The player will be allowed to enter the tournament."
                                    data-swal-icon="question"
                                    data-swal-confirm-text="Yes, approve"
                                    data-swal-confirm-color="#16a34a">
                                    <i class="fas fa-check"></i> Approve
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="admin-empty">
                                <i class="fas fa-inbox d-block"></i>
                                No rejected requests.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
