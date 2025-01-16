<x-guest-layout>
    <div class="container mx-auto py-8">
        <div class="bg-white shadow rounded p-6">
            <h3 class="text-xl mb-4 text-gray-800">All Meters</h3>

            @if(session('success'))
                <div class="bg-green-500 text-white p-4 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if($meters->isEmpty())
                <p class="text-gray-500">No meters found</p>
            @else
                <table class="min-w-full border-collapse border border-gray-300">
                    <thead>
                    <tr>
                        <th class="border border-gray-300 px-4 py-2 text-left">MPXN</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Type</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Installation Date</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Estimated Annual Consumption</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($meters as $meter)
                        <tr>
                            <td class="border border-gray-300 px-4 py-2">{{ $meter->mpxn }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ ucfirst($meter->type) }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $meter->installation_date->format('d-m-Y') }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $meter->estimated_annual_consumption }}kw</td>
                            <td class="border border-gray-300 px-4 py-2">
                                <a
                                    href="{{ route('meters.show', $meter) }}"
                                    class="text-blue-500 hover:underline"
                                >
                                    View
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
            <div class="mt-4 flex justify-end">
                <a href="{{ route('meters.readings.bulk-upload') }}" class="mr-4 bg-blue-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-600">
                    Bulk Upload
                </a>
                <a href="{{ route('meters.create') }}" class="bg-blue-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-600">
                    Add Meter
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>
