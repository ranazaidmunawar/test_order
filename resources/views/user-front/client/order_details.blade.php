@php
    use App\Constants\Constant;
    use App\Http\Helpers\Uploader;
    use App\Models\User\Product;
    $direction = 'direction:';
@endphp
@extends('user-front.layout')
@section('pageHeading')
    {{ $keywords['Order Details'] ?? __('Order Details') }}
@endsection
@section('content')
    <section class="user-dashboard-area">
        <div class="container">
            <div class="row">
                @include('user-front.client.inc.site_bar')
                <div class="col-lg-9">
                    <div class="dashboard-content">
                        <!-- Progress Steps Card -->
                        <div class="premium-dashboard-card mb-4">
                            <div class="card-header-icon">
                                <i class="fas fa-tasks"></i>
                                <h3>{{ $keywords['Order Status'] ?? __('Order Status') }} - #{{ $data->order_id }}</h3>
                            </div>
                            <div class="progress-area-step mt-4">
                                <ul class="progress-steps premium-steps">
                                    <li class="{{ $data->order_status == 'pending' ? 'active' : '' }}">
                                        <div class="icon-circle shadow-sm"><i class="fas fa-clock"></i></div>
                                        <div class="progress-title small fw-700 mt-2">{{ $keywords['Pending'] ?? __('Pending') }}</div>
                                    </li>
                                    <li class="{{ $data->order_status == 'received' ? 'active' : '' }}">
                                        <div class="icon-circle shadow-sm"><i class="fas fa-check-double"></i></div>
                                        <div class="progress-title small fw-700 mt-2">{{ $keywords['Received'] ?? __('Received') }}</div>
                                    </li>
                                    <li class="{{ $data->order_status == 'preparing' ? 'active' : '' }}">
                                        <div class="icon-circle shadow-sm"><i class="fas fa-utensils"></i></div>
                                        <div class="progress-title small fw-700 mt-2">{{ $keywords['Preparing'] ?? __('Preparing') }}</div>
                                    </li>
                                    @if ($data->serving_method != 'on_table')
                                        <li class="{{ $data->order_status == 'ready_to_pick_up' ? 'active' : '' }}">
                                            <div class="icon-circle shadow-sm"><i class="fas fa-shopping-bag"></i></div>
                                            <div class="progress-title small fw-700 mt-2">{{ $keywords['Ready'] ?? __('Ready') }}</div>
                                        </li>
                                    @endif
                                    @if ($data->serving_method == 'home_delivery')
                                        <li class="{{ $data->order_status == 'delivered' ? 'active' : '' }}">
                                            <div class="icon-circle shadow-sm"><i class="fas fa-home"></i></div>
                                            <div class="progress-title small fw-700 mt-2">{{ $keywords['Delivered'] ?? __('Delivered') }}</div>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>

                        <!-- Info Cards Row -->
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="premium-dashboard-card h-100">
                                    <div class="card-header-icon"><i class="fas fa-info-circle"></i><h3>{{ $keywords['Order General'] ?? __('General Info') }}</h3></div>
                                    <div class="info-list mt-3">
                                        <div class="info-item d-flex justify-content-between mb-2">
                                            <span class="text-muted">{{ $keywords['Order Date'] ?? __('Order Date') }}:</span>
                                            <span class="fw-700 text-dark">{{ $data->created_at->format('d M, Y') }}</span>
                                        </div>
                                        <div class="info-item d-flex justify-content-between mb-2">
                                            <span class="text-muted">{{ $keywords['Payment Status'] ?? __('Payment') }}:</span>
                                            <span class="badge {{ strtolower($data->payment_status) == 'pending' ? 'badge-danger' : 'badge-success' }}">{{ $keywords[ucfirst($data->payment_status)] ?? $data->payment_status }}</span>
                                        </div>
                                        <div class="info-item d-flex justify-content-between mb-2">
                                            <span class="text-muted">{{ $keywords['Serving Method'] ?? __('Serving') }}:</span>
                                            <span class="fw-700 text-dark">
                                                @if(strtolower($data->serving_method) == 'home_delivery') {{ $keywords['Home Delivery'] ?? __('Home Delivery') }}
                                                @elseif(strtolower($data->serving_method) == 'pick_up') {{ $keywords['Pick Up'] ?? __('Pick Up') }}
                                                @elseif(strtolower($data->serving_method) == 'on_table') {{ $keywords['On Table'] ?? __('On Table') }}
                                                @endif
                                            </span>
                                        </div>
                                        @if(!empty($data->order_notes))
                                        <div class="mt-3 p-3 bg-light rounded border">
                                            <label class="small text-muted mb-1 d-block fw-700">{{ $keywords['Order Notes'] ?? __('Order Notes') }}:</label>
                                            <p class="mb-0 small text-dark">{{ $data->order_notes }}</p>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-4">
                                <div class="premium-dashboard-card h-100">
                                    <div class="card-header-icon"><i class="fas fa-user-tag"></i><h3>{{ $keywords['Customer Info'] ?? __('Customer Info') }}</h3></div>
                                    <div class="info-list mt-3">
                                        @if($data->serving_method == 'home_delivery')
                                            <p class="mb-1 fw-700 text-dark">{{ convertUtf8($data->shipping_fname) }} {{ convertUtf8($data->shipping_lname) }}</p>
                                            <p class="mb-1 text-muted small"><i class="fas fa-envelope mr-1"></i> {{ convertUtf8($data->shipping_email) }}</p>
                                            <p class="mb-1 text-muted small"><i class="fas fa-phone mr-1"></i> {{ $data->shipping_country_code }}{{ $data->shipping_number }}</p>
                                            <p class="mt-2 text-dark small border-top pt-2"><i class="fas fa-map-marker-alt mr-1 text-muted"></i> {{ convertUtf8($data->shipping_address) }}, {{ convertUtf8($data->shipping_city) }}, {{ convertUtf8($data->shipping_country) }}</p>
                                        @else
                                            <p class="mb-1 fw-700 text-dark">{{ convertUtf8($data->billing_fname) }} {{ convertUtf8($data->billing_lname) }}</p>
                                            <p class="mb-1 text-muted small"><i class="fas fa-envelope mr-1"></i> {{ convertUtf8($data->billing_email) }}</p>
                                            <p class="mb-1 text-muted small"><i class="fas fa-phone mr-1"></i> {{ $data->billing_country_code }}{{ $data->billing_number }}</p>
                                            @if($data->serving_method == 'on_table')
                                                 <p class="mt-2 text-primary fw-800 small border-top pt-2"><i class="fas fa-chair mr-1"></i> {{ $keywords['Table No'] ?? __('Table') }}: {{ $data->table_number }}</p>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Items Table Card -->
                        <div class="premium-dashboard-card mb-4">
                            <div class="card-header-icon"><i class="fas fa-shopping-basket"></i><h3>{{ $keywords['Ordered Items'] ?? __('Ordered Items') }}</h3></div>
                            <div class="table-responsive mt-3">
                                <table class="premium-table w-100">
                                    <thead>
                                        <tr>
                                            <th>{{ $keywords['Product'] ?? __('Product') }}</th>
                                            <th>{{ $keywords['Price'] ?? __('Price') }}</th>
                                            <th class="text-center">{{ $keywords['Qty'] ?? __('Qty') }}</th>
                                            <th class="text-right">{{ $keywords['Total'] ?? __('Total') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data->orderItems as $item)
                                            @php
                                                $productInfo = Product::query()->join('product_informations', 'product_informations.product_id', 'products.id')
                                                    ->where('products.id', $item->product_id)
                                                    ->where('product_informations.language_id', $currentLang->id)->first();
                                                $variations = json_decode($item->variation, true);
                                                $addons = json_decode($item->addons, true);
                                            @endphp
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center gap-3">
                                                        <img src="{{ Uploader::getImageUrl(Constant::WEBSITE_PRODUCT_FEATURED_IMAGE, $item->product->feature_image, $userBs) }}" class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                                        <div>
                                                            <p class="mb-0 fw-700 text-dark">{{ $productInfo ? convertUtf8($productInfo->title) : '' }}</p>
                                                            @if(!empty($variations))
                                                                <div class="small text-muted mt-1">
                                                                    <strong>{{ $keywords['Variation'] ?? __('Variation') }}:</strong>
                                                                    @foreach($variations as $vKey => $vVal)
                                                                        {{ str_replace('_', ' ', $vKey) }}: {{ $vVal }}{{ !$loop->last ? ',' : '' }}
                                                                    @endforeach
                                                                </div>
                                                            @endif
                                                            @if(!empty($addons))
                                                                <div class="small text-muted">
                                                                    <strong>{{ $keywords['Addons'] ?? __('Addons') }}:</strong>
                                                                    @foreach($addons as $addon)
                                                                        {{ $addon['name'] }}{{ !$loop->last ? ',' : '' }}
                                                                    @endforeach
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-muted small">
                                                    {{ $data->currency_symbol_position == 'left' ? $data->currency_symbol : '' }}{{ (float)$item->product_price }}{{ $data->currency_symbol_position == 'right' ? $data->currency_symbol : '' }}
                                                </td>
                                                <td class="text-center fw-700">{{ $item->qty }}</td>
                                                <td class="text-right fw-800 text-dark">
                                                    {{ $data->currency_symbol_position == 'left' ? $data->currency_symbol : '' }}{{ (float)$item->total }}{{ $data->currency_symbol_position == 'right' ? $data->currency_symbol : '' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="border-top">
                                            <td colspan="3" class="text-right py-2 text-muted fw-700">{{ $keywords['Subtotal'] ?? __('Subtotal') }}:</td>
                                            <td class="text-right py-2 text-dark fw-700">{{ $data->currency_symbol_position == 'left' ? $data->currency_symbol : '' }} {{ $data->total - ($data->shipping_charge + $data->tax) }} {{ $data->currency_symbol_position == 'right' ? $data->currency_symbol : '' }}</td>
                                        </tr>
                                        @if($data->tax)
                                            <tr><td colspan="3" class="text-right py-1 text-muted small">{{ $keywords['Tax'] ?? __('Tax') }}:</td><td class="text-right py-1 text-dark small">+{{ $data->currency_symbol_position == 'left' ? $data->currency_symbol : '' }} {{ $data->tax }}</td></tr>
                                        @endif
                                        @if($data->shipping_charge)
                                            <tr><td colspan="3" class="text-right py-1 text-muted small">{{ $keywords['Shipping'] ?? __('Shipping') }}:</td><td class="text-right py-1 text-dark small">+{{ $data->currency_symbol_position == 'left' ? $data->currency_symbol : '' }} {{ $data->shipping_charge }}</td></tr>
                                        @endif
                                        <tr>
                                            <td colspan="3" class="text-right py-3 text-dark fw-800" style="font-size: 1.1rem;">{{ $keywords['Grand Total'] ?? __('Total') }}:</td>
                                            <td class="text-right py-3 text-primary fw-800" style="color: var(--elak-primary); font-size: 1.25rem;">{{ $data->currency_symbol_position == 'left' ? $data->currency_symbol : '' }} {{ $data->total }} {{ $data->currency_symbol_position == 'right' ? $data->currency_symbol : '' }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <div class="row align-items-center mb-5">
                            <div class="col-sm-6">
                                <a href="{{ route('user.client.orders', getParam()) }}" class="text-muted fw-700 small hover-text-primary"><i class="fas fa-arrow-left mr-2"></i> {{ $keywords['Back to Orders'] ?? __('Back to Orders') }}</a>
                            </div>
                            <div class="col-sm-6 text-sm-right mt-3 mt-sm-0">
                                <form action="{{ route('user.client.order.download', [getParam(), 'id' => $data->invoice_number]) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="premium-save-btn py-2 px-4 shadow-sm"><i class="fas fa-file-pdf mr-2"></i> {{ $keywords['Invoice'] ?? __('Invoice') }}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        :root { --elak-primary: #0f5156; --elak-bg: #f8fafc; --elak-text: #1e293b; --elak-muted: #64748b; }
        .user-dashboard-area { background: var(--elak-bg); padding: 40px 0 100px; min-height: 80vh; }
        .premium-dashboard-card { background: #fff; border-radius: 20px; padding: 30px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); border: 1px solid #f1f5f9; }
        .card-header-icon { display: flex; align-items: center; gap: 15px; border-bottom: 1px solid #f1f5f9; padding-bottom: 20px; }
        .card-header-icon i { font-size: 1.35rem; color: var(--elak-primary); background: rgba(15, 81, 86, 0.08); width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 10px; }
        .card-header-icon h3 { font-size: 1.15rem; font-weight: 800; color: var(--elak-text); margin: 0; }
        .premium-steps { list-style: none; display: flex; justify-content: space-between; padding: 0; position: relative; }
        .premium-steps::before { content: ""; position: absolute; top: 12.5px; left: 0; right: 0; height: 2px; background: #e2e8f0; z-index: 1; }
        .premium-steps li { position: relative; z-index: 2; text-align: center; flex: 1; }
        .icon-circle { width: 25px; height: 25px; background: #fff; border: 2px solid #e2e8f0; border-radius: 50%; margin: 0 auto; display: flex; align-items: center; justify-content: center; color: #cbd5e1; font-size: 0.65rem; transition: 0.3s; }
        .premium-steps li.active .icon-circle { background: var(--elak-primary); border-color: var(--elak-primary); color: #fff; transform: scale(1.15); }
        .premium-table thead th { border: none; color: var(--elak-muted); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; padding: 12px; }
        .premium-table tbody td { border-bottom: 1px solid #f1f5f9; padding: 15px 12px; vertical-align: middle; }
        .premium-save-btn { background: var(--elak-primary); color: #fff !important; border: none; border-radius: 10px; font-weight: 700; transition: 0.2s; display: inline-flex; align-items: center; }
        .hover-text-primary:hover { color: var(--elak-primary) !important; text-decoration: none; }
    </style>

    <style>
        :root {
            --elak-primary: #0f5156;
            --elak-bg: #f8fafc;
            --elak-text: #1e293b;
            --elak-muted: #64748b;
        }

        .user-dashboard-area { background: var(--elak-bg); padding: 40px 0 100px; min-height: 80vh; }
        .premium-dashboard-card { background: #fff; border-radius: 20px; padding: 30px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); border: 1px solid #f1f5f9; }
        
        .card-header-icon { display: flex; align-items: center; gap: 15px; margin-bottom: 25px; border-bottom: 1px solid #f1f5f9; padding-bottom: 20px; }
        .card-header-icon i { font-size: 1.5rem; color: var(--elak-primary); background: rgba(15, 81, 86, 0.08); width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; border-radius: 12px; }
        .card-header-icon h3 { font-size: 1.25rem; font-weight: 800; color: var(--elak-text); margin: 0; }

        /* Steps */
        .premium-steps { list-style: none; display: flex; justify-content: space-between; padding: 0; position: relative; }
        .premium-steps::before { content: ""; position: absolute; top: 12.5px; left: 0; right: 0; height: 2px; background: #e2e8f0; z-index: 1; }
        .premium-steps li { position: relative; z-index: 2; text-align: center; flex: 1; }
        .icon-circle { width: 25px; height: 25px; background: #fff; border: 2px solid #e2e8f0; border-radius: 50%; margin: 0 auto; display: flex; align-items: center; justify-content: center; color: #cbd5e1; transition: all 0.3s; font-size: 0.65rem; }
        .premium-steps li.active .icon-circle { background: var(--elak-primary); border-color: var(--elak-primary); color: #fff; transform: scale(1.2); }
        .premium-steps li.active .progress-title { color: var(--elak-primary); }

        /* Table */
        .premium-table thead th { background: transparent; color: var(--elak-muted); font-weight: 700; text-transform: uppercase; font-size: 0.75rem; border: none; padding: 10px 15px; }
        .premium-table tbody td { padding: 15px; vertical-align: middle; border-bottom: 1px solid #f1f5f9; }
        .fw-700 { font-weight: 700; }
        .fw-800 { font-weight: 800; }
        .gap-3 { gap: 1rem; }
        .line-clamp-1 { display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden; }

        .premium-save-btn { background: var(--elak-primary); color: #fff !important; border: none; padding: 10px 20px; border-radius: 10px; font-weight: 700; transition: opacity 0.2s; display: inline-flex; align-items: center; justify-content: center; }
        .hover-text-primary:hover { color: var(--elak-primary) !important; text-decoration: none; }

        @media (max-width: 767px) {
            .premium-steps { overflow-x: auto; padding-bottom: 10px; }
            .premium-steps li { min-width: 100px; }
        }
    </style>
@endsection
