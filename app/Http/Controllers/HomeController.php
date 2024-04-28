<?php

namespace App\Http\Controllers;

use App\Mail\LeaveApproved;
use App\Mail\LeaveRejected;
use App\Models\LeaveRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $leave_types = LeaveRequest::TYPES;
        if (auth()->user()->user_type == User::USER_TYPE_ADMIN) {
            $leaves = LeaveRequest::where('status', LeaveRequest::STATUS_PENDING)->get();
        } elseif (auth()->user()->user_type == User::USER_TYPE_EMPLOYEE) {
            $leaves = LeaveRequest::where('user_id', auth()->id())->get();
        }
        $is_admin = (new User())->isAdmin();
        return view('home', compact('leaves', 'leave_types', 'is_admin'));
    }

    public function submitLeave(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'type' => 'required|in:1,2,3',
            'reason' => 'required|min:10'
        ]);

        try {
            LeaveRequest::create([
                'user_id' => auth()->id(),
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'type' => $request->type,
                'reason' => $request->reason,
            ]);
            $message = 'Request submitted successfully';
        } catch (\Throwable $th) {
            //throw $th;
            $message = 'Something went wrong';
        }

        return redirect()->back()->with('message', $message);
    }

    public function leaveDecision(Request $request)
    {
        try {
            $leave = LeaveRequest::findOrFail($request->leave_id);
            $leave->status = $request->status;
            $leave->save();
            if ($request->status == LeaveRequest::STATUS_APPROVED) {
                Mail::to($leave->user->email)->queue(new LeaveApproved($leave->user->name, auth()->user()->name));
            } elseif ($request->status == LeaveRequest::STATUS_REJECTED) {
                Mail::to($leave->user->email)->queue(new LeaveRejected($leave->user->name, auth()->user()->name));
            }
            $message = 'Leave updated successfully';
        } catch (\Throwable $th) {
            $message = 'Something went wrong';
        }

        return redirect()->back()->with('message', $message);
    }
}
