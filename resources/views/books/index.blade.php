<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Books') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-10">
                <a href="{{ route('books.create') }}"
                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    + CREATE BOOK
                </a>
            </div>
            <div class="bg-white">
                <table class="table-auto w-full">
                    <thead>
                        <tr>
                            <th class="border px-6 py-4">ID</th>
                            <th class="border px-6 py-4">Judul</th>
                            <th class="border px-6 py-4">Penulis</th>
                            <th class="border px-6 py-4">Genre</th>
                            <th class="border px-6 py-4">Harga</th>
                            <th class="border px-6 py-4">Stok</th>
                            <th class="border px-6 py-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($books as $item)
                            <tr>
                                <th class="border px-6 py-4">{{ $item->id }}</th>
                                <th class="border px-6 py-4">{{ $item->title }}</th>
                                <th class="border px-6 py-4">{{ $item->author }}</th>
                                <th class="border px-6 py-4">{{ $item->genre }}</th>
                                <th class="border px-6 py-4">Rp. {{ $item->price }}</th>
                                <th class="border px-6 py-4">{{ $item->quantity_in_stock }}</th>
                                <th class="border px-6 py-4 text-center">
                                    <a href="{{ route('books.edit', $item->id) }}"
                                        class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 mx-2 rounded">
                                        Edit
                                    </a>
                                    <form action="{{ route('books.destroy', $item->id) }}" method="POST"
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
                {{ $books->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
