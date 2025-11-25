<x-app-layout title="Settings" subtitle="Settings / Pengaturan">
	<div class="flex h-screen bg-slate-50">
	    @include('components.sidebar-component')
	    <div class="flex-1 p-6 overflow-y-auto">
		    <div class="card bg-white">
		    	<div class="card-body">
		    		<div class="font-semibold text-xl">All Settings</div>
		    		
		    		<div class="grid grid-cols-2 gap-x-16 border border-slate-100 rounded-md">
		    			<div class="form-control m-5 w-full">
			               	<fieldset class="fieldset">
							  <legend class="fieldset-legend text-[15px] required">API Key</legend>
							    <input
								  type="text"
								  class="input input-md validator w-full"
								  required
								  placeholder="QweSxuERYx128PA"
								  name="api_key"
								/>
								<p class="validator-hint">Must be Valid API Key</p>
							</fieldset>
						</div>

						<div class="form-control m-5 w-full">
							<fieldset class="fieldset">
							  <legend class="fieldset-legend text-[15px] required">Theme</legend>

							  <div class="flex gap-x-2">
							  	<div class="flex items-center gap-x-2">
							  		<input
									  type="radio"
									  class="radio radio-md bg-blue-100 border-blue-300 checked:bg-blue-200 checked:text-blue-600 checked:border-blue-600"
									  name="theme"
									  value="theme"
									  checked="true"
									/>
									<span class="font-semibold text-lg">Light</span>
							  	</div>
							  	<div class="flex items-center gap-x-2">
							  		<input
									  type="radio"
									  class="radio radio-md bg-blue-100 border-blue-300 checked:bg-blue-200 checked:text-blue-600 checked:border-blue-600"
									  name="theme"
									  value="theme"
									/>
									<span class="font-semibold text-lg">Dark</span>
							  	</div>
							  	<div class="flex items-center gap-x-2">
							  		<input
									  type="radio"
									  class="radio radio-md bg-blue-100 border-blue-300 checked:bg-blue-200 checked:text-blue-600 checked:border-blue-600"
									  name="theme"
									  value="theme"
									/>
									<span class="font-semibold text-lg">System Theme</span>
							  	</div>
							  </div>
							</fieldset>
						</div>

		    		</div>
		    		<div class="m-5">
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
		document.getElementById('saveSetting').addEventListener('click', function (e) {
		    e.preventDefault();

		    let api_key   = document.querySelector('input[name="api_key"]').value;
		    let theme     = document.querySelector('input[name="theme"]:checked').value;

		    fetch("{{ route('admin.set.settings') }}", {
		        method: "POST",
		        headers: {
		            "Content-Type": "application/json",
		            "X-CSRF-TOKEN": "{{ csrf_token() }}"
		        },
		        body: JSON.stringify({
		            api_key: api_key,
		            theme: theme,
		        })
		    })
		    .then(response => response.json())
		    .then(data => {
		        if (data.status === true) {
		            Notify("Setting berhasil disimpan!", null, null, "success");
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