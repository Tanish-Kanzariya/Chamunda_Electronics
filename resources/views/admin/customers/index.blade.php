@extends('layouts.admin')

@section('title', 'Customers - Chamunda Electrics Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-0">Registered Customers</h3>
        <small class="text-secondary">View registered electricians, contractors, and retail consumers.</small>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light small">
                <tr>
                    <th>Customer Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Location</th>
                    <th>Orders Placed</th>
                    <th>Total Spent</th>
                    <th>Registered</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $cust)
                    <tr>
                        <td>
                            <div class="fw-bold text-dark">{{ $cust->name }}</div>
                        </td>
                        <td>{{ $cust->email }}</td>
                        <td>{{ $cust->phone ?? 'Not provided' }}</td>
                        <td>
                            <div>{{ $cust->city ?? 'Gujarat' }}</div>
                            <small class="text-muted">{{ $cust->pincode ?? '' }}</small>
                        </td>
                        <td><span class="badge bg-primary-subtle text-primary">{{ $cust->orders_count }} orders</span></td>
                        <td class="fw-bold text-success">₹{{ number_format($cust->orders_sum_total_amount ?? 0, 2) }}</td>
                        <td>{{ $cust->created_at->format('d M Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No customers registered yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $customers->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
