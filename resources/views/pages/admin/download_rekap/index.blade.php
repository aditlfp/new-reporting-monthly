<x-app-layout title="Download Rekap" subtitle="Generate dan download rekap khusus admin">
    <div class="flex min-h-screen pb-10 admin-shell bg-slate-50">
        @include('components.sidebar-component')

        <div class="flex-1 min-w-0 p-2 overflow-y-auto sm:p-3 admin-content md:p-6">
            <div class="container px-3 py-6 mx-auto space-y-5 md:px-4 md:py-8">
                <section class="overflow-hidden bg-white border shadow-xl rounded-2xl border-slate-200 admin-panel">
                    <div class="grid gap-0 md:grid-cols-[1.4fr_1fr]">
                        <div class="px-5 py-6 border-b md:px-7 md:py-8 md:border-b-0 md:border-r border-slate-200">
                            <p class="text-xs font-semibold tracking-wider text-blue-600 uppercase">Admin Tools</p>
                            <h2 class="mt-2 text-2xl font-bold text-slate-900 md:text-3xl">Download Rekap</h2>
                            <p class="mt-3 text-sm leading-6 text-slate-600">
                                Pilih filter periode lalu generate file gabungan untuk cover, surat, upload tambahan, signature, dan rekap foto.
                            </p>
                        </div>
                        <div class="grid content-between gap-4 px-5 py-6 md:px-6 md:py-8 bg-slate-50">
                            <div class="p-3 border rounded-xl border-blue-200 bg-blue-50">
                                <p class="text-xs font-semibold tracking-wide text-blue-700 uppercase">Periode Aktif</p>
                                <p class="mt-1 text-lg font-bold text-blue-800">{{ $period_label }}</p>
                            </div>
                            <div class="p-3 border rounded-xl border-slate-200 bg-white">
                                <p class="text-xs font-semibold tracking-wide text-slate-500 uppercase">Total Cover</p>
                                <p class="mt-1 text-sm font-semibold text-slate-800">{{ $covers->total() }} data</p>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="p-4 bg-white shadow-xl rounded-2xl admin-panel md:p-5">
                    <form method="GET" action="{{ route('admin.download-rekap.index') }}" class="grid gap-3 md:grid-cols-5">
                        <div>
                            <label class="text-xs font-medium text-slate-600">Client</label>
                            <select name="client" class="w-full rounded-md select select-bordered select-sm">
                                <option value="">Semua Client</option>
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}" {{ (string) $selected_client === (string) $client->id ? 'selected' : '' }}>
                                        {{ $client->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-slate-600">Bulan</label>
                            <select name="month" class="w-full rounded-md select select-bordered select-sm" required>
                                @foreach (range(1, 12) as $month)
                                    <option value="{{ $month }}" {{ (int) $selected_month === $month ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::createFromDate(null, $month, 1)->locale('id')->translatedFormat('F') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-slate-600">Tahun</label>
                            <select name="year" class="w-full rounded-md select select-bordered select-sm" required>
                                @foreach (range(now()->year - 3, now()->year + 1) as $year)
                                    <option value="{{ $year }}" {{ (int) $selected_year === $year ? 'selected' : '' }}>{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="justify-center w-full text-white border-none btn btn-sm bg-blue-600 hover:bg-blue-700">
                                Terapkan Filter
                            </button>
                        </div>
                    </form>
                </section>

                <section class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
                    @forelse ($covers as $cover)
                        <article class="overflow-hidden bg-white border shadow rounded-2xl border-slate-200">
                            <div class="relative h-48 bg-slate-100">
                                @php
                                    $preview = $cover->img_src_1 ?: $cover->img_src_2;
                                @endphp
                                @if ($preview)
                                    <img src="{{ asset('storage/' . ltrim($preview, '/')) }}" alt="Cover {{ $cover->client?->name }}"
                                        class="object-cover w-full h-full">
                                @else
                                    <div class="flex items-center justify-center w-full h-full text-slate-400">No Cover Image</div>
                                @endif
                            </div>
                            <div class="p-4 space-y-3">
                                <div>
                                    <p class="text-xs font-semibold tracking-wide text-blue-600 uppercase">{{ $cover->jenis_rekap }}</p>
                                    <h3 class="text-base font-bold text-slate-900">{{ $cover->client?->name ?? '-' }}</h3>
                                </div>

                                @if ($cover->has_letter_for_period)
                                    <button type="button"
                                        class="w-full text-white border-none btn btn-sm bg-blue-600 hover:bg-blue-700 generate-rekap-btn"
                                        data-cover-id="{{ $cover->id }}"
                                        data-letter-id="{{ $cover->latest_letter_id ?? '' }}"
                                        data-month="{{ $selected_month }}"
                                        data-year="{{ $selected_year }}">
                                        Generate & Download
                                    </button>
                                @else
                                    <button type="button" class="w-full btn btn-sm btn-disabled" disabled>
                                        Surat periode ini belum tersedia
                                    </button>
                                @endif
                            </div>
                        </article>
                    @empty
                        <div class="p-8 text-center bg-white border md:col-span-2 xl:col-span-3 rounded-2xl border-slate-200 text-slate-500">
                            Data cover tidak ditemukan untuk filter saat ini.
                        </div>
                    @endforelse
                </section>

                <div class="pt-2">
                    {{ $covers->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://unpkg.com/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/html2canvas-pro@1.5.13/dist/html2canvas-pro.min.js"></script>
        <script src="{{ asset('js/coverPages.js') }}"></script>
        <script src="{{ asset('js/letterPages.js') }}"></script>
        <script>
            (() => {
                const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                const assetUrl = @json(asset('storage'));
                let isGenerating = false;

                const loadImage = (src) => new Promise((resolve) => {
                    if (!src) {
                        resolve();
                        return;
                    }

                    const img = new Image();
                    img.crossOrigin = 'anonymous';
                    img.onload = () => resolve();
                    img.onerror = () => resolve();
                    img.src = src;
                });

                const warmupTemplateAssets = async (letterData) => {
                    const sources = [
                        `${window.location.origin}/img/COVER.svg`,
                        `${window.location.origin}/img/header.png`,
                        `${window.location.origin}/img/stampel.png`,
                        `${window.location.origin}/img/ttdParno.png`,
                    ];

                    if (letterData?.cover?.img_src_1) {
                        sources.push(`${assetUrl}/${String(letterData.cover.img_src_1).replace(/^\/+/, '')}`);
                    }

                    if (letterData?.cover?.img_src_2) {
                        sources.push(`${assetUrl}/${String(letterData.cover.img_src_2).replace(/^\/+/, '')}`);
                    }

                    await Promise.all(sources.map(loadImage));
                };

                const createRenderContainer = (html, widthMm, fontFamily) => {
                    const container = document.createElement('div');
                    container.style.position = 'absolute';
                    container.style.left = '-9999px';
                    container.style.top = '0';
                    container.style.width = `${widthMm}mm`;
                    container.style.backgroundColor = 'white';
                    container.style.padding = '0';
                    container.style.margin = '0';
                    container.style.boxSizing = 'border-box';
                    container.style.overflow = 'hidden';
                    container.style.fontFamily = fontFamily;
                    container.innerHTML = html;
                    document.body.appendChild(container);

                    return container;
                };

                const removeRenderContainers = (...containers) => {
                    containers.forEach((container) => {
                        if (container && container.parentNode) {
                            container.parentNode.removeChild(container);
                        }
                    });
                };

                const buildPdfBlob = async (letterData) => {
                    await warmupTemplateAssets(letterData);

                    const coverDiv = createRenderContainer(getCoverPageHtml(letterData, assetUrl), 210, 'Arial, sans-serif');
                    const letterDiv = createRenderContainer(getLetterPageHtml(letterData, assetUrl), 210, '"Times New Roman", serif');

                    try {
                        const jsPDFConstructor =
                            window.jspdf?.jsPDF ||
                            window.jspdf?.default ||
                            window.jsPDF ||
                            (typeof jspdf !== 'undefined' ? jspdf.jsPDF : null);

                        if (!jsPDFConstructor) {
                            throw new Error('jsPDF tidak berhasil dimuat.');
                        }

                        const pdf = new jsPDFConstructor({
                            orientation: 'portrait',
                            unit: 'mm',
                            format: 'a4',
                        });

                        const coverCanvas = await html2canvas(coverDiv, {
                            scale: 2,
                            useCORS: true,
                            allowTaint: true,
                            logging: false,
                            width: coverDiv.offsetWidth,
                            height: coverDiv.offsetHeight,
                            windowWidth: coverDiv.scrollWidth,
                            windowHeight: coverDiv.scrollHeight,
                            backgroundColor: '#ffffff',
                        });

                        const coverImg = coverCanvas.toDataURL('image/jpeg', 0.9);
                        pdf.addImage(coverImg, 'JPEG', 0, 0, 210, 297);
                        pdf.addPage();

                        const letterCanvas = await html2canvas(letterDiv, {
                            scale: 2,
                            useCORS: true,
                            allowTaint: true,
                            logging: false,
                            width: letterDiv.offsetWidth,
                            height: letterDiv.offsetHeight,
                            windowWidth: letterDiv.scrollWidth,
                            windowHeight: letterDiv.scrollHeight,
                            backgroundColor: '#ffffff',
                        });

                        const letterImg = letterCanvas.toDataURL('image/jpeg', 0.9);
                        pdf.addImage(letterImg, 'JPEG', 0, 0, 210, 297);

                        return pdf.output('blob');
                    } finally {
                        removeRenderContainers(coverDiv, letterDiv);
                    }
                };

                const fetchLetterData = async (letterId) => {
                    const response = await fetch(`/admin/admin-latters/${letterId}`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });

                    const result = await response.json();
                    if (!response.ok || !result?.status || !result?.data) {
                        throw new Error(result?.message || 'Data surat gagal dimuat.');
                    }

                    return result.data;
                };

                const sendGeneratedPdf = async ({
                    coverId,
                    month,
                    year,
                    pdfBlob
                }) => {
                    const formData = new FormData();
                    formData.append('cover_id', String(coverId));
                    formData.append('month', String(month));
                    formData.append('year', String(year));
                    formData.append('pdf', pdfBlob, `download-rekap-${coverId}-${year}-${month}.pdf`);

                    const response = await fetch(@json(route('admin.download-rekap.generate')), {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrf,
                            'Accept': 'application/json',
                        },
                        body: formData,
                    });

                    const result = await response.json();
                    if (!response.ok || !result.success) {
                        throw new Error(result.message || 'Gagal membuat file rekap.');
                    }

                    return result;
                };

                document.querySelectorAll('.generate-rekap-btn').forEach((button) => {
                    button.addEventListener('click', async () => {
                        if (isGenerating) {
                            return;
                        }

                        const coverId = button.dataset.coverId;
                        const letterId = button.dataset.letterId;
                        const month = button.dataset.month;
                        const year = button.dataset.year;
                        const originalLabel = button.textContent;

                        isGenerating = true;
                        button.disabled = true;
                        button.textContent = 'Generating...';

                        try {
                            const letterData = await fetchLetterData(letterId);
                            const pdfBlob = await buildPdfBlob(letterData);
                            const result = await sendGeneratedPdf({
                                coverId: Number(coverId),
                                month: Number(month),
                                year: Number(year),
                                pdfBlob,
                            });

                            if (window.Notify) {
                                window.Notify(result.message || 'Rekap berhasil dibuat.', null, null, 'success');
                            }

                            window.location.href = result.url;
                        } catch (error) {
                            if (window.Notify) {
                                window.Notify(error.message || 'Gagal membuat file rekap.', null, null, 'error');
                            } else {
                                alert(error.message || 'Gagal membuat file rekap.');
                            }
                        } finally {
                            isGenerating = false;
                            button.disabled = false;
                            button.textContent = originalLabel;
                        }
                    });
                });
            })();
        </script>
    @endpush
</x-app-layout>
