<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Trasactions') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white">
                <table class="table-auto w-full">
                    <thead>
                        <tr>
                            <th class="border px-6 py-4">ID</th>
                            <th class="border px-6 py-4">Book</th>
                            <th class="border px-6 py-4">User</th>
                            <th class="border px-6 py-4">Quantity</th>
                            <th class="border px-6 py-4">Total</th>
                            <th class="border px-6 py-4">Status</th>
                            <th class="border px-6 py-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $item)
                            <tr>
                                <th class="border px-6 py-4">{{ $item->id }}</th>
                                <th class="border px-6 py-4">{{ $item->books->title }}</th>
                                <th class="border px-6 py-4">{{ $item->user->name }}</th>
                                <th class="border px-6 py-4">{{ $item->quantity }}</th>
                                <th class="border px-6 py-4">{{ $item->total }}</th>
                                <th class="border px-6 py-4">{{ $item->status }}</th>
                                <th class="border px-6 py-4 text-center">
                                    <a href="{{ route('transactions.show', $item->id) }}"
                                        class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 mx-2 rounded">
                                        View
                                    </a>
                                    <form action="{{ route('transactions.destroy', $item->id) }}" method="POST"
                                        class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 mx-2 rounded">Delete</button>
                                    </form>
                                </th>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="border text-center p-5">Data tidak ditemukan</td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>
            <div class="text-center mt-5">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
