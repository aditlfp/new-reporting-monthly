<x-app-layout>
	<div class="flex flex-col h-screen bg-white">
	    <!-- Top Navbar -->
	    <x-user-navbar />

	    <div class="flex flex-1 overflow-hidden">
	        {{-- sidebar --}}
	        <x-user-sidebar />

	        <!-- Main Content -->
	        <main class="flex-1 p-1 overflow-y-auto xs:p-2 sm:p-4 md:p-6">
	        	<div class="w-full max-w-6xl mx-auto">
	                <!-- Page Header -->
                    <div class="mb-8">
                        <h2 class="mb-1 text-2xl font-bold text-slate-900">Semua Pengaturan</h2>
                        <p class="text-slate-500">Atur Tema Sesuai Keinginan Anda</p>
                    </div>
	        	</div>

                <div class="p-4 bg-white border rounded-lg shadow-sm border-slate-100 sm:p-6">
                	<h2 class="mb-1 text-xl font-bold text-slate-900">Pengaturan Warna</h2>
				    <form action="/save-settings" id="settingsForm" method="POST">
				        @csrf
				        <div class="flex flex-col gap-2">
					        <div>
						        <input type="color" id="bg_color" name="bg_color" value="{{ $dataSetting->data_theme['bg_color'] }}" class="input input-xs">
						        <label for="bg_color">Warna Latar Belakang</label>
					        </div>
					        <div>
						        <input type="color" id="text_color_1" name="text_color_1" value="{{ $dataSetting->data_theme['text_color_1'] }}" class="input input-xs">
						        <label for="text_color_1">Warna Text 1</label>
					        </div>
					        <div>
						        <input type="color" id="text_color_2" name="text_color_2" value="{{ $dataSetting->data_theme['text_color_2'] }}" class="input input-xs">
						        <label for="text_color_2">Warna Text 2</label>
					        </div>
					        <div>
						        <input type="color" id="primary_color" name="primary_color" value="{{ $dataSetting->data_theme['primary_color'] }}" class="input input-xs">
						        <label for="primary_color">Warna Utama</label>
					        </div>
					        <div>
						        <input type="color" id="secondary_color" name="secondary_color" value="{{ $dataSetting->data_theme['secondary_color'] }}" class="input input-xs">
						        <label for="secondary_color">Warna Kedua</label>
					        </div>
					        <div>
						        <input type="color" id="error_color" name="error_color" value="{{ $dataSetting->data_theme['error_color'] }}" class="input input-xs">
						        <label for="error_color">Warna Lainnya</label>
					        </div>
					    </div>
				        <button class="btn btn-sm my-2 bg-blue-500/20 text-blue-500 hover:bg-blue-500 hover:text-white transition-all ease-in-out duration-150 rounded-sm border-none">Simpan</button>

				    </form>
				</div>
	       	</main>
	    </div>
	</div>
</x-app-layout>
