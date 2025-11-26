<x-app-layout title="Dashboard" subtitle="Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod">
<div class="flex min-h-screen bg-slate-50">
  @include('components.sidebar-component')

  <!-- Main Content -->
  <main class="flex-1 overflow-hidden">

    <!-- Dashboard Content -->
    <div class="p-8">

      <!-- Stats Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-xl p-6 border border-slate-200">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-slate-500">Total Data Rekab</p>
              <p class="text-3xl font-bold text-slate-900 mt-2">
                {{ number_format($totalThisMonth) }}</p>
              <p class="text-sm text-emerald-600 mt-2
                {{ $growthDirection === 'up' ? 'text-emerald-600' : 'text-red-600' }}">
    
                {{ $growthDirection === 'up' ? '↑' : '↓' }}
                {{ number_format($growthAbs, 1) }}% from last month
              </p>
            </div>
            <div class="w-12 h-12 bg-slate-100 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
              </svg>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-xl p-6 border border-slate-200">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-slate-500">Active Sessions</p>

              <p class="text-3xl font-bold text-slate-900 mt-2">
                {{ number_format($current) }}
              </p>

              @if($isUp)
                <p class="text-sm text-green-600 mt-2">
                  ↑ {{ $percentage }}% from last month
                </p>
              @else
                <p class="text-sm text-red-600 mt-2">
                  ↓ {{ abs($percentage) }}% from last month
                </p>
              @endif
            </div>

            <div class="w-12 h-12 bg-slate-100 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0
                     117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0
                     11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
              </svg>
            </div>
          </div>
        </div>

      <!-- Recent Activity -->
      <div class="bg-white rounded-xl border border-slate-200 overflow-y-auto">
          <div class="px-6 py-5 border-b border-slate-200">
              <h2 class="text-lg font-semibold text-slate-900">Recent Activity</h2>
          </div>

          <div class="divide-y divide-slate-200">
              @forelse ($activities as $activity)
                  <div class="px-6 py-4 hover:bg-slate-50 transition-colors">
                      <div class="flex items-center justify-between">
                          <div class="flex items-center space-x-4">
                             <div class="w-10 h-10 rounded-full flex items-center justify-center
                                  {{
                                      $activity->type == 'upload'
                                        ? 'bg-emerald-100 text-emerald-600'
                                        : (
                                            $activity->type == 'update'
                                                ? 'bg-amber-100 text-amber-600'
                                                : (
                                                    $activity->type == 'delete'
                                                        ? 'bg-red-100 text-red-600'
                                                        : ''
                                                )
                                        )
                                  }}
                              ">
                                @if($activity->type == 'upload')
                                  <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M14.8287 7.75737L9.1718 13.4142C8.78127 13.8047 8.78127 14.4379 9.1718 14.8284C9.56232 15.219 10.1955 15.219 10.586 14.8284L16.2429 9.17158C17.4144 8.00001 17.4144 6.10052 16.2429 4.92894C15.0713 3.75737 13.1718 3.75737 12.0002 4.92894L6.34337 10.5858C4.39075 12.5384 4.39075 15.7042 6.34337 17.6569C8.29599 19.6095 11.4618 19.6095 13.4144 17.6569L19.0713 12L20.4855 13.4142L14.8287 19.0711C12.095 21.8047 7.66283 21.8047 4.92916 19.0711C2.19549 16.3374 2.19549 11.9053 4.92916 9.17158L10.586 3.51473C12.5386 1.56211 15.7045 1.56211 17.6571 3.51473C19.6097 5.46735 19.6097 8.63317 17.6571 10.5858L12.0002 16.2427C10.8287 17.4142 8.92916 17.4142 7.75759 16.2427C6.58601 15.0711 6.58601 13.1716 7.75759 12L13.4144 6.34316L14.8287 7.75737Z"></path></svg>
                                @elseif($activity->type == 'update')
                                  <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M19.0375 6.37823C19.2671 6.14869 19.5939 6.04506 19.9138 6.09994C20.2337 6.15486 20.5066 6.36161 20.6465 6.65444C21.8888 9.25501 21.4352 12.4656 19.2792 14.6218C17.4463 16.4548 14.851 17.0568 12.5175 16.4338L8.67263 20.2787C7.30579 21.6455 5.08972 21.6455 3.72288 20.2787C2.35605 18.9118 2.35605 16.6958 3.72288 15.3289L7.56777 11.484C6.94478 9.15056 7.54679 6.55527 9.37973 4.72233C11.536 2.56631 14.7465 2.11277 17.3471 3.35507C17.6399 3.49498 17.8467 3.76787 17.9016 4.08773C17.9565 4.40767 17.8529 4.73448 17.6233 4.96402L15.0366 7.55076C14.6461 7.94128 14.6461 8.57444 15.0366 8.96497C15.4271 9.35549 16.0603 9.35549 16.4508 8.96497L19.0375 6.37823ZM17.865 10.3792C16.6934 11.5508 14.7939 11.5508 13.6224 10.3792C12.4508 9.20761 12.4508 7.30811 13.6224 6.13654L15.0373 4.72164C13.5328 4.50775 11.9501 4.98056 10.7939 6.13654C9.36815 7.56234 8.98139 9.63694 9.64076 11.415C9.7766 11.7813 9.68699 12.1931 9.41081 12.4694L5.13709 16.7431C4.55131 17.3289 4.55131 18.2787 5.13709 18.8645C5.72288 19.4503 6.67263 19.4503 7.25841 18.8645L11.5321 14.5907C11.8085 14.3146 12.2202 14.225 12.5866 14.3608C14.3646 15.0202 16.4392 14.6334 17.865 13.2076C19.021 12.0515 19.4938 10.4688 19.2799 8.96428L17.865 10.3792Z"></path></svg>
                                @elseif($activity->type == 'delete')
                                  <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M4 8H20V21C20 21.5523 19.5523 22 19 22H5C4.44772 22 4 21.5523 4 21V8ZM6 10V20H18V10H6ZM9 12H11V18H9V12ZM13 12H15V18H13V12ZM7 5V3C7 2.44772 7.44772 2 8 2H16C16.5523 2 17 2.44772 17 3V5H22V7H2V5H7ZM9 4V5H15V4H9Z"></path></svg>
                                @endif
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


      <!-- Percentage -->
      <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-200">
          <h2 class="text-lg font-semibold text-slate-900">Percentage Activity Monthly</h2>
        </div>
        @foreach ($result as $item)
        <div class="p-4 rounded-lg my-3">
            <p class="text-sm text-slate-500">{{ $item['client'] }} — {{ $item['jabatan'] }}</p>

            @if($item['percentage'] <= 50)
              <progress class="progress progress-error w-full" value="{{ $item['percentage'] }}" max="100"></progress>
            @elseif($item['percentage'] >= 51 && $item['percentage'] <=85)
              <progress class="progress progress-warning w-full" value="{{ $item['percentage'] }}" max="100"></progress>
            @elseif($item['percentage'] >= 86)
              <progress class="progress progress-success w-full" value="{{ $item['percentage'] }}" max="100"></progress>
            @endif
                {{ $item['percentage'] }}%
                {{ $item['percentage'] >= 100 ? '✓ Tercapai' : '✕ Belum' }}
        </div>
        @endforeach

      </div>
    </div>
  </main>
</div>

</x-app-layout>