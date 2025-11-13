<x-app-layout>
    <div class="flex h-screen bg-slate-50">
        @include('components.sidebar-component')
        <div class="flex-1 p-6 overflow-y-auto">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-semibold text-slate-900">Letters Reports</h1>
                <button id="openLetterModal"
                    class="px-4 py-2 text-white transition-colors bg-blue-500 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                    Add New Letters
                </button>
            </div>

            <div class="p-4 bg-white rounded-lg shadow-md">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                No. Surat
                            </th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                Mitra</th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                Jenis Rekap</th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                mter</th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                Periode</th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                Content</th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                TTD</th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($letters as $letter)
                            <tr class="transition-colors hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">{{ $letter->latter_numbers }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ ucwords(strtolower($letter->cover->client->name)) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ ucwords(strtolower($letter->cover->jenis_rekap)) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $letter->latter_matters }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $letter->period }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $letter->report_content }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $letter->signature }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex space-x-2">
                                        <button data-letter-id="{{ $letter->id }}"
                                            class="text-blue-600 edit-letter-btn hover:text-blue-900 focus:outline-none focus:ring-1 focus:ring-blue-500">Edit</button>
                                        <button data-letter-id="{{ $letter->id }}"
                                            class="text-red-600 delete-letter-btn hover:text-red-900 focus:outline-none focus:ring-1 focus:ring-red-500">Delete</button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                    No letters found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if ($letters->hasPages())
                    <div class="flex justify-center mt-4">
                        {{ $letters->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
