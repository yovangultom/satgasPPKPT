@props(['items', 'emptyMessage'])

<table class="w-full text-sm text-left text-gray-600">
    <thead class="text-xs font-semibold text-gray-500 uppercase bg-gray-50 tracking-wider">
        <tr>
            <th scope="col" class="p-4">No</th>
            <th scope="col" class="p-4">Nomor Pengaduan</th>
            <th scope="col" class="p-4">Jenis Kejadian</th>
            <th scope="col" class="p-4">Tanggal Pelaporan</th>
            <th scope="col" class="p-4">Status</th>
            <th scope="col" class="p-4">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($items as $pengaduan)
            <tr class="bg-white border-b hover:bg-gray-50">
                <td class="p-4 font-medium text-gray-900">{{ $loop->iteration }}</td>
                <td class="p-4">{{ $pengaduan->nomor_pengaduan }}</td>
                <td class="p-4">{{ $pengaduan->jenis_kejadian }}</td>
                <td class="p-4">{{ \Carbon\Carbon::parse($pengaduan->tanggal_pelaporan)->format('d M Y') }}</td>
                <td class="p-4">
                    {{-- Badge Status Dinamis --}}
                    <span @class([
                        'px-2 py-1 text-xs font-semibold rounded-full',
                        'bg-yellow-100 text-yellow-800' =>
                            $pengaduan->status_pengaduan === 'Belum Dikerjakan',
                        'bg-blue-100 text-blue-800' =>
                            $pengaduan->status_pengaduan === 'Sedang Dikerjakan',
                        'bg-green-100 text-green-800' =>
                            $pengaduan->status_pengaduan === 'Selesai Dikerjakan',
                    ])>
                        {{ $pengaduan->status_pengaduan }}
                    </span>
                </td>
                <td class="p-4">
                    <a href="{{ route('pengaduan.show', $pengaduan->id) }}"
                        class="font-medium text-blue-600 hover:underline">
                        Lihat Detail
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="p-4 text-center text-gray-500">{{ $emptyMessage }}</td>
            </tr>
        @endforelse
    </tbody>
</table>
