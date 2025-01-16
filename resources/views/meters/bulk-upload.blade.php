<x-guest-layout>
    <div class="container mx-auto py-8">
        <div class="bg-white shadow rounded p-6">
            <h3 class="text-xl mb-4 text-gray-800">Upload Meter Readings CSV</h3>

            @if(session('success'))
                <div class="bg-green-500 text-white p-4 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('meters.readings.upload-csv') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-4">
                    <label for="csv_file" class="text-gray-700">CSV File</label>
                    <input type="file" id="csv_file" name="csv_file" class="mt-2 p-2 border rounded @error('csv_file') border-red-500 @enderror" />
                    @error('csv_file')
                        <div class="text-red-500 text-sm">{{ $message }}</div>
                    @enderror
                </div>

                <div class="grid justify-items-end">
                    <button type="submit" class="bg-blue-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-600">
                        Upload CSV
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
