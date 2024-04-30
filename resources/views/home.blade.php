@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            @if ($is_admin)
            <div class="row">

                  <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-2 mt-4">
                      <div class="inforide">
                        <div class="row">
                          <div class="fontsty">
                              <h4>Total Request</h4>
                              <h2>{{$totalRequests}}</h2>
                          </div>
                        </div>
                      </div>
                  </div>

                  <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-2 mt-4">
                      <div class="inforide">
                        <div class="row">
                          <div class="fontsty">
                              <h4>Pending Request</h4>
                              <h2>{{$pendingRequests}}</h2>
                          </div>
                        </div>
                      </div>
                  </div>

                  <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-2 mt-4">
                      <div class="inforide">
                        <div class="row">
                          <div class="fontsty">
                              <h4>Approved Request</h4>
                              <h2>{{$approvedRequests}}</h2>
                          </div>
                        </div>
                      </div>
                  </div>
                  <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-2 mt-4">
                    <div class="inforide">
                      <div class="row">
                        <div class="fontsty">
                            <h4>Rejected Request</h4>
                            <h2>{{$rejectedRequests}}</h2>
                        </div>
                      </div>
                    </div>
                </div>

            </div>
            <div class="m-4"></div>
            @endif
            <div>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (Session::has('message'))
                    <div class="alert alert-success">
                        <span>{{Session::get('message')}}</span>
                    </div>
                @endif
                <h3 class="text-center mb-5">Leave History</h3>
                @if (!$is_admin)
                <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal"
                data-bs-target="#exampleModal">Apply for Leave</button>
                @endif

            </div>
            <div class="col-md-8">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Type</th>
                            <th scope="col">Start Date</th>
                            <th scope="col">End Date</th>
                            <th scope="col">Reason</th>
                            <th scope="col">Status</th>
                            @if ($is_admin)
                            <th scope="col">Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($leaves as $item)
                            <tr>
                                <th scope="row">{{$loop->iteration}}</th>
                                <td>{{App\Models\LeaveRequest::TYPES[$item->type]}}</td>
                                <td>{{$item->start_date}}</td>
                                <td>{{$item->end_date}}</td>
                                <td>{{$item->reason}}</td>
                                <td>
                                    <span class="badge {{($item->status == 0 ? 'text-bg-warning' : ($item->status == 1 ? 'text-bg-success' : 'text-bg-danger'))}} ">{{App\Models\LeaveRequest::STATUSES[$item->status]}}</span>
                                </td>
                                @if ($is_admin)
                                <td class="d-flex justify-content-between">
                                    <button style="margin-right: 3%" onclick="approveLeave({{$item}})" type="button" class="btn btn-sm btn-success">Approve</button>
                                    <button onclick="rejectLeave({{$item}})" type="button" class="btn btn-sm btn-danger">Reject</button>
                                </td>
                                @endif

                            </tr>
                        @empty
                            <p class="text-danger text-center">No data found</p>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4">
                    {{$leaves->links()}}
                </div>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Leave Request</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('leave.submit') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="startDate" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="startDate" name="start_date">
                        </div>
                        <div class="mb-3">
                            <label for="endDate" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="endDate" name="end_date">
                        </div>
                        <div class="mb-3">
                            <label for="endDate" class="form-label">Type</label>
                            <select class="form-control" name="type" id="">
                                <option value="">Select</option>
                                @foreach ($leave_types as $key => $item)
                                    <option value="{{ $key }}">{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="reason" class="form-label">Reason</label><br>
                            <textarea class="form-control" name="reason" id="reason" cols="30" rows="10"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Apply</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <form id="leave_submission_form_approve" action="{{route('leave.decision')}}" method="POST">
        @csrf
        <input type="hidden" name="status" value="1">
        <input type="hidden" name="leave_id">
    </form>
    <form id="leave_submission_form_reject" action="{{route('leave.decision')}}" method="POST">
        @csrf
        <input type="hidden" name="status" value="2">
        <input type="hidden" name="leave_id">
    </form>
    <script>
        function approveLeave(item) {
            $('input[name="leave_id"]').val(item.id);
            if (confirm(`Are you sure to approve?`)) {
                $("#leave_submission_form_approve").submit();
            }
        }

        function rejectLeave(item) {
            $('input[name="leave_id"]').val(item.id);
            if (confirm('Are you sure to reject?')) {
                $("#leave_submission_form_reject").submit();
            }
        }
    </script>
@endsection
