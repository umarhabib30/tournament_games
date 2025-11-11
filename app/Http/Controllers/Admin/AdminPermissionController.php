<?php

namespace App\Http\Controllers\Admin;

use App\Events\NotifyUserEvent;
use App\Events\PermissionRequestEvent;
use App\Http\Controllers\Controller;
use App\Models\TournamentPermission;
use Illuminate\Http\Request;

class AdminPermissionController extends Controller
{
    public function pending()
    {

        $data = [
            'heading' => 'Pending',
            'title' => 'Permission requests',
            'active' => 'requests',
            'requests' => TournamentPermission::where('status', 'Pending')->get(),
        ];
        return view('admin.permission.pending', $data);
    }

    public function approved()
    {
        $data = [
            'heading' => 'Approved',
            'title' => 'Permission requests',
            'active' => 'requests',
            'requests' => TournamentPermission::where('status', 'Accepted')->get(),
        ];

        return view('admin.permission.approved', $data);
    }

    public function rejected()
    {
        $data = [
            'heading' => 'Rejected',
            'title' => 'Permission requests',
            'active' => 'requests',
            'requests' => TournamentPermission::where('status', 'Rejected')->get(),
        ];
        return view('admin.permission.rejected', $data);
    }

    public function approveRequest($id)
    {
        $request = TournamentPermission::find($id);
        $request->update([
            'status' => 'Accepted',
        ]);
     event(new NotifyUserEvent($request->user_id, 'Request has been accepted', true));
     return redirect()->back()->with('scuccess', 'Request approved successfully');
    }

    public function rejectRequest($id)
    {
        $request = TournamentPermission::find($id);
        $request->update([
            'status' => 'Rejected',
        ]);
        event(new NotifyUserEvent($request->user_id, 'Request has been rejected', false));

        return redirect()->back()->with('scuccess', 'Request rejected successfully');
    }
}
