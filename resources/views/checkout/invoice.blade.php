<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tax Invoice - {{ $order->order_number }} - Chamunda Electrics</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-size: 14px; background: #fff; }
        .invoice-card { max-width: 850px; margin: 20px auto; padding: 30px; border: 1px solid #ccc; }
        @media print {
            .no-print { display: none !important; }
            .invoice-card { border: none; padding: 0; margin: 0; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="text-end my-3 no-print">
            <button onclick="window.print()" class="btn btn-primary btn-sm"><i class="bi bi-printer"></i> Print Invoice</button>
            <a href="{{ route('home') }}" class="btn btn-secondary btn-sm ms-2">Back to Store</a>
        </div>

        <div class="invoice-card">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-start border-bottom pb-3 mb-4">
                <div>
                    <h3 class="fw-bold text-primary mb-1">CHAMUNDA ELECTRICS</h3>
                    <p class="mb-0 text-muted small">Authorized Electrical Wholesale & Retail Distributor</p>
                    <p class="mb-0 text-muted small">Shop No. 12-14, Shree Chamunda Complex, Station Road, Gujarat</p>
                    <p class="mb-0 text-muted small">Phone: +91 98765 43210 | Email: support@chamundaelectrics.com</p>
                    <p class="mb-0 fw-bold small text-dark mt-1">GSTIN: 24AABCE1234F1Z8</p>
                </div>
                <div class="text-end">
                    <h4 class="fw-bold text-uppercase text-secondary">TAX INVOICE</h4>
                    <p class="mb-1"><strong>Invoice No:</strong> {{ $order->order_number }}</p>
                    <p class="mb-1"><strong>Date:</strong> {{ $order->created_at->format('d-M-Y H:i') }}</p>
                    <p class="mb-0"><strong>Payment:</strong> {{ strtoupper(str_replace('_', ' ', $order->payment_method)) }} ({{ ucfirst($order->payment_status) }})</p>
                </div>
            </div>

            <!-- Bill To & Ship To -->
            <div class="row mb-4">
                <div class="col-6">
                    <h6 class="fw-bold text-uppercase text-secondary small">Billed / Shipped To:</h6>
                    <div class="fw-bold">{{ $order->customer_name }}</div>
                    <div>{{ $order->shipping_address }}</div>
                    <div>{{ $order->city }}, {{ $order->state }} - {{ $order->pincode }}</div>
                    <div>Phone: {{ $order->customer_phone }}</div>
                    <div>Email: {{ $order->customer_email }}</div>
                </div>
                <div class="col-6 text-end">
                    <h6 class="fw-bold text-uppercase text-secondary small">Order Meta:</h6>
                    <div>Status: <span class="badge bg-secondary">{{ ucfirst($order->order_status) }}</span></div>
                    @if($order->tracking_number)
                        <div>Courier AWB: <strong>{{ $order->tracking_number }}</strong></div>
                    @endif
                </div>
            </div>

            <!-- Items Table -->
            <table class="table table-bordered mb-4">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Product & Specification</th>
                        <th>SKU</th>
                        <th class="text-end">Rate (₹)</th>
                        <th class="text-center">Qty</th>
                        <th class="text-end">Amount (₹)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <strong>{{ $item->product_name }}</strong>
                            </td>
                            <td>{{ $item->product_sku ?? 'N/A' }}</td>
                            <td class="text-end">{{ number_format($item->price, 2) }}</td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-end">{{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5" class="text-end fw-semibold">Subtotal:</td>
                        <td class="text-end">{{ number_format($order->subtotal, 2) }}</td>
                    </tr>
                    @if($order->discount_amount > 0)
                        <tr>
                            <td colspan="5" class="text-end text-success fw-semibold">Coupon Discount ({{ $order->coupon_code }}):</td>
                            <td class="text-end text-success">- {{ number_format($order->discount_amount, 2) }}</td>
                        </tr>
                    @endif
                    <tr>
                        <td colspan="5" class="text-end fw-semibold">CGST (9%) + SGST (9%):</td>
                        <td class="text-end">{{ number_format($order->tax_amount, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="5" class="text-end fw-semibold">Shipping / Delivery:</td>
                        <td class="text-end">{{ $order->shipping_charge == 0 ? 'FREE' : number_format($order->shipping_charge, 2) }}</td>
                    </tr>
                    <tr class="table-light">
                        <td colspan="5" class="text-end fw-bold fs-6">Grand Total:</td>
                        <td class="text-end fw-bold fs-6">₹{{ number_format($order->total_amount, 2) }}</td>
                    </tr>
                </tfoot>
            </table>

            <!-- Footer terms -->
            <div class="row pt-3 border-top small text-muted">
                <div class="col-8">
                    <strong>Terms & Conditions:</strong>
                    <ul class="mb-0 ps-3">
                        <li>All goods are covered under original manufacturer warranty against manufacturing defects.</li>
                        <li>Subject to local jurisdiction. Goods once sold can be exchanged within 7 days in original condition.</li>
                    </ul>
                </div>
                <div class="col-4 text-end">
                    <br><br>
                    <strong>For CHAMUNDA ELECTRICS</strong>
                    <div class="text-muted">(Authorized Signatory)</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
