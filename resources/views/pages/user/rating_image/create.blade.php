<x-guest-layout>
    <div class="relative min-h-screen overflow-hidden bg-gradient-to-br from-indigo-50 via-white to-purple-50">
        <div class="absolute -top-20 -left-16 h-72 w-72 rounded-full bg-indigo-200/40 blur-3xl"></div>
        <div class="absolute -bottom-20 -right-10 h-72 w-72 rounded-full bg-purple-200/40 blur-3xl"></div>

        <div class="relative z-10 mx-auto flex min-h-screen w-full max-w-7xl items-center justify-center px-4 py-6 md:px-6 md:py-10">
            <div class="grid w-full max-w-6xl grid-cols-1 overflow-hidden rounded-3xl border border-white/60 bg-white/80 shadow-2xl backdrop-blur lg:grid-cols-12">
                <div class="flex flex-col justify-between bg-gradient-to-br from-indigo-600 to-indigo-800 p-7 text-white md:p-10 lg:col-span-5">
                    <div>
                        <p class="mb-4 inline-flex items-center gap-2 rounded-full bg-white/15 px-3 py-1 text-xs font-medium">
                            <i class="ri-customer-service-2-line"></i>
                            Feedback Pelanggan
                        </p>
                        <h1 class="text-3xl font-bold leading-tight md:text-4xl">
                            Bantu kami jadi lebih baik
                        </h1>
                        <p class="mt-4 text-sm text-indigo-100 md:text-base">
                            Isi form singkat ini untuk memberi penilaian layanan kami.
                        </p>
                    </div>

                    <div class="mt-6 grid gap-3 text-sm md:mt-8">
                        <div class="flex items-start gap-3 rounded-xl bg-white/10 p-3">
                            <i class="ri-shield-check-line mt-0.5 text-lg"></i>
                            <p>Data Anda aman dan hanya dipakai untuk evaluasi layanan.</p>
                        </div>
                        <div class="flex items-start gap-3 rounded-xl bg-white/10 p-3">
                            <i class="ri-time-line mt-0.5 text-lg"></i>
                            <p>Isi form ini cuma butuh sekitar 1 menit.</p>
                        </div>
                    </div>
                </div>

                <div class="p-5 md:p-8 lg:col-span-7 lg:p-10">
                    <div class="mb-5 md:mb-6">
                        <h2 class="text-2xl font-bold leading-tight text-slate-900 md:text-3xl">Form Ulasan Pekerjaan</h2>
                        <p class="mt-1 inline-flex rounded-full bg-indigo-50 px-3 py-1 text-xs font-medium text-indigo-700 md:text-sm">
                            Area {{ $nValue ?? '-' }}
                        </p>
                        <p class="mt-1 text-sm text-slate-500">Terima kasih telah meluangkan waktu untuk memberi penilaian.</p>
                    </div>

                    @if (session('success'))
                        <div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                            <ul class="list-inside list-disc space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-5 rounded-2xl border border-slate-200 bg-slate-50/70 p-4 md:p-5">
                        <div class="mb-3 flex items-center justify-between">
                            <h3 class="text-sm font-semibold text-slate-800">Preview Gambar Terkait</h3>
                            <span class="text-xs text-slate-500">Area {{ $nValue ?? '-' }}</span>
                        </div>
                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                            <div>
                                <p class="mb-1 text-xs font-medium text-slate-600">Before</p>
                                <img
                                    src="{{ $uploadPreview?->img_before ? asset('storage/' . $uploadPreview->img_before) : 'https://placehold.co/600x400?text=No+Image' }}"
                                    class="aspect-[4/3] w-full rounded-lg border border-slate-200 object-cover"
                                    alt="Before image preview"
                                    onerror="this.onerror=null;this.src='https://placehold.co/600x400?text=No+Image';">
                            </div>
                            <div>
                                <p class="mb-1 text-xs font-medium text-slate-600">Progress</p>
                                <img
                                    src="{{ $uploadPreview?->img_proccess ? asset('storage/' . $uploadPreview->img_proccess) : 'https://placehold.co/600x400?text=No+Image' }}"
                                    class="aspect-[4/3] w-full rounded-lg border border-slate-200 object-cover"
                                    alt="Progress image preview"
                                    onerror="this.onerror=null;this.src='https://placehold.co/600x400?text=No+Image';">
                            </div>
                            <div>
                                <p class="mb-1 text-xs font-medium text-slate-600">After</p>
                                <img
                                    src="{{ $uploadPreview?->img_final ? asset('storage/' . $uploadPreview->img_final) : 'https://placehold.co/600x400?text=No+Image' }}"
                                    class="aspect-[4/3] w-full rounded-lg border border-slate-200 object-cover"
                                    alt="After image preview"
                                    onerror="this.onerror=null;this.src='https://placehold.co/600x400?text=No+Image';">
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('rating-pekerjaan.store') }}" method="POST" class="space-y-5 rounded-2xl border border-slate-200 bg-white p-4 md:p-5">
                        @csrf

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div class="form-control">
                                <label for="name" class="mb-2 block text-sm font-medium text-slate-700">Nama</label>
                                <input id="name" name="name" type="text" value="{{ old('name') }}"
                                    placeholder="Contoh: Budi Santoso"
                                    required
                                    class="input input-bordered w-full rounded-xl border-slate-300 bg-white focus:border-indigo-500 focus:outline-none" />
                            </div>

                            <div class="form-control">
                                <label for="email" class="mb-2 block text-sm font-medium text-slate-700">Email</label>
                                <input id="email" name="email" type="email" value="{{ old('email') }}"
                                    placeholder="nama@email.com"
                                    class="input input-bordered w-full rounded-xl border-slate-300 bg-white focus:border-indigo-500 focus:outline-none @error('email') input-error @enderror"
                                    required />
                            </div>
                        </div>

                        <div class="form-control">
                            <label class="mb-2 block text-sm font-medium text-slate-700">Rating Pelayanan</label>
                            <div class="rating rating-lg gap-1">
                                @for ($i = 1; $i <= 5; $i++)
                                    <input type="radio" name="rate" value="{{ $i }}" aria-label="{{ $i }} star"
                                        class="mask mask-star-2 bg-amber-400"
                                        {{ (int) old('rate', 0) === $i ? 'checked' : '' }} />
                                @endfor
                            </div>
                            <p class="mt-1 text-xs text-slate-500">Pilih bintang dari 1 sampai 5.</p>
                        </div>

                        <div class="form-control">
                            <label for="comment" class="mb-2 block text-sm font-medium text-slate-700">Komentar</label>
                            <textarea id="comment" name="comment" rows="4" placeholder="Tulis pengalaman Anda..." required
                                class="textarea textarea-bordered w-full rounded-xl border-slate-300 bg-white focus:border-indigo-500 focus:outline-none">{{ old('comment') }}</textarea>
                        </div>

                        <input type="hidden" name="n" value="{{ $nValue }}">

                        <button type="submit"
                            class="btn h-12 w-full rounded-xl border-none bg-indigo-600 text-base font-semibold text-white hover:bg-indigo-700">
                            <i class="ri-send-plane-line"></i>
                            Kirim Ulasan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
