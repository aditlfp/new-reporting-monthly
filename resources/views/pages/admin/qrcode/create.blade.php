@php
    $isEdit = isset($qrCode);
@endphp

<x-app-layout :title="$isEdit ? 'Edit QR Code' : 'Tambah QR Code'" :subtitle="$isEdit ? 'Memperbarui data QR code tanpa mengganti path file' : 'Membuat data QR code baru'">
    <div class="flex h-screen bg-slate-50">
        @include('components.sidebar-component')

        <div class="flex-1 p-6 mt-16 overflow-y-auto md:mt-0">
            <div class="max-w-3xl bg-white shadow-lg card">
                <div class="card-body">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-semibold text-slate-900">{{ $isEdit ? 'Edit QR Code' : 'Form QR Code' }}</h2>
                        <a href="{{ route('admin-qrcode.index') }}" class="btn btn-ghost btn-sm">Kembali</a>
                    </div>

                    <form action="{{ $isEdit ? route('admin-qrcode.update', $qrCode->id) : route('admin-qrcode.store') }}" method="POST" class="space-y-4">
                        @csrf
                        @if ($isEdit)
                            @method('PUT')
                        @endif

                        <div class="form-control">
                            <label class="label">
                                <span class="font-medium label-text">Data Area</span>
                            </label>
                            <textarea
                                name="data"
                                rows="5"
                                placeholder="Masukkan data..."
                                class="textarea textarea-bordered w-full @error('data') textarea-error @enderror">{{ old('data', $qrCode->data ?? '') }}</textarea>
                            @error('data')
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        {{-- <div class="p-4 text-sm border rounded-lg bg-slate-50 border-slate-200 text-slate-600">
                            {{ $isEdit ? 'Saat diupdate, path file QR tetap sama dan isi QR akan diperbarui sesuai data terbaru.' : 'QR code akan digenerate otomatis ke URL http://laporan.wow/send-img/laporan?n=[data] dan file disimpan ke storage/app/public/qr.' }}
                        </div> --}}

                        @if ($isEdit)
                            <div class="p-4 bg-white border rounded-lg border-slate-200">
                                <p class="mb-2 text-sm font-medium text-slate-700">Preview QR Saat Ini</p>
                                <div class="flex items-center justify-center p-2 border rounded-lg bg-slate-50 border-slate-200 w-36 h-36">
                                    <img
                                        src="{{ \App\Helpers\FileHelper::getImageUrl($qrCode->qr) }}"
                                        alt="Preview QR"
                                        class="object-contain w-28 h-28">
                                </div>
                                <p class="mt-3 text-xs break-all text-slate-500">{{ $qrCode->qr }}</p>
                            </div>
                        @endif

                        <div class="flex gap-2 pt-2">
                            <button type="submit" class="text-white border-none btn bg-slate-900 hover:bg-slate-800">
                                {{ $isEdit ? 'Update' : 'Simpan' }}
                            </button>
                            <a href="{{ route('admin-qrcode.index') }}" class="btn btn-ghost">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
