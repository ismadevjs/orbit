@extends('layouts.backend')

@section('content')
    <div class="container-fluid py-3">
        <div class="row">
            <div class="col-12">
                <div class="card border-0" style="background: linear-gradient(135deg, #1a1a1a 0%, #ffffff 100%);">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0 fw-bold">Email Logs</h4>
                        <span class="badge bg-dark text-white">{{ $logs->total() }}</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 text-dark">
                                <thead style="background: rgba(255, 255, 255, 0.1);">
                                    <tr>
                                        <th scope="col" class="ps-3">#</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">IP</th>
                                        <th scope="col">Device</th>
                                        <th scope="col">Location</th>
                                        <th scope="col">Reason</th>
                                        <th scope="col">Attempts</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($logs as $index => $log)
                                        <tr style="background: rgba(255, 255, 255, 0.05);">
                                            <td class="ps-3">{{ $logs->firstItem() + $index }}</td>
                                            <td><span class="">{{ $log->email }}</span></td>
                                            <td><span class="badge bg-secondary">{{ $log->ip_address }}</span></td>
                                            <td><span class="badge bg-info text-dark">{{ $log->device }}</span></td>
                                            <td><span class="badge bg-primary">{{ $log->location }}</span></td>
                                            <td><span class="badge bg-warning text-dark">{{ $log->reason }}</span></td>
                                            <td><span class="badge bg-dark text-white">{{ $log->attempt_count }}</span></td>
                                            <td>
                                                @if ($log->is_blocked)
                                                    <span class="badge bg-danger">Blocked</span>
                                                @else
                                                    <span class="badge bg-success">Active</span>
                                                @endif
                                            </td>
                                            <td><span class="">{{ $log->created_at->format('d M Y, H:i') }}</span></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center py-3 text-muted">
                                                No logs available
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-primary d-flex justify-content-between align-items-center text-dark">
                        <small>Showing {{ $logs->firstItem() }} - {{ $logs->lastItem() }} of {{ $logs->total() }}</small>
                        <div>
                            {{ $logs->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection