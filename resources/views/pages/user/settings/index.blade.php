<x-app-layout>
    <div class="flex flex-col h-screen bg-white">
        <x-user-navbar />

        <div class="flex flex-1 overflow-hidden">
            <x-user-sidebar />

            <main class="flex-1 p-4 overflow-y-auto md:p-6">
                <div class="max-w-3xl mx-auto">
                    <div class="mb-8">
                        <h2 class="mb-1 text-2xl font-bold text-slate-900">Pengaturan</h2>
                        <p class="text-slate-500">Atur tema website dan splashscreen setelah login.</p>
                    </div>

                    <div class="p-6 bg-white border rounded-lg shadow-sm border-slate-100">
                        <form action="/save-settings" method="POST" class="space-y-8">
                            @csrf

                            <section>
                                <h3 class="text-base font-semibold text-slate-900">Tema Website</h3>
                                <p class="mt-1 text-sm text-slate-500">Pilih tema tampilan utama website.</p>

                                <div class="grid grid-cols-1 gap-3 mt-4 sm:grid-cols-2">
                                    <label class="flex items-center gap-3 p-3 border rounded-lg cursor-pointer border-slate-200">
                                        <input type="radio" name="theme_mode" value="light" class="radio radio-sm"
                                            {{ ($preferences['theme_mode'] ?? 'light') === 'light' ? 'checked' : '' }} required />
                                        <div>
                                            <p class="font-medium text-slate-900">Light</p>
                                            <p class="text-xs text-slate-500">Tampilan standar aplikasi.</p>
                                        </div>
                                    </label>

                                    <label class="flex items-center gap-3 p-3 border rounded-lg cursor-pointer border-slate-200">
                                        <input type="radio" name="theme_mode" value="dark" class="radio radio-sm"
                                            {{ ($preferences['theme_mode'] ?? 'light') === 'dark' ? 'checked' : '' }} required />
                                        <div>
                                            <p class="font-medium text-slate-900">Dark</p>
                                            <p class="text-xs text-slate-500">Tampilan gelap untuk fokus.</p>
                                        </div>
                                    </label>
                                </div>
                            </section>

                            <section>
                                <h3 class="text-base font-semibold text-slate-900">Splashscreen Setelah Login</h3>
                                <p class="mt-1 text-sm text-slate-500">Tampilkan splashscreen sekali saat login berhasil.</p>

                                <label class="flex items-center justify-between p-3 mt-4 border rounded-lg border-slate-200">
                                    <div>
                                        <p class="font-medium text-slate-900">Aktifkan Splashscreen Login</p>
                                        <p class="text-xs text-slate-500">Jika nonaktif, halaman langsung tampil tanpa splash.</p>
                                    </div>
                                    <input type="checkbox" name="splash_on_login" value="1" class="toggle toggle-primary"
                                        {{ !empty($preferences['splash_on_login']) ? 'checked' : '' }} />
                                </label>
                            </section>

                            <button type="submit"
                                class="btn btn-sm bg-blue-500/20 text-blue-500 hover:bg-blue-500 hover:text-white transition-all ease-in-out duration-150 rounded-sm border-none">
                                Simpan Pengaturan
                            </button>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>
</x-app-layout>
