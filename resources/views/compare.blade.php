<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Perbandingan Mutasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800 p-6">
    <h1 class="text-2xl font-bold mb-4">Perbandingan Mutasi Centrall & Bank</h1>

    <div class="grid grid-cols-2 gap-6">
        <div>
            <h2 class="text-xl font-semibold mb-2">Mutasi Centrall</h2>
            <table class="w-full bg-white shadow rounded">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="p-2 text-left">Tanggal</th>
                        <th class="p-2 text-left">Nominal</th>
                        <th class="p-2 text-left">Pemilik</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($centralls as $item)
                    <tr class="border-b">
                        <td class="p-2">{{ $item->date }}</td>
                        <td class="p-2">Rp {{ number_format($item->amount, 2, ',', '.') }}</td>
                        <td class="p-2">{{ $item->account_holder }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div>
            <h2 class="text-xl font-semibold mb-2">Mutasi Bank</h2>
            <table class="w-full bg-white shadow rounded">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="p-2 text-left">Tanggal</th>
                        <th class="p-2 text-left">Nominal</th>
                        <th class="p-2 text-left">Pemilik</th>
                        <th class="p-2 text-left">Tipe</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($banks as $item)
                    <tr class="border-b">
                        <td class="p-2">{{ $item->date }}</td>
                        <td class="p-2">Rp {{ number_format($item->amount, 2, ',', '.') }}</td>
                        <td class="p-2">{{ $item->account_holder }}</td>
                        <td class="p-2 uppercase">{{ $item->type }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-10">
        <h2 class="text-xl font-semibold mb-2">Mutasi yang Cocok (Match)</h2>
        @if (count($matches))
        <table class="w-full bg-white shadow rounded">
            <thead class="bg-green-100">
                <tr>
                    <th class="p-2 text-left">Tanggal</th>
                    <th class="p-2 text-left">Nominal</th>
                    <th class="p-2 text-left">Pemilik</th>
                    <th class="p-2 text-left">Bank Type</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($matches as $match)
                <tr class="border-b">
                    <td class="p-2">{{ $match['centrall']->date }}</td>
                    <td class="p-2">Rp {{ number_format($match['centrall']->amount, 2, ',', '.') }}</td>
                    <td class="p-2">{{ $match['centrall']->account_holder }}</td>
                    <td class="p-2 uppercase">{{ $match['bank']->type }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p class="text-red-600">Tidak ada mutasi yang cocok ditemukan.</p>
        @endif
    </div>
</body>
</html>
