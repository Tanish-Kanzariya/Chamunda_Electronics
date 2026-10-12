@extends('layouts.admin')

@section('title', 'Inquiries Inbox - Chamunda Electrics Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-0">Customer & Contractor Inquiries</h3>
        <small class="text-secondary">Messages received from the storefront contact form.</small>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light small">
                <tr>
                    <th>Sender</th>
                    <th>Subject</th>
                    <th>Message</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($messages as $msg)
                    <tr class="{{ !$msg->is_read ? 'table-warning-subtle fw-semibold' : '' }}">
                        <td>
                            <div class="fw-bold text-dark">{{ $msg->name }}</div>
                            <small class="text-muted d-block">{{ $msg->email }}</small>
                            <small class="text-muted">{{ $msg->phone ?? '' }}</small>
                        </td>
                        <td>{{ $msg->subject }}</td>
                        <td style="max-width: 300px;">
                            <div class="text-truncate" title="{{ $msg->message }}">{{ $msg->message }}</div>
                        </td>
                        <td>{{ $msg->created_at->format('d M Y, h:i A') }}</td>
                        <td>
                            @if($msg->is_read)
                                <span class="badge bg-secondary">Read</span>
                            @else
                                <span class="badge bg-danger">New Unread</span>
                            @endif
                        </td>
                        <td>
                            @if(!$msg->is_read)
                                <form action="{{ route('admin.messages.read', $msg->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-check2"></i> Mark Read
                                    </button>
                                </form>
                            @else
                                <span class="text-muted small"><i class="bi bi-check2-all text-success"></i> Handled</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No inquiry messages in inbox.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $messages->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
