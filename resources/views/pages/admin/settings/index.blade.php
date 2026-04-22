<x-app-layout title="Settings" subtitle="Settings / Pengaturan">
    <div class="admin-shell flex min-h-screen bg-slate-50">
        @include('components.sidebar-component')
        <div class="admin-content flex-1 p-6 overflow-y-auto">
            <div class="card bg-white admin-panel max-w-3xl">
                <div class="card-body">
                    <div class="text-xl font-semibold">Pengaturan Admin</div>
                    <p class="text-sm text-slate-500">Ubah tema tampilan admin menjadi Light atau Dark.</p>

                    <div class="admin-filter-card mt-5 border border-slate-100 rounded-md p-5">
                        <fieldset class="fieldset">
                            <legend class="fieldset-legend text-[15px] required">Theme</legend>
                            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                                <label class="flex items-center gap-x-2 rounded-lg border border-slate-200 p-3">
                                    <input type="radio" class="radio radio-md bg-blue-100 border-blue-300 checked:bg-blue-200 checked:text-blue-600 checked:border-blue-600"
                                        name="theme" value="light" {{ $currentTheme === 'light' ? 'checked' : '' }} />
                                    <span class="font-semibold text-base">Light</span>
                                </label>
                                <label class="flex items-center gap-x-2 rounded-lg border border-slate-200 p-3">
                                    <input type="radio" class="radio radio-md bg-blue-100 border-blue-300 checked:bg-blue-200 checked:text-blue-600 checked:border-blue-600"
                                        name="theme" value="dark" {{ $currentTheme === 'dark' ? 'checked' : '' }} />
                                    <span class="font-semibold text-base">Dark</span>
                                </label>
                            </div>
                        </fieldset>
                    </div>

                    <div class="mt-5">
                        <button id="saveSetting" class="btn btn-primary text-white">
                            Save Settings
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.getElementById('saveSetting').addEventListener('click', function(e) {
                e.preventDefault();

                const theme = document.querySelector('input[name="theme"]:checked')?.value ?? 'light';

                fetch("{{ route('admin.set.settings') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            theme: theme,
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === true) {
                            Notify("Setting berhasil disimpan!", null, null, "success");
                            setTimeout(() => window.location.reload(), 450);
                        } else {
                            Notify("Gagal menyimpan setting!", null, null, "danger");
                        }
                    })
                    .catch(error => {
                        console.error(error);
                        Notify("Terjadi kesalahan server!", null, null, "danger");
                    });
            });
        </script>
    @endpush
</x-app-layout>
