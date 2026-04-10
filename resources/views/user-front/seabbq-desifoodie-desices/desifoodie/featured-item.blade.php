@php
    use App\Constants\Constant;
    use App\Http\Helpers\Uploader;
    use Illuminate\Support\Facades\Auth;
@endphp

<!-- Category Scroller -->
<nav class="category-scroller sticky-top bg-white py-3 shadow-sm" style="top: 0; z-index: 1020;">
    <div class="container d-flex align-items-center justify-content-center gap-2 overflow-auto no-scrollbar">
        <a href="javascript:void(0)" class="category-pill active" data-filter="all">
            <i class="fas fa-th-large"></i> {{ $keywords['All'] ?? 'All' }}
        </a>
        @foreach ($categories as $category)
            <a href="#cat-{{ $category->id }}" class="category-pill" data-filter=".cat-{{ $category->id }}">
                <img src="{{ Uploader::getImageUrl(Constant::WEBSITE_PRODUCT_CATEGORY_IMAGE, $category->image, $userBs) }}"
                    alt="{{ convertUtf8($category->name) }}"> {{ convertUtf8($category->name) }}
            </a>
        @endforeach
    </div>
</nav>

<!-- Products Display -->
<div class="container-fluid mt-3 px-lg-5">
    <div class="row mx-1">
        @foreach ($categories as $category)
            @php
                $category_products = $featured_products->where('category_id', $category->id);
            @endphp
            @if ($category_products->count() > 0)
                <div id="cat-{{ $category->id }}" class="col-12 mb-4 cat-section cat-{{ $category->id }}">
                    <h5 class="fw-bold mb-3 px-2 border-start border-4 border-primary ps-2">
                        {{ convertUtf8($category->name) }}
                    </h5>
                    <div class="row">
                        @foreach ($category_products as $productInfo)
                            <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                                <div class="product-card" onclick="openProductModal({{ $productInfo->toJson() }})">
                                    <img src="{{ Uploader::getImageUrl(Constant::WEBSITE_PRODUCT_FEATURED_IMAGE, $productInfo->feature_image, $userBs) }}"
                                        class="product-image" alt="{{ convertUtf8($productInfo->title) }}">
                                    <div class="product-details ">
                                        <h6 class="product-title">{{ convertUtf8($productInfo->title) }}</h6>
                                        <p class="product-desc mb-2">
                                            {{ convertUtf8($productInfo->summary ?? $productInfo->description) }}
                                        </p>
                                        <div class="d-flex justify-content-center align-items-center gap-3 mt-2">
                                            <span class="product-price">
                                                {{ $userBe->base_currency_symbol_position == 'left' ? $userBe->base_currency_symbol : '' }}{{ number_format($productInfo->current_price, 2) }}{{ $userBe->base_currency_symbol_position == 'right' ? $userBe->base_currency_symbol : '' }}
                                            </span>
                                            <button class="add-btn shadow-sm">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</div>

<!-- Product Details Modal (Bottom Sheet Style) -->
<div class="modal fade bottom-sheet" id="productModal" tabindex="-1" aria-hidden="true"
    style="padding-left: 0 !important;">
    <div class="modal-dialog">
        <div class="modal-content  border-0 bg-white shadow-lg">

            <!-- Hero Image Section -->
            <div class="modal-product-hero">
                <button type="button" class="modal-close-btn" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i>
                </button>
                <button type="button" class="modal-fav-btn">
                    <i class="far fa-heart"></i>
                </button>
                <img id="modalImg" src="" class="modal-product-img" alt="Product Image">
                <div
                    class="position-absolute bottom-0 start-0 w-100 h-25 bg-gradient-to-t from-black to-transparent opacity-50">
                </div>
                <div class="position-absolute top-0 start-50 translate-middle-x mt-2"
                    style="width: 40px; height: 4px; background: rgba(255,255,255,0.5); border-radius: 2px;"></div>
            </div>

            <div class="modal-additions-section p-3 text-center">
                <div class="mb-4">
                    <h4 id="modalTitle" class="fw-bold mb-1 fs-4"></h4>
                    <p id="modalDesc" class="text-muted small mb-0"></p>
                </div>

                <hr class="opacity-10 my-3">

                <!-- Variations Container -->
                <div id="variationsContainer" class="mb-4 text-center"></div>

                <!-- Addons Container -->
                <div id="addonsContainer" class="mb-5 text-center">
                    <h6 class="fw-bold mb-3">{{ $keywords['Addons'] ?? __('Addons') }}</h6>
                    <div class="d-flex flex-wrap justify-content-center gap-2"></div>
                </div>
            </div>

            <!-- Sticky Footer - Premium Bar Design (Full Width) -->
            <div class="modal-sticky-footer">
                <div class="add-to-cart-bar shadow-sm">
                    <div class="price-side">
                        <span id="modalTotalBtn">0.00</span>
                    </div>

                    <div class="action-side" id="elakAddToCartBtn" onclick="elakAddToCart()">
                        <span id="addToCartText">{{ $keywords['Add to Cart'] ?? __('Add to Cart') }}</span>
                    </div>

                    <div class="qty-side">
                        <button type="button" class="qty-btn-circle" onclick="elakUpdateQty(1)"><i
                                class="fas fa-plus"></i></button>
                        <input type="text" id="qtyInput" value="1" readonly class="qty-input-text">
                        <button type="button" class="qty-btn-circle" onclick="elakUpdateQty(-1)"><i
                                class="fas fa-minus"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Category Scroller - Premium Minimal */
    .category-scroller {
        white-space: nowrap;
        background: white !important;
        overflow-x: auto;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        padding: 12px 10px;
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .category-scroller::-webkit-scrollbar {
        display: none;
    }

    .category-pill {
        display: inline-flex;
        align-items: center;
        padding: 8px 18px;
        border-radius: 50px;
        background: #f8f9fb;
        color: #4b5563;
        text-decoration: none !important;
        transition: all 0.2s ease;
        border: 1px solid #e5e7eb;
        font-weight: 500;
        font-size: 13px;
        margin-right: 8px;
    }

    .category-pill img {
        width: 22px;
        height: 22px;
        object-fit: contain;
        margin-right: 8px;
        border-radius: 50%;
    }

    .category-pill.active {
        background: #0a4a4f !important;
        color: white !important;
        border-color: #0a4a4f;
        box-shadow: 0 4px 12px rgba(10, 74, 79, 0.2);
    }

    /* Product Cards - Centered & Clean (SS 2 style) */
    .product-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        cursor: pointer;
        height: 100%;
        border: 1px solid rgba(0, 0, 0, 0.02);
        display: flex;
        flex-direction: column;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .product-image {
        width: 100%;
        height: 180px;
        object-fit: cover;
    }

    .product-details {
        padding: 20px;
        text-align: center;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .product-title {
        font-weight: 700;
        font-size: 16px;
        color: #111;
        margin-bottom: 6px;
    }

    .product-desc {
        font-size: 12px;
        color: #6b7280;
        line-height: 1.5;
        margin-bottom: 12px;
        height: 36px;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .product-price {
        font-weight: 800;
        color: #0a4a4f;
        font-size: 17px;
    }

    .add-btn {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #eef7f6;
        color: #0a4a4f;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }

    .add-btn:hover {
        background: #0a4a4f;
        color: white;
    }

    /* Modal Styling - Rounded Rect Pillars */
    .modal.bottom-sheet {
        z-index: 9999;
    }

    .modal.bottom-sheet .modal-dialog {
        position: fixed;
        bottom: 0;
        margin: 0;
        width: 100%;
        max-width: 500px;
        left: 50%;
        transform: translateX(-50%) translateY(100%);
        transition: transform 0.3s ease-out;
        z-index: 10000;
    }

    .modal.bottom-sheet.show .modal-dialog {
        transform: translateX(-50%) translateY(0);
    }

    .modal.bottom-sheet .modal-content {
        border-radius: 24px 24px 0 0;
        max-height: 92vh;
        /* Slightly taller for better view */
        border: none;
        overflow-y: auto;
        /* Enable vertical scroll */
        scrollbar-width: none;
        /* Hide scrollbar for clean look */
        -ms-overflow-style: none;
    }

    .modal.bottom-sheet .modal-content::-webkit-scrollbar {
        display: none;
    }

    .modal-product-hero {
        position: relative;
        height: 260px;
        background: #fff;
    }

    .modal-product-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .modal-additions-section {
        padding-bottom: 120px !important;
    }

    .modal-close-btn,
    .modal-fav-btn {
        position: absolute;
        top: 15px;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: white;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        z-index: 10;
        color: #333;
    }

    .addition-pill {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 10px 18px;
        font-size: 0.95rem;
        color: #374151;
        display: inline-block;
        margin-right: 8px;
        margin-bottom: 8px;
        cursor: pointer;
        transition: all 0.2s;
        font-weight: 500;
        text-align: center;
    }

    .addition-pill:hover {
        background: #f3f4f6;
    }

    .addition-pill.active {
        background: #eef7f6;
        border-color: #0a4a4f;
        color: #0a4a4f;
        font-weight: 700;
        box-shadow: 0 4px 10px rgba(10, 74, 79, 0.1);
    }

    /* Sticky Footer - Full Width Stretch */
    .modal-sticky-footer {
        position: sticky;
        bottom: 0;
        width: 100%;
        padding: 12px 14px 28px;
        /* Reduced side padding for 'full width' feel */
        background: white;
        z-index: 100;
        border-top: 1px solid rgba(0, 0, 0, 0.05);
    }

    .add-to-cart-bar {
        background: #0a4a4f;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        color: white;
        height: 64px;
        padding: 0 8px;
        box-shadow: 0 8px 20px rgba(10, 74, 79, 0.25);
        width: 100%;
    }

    .price-side {
        padding: 0 12px;
        font-weight: 700;
        font-size: 1.15rem;
        min-width: 100px;
    }

    .action-side {
        flex: 1;
        text-align: center;
        font-weight: 700;
        cursor: pointer;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.05rem;
    }

    .qty-side {
        display: flex;
        align-items: center;
        background: rgba(255, 255, 255, 0.12);
        border-radius: 10px;
        padding: 3px;
        gap: 6px;
    }

    .qty-btn-circle {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: none;
        background: white;
        color: #0a4a4f;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        transition: all 0.2s;
    }

    .qty-btn-circle:active {
        transform: scale(0.9);
    }

    .qty-input-text {
        width: 32px;
        background: transparent;
        border: none;
        color: white;
        text-align: center;
        font-weight: 700;
        font-size: 1.1rem;
    }

    @media (max-width: 576px) {
        .modal.bottom-sheet .modal-dialog {
            max-width: 100%;
        }

        .add-to-cart-bar {
            height: 60px;
        }

        .modal-sticky-footer {
            padding: 10px 10px 30px;
        }
    }
</style>
<script>
    let currentProduct = null;
    let currentProductBasePrice = 0;
    let selectedAddonsTotal = 0;
    let selectedVariationsTotal = 0;
    let currentQty = 1;
    let selectedVariations = {};
    let selectedAddons = [];
    let currencySymbol = "{{ $userBe->base_currency_symbol }}";
    let currencyPos = "{{ $userBe->base_currency_symbol_position }}";

    function openProductModal(product) {
        if (typeof product === 'string') {
            product = JSON.parse(product);
        }
        currentProduct = product.product || product;

        currentProductBasePrice = parseFloat(currentProduct.current_price || 0);
        selectedAddonsTotal = 0;
        selectedVariationsTotal = 0;
        currentQty = 1;
        selectedVariations = {};
        selectedAddons = [];

        // Update UI
        document.getElementById('modalTitle').innerText = product.title || product.name;
        document.getElementById('modalDesc').innerText = product.summary || product.description || '';

        const imgUrl = "{{ Uploader::getImageUrl(Constant::WEBSITE_PRODUCT_FEATURED_IMAGE, ':img', $userBs) }}".replace(':img', currentProduct.feature_image);
        document.getElementById('modalImg').src = imgUrl;
        document.getElementById('qtyInput').value = currentQty;

        // Render Variations
        const varContainer = document.getElementById('variationsContainer');
        varContainer.innerHTML = '';
        if (currentProduct.variations) {
            try {
                const variations = JSON.parse(currentProduct.variations);
                for (const [vName, vOptions] of Object.entries(variations)) {
                    const section = document.createElement('div');
                    section.className = 'mb-3';
                    section.innerHTML = `<h6 class="fw-bold mb-2">${vName.replace(/_/g, ' ')}</h6>`;

                    const optionsDiv = document.createElement('div');
                    optionsDiv.className = 'd-flex flex-wrap justify-content-center gap-2';

                    vOptions.forEach(opt => {
                        const pill = document.createElement('span');
                        pill.className = 'addition-pill';
                        pill.innerHTML = `${opt.name} (${currencyPos == 'left' ? currencySymbol : ''}${opt.price}${currencyPos == 'right' ? currencySymbol : ''})`;
                        pill.onclick = function () {
                            // Clear previous selection for this variation
                            optionsDiv.querySelectorAll('.addition-pill').forEach(p => p.classList.remove('active'));
                            this.classList.add('active');

                            selectedVariations[vName] = { name: opt.name, price: opt.price };
                            elakCalculateVariationTotal();
                        };
                        optionsDiv.appendChild(pill);
                    });
                    section.appendChild(optionsDiv);
                    varContainer.appendChild(section);
                }
            } catch (e) { console.error("Variations error:", e); }
        }

        // Render Addons
        const additionsContainer = document.querySelector('#addonsContainer .d-flex');
        additionsContainer.innerHTML = '';
        if (currentProduct.addons) {
            try {
                const addons = JSON.parse(currentProduct.addons);
                addons.forEach(addon => {
                    const pill = document.createElement('span');
                    pill.className = 'addition-pill';
                    pill.innerHTML = `${addon.name} (+${currencyPos == 'left' ? currencySymbol : ''}${addon.price}${currencyPos == 'right' ? currencySymbol : ''})`;
                    pill.onclick = function () {
                        this.classList.toggle('active');
                        const price = parseFloat(addon.price);
                        if (this.classList.contains('active')) {
                            selectedAddons.push({ name: addon.name, price: addon.price });
                            selectedAddonsTotal += price;
                        } else {
                            selectedAddons = selectedAddons.filter(a => a.name !== addon.name);
                            selectedAddonsTotal -= price;
                        }
                        elakCalculateTotal();
                    };
                    additionsContainer.appendChild(pill);
                });
            } catch (e) { console.error("Addons error:", e); }
        }

        elakCalculateTotal();

        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            new bootstrap.Modal(document.getElementById('productModal')).show();
        } else {
            $('#productModal').modal('show');
        }
    }

    function elakCalculateVariationTotal() {
        selectedVariationsTotal = 0;
        for (const v in selectedVariations) {
            selectedVariationsTotal += parseFloat(selectedVariations[v].price);
        }
        elakCalculateTotal();
    }

    function elakUpdateQty(delta) {
        currentQty += delta;
        if (currentQty < 1) currentQty = 1;
        document.getElementById('qtyInput').value = currentQty;
        elakCalculateTotal();
    }

    function elakCalculateTotal() {
        const total = (currentProductBasePrice + selectedAddonsTotal + selectedVariationsTotal) * currentQty;
        const formattedTotal = (currencyPos == 'left' ? currencySymbol : '') + total.toFixed(2) + (currencyPos == 'right' ? currencySymbol : '');
        document.getElementById('modalTotalBtn').innerText = formattedTotal;
    }

    function elakAddToCart() {
        if (!currentProduct) return;

        const btn = document.getElementById('elakAddToCartBtn');
        const btnText = document.getElementById('addToCartText');
        const originalText = btnText ? btnText.innerText : 'Add to Cart';

        // Validation for variations (ensure all are selected)
        if (currentProduct.variations) {
            try {
                const variations = JSON.parse(currentProduct.variations);
                for (const vName in variations) {
                    if (!selectedVariations[vName]) {
                        toastr["warning"]("Please select " + vName.replace(/_/g, ' '));
                        return;
                    }
                }
            } catch (e) { }
        }

        // Use product_id if available, otherwise fallback to id
        const pid = currentProduct.product_id || currentProduct.id;
        const total = (currentProductBasePrice + selectedAddonsTotal + selectedVariationsTotal) * currentQty;
        const variationsStr = JSON.stringify(selectedVariations);
        const addonsStr = JSON.stringify(selectedAddons);

        // Construct the multi-parameter ID exactly as the controller expects
        const cartKey = pid + ',,,' + currentQty + ',,,' + total.toFixed(2) + ',,,' + variationsStr + ',,,' + addonsStr;
        const url = "{{ route('user.front.add.cart', [getParam(), ':id']) }}".replace(':id', cartKey);

        if (btn) {
            btn.style.pointerEvents = 'none';
            btn.style.opacity = '0.7';
            if (btnText) btnText.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
        }

        $.get(url, function (data) {
            if (data.error) {
                toastr["error"](data.error);
                if (btn) {
                    btn.style.pointerEvents = 'auto';
                    btn.style.opacity = '1';
                    if (btnText) btnText.innerText = originalText;
                }
            } else {
                toastr["success"](data.message || "Added to cart!");
                // Refresh to update cart count in header
                setTimeout(() => { location.reload(); }, 500);
            }
        }).fail(function (xhr) {
            console.error("Cart Request Failed", xhr);
            toastr["error"]("Failed to add to cart. Please try again.");
            if (btn) {
                btn.style.pointerEvents = 'auto';
                btn.style.opacity = '1';
                if (btnText) btnText.innerText = originalText;
            }
        });
    }
</script>