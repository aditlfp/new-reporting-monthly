<footer
    class="{{ Auth::check() && Auth::user()->role_id == 2 ? 'admin-footer fixed bottom-0 left-0 right-0 z-30' : '' }}">
    <div class="bg-base-100 w-full p-2 shadow-sm border border-gray-200 text-[12px]">
        <div class="flex w-full items-center justify-between">
            <!-- Center section -->
            <div class="flex-1 flex justify-center items-center gap-x-2">
                <span>Develop & Design By @aditlfp & @syafi-M</span>
            </div>

        </div>
    </div>
</footer>
