@extends('layouts.app')

@section('title', __('QRIS Settings'))

@section('content')
<div class="max-w-2xl mx-auto">
  <div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-slate-200">{{ __('QRIS Settings') }}</h1>
    <a href="{{ route('dashboard.admin') }}" class="inline-flex items-center gap-2 px-4 py-2.5 font-medium rounded-xl bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-slate-300 hover:bg-gray-200 dark:hover:bg-slate-600 btn-scale">
      <i class="bi bi-arrow-left"></i> {{ __('Back') }}
    </a>
  </div>

  @if (session('success'))
    <div class="alert flex items-center gap-2 p-4 mb-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 shadow-sm card-hover">
      <i class="bi bi-check-circle-fill text-emerald-500"></i>{{ session('success') }}
    </div>
  @endif

  @if ($errors->any())
    <div class="alert p-4 mb-4 rounded-xl bg-red-50 border border-red-200 text-red-700 shadow-sm">
      <ul class="mb-0 list-disc list-inside text-sm">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="space-y-4">
    <!-- Upload QRIS -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 card-hover">
      <div class="p-5">
        <h2 class="font-bold text-gray-900 dark:text-slate-200 mb-1"><i class="bi bi-cloud-upload text-indigo-600 me-2"></i>{{ __('Upload QRIS') }}</h2>
        <p class="text-sm text-gray-500 dark:text-slate-400 mb-4">{{ __('Upload QRIS image from your bank or Payment Service Provider (PNG/JPG, max 2MB)') }}</p>

        <form action="{{ route('settings.qris.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
          @csrf
          <div class="flex flex-col items-center gap-4">
            @if ($qrisExists)
              <div class="relative group">
                <img src="{{ asset('storage/qris.png') . '?v=' . time() }}" alt="QRIS" class="w-48 rounded-2xl shadow-sm border border-gray-200">
                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/5 rounded-2xl transition-all"></div>
              </div>
            @else
              <div class="w-48 h-48 bg-gradient-to-br from-gray-50 to-indigo-50 dark:from-slate-700 dark:to-slate-800 rounded-2xl flex items-center justify-center border-2 border-dashed border-gray-300 dark:border-slate-600">
                <div class="text-center">
                  <i class="bi bi-qr-code text-5xl text-gray-300 dark:text-slate-500"></i>
                  <p class="text-xs text-gray-400 dark:text-slate-500 mt-1">{{ __('No QRIS yet') }}</p>
                </div>
              </div>
            @endif
            <div class="w-full max-w-sm">
              <label class="flex items-center gap-3 px-4 py-3 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer hover:border-indigo-400 hover:bg-indigo-50/30 transition-all">
                <i class="bi bi-image text-2xl text-gray-400"></i>
                <div class="flex-1">
                  <p class="text-sm font-medium text-gray-700 dark:text-slate-300">{{ __('Choose image file') }}</p>
                  <p class="text-xs text-gray-400 dark:text-slate-500">PNG {{ __('or') }} JPG, {{ __('max') }} 2MB</p>
                </div>
                <input type="file" name="qris_image" accept="image/png,image/jpeg" required class="hidden">
              </label>
            </div>
          </div>
          <button type="submit" class="w-full py-2.5 px-4 gradient-primary text-white font-medium rounded-xl btn-scale">
            <i class="bi bi-save me-1"></i> {{ __('Save QRIS') }}
          </button>
        </form>
      </div>
    </div>

    <!-- Generate QR Code -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 card-hover">
      <div class="p-5">
        <h2 class="font-bold text-gray-900 dark:text-slate-200 mb-1"><i class="bi bi-qr-code text-purple-600 me-2"></i>{{ __('Generate QR Code') }}</h2>
        <p class="text-sm text-gray-500 dark:text-slate-400 mb-4">{{ __('Create your own QR code.') }} <span class="text-amber-600 dark:text-amber-400 font-medium">{{ __('Note') }}:</span> {{ __('This is not an official QRIS, just a regular QR code.') }}</p>

        <div class="flex flex-col items-center gap-4">
          <div class="w-full max-w-sm">
            <label class="text-sm font-medium text-gray-700 dark:text-slate-300 mb-1 block">{{ __('Enter text for QR code') }}</label>
            <input type="text" id="qrText" value="{{ config('app.name', 'Kasirku') }}" placeholder="{{ __('Store name or link') }}..." class="w-full px-4 py-2.5 border border-gray-300 rounded-xl input-focus text-sm">
          </div>
          <div class="flex gap-2">
            <button onclick="generateQr()" class="py-2 px-6 gradient-purple text-white font-medium rounded-xl btn-scale"><i class="bi bi-magic me-1"></i> {{ __('Generate') }}</button>
            <button onclick="downloadQr()" class="py-2 px-6 bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-slate-300 font-medium rounded-xl btn-scale hover:bg-gray-200 dark:hover:bg-slate-600"><i class="bi bi-download me-1"></i> {{ __('Download') }}</button>
          </div>
          <div id="qrResult" class="hidden flex flex-col items-center gap-3">
            <div id="qrCanvas" class="bg-white p-3 rounded-2xl shadow-sm border border-gray-200"></div>
            <p id="qrInfo" class="text-xs text-gray-400"></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/qrious@4.0.2/dist/qrious.min.js"></script>
<script>
var qr = null;
function generateQr() {
    var text = document.getElementById('qrText').value.trim();
    if (!text) { alert('{{ __("Please enter text first") }}'); return; }
    var container = document.getElementById('qrCanvas');
    container.innerHTML = '';
    qr = new QRious({
        element: document.createElement('canvas'),
        value: text,
        size: 256,
        level: 'H',
        backgroundAlpha: 0,
        foreground: '#111827',
    });
    container.appendChild(qr.canvas);
    document.getElementById('qrResult').classList.remove('hidden');
    document.getElementById('qrInfo').textContent = '{{ __("Content") }}: ' + text;
}
function downloadQr() {
    if (!qr) { alert('{{ __("Generate QR code first") }}'); return; }
    var link = document.createElement('a');
    link.download = 'qrcode-' + Date.now() + '.png';
    link.href = qr.canvas.toDataURL('image/png');
    link.click();
}
// Auto-generate on load
generateQr();
</script>
@endpush
