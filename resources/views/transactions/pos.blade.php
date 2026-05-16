@extends('layouts.app')

@section('title', __('Point of Sale'))

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
  <!-- LEFT: Products -->
  <div class="lg:col-span-2">
    <!-- Search bar card -->
    <div class="bg-white/80 backdrop-blur rounded-2xl shadow-sm border border-gray-200 mb-4">
      <div class="p-4">
        <div class="flex gap-2">
          <input type="text" id="searchProduct" placeholder="{{ __('Search product') }}..." autofocus
                 class="flex-1 px-4 py-2.5 border border-gray-300 rounded-xl input-focus">
          <select id="categoryFilter" class="px-4 py-2.5 border border-gray-300 rounded-xl input-focus appearance-none max-w-[130px]">
            <option value="">{{ __('All Categories') }}</option>
            @foreach ($products->unique('category')->pluck('category')->filter() as $cat)
              <option value="{{ $cat }}">{{ $cat }}</option>
            @endforeach
          </select>
        </div>
      </div>
    </div>
    <!-- Product grid grouped by category -->
    @php
    $cardThemes = [
        ['grad' => 'from-pink-500 to-rose-500',  'pill' => 'bg-pink-100 text-pink-700',   'icon' => 'bi-gem'],
        ['grad' => 'from-purple-500 to-indigo-500','pill' => 'bg-purple-100 text-purple-700','icon' => 'bi-stars'],
        ['grad' => 'from-blue-500 to-cyan-500',   'pill' => 'bg-blue-100 text-blue-700',   'icon' => 'bi-cup-hot'],
        ['grad' => 'from-emerald-500 to-teal-500', 'pill' => 'bg-emerald-100 text-emerald-700','icon' => 'bi-tree'],
        ['grad' => 'from-amber-500 to-orange-500', 'pill' => 'bg-amber-100 text-amber-700', 'icon' => 'bi-sun'],
        ['grad' => 'from-violet-500 to-fuchsia-500','pill' => 'bg-violet-100 text-violet-700','icon' => 'bi-flower1'],
    ];
    $catNames = $products->pluck('category')->filter()->unique()->values()->all();
    $catThemeMap = [];
    foreach ($catNames as $i => $cat) {
        $catThemeMap[$cat] = $cardThemes[$i % count($cardThemes)];
    }
    $defaultTheme = ['grad' => 'from-gray-500 to-slate-500', 'pill' => 'bg-gray-100 text-gray-700', 'icon' => 'bi-box'];
    $grouped = [];
    foreach ($products as $p) {
        $key = $p->category ?: "\x00";
        $grouped[$key][] = $p;
    }
    @endphp
    <div id="productList">
      @foreach ($grouped as $catKey => $catProducts)
        @php
            $isUncat = $catKey === "\x00";
            $displayCat = $isUncat ? __('No Category') : $catKey;
            $t = $isUncat ? $defaultTheme : ($catThemeMap[$catKey] ?? $defaultTheme);
        @endphp
        <div class="category-group mb-6">
          <div class="flex items-center gap-3 mb-3">
            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-sm font-bold {{ $t['pill'] }}">
              <span class="w-2.5 h-2.5 rounded-full bg-current"></span>
              {{ $displayCat }}
              <span class="text-xs opacity-60 font-normal">({{ count($catProducts) }})</span>
            </span>
            <div class="flex-1 h-px bg-gradient-to-r {{ $t['grad'] }} opacity-30"></div>
          </div>
          <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-3 md:gap-4">
            @foreach ($catProducts as $product)
              <div class="product-card cursor-pointer" data-id="{{ $product->id }}" data-name="{{ $product->name }}" data-price="{{ $product->selling_price }}" data-stock="{{ $product->stock }}" data-category="{{ $product->category }}" data-image="{{ $product->image ? asset('storage/'.$product->image) : '' }}">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-xl card-hover h-full overflow-hidden group dark:bg-slate-800 dark:border-slate-700">
                  <div class="h-2 bg-gradient-to-r {{ $t['grad'] }}"></div>
                  @if ($product->image)
                    <div class="relative">
                      <div class="w-full h-28 bg-gray-100 bg-cover bg-center bg-no-repeat" data-bg="{{ asset('storage/'.$product->image) }}"></div>
                      <div class="absolute inset-0 bg-gradient-to-t from-black/15 to-transparent"></div>
                    </div>
                  @else
                    <div class="bg-gradient-to-br {{ $t['grad'] }} flex items-center justify-center h-28">
                      <i class="bi {{ $t['icon'] }} text-3xl text-white/50"></i>
                    </div>
                  @endif
                  <div class="p-3">
                    <div class="font-semibold text-sm truncate text-gray-800 mb-2">{{ $product->name }}</div>
                    <div class="flex items-center justify-between">
                      <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold {{ $t['pill'] }}">
                        Rp{{ number_format($product->selling_price, 0, ',', '.') }}
                      </span>
                      @if ($product->stock <= 0)
                        <span class="flex items-center gap-1 text-xs font-semibold text-red-600 bg-red-50 px-2 py-0.5 rounded-full">
                          <i class="bi bi-x-circle-fill text-[10px]"></i> 0
                        </span>
                      @elseif ($product->stock <= 5)
                        <span class="flex items-center gap-1 text-xs font-semibold text-red-500 bg-red-50 px-2 py-0.5 rounded-full">
                          <i class="bi bi-exclamation-circle-fill text-[10px]"></i> {{ $product->stock }}
                        </span>
                      @elseif ($product->stock < 10)
                        <span class="flex items-center gap-1 text-xs font-semibold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full">
                          <i class="bi bi-exclamation-triangle-fill text-[10px]"></i> {{ $product->stock }}
                        </span>
                      @else
                        <span class="flex items-center gap-1 text-xs text-emerald-600">
                          <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                          {{ $product->stock }}
                        </span>
                      @endif
                    </div>
                    @if ($product->stock > 0 && $product->stock < 10)
                    <div class="mt-1.5 w-full bg-gray-100 rounded-full h-1.5 overflow-hidden">
                      <div class="h-full rounded-full {{ $product->stock <= 5 ? 'bg-red-400' : 'bg-amber-400' }}" style="width: {{ max(10, ($product->stock / 10) * 100) }}%"></div>
                    </div>
                    @endif
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      @endforeach
      @if (count($grouped) === 0)
        <div class="text-center py-12 text-gray-400">
          <i class="bi bi-box text-5xl block mb-3"></i>
          <p>{{ __('No products.') }}</p>
        </div>
      @endif
    </div>
  </div>
  <!-- RIGHT: Cart sidebar -->
  <div class="lg:col-span-1">
    <div class="bg-white/90 backdrop-blur rounded-2xl shadow-lg border border-gray-100 lg:sticky lg:top-4">
      <div class="p-4">
        <!-- Cart header -->
        <div class="flex items-center justify-between mb-3 gradient-primary -m-4 p-4 rounded-t-2xl text-white">
          <h2 class="font-bold"><i class="bi bi-cart3 me-2"></i>{{ __('Cart') }}</h2>
          <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-white/20 text-white text-xs font-bold" id="cartCount">0</span>
        </div>
        <!-- Cart items -->
        <div id="cartItems" class="mb-3 space-y-2 max-h-[280px] overflow-y-auto">
          <p class="text-gray-400 text-center py-6" id="emptyCart">{{ __('No items in cart') }}</p>
        </div>
        <hr class="border-gray-100 my-3">
        <!-- Member Section -->
        <div class="mb-3">
          <button type="button" id="memberToggle" class="w-full flex items-center justify-between py-1.5 text-xs font-medium text-gray-500 hover:text-purple-600 transition-all">
            <span><i class="bi bi-person-badge me-1"></i> {{ __('Member') }} & {{ __('Point') }}</span>
            <i class="bi bi-chevron-down text-[10px]" id="memberArrow"></i>
          </button>
          <div id="memberSection" class="hidden mt-1 space-y-2">
            <div class="flex gap-1.5">
              <input type="tel" id="memberPhone" placeholder="{{ __('Search member') }}..." class="flex-1 px-3 py-1.5 border border-gray-300 rounded-lg input-focus text-xs">
              <button type="button" id="memberSearchBtn" class="px-2.5 py-1.5 bg-indigo-100 text-indigo-600 rounded-lg hover:bg-indigo-200 transition-all text-xs"><i class="bi bi-search"></i></button>
            </div>
            <div id="memberInfo" class="hidden p-3 bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl border border-purple-200">
              <div class="flex items-center justify-between">
                <div>
                  <p class="font-semibold text-sm text-gray-800" id="memberName">-</p>
                  <p class="text-xs text-gray-500 dark:text-slate-400">{{ __('Points') }}: <span id="memberPoints" class="font-bold text-amber-600 dark:text-amber-400">0</span></p>
                </div>
                <button type="button" onclick="clearMember()" class="text-gray-400 hover:text-red-500 text-xs"><i class="bi bi-x-circle"></i></button>
              </div>
              <div class="mt-2 pt-2 border-t border-purple-200">
                <label class="text-xs text-gray-500 dark:text-slate-400 font-medium">{{ __('Redeem Points') }}</label>
                <div class="flex items-center gap-1.5 mt-1">
                  <button type="button" onclick="adjustRedeem(-100)" class="w-7 h-7 flex items-center justify-center rounded-lg border border-gray-300 text-gray-600 hover:bg-purple-100 transition-all text-xs font-bold">-</button>
                  <input type="number" id="redeemPoints" value="0" min="0" class="flex-1 px-2 py-1.5 border border-gray-300 rounded-lg text-center text-xs input-focus" readonly>
                  <button type="button" onclick="adjustRedeem(100)" class="w-7 h-7 flex items-center justify-center rounded-lg border border-gray-300 text-gray-600 hover:bg-purple-100 transition-all text-xs font-bold">+</button>
                  <button type="button" onclick="adjustRedeem(-1, true)" class="px-2 py-1.5 text-xs text-purple-600 bg-purple-100 rounded-lg hover:bg-purple-200 transition-all font-medium">{{ __('All') }}</button>
                </div>
                <div class="text-xs text-gray-500 dark:text-slate-400 mt-1">{{ __('Discount') }}: <span id="redeemDiscount" class="font-bold text-emerald-600 dark:text-emerald-400">Rp0</span></div>
              </div>
            </div>
            <div id="memberNotFound" class="hidden">
              <button type="button" onclick="openModal('registerMemberModal')" class="w-full py-2 text-xs text-purple-600 bg-purple-50 rounded-xl hover:bg-purple-100 border border-dashed border-purple-200 transition-all">
                <i class="bi bi-person-plus me-1"></i> {{ __('Register New Member') }}
              </button>
            </div>
            <div id="memberSearching" class="hidden text-center py-2 text-xs text-gray-400">
              <i class="bi bi-arrow-repeat animate-spin me-1"></i> {{ __('Searching') }}...
            </div>
          </div>
        </div>
        <hr class="border-gray-100 my-3">
        <!-- Subtotal -->
        <div class="flex justify-between text-sm mb-1">
          <span class="text-gray-500 dark:text-slate-400">{{ __('Subtotal') }}</span>
          <span class="font-semibold text-gray-800 dark:text-slate-200" id="subtotal">Rp0</span>
        </div>
        <!-- Diskon -->
        <div class="flex justify-between items-center mb-1 text-sm">
          <span class="text-gray-500 dark:text-slate-400">{{ __('Discount') }}</span>
          <div class="flex">
            <input type="number" id="discountInput" value="0" min="0" max="100" class="w-16 px-2 py-1.5 border border-gray-300 rounded-l-lg input-focus text-right text-sm">
            <span class="px-2 py-1.5 bg-gray-50 border border-l-0 border-gray-300 rounded-r-lg text-gray-500 text-sm">%</span>
          </div>
        </div>
        <div class="flex justify-between text-sm mb-1 text-purple-600">
          <span>{{ __('Points Discount') }}</span>
          <span id="pointsDiscountLabel" class="font-semibold">Rp0</span>
        </div>
        <hr class="border-gray-100 my-3">
        <!-- Total -->
        <div class="flex justify-between items-center text-lg font-bold mb-3">
          <span>{{ __('Total') }}</span>
          <span id="totalDisplay" class="text-indigo-600 dark:text-indigo-400">Rp0</span>
        </div>
        <!-- Payment Method Toggle -->
        <div class="mb-3">
          <div class="text-xs font-medium text-gray-500 dark:text-slate-400 mb-1.5">{{ __('Payment Method') }}</div>
          <div class="flex rounded-xl border border-gray-200 overflow-hidden">
            <button id="payTunai" class="flex-1 py-2 text-sm font-medium flex items-center justify-center gap-1.5 transition-all" style="background:#f3f4f6;color:#374151">
              <i class="bi bi-cash"></i> {{ __('Cash Payment') }}
            </button>
            <button id="payQris" class="flex-1 py-2 text-sm font-medium flex items-center justify-center gap-1.5 transition-all" style="background:#fff;color:#9ca3af">
              <i class="bi bi-qr-code"></i> {{ __('QRIS Payment') }}
            </button>
          </div>
        </div>
        <!-- Tunai Section -->
        <div id="tunaiSection">
          <div class="flex justify-between items-center mb-1">
            <span class="text-gray-600 dark:text-slate-400 text-sm">{{ __('Pay') }}</span>
            <input type="number" id="amountPaid" value="0" min="0" class="w-32 px-3 py-2 border border-gray-300 rounded-xl input-focus text-right text-sm">
          </div>
          <div class="flex justify-between items-center text-lg font-bold text-emerald-600 mb-3">
            <span class="text-sm font-medium">{{ __('Change') }}</span>
            <span id="changeDisplay" class="text-sm font-bold">Rp0</span>
          </div>
        </div>
        <!-- QRIS Section -->
        <div id="qrisSection" class="hidden">
          <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-2xl p-4 text-center mb-3 border border-dashed border-indigo-200">
            @if ($qrisExists)
              <img src="{{ asset('storage/qris.png') }}" alt="QRIS" class="w-44 mx-auto mb-3 rounded-xl">
            @else
              <div class="w-44 h-44 mx-auto mb-3 bg-white rounded-2xl flex items-center justify-center shadow-sm border border-gray-100">
                <div class="text-center">
                  <i class="bi bi-qr-code text-6xl text-indigo-300"></i>
                  <p class="text-xs text-gray-400 mt-2">Tempatkan QRIS</p>
                </div>
              </div>
            @endif
            <div class="text-sm font-semibold text-indigo-700 dark:text-indigo-400 mb-1">{{ __('Scan to pay') }}</div>
            <div class="text-xs text-gray-500 dark:text-slate-400">{{ __('Total') }}: <span id="qrisTotal" class="font-bold text-indigo-600 dark:text-indigo-400">Rp0</span></div>
          </div>
        </div>
        <!-- Checkout button -->
        <button id="checkoutBtn" class="w-full py-3 px-4 gradient-success text-white font-bold rounded-xl btn-scale disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100 mb-2 shadow-md" disabled>
          <i class="bi bi-cash me-1"></i> {{ __('Process Payment') }}
        </button>
        <button id="clearCartBtn" class="w-full py-2.5 px-4 bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-slate-300 font-medium rounded-xl hover:bg-gray-200 dark:hover:bg-slate-600 transition-all"><i class="bi bi-trash me-1"></i> {{ __('Clear') }}</button>
      </div>
    </div>
  </div>
</div>
<!-- Success Modal -->
<div id="successModal" class="modal-overlay hidden fixed inset-0 z-50 items-center justify-center bg-black/40 backdrop-blur-sm" onclick="if(event.target===this)closeModal('successModal')">
  <div class="bg-white rounded-3xl p-8 text-center max-w-sm mx-4 w-full shadow-2xl card-hover">
    <div class="w-16 h-16 rounded-full gradient-success flex items-center justify-center mx-auto mb-4 text-white text-3xl"><i class="bi bi-check-lg"></i></div>
    <h3 class="text-lg font-bold mb-2 text-gray-900 dark:text-slate-200">{{ __('Transaction Successful') }}!</h3>
    <p id="invoiceDisplay" class="text-gray-500 text-sm"></p>
    <div id="pointsDisplay" class="hidden mt-2 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-amber-50 border border-amber-200 text-amber-700 text-xs font-medium">
      <i class="bi bi-star-fill"></i> <span id="pointsEarnedText">0</span> {{ __('Points Earned') }}
    </div>
    <!-- QRIS display in modal -->
    <div id="qrisDisplay" class="hidden mt-3 mb-3">
      <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-2xl p-3 border border-dashed border-indigo-200">
        @if ($qrisExists)
          <img src="{{ asset('storage/qris.png') }}" alt="QRIS" class="w-48 mx-auto rounded-xl">
        @else
          <div class="w-48 h-48 mx-auto bg-white rounded-2xl flex items-center justify-center shadow-sm border border-gray-100">
            <div class="text-center">
              <i class="bi bi-qr-code text-6xl text-indigo-300"></i>
              <p class="text-xs text-gray-400 mt-2">Tempatkan QRIS</p>
            </div>
          </div>
        @endif
        <div class="text-center mt-2">
          <div class="text-sm font-semibold text-indigo-700 dark:text-indigo-400">{{ __('Scan to pay') }}</div>
          <div class="text-xs text-gray-500 dark:text-slate-400">{{ __('Total') }}: <span id="qrisModalTotal" class="font-bold text-indigo-600 dark:text-indigo-400">Rp0</span></div>
        </div>
      </div>
    </div>
    <div class="mt-3 mb-4 px-2">
      <div class="flex items-center gap-2 px-3 py-2.5 border border-gray-200 rounded-xl bg-gray-50 focus-within:border-emerald-400 focus-within:ring-2 focus-within:ring-emerald-100 transition-all text-left">
        <i class="bi bi-whatsapp text-emerald-500 text-lg"></i>
        <input type="tel" id="waNumber" placeholder="{{ __('Customer WhatsApp number') }}" class="flex-1 bg-transparent border-none outline-none text-sm text-gray-700 dark:text-slate-200 placeholder-gray-400">
      </div>
    </div>
    <div class="flex flex-col gap-2">
      <button onclick="printReceipt()" class="w-full py-2.5 px-4 gradient-primary text-white font-medium rounded-xl btn-scale"><i class="bi bi-printer me-1"></i> {{ __('Print Receipt') }}</button>
      <button onclick="openPdf()" class="w-full py-2.5 px-4 text-white font-medium rounded-xl btn-scale" style="background:#dc2626"><i class="bi bi-filetype-pdf me-1"></i> {{ __('Download PDF') }}</button>
      <button onclick="shareWa()" class="w-full py-2.5 px-4 text-white font-medium rounded-xl btn-scale" style="background:#25d366"><i class="bi bi-whatsapp me-1"></i> {{ __('Share WhatsApp') }}</button>
      <button onclick="closeModal('successModal')" class="w-full py-2.5 px-4 bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-slate-300 font-medium rounded-xl hover:bg-gray-200 dark:hover:bg-slate-600 transition-all">{{ __('Close') }}</button>
    </div>
  </div>
</div>
<!-- Register Member Modal -->
<div id="registerMemberModal" class="modal-overlay hidden fixed inset-0 z-50 items-center justify-center bg-black/40 backdrop-blur-sm" onclick="if(event.target===this)closeModal('registerMemberModal')">
  <div class="bg-white rounded-3xl p-6 max-w-sm mx-4 w-full shadow-2xl">
    <div class="text-center mb-4">
      <div class="w-14 h-14 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center mx-auto mb-3 shadow-md">
        <i class="bi bi-person-plus text-2xl text-white"></i>
      </div>
      <h3 class="text-lg font-bold text-gray-900 dark:text-slate-200">{{ __('Register New Member') }}</h3>
      <p class="text-xs text-gray-500 dark:text-slate-400 mt-1">{{ __('Customers will earn points on every purchase.') }}</p>
    </div>
    <div class="mb-3">
      <label class="block text-xs font-medium text-gray-600 dark:text-slate-400 mb-1">{{ __('Name') }}</label>
      <input type="text" id="regName" placeholder="{{ __('Customer name') }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl input-focus text-sm">
    </div>
    <div class="mb-4">
      <label class="block text-xs font-medium text-gray-600 dark:text-slate-400 mb-1">{{ __('Phone') }}</label>
      <input type="tel" id="regPhone" placeholder="08234567890" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl input-focus text-sm">
      <p id="regError" class="hidden text-red-500 text-xs mt-1"></p>
    </div>
    <div class="flex gap-2">
      <button onclick="closeModal('registerMemberModal')" class="flex-1 py-2.5 bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-slate-300 font-medium rounded-xl hover:bg-gray-200 dark:hover:bg-slate-600 transition-all">{{ __('Cancel') }}</button>
      <button onclick="registerMember()" id="regBtn" class="flex-1 py-2.5 text-white font-medium rounded-xl btn-scale" style="background: linear-gradient(135deg, #7c3aed, #db2777);">{{ __('Save') }}</button>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
var cart = [];
var receiptUrl = '';
var lastInvoice = '';
var lastTotal = '';
var lastDate = '';
var isQris = false;
var selectedMember = null;
var EARN_PER_AMOUNT = {{ $pointsEarnPerAmount }};
var REDEEM_PER_DISCOUNT = {{ $pointsRedeemPerDiscount }};
var REDEEM_DISCOUNT = {{ $pointsDiscountPerUnit }};
var pointsDiscount = 0;

function formatPrice(n) {
    return 'Rp' + n.toLocaleString('id-ID');
}

function updateCart() {
    const container = document.getElementById('cartItems');
    const checkoutBtn = document.getElementById('checkoutBtn');
    const cartCount = document.getElementById('cartCount');

    if (cart.length === 0) {
        container.innerHTML = '<p class="text-gray-400 text-center py-6" id="emptyCart">{{ __("No items in cart") }}</p>';
        checkoutBtn.disabled = true;
        cartCount.textContent = '0';
        updateTotals();
        return;
    }

    checkoutBtn.disabled = false;
    const totalQty = cart.reduce((sum, item) => sum + item.qty, 0);
    cartCount.textContent = totalQty;
    container.innerHTML = cart.map((item, idx) => `
        <div class="flex items-center gap-2 bg-gray-50 rounded-xl p-2.5 border border-gray-100">
            <div class="flex-1 min-w-0">
                <div class="font-semibold text-sm truncate text-gray-800">${item.name}</div>
                <div class="text-gray-400 text-xs">${formatPrice(item.price)}</div>
            </div>
            <div class="flex items-center gap-1 flex-shrink-0">
                <button onclick="updateQty(${idx}, -1)" class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-300 text-gray-600 hover:bg-indigo-50 hover:border-indigo-300 transition-all font-bold">-</button>
                <span class="font-bold text-sm w-6 text-center text-gray-800">${item.qty}</span>
                <button onclick="updateQty(${idx}, 1)" class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-300 text-gray-600 hover:bg-indigo-50 hover:border-indigo-300 transition-all font-bold">+</button>
            </div>
            <div class="font-semibold text-sm text-right w-16 text-indigo-600">${formatPrice(item.price * item.qty)}</div>
            <button onclick="removeItem(${idx})" class="w-8 h-8 flex items-center justify-center text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all flex-shrink-0">&times;</button>
        </div>
    `).join('');
    updateTotals();
}

function updateTotals() {
    const subtotal = cart.reduce((sum, item) => sum + item.price * item.qty, 0);
    const discountPct = parseFloat(document.getElementById('discountInput').value) || 0;
    const discount = subtotal * (discountPct / 100);
    const totalBeforePoints = subtotal - discount;
    pointsDiscount = calculatePointsDiscount();
    const total = Math.max(0, totalBeforePoints - pointsDiscount);
    const paid = parseFloat(document.getElementById('amountPaid').value) || 0;
    const change = Math.max(0, paid - total);

    document.getElementById('subtotal').textContent = formatPrice(subtotal);
    document.getElementById('pointsDiscountLabel').textContent = pointsDiscount > 0 ? '-' + formatPrice(pointsDiscount) : 'Rp0';
    document.getElementById('totalDisplay').textContent = formatPrice(total);
    document.getElementById('changeDisplay').textContent = formatPrice(change);
    document.getElementById('qrisTotal').textContent = formatPrice(total);
    document.getElementById('qrisModalTotal').textContent = formatPrice(total);
}

function calculatePointsDiscount() {
    if (!selectedMember) return 0;
    const redeem = parseInt(document.getElementById('redeemPoints').value) || 0;
    const units = Math.floor(redeem / REDEEM_PER_DISCOUNT);
    return units * REDEEM_DISCOUNT;
}

function updateRedeemDisplay() {
    const discount = calculatePointsDiscount();
    document.getElementById('redeemDiscount').textContent = formatPrice(discount);
    updateTotals();
}

function addToCart(id, name, price, stock) {
    if (stock <= 0) { alert('{{ __("Stock Out") }}!'); return; }
    const existing = cart.find(item => item.id === id);
    if (existing) {
        if (existing.qty >= stock) { alert('{{ __("Insufficient stock") }}!'); return; }
        existing.qty++;
    } else {
        cart.push({ id, name, price, stock, qty: 1 });
    }
    updateCart();
}

function updateQty(idx, delta) {
    const item = cart[idx];
    const newQty = item.qty + delta;
    if (newQty <= 0) { removeItem(idx); return; }
    if (newQty > item.stock) { alert('{{ __("Insufficient stock") }}!'); return; }
    item.qty = newQty;
    updateCart();
}

function removeItem(idx) {
    cart.splice(idx, 1);
    updateCart();
}

document.getElementById('clearCartBtn').addEventListener('click', () => { cart.length = 0; updateCart(); });

// Member functions
document.getElementById('memberToggle').addEventListener('click', function() {
    const section = document.getElementById('memberSection');
    const arrow = document.getElementById('memberArrow');
    section.classList.toggle('hidden');
    arrow.classList.toggle('bi-chevron-down');
    arrow.classList.toggle('bi-chevron-up');
});

function searchMember() {
    const phone = document.getElementById('memberPhone').value.trim();
    if (!phone) return;
    document.getElementById('memberSearching').classList.remove('hidden');
    document.getElementById('memberInfo').classList.add('hidden');
    document.getElementById('memberNotFound').classList.add('hidden');
    fetch('{{ route("members.search-by-phone") }}?phone=' + encodeURIComponent(phone))
        .then(r => r.json())
        .then(data => {
            document.getElementById('memberSearching').classList.add('hidden');
            if (data.found) {
                selectedMember = data;
                document.getElementById('memberName').textContent = data.name + ' (' + data.phone + ')';
                document.getElementById('memberPoints').textContent = data.points.toLocaleString('id-ID');
                document.getElementById('memberInfo').classList.remove('hidden');
                document.getElementById('memberNotFound').classList.add('hidden');
                document.getElementById('redeemPoints').value = 0;
                document.getElementById('redeemPoints').max = Math.floor(data.points / REDEEM_PER_DISCOUNT) * REDEEM_PER_DISCOUNT;
                updateRedeemDisplay();
            } else {
                document.getElementById('memberInfo').classList.add('hidden');
                document.getElementById('memberNotFound').classList.remove('hidden');
                document.getElementById('regPhone').value = phone;
            }
        });
}
document.getElementById('memberSearchBtn').addEventListener('click', searchMember);
document.getElementById('memberPhone').addEventListener('keydown', function(e) { if (e.key === 'Enter') searchMember(); });

function clearMember() {
    selectedMember = null;
    document.getElementById('memberInfo').classList.add('hidden');
    document.getElementById('memberNotFound').classList.add('hidden');
    document.getElementById('memberPhone').value = '';
    document.getElementById('redeemPoints').value = 0;
    pointsDiscount = 0;
    updateTotals();
}

function adjustRedeem(delta, all) {
    if (!selectedMember) return;
    var input = document.getElementById('redeemPoints');
    var val = parseInt(input.value) || 0;
    var max = Math.floor(selectedMember.points / REDEEM_PER_DISCOUNT) * REDEEM_PER_DISCOUNT;
    if (all) {
        val = max;
    } else {
        val = Math.max(0, Math.min(max, val + delta));
    }
    input.value = val;
    updateRedeemDisplay();
}

function registerMember() {
    var name = document.getElementById('regName').value.trim();
    var phone = document.getElementById('regPhone').value.trim();
    var err = document.getElementById('regError');
    if (!name || !phone) { err.textContent = '{{ __("Name and phone are required") }}'; err.classList.remove('hidden'); return; }
    var btn = document.getElementById('regBtn');
    btn.disabled = true; btn.textContent = '{{ __("Saving") }}...';
    fetch('{{ route("transactions.register-member") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ name: name, phone: phone }),
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            selectedMember = data.member;
            document.getElementById('memberName').textContent = data.member.name + ' (' + data.member.phone + ')';
            document.getElementById('memberPoints').textContent = '0';
            document.getElementById('memberInfo').classList.remove('hidden');
            document.getElementById('memberNotFound').classList.add('hidden');
            document.getElementById('memberPhone').value = data.member.phone;
            document.getElementById('redeemPoints').value = 0;
            document.getElementById('redeemPoints').max = 0;
            updateRedeemDisplay();
            closeModal('registerMemberModal');
        } else {
            err.textContent = data.message || '{{ __("Failed to register member") }}';
            err.classList.remove('hidden');
        }
    })
    .catch(e => { err.textContent = '{{ __("An error occurred") }}'; err.classList.remove('hidden'); })
    .finally(() => { btn.disabled = false; btn.textContent = '{{ __("Save") }}'; });
}

document.querySelectorAll('.product-card').forEach(card => {
    card.addEventListener('click', function() {
        addToCart(parseInt(this.dataset.id), this.dataset.name, parseFloat(this.dataset.price), parseInt(this.dataset.stock));
    });
});

var searchTimer;
document.getElementById('searchProduct').addEventListener('input', function() {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(() => filterProducts(), 300);
});
document.getElementById('categoryFilter').addEventListener('change', filterProducts);

function filterProducts() {
    const q = document.getElementById('searchProduct').value.toLowerCase();
    const cat = document.getElementById('categoryFilter').value;
    document.querySelectorAll('.category-group').forEach(group => {
        let visible = 0;
        group.querySelectorAll('.product-card').forEach(card => {
            const name = card.dataset.name.toLowerCase();
            const category = card.dataset.category || '';
            const show = name.includes(q) && (!cat || category === cat);
            card.style.display = show ? '' : 'none';
            if (show) visible++;
        });
        group.style.display = visible > 0 ? '' : 'none';
    });
    loadVisibleBgs();
}
function loadVisibleBgs() {
    document.querySelectorAll('[data-bg]:not([style*="background-image"])').forEach(function(el) {
        if (el.offsetParent !== null) {
            el.style.backgroundImage = 'url(' + el.dataset.bg + ')';
            el.removeAttribute('data-bg');
        }
    });
}
// Lazy load background images below the fold
var bgObserver = new IntersectionObserver(function(entries) {
    entries.forEach(function(entry) {
        if (entry.isIntersecting) {
            var el = entry.target;
            el.style.backgroundImage = 'url(' + el.dataset.bg + ')';
            el.removeAttribute('data-bg');
            bgObserver.unobserve(el);
        }
    });
}, { rootMargin: '200px' });
document.querySelectorAll('[data-bg]').forEach(function(el) { bgObserver.observe(el); });

// Payment method toggle
function setPaymentTunai() {
    isQris = false;
    document.getElementById('payTunai').style.cssText = 'background:#f3f4f6;color:#374151';
    document.getElementById('payQris').style.cssText = 'background:#fff;color:#9ca3af';
    document.getElementById('tunaiSection').classList.remove('hidden');
    document.getElementById('qrisSection').classList.add('hidden');
    document.getElementById('checkoutBtn').innerHTML = '<i class="bi bi-cash me-1"></i> {{ __("Process Payment") }}';
}
function setPaymentQris() {
    isQris = true;
    document.getElementById('payTunai').style.cssText = 'background:#fff;color:#9ca3af';
    document.getElementById('payQris').style.cssText = 'background:#f3f4f6;color:#374151';
    document.getElementById('tunaiSection').classList.add('hidden');
    document.getElementById('qrisSection').classList.remove('hidden');
    document.getElementById('checkoutBtn').innerHTML = '<i class="bi bi-qr-code me-1"></i> {{ __("Confirm QRIS Payment") }}';
}
document.getElementById('payTunai').addEventListener('click', setPaymentTunai);
document.getElementById('payQris').addEventListener('click', setPaymentQris);

document.getElementById('discountInput').addEventListener('input', updateTotals);
document.getElementById('amountPaid').addEventListener('input', updateTotals);

document.getElementById('checkoutBtn').addEventListener('click', function() {
    if (cart.length === 0) return;
    const subtotal = cart.reduce((sum, item) => sum + item.price * item.qty, 0);
    const discountPct = parseFloat(document.getElementById('discountInput').value) || 0;
    const total = subtotal - (subtotal * (discountPct / 100));
    var paid;
    if (isQris) {
        paid = total;
    } else {
        paid = parseFloat(document.getElementById('amountPaid').value) || 0;
        if (paid < total) { alert('{{ __("Insufficient payment") }}!'); return; }
    }

    this.disabled = true;
    this.innerHTML = '<svg class="animate-spin h-5 w-5 mx-auto" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>';

    var bodyData = { items: cart.map(item => ({ product_id: item.id, quantity: item.qty })), amount_paid: paid };
    if (selectedMember) {
        bodyData.member_id = selectedMember.id;
        bodyData.points_redeemed = parseInt(document.getElementById('redeemPoints').value) || 0;
    }
    fetch('{{ route("transactions.checkout") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
        body: JSON.stringify(bodyData),
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            document.getElementById('invoiceDisplay').textContent = 'Invoice: ' + data.transaction.invoice_number;
            receiptUrl = data.redirect;
            lastInvoice = data.transaction.invoice_number;
            lastTotal = new Intl.NumberFormat('id-ID').format(data.transaction.total_price);
            lastDate = new Date().toLocaleDateString('id-ID', { day:'2-digit', month:'2-digit', year:'numeric', hour:'2-digit', minute:'2-digit' });
            var actualTotal = data.transaction.total_price;
            if (isQris) {
                document.getElementById('qrisDisplay').classList.remove('hidden');
            } else {
                document.getElementById('qrisDisplay').classList.add('hidden');
            }
            // Show points earned
            var pointsEl = document.getElementById('pointsDisplay');
            if (data.transaction.points_earned > 0) {
                document.getElementById('pointsEarnedText').textContent = data.transaction.points_earned;
                pointsEl.classList.remove('hidden');
            } else {
                pointsEl.classList.add('hidden');
            }
            cart.length = 0;
            updateCart();
            document.getElementById('amountPaid').value = 0;
            document.getElementById('discountInput').value = 0;
            document.getElementById('redeemPoints').value = 0;
            pointsDiscount = 0;
            document.getElementById('pointsDiscountLabel').textContent = 'Rp0';
            selectedMember = null;
            document.getElementById('memberInfo').classList.add('hidden');
            document.getElementById('memberNotFound').classList.add('hidden');
            document.getElementById('memberPhone').value = '';
            document.getElementById('qrisModalTotal').textContent = formatPrice(actualTotal);
            document.getElementById('qrisTotal').textContent = formatPrice(actualTotal);
            openModal('successModal');
        } else {
            alert(data.message || '{{ __("Transaction failed") }}');
        }
    })
    .catch(err => alert('Terjadi kesalahan: ' + err.message))
    .finally(() => { this.disabled = false; this.innerHTML = '<i class="bi bi-cash me-1"></i> Proses Pembayaran'; });
});

function printReceipt() {
    if (receiptUrl) window.open(receiptUrl, '_blank', 'width=400,height=600');
    closeModal('successModal');
}
function openPdf() {
    if (receiptUrl) downloadPdf();
    closeModal('successModal');
}
function downloadPdf() {
    var a = document.createElement('a');
    a.href = receiptUrl + '/pdf';
    a.download = 'invoice-' + lastInvoice + '.pdf';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
}
function shareWa() {
    var num = document.getElementById('waNumber').value.replace(/\D/g, '');
    if (num.length < 8) { alert('Masukkan nomor WhatsApp pelanggan dengan benar'); return; }
    if (num.startsWith('0')) num = '62' + num.slice(1);
    var msg = encodeURIComponent('{{ __("Thank you for shopping at") }} {{ $storeName }}!\n{{ __("Invoice") }}: ' + lastInvoice + '\n{{ __("Total") }}: Rp' + lastTotal + '\n{{ __("Date") }}: ' + lastDate);
    downloadPdf();
    window.open('https://wa.me/' + num + '?text=' + msg, '_blank');
    closeModal('successModal');
}

document.addEventListener('keydown', function(e) { if (e.key === 'F8') { e.preventDefault(); document.getElementById('searchProduct').focus(); } });
document.getElementById('searchProduct').addEventListener('keydown', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        const card = document.querySelector('.product-card:not([style*="display: none"])');
        if (card) { card.click(); this.value = ''; this.focus(); }
    }
});
</script>
@endpush
