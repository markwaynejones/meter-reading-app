<x-guest-layout>
    <div class="container mx-auto py-8">
        <div class="bg-white shadow rounded p-6">

            <div class="mb-4">
                <a href="{{ route('meters.index') }}" class="text-blue-500 hover:underline text-sm">
                    &larr; Back to All Meters
                </a>
            </div>

            <h3 class="text-xl mb-4 text-gray-800">Add Meter</h3>

            @if(session('success'))
                <div class="bg-green-500 text-white p-4 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="/meters">
                @csrf

                <div class="mb-4 grid grid-cols-2 gap-4 items-center">
                    <label for="mpxn">MPXN</label>
                    <input
                        id="mpxn"
                        type="text"
                        name="mpxn"
                        class="@error('mpxn') is-invalid @enderror"
                        value="{{ old('mpxn') }}"
                        required
                    />
                </div>

                @error('mpxn')
                    <p class="text-sm text-red-600 mt-5">{{ $message }}</p>
                @enderror

                <div class="mb-4 grid grid-cols-2 gap-4 items-center">
                    <label for="type">Meter Type</label>
                    <select
                        id="type"
                        name="type"
                        class="@error('meter_type') is-invalid @enderror"
                        required
                    >
                        <option value="gas" {{ old('type') == 'gas' ? 'selected' : '' }}>Gas</option>
                        <option value="electric" {{ old('type') == 'electric' ? 'selected' : '' }}>Electric</option>
                    </select>
                </div>

                @error('type')
                    <p class="text-sm text-red-600 mt-5">{{ $message }}</p>
                @enderror

                <div class="mb-4 grid grid-cols-2 gap-4 items-center">
                    <label for="installation_date">Installation Date</label>
                    <input
                        id="installation_date"
                        type="date"
                        name="installation_date"
                        class="@error('installation_date') is-invalid @enderror"
                        value="{{ old('installation_date') }}"
                        required
                    />
                </div>

                @error('installation_date')
                    <p class="text-sm text-red-600 mt-5">{{ $message }}</p>
                @enderror

                <div class="mb-4 grid grid-cols-2 gap-4 items-center">
                    <label for="estimated_annual_consumption">Estimated Annual Consumption</label>
                    <input
                        id="estimated_annual_consumption"
                        type="text"
                        name="estimated_annual_consumption"
                        class="@error('estimated_annual_consumption') is-invalid @enderror"
                        value="{{ old('estimated_annual_consumption') }}"
                        required
                    />
                </div>

                @error('estimated_annual_consumption')
                    <p class="text-sm text-red-600 mt-5">{{ $message }}</p>
                @enderror

                <div class="grid justify-items-end">
                    <button type="submit" class="bg-blue-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-600">
                        Submit
                    </button>
                </div>

            </form>

        </div>
    </div>
</x-guest-layout>
