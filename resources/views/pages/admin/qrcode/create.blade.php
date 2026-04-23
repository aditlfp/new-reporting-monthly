@php
    $isEdit = isset($qrCode);
    $qrCodeParts = $isEdit
        ? \App\Services\Media\QrCodeService::splitStoredData($qrCode->data)
        : ['data' => '', 'kegiatan' => ''];
    $kegiatanOptions = $kegiatanOptions ?? [];
@endphp

<x-app-layout :title="$isEdit ? 'Edit QR Code' : 'Tambah QR Code'" :subtitle="$isEdit ? 'Memperbarui data QR code tanpa mengganti path file' : 'Membuat data QR code baru'">
    @push('styles')
        <style>
            .kegiatan-combobox {
                position: relative;
            }

            .kegiatan-suggestions {
                position: absolute;
                top: calc(100% + 0.35rem);
                left: 0;
                right: 0;
                z-index: 30;
                overflow: hidden;
                border: 1px solid rgba(148, 163, 184, 0.28);
                border-radius: 0.875rem;
                background: rgba(255, 255, 255, 0.98);
                box-shadow: 0 18px 45px rgba(15, 23, 42, 0.14);
            }

            .kegiatan-suggestions[hidden] {
                display: none;
            }

            .kegiatan-suggestion-list {
                max-height: 14rem;
                overflow-y: auto;
                padding: 0.35rem;
            }

            .kegiatan-suggestion-item {
                width: 100%;
                display: flex;
                align-items: center;
                gap: 0.6rem;
                padding: 0.65rem 0.75rem;
                border-radius: 0.65rem;
                color: #334155;
                font-size: 0.875rem;
                font-weight: 600;
                text-align: left;
                transition: background-color 150ms ease, color 150ms ease;
            }

            .kegiatan-suggestion-item:hover,
            .kegiatan-suggestion-item.is-active {
                background: #eff6ff;
                color: #1d4ed8;
            }

            .kegiatan-suggestion-icon {
                width: 1.65rem;
                height: 1.65rem;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
                border-radius: 9999px;
                background: #dbeafe;
                color: #2563eb;
            }

            .kegiatan-suggestion-empty {
                padding: 0.85rem 1rem;
                color: #64748b;
                font-size: 0.875rem;
            }
        </style>
    @endpush

    <div class="admin-shell flex min-h-screen bg-slate-50">
        @include('components.sidebar-component')

        <div class="admin-content flex-1 p-6 overflow-y-auto">
            <div class="max-w-3xl bg-white shadow-lg card admin-panel">
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
                                class="textarea textarea-bordered w-full @error('data') textarea-error @enderror">{{ old('data', $qrCodeParts['data']) }}</textarea>
                            @error('data')
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="font-medium label-text">Kegiatan</span>
                                <span class="label-text-alt text-slate-500">Opsional</span>
                            </label>
                            <div class="kegiatan-combobox" data-kegiatan-combobox data-options='@json($kegiatanOptions)'>
                                <input
                                    type="text"
                                    name="kegiatan"
                                    value="{{ old('kegiatan', $qrCodeParts['kegiatan']) }}"
                                    placeholder="Masukkan nama kegiatan..."
                                    autocomplete="off"
                                    aria-autocomplete="list"
                                    aria-expanded="false"
                                    class="input input-bordered w-full pr-10 @error('kegiatan') input-error @enderror"
                                    data-kegiatan-input>
                                <i class="ri-arrow-down-s-line pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-xl text-slate-400"></i>
                                <div class="kegiatan-suggestions" hidden data-kegiatan-menu>
                                    <div class="kegiatan-suggestion-list" data-kegiatan-list></div>
                                </div>
                            </div>
                            <label class="label">
                                <span class="label-text-alt text-slate-500">Pilih dari referensi atau ketik kegiatan baru.</span>
                            </label>
                            @error('kegiatan')
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        {{-- <div class="p-4 text-sm border rounded-lg bg-slate-50 border-slate-200 text-slate-600">
                            {{ $isEdit ? 'Saat diupdate, path file QR tetap sama dan isi QR akan diperbarui sesuai data terbaru.' : 'QR code akan digenerate otomatis ke URL https://laporan-sac.sac-po.com/send-img/laporan?n=[data]&keg=[kegiatan] dan file disimpan ke storage/app/public/qr.' }}
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

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                document.querySelectorAll('[data-kegiatan-combobox]').forEach((combobox) => {
                    const input = combobox.querySelector('[data-kegiatan-input]');
                    const menu = combobox.querySelector('[data-kegiatan-menu]');
                    const list = combobox.querySelector('[data-kegiatan-list]');
                    const options = JSON.parse(combobox.dataset.options || '[]');
                    let activeIndex = -1;
                    let visibleOptions = [];

                    const closeMenu = () => {
                        menu.hidden = true;
                        input.setAttribute('aria-expanded', 'false');
                        activeIndex = -1;
                    };

                    const renderOptions = () => {
                        const query = input.value.trim().toLowerCase();
                        visibleOptions = options
                            .filter((option) => option.toLowerCase().includes(query))
                            .slice(0, 10);

                        list.innerHTML = '';

                        if (!visibleOptions.length) {
                            list.innerHTML = '<div class="kegiatan-suggestion-empty">Belum ada referensi yang cocok.</div>';
                            menu.hidden = false;
                            input.setAttribute('aria-expanded', 'true');
                            return;
                        }

                        visibleOptions.forEach((option, index) => {
                            const button = document.createElement('button');
                            button.type = 'button';
                            button.className = 'kegiatan-suggestion-item';
                            button.dataset.index = String(index);
                            button.innerHTML = `
                                <span class="kegiatan-suggestion-icon"><i class="ri-sparkling-2-line"></i></span>
                                <span>${option.replace(/[&<>"']/g, (char) => ({
                                    '&': '&amp;',
                                    '<': '&lt;',
                                    '>': '&gt;',
                                    '"': '&quot;',
                                    "'": '&#039;',
                                }[char]))}</span>
                            `;
                            button.addEventListener('mousedown', (event) => {
                                event.preventDefault();
                                input.value = option;
                                closeMenu();
                            });
                            list.appendChild(button);
                        });

                        menu.hidden = false;
                        input.setAttribute('aria-expanded', 'true');
                    };

                    const setActiveOption = (nextIndex) => {
                        const items = Array.from(list.querySelectorAll('.kegiatan-suggestion-item'));
                        items.forEach((item) => item.classList.remove('is-active'));

                        if (!items.length) {
                            activeIndex = -1;
                            return;
                        }

                        activeIndex = (nextIndex + items.length) % items.length;
                        items[activeIndex].classList.add('is-active');
                        items[activeIndex].scrollIntoView({ block: 'nearest' });
                    };

                    input.addEventListener('focus', renderOptions);
                    input.addEventListener('input', renderOptions);
                    input.addEventListener('keydown', (event) => {
                        if (menu.hidden && ['ArrowDown', 'ArrowUp'].includes(event.key)) {
                            renderOptions();
                        }

                        if (event.key === 'ArrowDown') {
                            event.preventDefault();
                            setActiveOption(activeIndex + 1);
                        } else if (event.key === 'ArrowUp') {
                            event.preventDefault();
                            setActiveOption(activeIndex - 1);
                        } else if (event.key === 'Enter' && activeIndex >= 0 && visibleOptions[activeIndex]) {
                            event.preventDefault();
                            input.value = visibleOptions[activeIndex];
                            closeMenu();
                        } else if (event.key === 'Escape') {
                            closeMenu();
                        }
                    });

                    document.addEventListener('mousedown', (event) => {
                        if (!combobox.contains(event.target)) {
                            closeMenu();
                        }
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>
