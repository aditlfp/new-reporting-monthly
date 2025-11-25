@props(['activitis' => []])

<!-- Recent Activity -->
<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
  <div class="px-6 py-5 border-b border-slate-200">
      <h2 class="text-lg font-semibold text-slate-900">Recent Activity</h2>
  </div>

  <div class="divide-y divide-slate-200">
      @forelse ($activitis as $activity)
          <div class="px-6 py-4 hover:bg-slate-50 transition-colors">
              <div class="flex items-center justify-between">
                  <div class="flex items-center space-x-4">
                      <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center">
                          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-600" viewBox="0 0 24 24" fill="currentColor"><path d="M14.8287 7.75737L9.1718 13.4142C8.78127 13.8047 8.78127 14.4379 9.1718 14.8284C9.56232 15.219 10.1955 15.219 10.586 14.8284L16.2429 9.17158C17.4144 8.00001 17.4144 6.10052 16.2429 4.92894C15.0713 3.75737 13.1718 3.75737 12.0002 4.92894L6.34337 10.5858C4.39075 12.5384 4.39075 15.7042 6.34337 17.6569C8.29599 19.6095 11.4618 19.6095 13.4144 17.6569L19.0713 12L20.4855 13.4142L14.8287 19.0711C12.095 21.8047 7.66283 21.8047 4.92916 19.0711C2.19549 16.3374 2.19549 11.9053 4.92916 9.17158L10.586 3.51473C12.5386 1.56211 15.7045 1.56211 17.6571 3.51473C19.6097 5.46735 19.6097 8.63317 17.6571 10.5858L12.0002 16.2427C10.8287 17.4142 8.92916 17.4142 7.75759 16.2427C6.58601 15.0711 6.58601 13.1716 7.75759 12L13.4144 6.34316L14.8287 7.75737Z"></path></svg>
                      </div>

                      <div>
                          <p class="text-sm font-medium text-slate-900">
                              {{ $activity->title }}
                          </p>
                          <p class="text-xs text-slate-500 mt-1">
                              {{ $activity->description }}
                          </p>
                      </div>
                  </div>

                  <span class="text-xs text-slate-400">
                      {{ $activity->created_at->diffForHumans() }}
                  </span>
              </div>
          </div>
      @empty
          <div class="px-6 py-4 text-sm text-slate-400">
              No recent activity yet.
          </div>
      @endforelse
  </div>
</div>