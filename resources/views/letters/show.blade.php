@extends('layouts.app')

@section('title', $letter->letter_number)

@section('content')
<div class="max-w-3xl mx-auto">
  <div class="flex items-center justify-between gap-3 mb-4 flex-wrap">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-slate-200">{{ $letter->letter_number }}</h1>
    <div class="flex gap-2">
      <a href="{{ route('letters.edit', $letter) }}" class="inline-flex items-center gap-2 px-4 py-2.5 font-medium rounded-xl bg-amber-50 text-amber-700 hover:bg-amber-100 btn-scale dark:bg-amber-900/30 dark:text-amber-400">
        <i class="bi bi-pencil"></i> {{ __('Edit') }}
      </a>
      <a href="{{ route('letters.export-word', $letter) }}" class="inline-flex items-center gap-2 px-4 py-2.5 font-medium rounded-xl bg-emerald-50 text-emerald-700 hover:bg-emerald-100 btn-scale dark:bg-emerald-900/30 dark:text-emerald-400">
        <i class="bi bi-file-word"></i> {{ __('Download Word') }}
      </a>
      <a href="{{ route('letters.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 font-medium rounded-xl bg-gray-100 text-gray-700 hover:bg-gray-200 btn-scale dark:bg-slate-700 dark:text-slate-300">
        <i class="bi bi-arrow-left"></i> {{ __('Back') }}
      </a>
    </div>
  </div>

  <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 sm:p-12 leading-relaxed" style="font-family:'Times New Roman',serif;">
    <p class="text-center font-bold text-sm mb-6">{{ $letter->letter_number }}</p>

    <table class="w-full text-sm mb-6" style="font-family:'Times New Roman',serif;">
      <tr><td style="width:80px">Nomor</td><td style="width:16px">:</td><td>{{ $letter->letter_number }}</td></tr>
      <tr><td>Lampiran</td><td>:</td><td>{{ $letter->attachment_count ?? '-' }}</td></tr>
      <tr><td>Perihal</td><td>:</td><td><b>{{ $letter->subject }}</b></td></tr>
    </table>

    <p class="mb-1">Kepada Yth.</p>
    <p class="font-bold mb-0">{{ $letter->recipient_name }}</p>
    @if ($letter->recipient_place)
      <p class="mb-4">di {{ $letter->recipient_place }}</p>
    @endif

    <p class="mb-4">Dengan hormat,</p>

    <div class="mb-4 whitespace-pre-line text-justify">{{ $letter->body }}</div>

    <p class="mb-8">Demikian surat permohonan ini dibuat, atas perhatian dan kerjasamanya diucapkan terima kasih.</p>

    <div class="text-right mb-8">
      <p>{{ ($letter->place ?? '________') }}, {{ \Carbon\Carbon::parse($letter->date)->isoFormat('D MMMM Y') }}</p>
      <p class="mt-8">Hormat Kami,</p>
      <p class="mt-12 font-bold">{{ $letter->sender_name }}</p>
      <p>{{ $letter->sender_position }}</p>
    </div>
  </div>
</div>
@endsection