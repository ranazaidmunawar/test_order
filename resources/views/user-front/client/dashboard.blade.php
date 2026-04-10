@php
    use App\Constants\Constant;
    use App\Http\Helpers\Uploader;
@endphp
@extends('user-front.layout')
@section('pageHeading')
    {{ $keywords['Dashboard'] ?? __('Dashboard') }}
@endsection
@section('content')


    <section class="user-dashboard-area">
        <div class="container">
            <div class="row">
                @include('user-front.client.inc.site_bar')
                <div class="col-lg-9">
                    <div class="dashboard-content">
                        <!-- Account Summary Card -->
                        <div class="premium-dashboard-card mb-4">
                            <div class="card-header-icon">
                                <i class="fas fa-id-card"></i>
                                <h3>{{ $keywords['Account Information'] ?? __('Account Information') }}</h3>
                            </div>
                            <div class="account-grid">
                                <div class="info-block">
                                    <span class="info-label">{{ $keywords['Username'] ?? __('Username') }}</span>
                                    <span class="info-value">{{ convertUtf8($customer->username) }}</span>
                                </div>
                                <div class="info-block">
                                    <span class="info-label">{{ $keywords['Email_Address'] ?? __('Email Address') }}</span>
                                    <span class="info-value">{{ convertUtf8($customer->email) }}</span>
                                </div>
                                <div class="info-block">
                                    <span class="info-label">{{ $keywords['Phone'] ?? __('Phone') }}</span>
                                    <span class="info-value">{{ convertUtf8($customer->number) ?: '-' }}</span>
                                </div>
                                <div class="info-block">
                                    <span class="info-label">{{ $keywords['Location'] ?? __('Location') }}</span>
                                    <span class="info-value">
                                        {{ convertUtf8($customer->city) }}{{ $customer->city && $customer->country ? ', ' : '' }}{{ convertUtf8($customer->country) }}
                                    </span>
                                </div>
                                <div class="info-block-full mt-3">
                                    <span class="info-label">{{ $keywords['Address'] ?? __('Full Address') }}</span>
                                    <span class="info-value">{{ convertUtf8($customer->address) ?: '-' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Orders Table Card -->
                        <div class="premium-dashboard-card">
                            <div class="card-header-icon">
                                <i class="fas fa-history"></i>
                                <h3>{{ $keywords['Recent Orders'] ?? __('Recent Orders') }}</h3>
                            </div>
                            <div class="table-container mt-3">
                                <div class="table-responsiv">
                                    <table id="example" class="premium-table w-100">
                                        <thead>
                                            <tr>
                                                <th>{{ $keywords['Order'] ?? __('Order #') }}</th>
                                                <th>{{ $keywords['Type'] ?? __('Type') }}</th>
                                                <th>{{ $keywords['Method'] ?? __('Method') }}</th>
                                                <th>{{ $keywords['Date'] ?? __('Date') }}</th>
                                                <th>{{ $keywords['Total Price'] ?? __('Total') }}</th>
                                                <th>{{ $keywords['Action'] ?? __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($orders && $orders->count() > 0)
                                                @foreach ($orders as $order)
                                                    <tr>
                                                        <td class="fw-bold text-dark">#{{ $order->order_number }}</td>
                                                        <td>
                                                            <span class="type-badge">
                                                                {{ $order->type == 'website' ? ($keywords['Website'] ?? __('Website')) : ($keywords['QR'] ?? __('QR')) }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            @php
                                                                $method = $order->serving_method;
                                                                $methodText = '';
                                                                if($method == 'on_table') $methodText = $keywords['On Table'] ?? __('On Table');
                                                                elseif($method == 'home_delivery') $methodText = $keywords['Home Delivery'] ?? __('Home Delivery');
                                                                elseif($method == 'pick_up') $methodText = $keywords['Pick Up'] ?? __('Pick Up');
                                                            @endphp
                                                            {{ $methodText }}
                                                        </td>
                                                        <td class="text-muted">{{ $order->created_at->format('d M, Y') }}</td>
                                                        <td class="fw-800 text-dark">
                                                            {{ $order->currency_symbol_position == 'left' ? $order->currency_symbol : '' }}
                                                            {{ $order->total }}
                                                            {{ $order->currency_symbol_position == 'right' ? $order->currency_symbol : '' }}
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('user.client.orders.details', [getParam(), $order->id]) }}"
                                                                class="details-btn">
                                                                {{ $keywords['View'] ?? __('View') }}
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="6" class="text-center py-5">
                                                        <div class="empty-state">
                                                            <i class="fas fa-shopping-cart mb-3 opacity-25" style="font-size: 3rem;"></i>
                                                            <p class="text-muted">{{ $keywords['No Orders Found'] ?? __('No recent orders found') }}</p>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        :root {
            --elak-primary: #0f5156;
            --elak-bg: #f8fafc;
            --elak-text: #1e293b;
            --elak-muted: #64748b;
        }

        .user-dashboard-area {
            background: var(--elak-bg);
            padding: 40px 0 100px;
            min-height: 80vh;
        }

        .premium-dashboard-card {
            background: #fff;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            border: 1px solid #f1f5f9;
        }

        .card-header-icon {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 25px;
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 20px;
        }

        .card-header-icon i {
            font-size: 1.5rem;
            color: var(--elak-primary);
            background: rgba(15, 81, 86, 0.08);
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
        }

        .card-header-icon h3 {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--elak-text);
            margin: 0;
        }

        .account-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 25px;
        }

        .info-block {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .info-label {
            font-size: 0.8rem;
            font-weight: 700;
            color: var(--elak-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-value {
            font-size: 1rem;
            font-weight: 600;
            color: var(--elak-text);
        }

        .info-block-full {
            grid-column: 1 / -1;
        }

        /* Table Styling */
        .premium-table {
            border-collapse: separate;
            border-spacing: 0 10px;
        }

        .premium-table thead th {
            background: transparent;
            color: var(--elak-muted);
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.75rem;
            border: none;
            padding: 10px 15px;
        }

        .premium-table tbody tr {
            transition: transform 0.2s;
        }

        .premium-table tbody td {
            background: #fff;
            padding: 15px;
            font-size: 0.9rem;
            border-top: 1px solid #f1f5f9;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        .premium-table tbody td:first-child {
            border-left: 1px solid #f1f5f9;
            border-top-left-radius: 12px;
            border-bottom-left-radius: 12px;
        }

        .premium-table tbody td:last-child {
            border-right: 1px solid #f1f5f9;
            border-top-right-radius: 12px;
            border-bottom-right-radius: 12px;
        }

        .type-badge {
            background: #f1f5f9;
            padding: 4px 10px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.75rem;
            color: var(--elak-muted);
        }

        .details-btn {
            background: var(--elak-primary);
            color: #fff !important;
            padding: 8px 16px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.8rem;
            text-decoration: none !important;
            transition: opacity 0.2s;
            display: inline-block;
        }

        .details-btn:hover {
            opacity: 0.9;
        }

        .fw-800 { font-weight: 800; }

        @media (max-width: 991px) {
            .dashboard-content { margin-top: 20px; }
            .user-dashboard-area { padding: 20px 0 60px; }
        }
    </style>
@endsection