@extends('layouts.main')

@section('title', 'Dashboard')

@section('content')
    <div class="p-6">
        <div class="relative bg-white overflow-x-auto p-12 rounded-xl shadow">
            <div class="flex justify-center mb-7">
                <h2 class="text-3xl font-semibold uppercase">Dashboard Mutasi SM</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($mutationCounts as $userId => $data)
                    <div class="bg-blue-100 p-6 rounded-lg shadow-md hover:bg-blue-200 transition-all">
                        <h3 class="text-xl font-semibold text-gray-700 mb-2">User ID: {{ $userId }}</h3>
                        <p class="text-gray-600">Jumlah Mutasi:</p>
                        <p class="text-3xl font-bold text-blue-800">{{ $data->total }}</p>
                    </div>
                @endforeach
            </div>

            @if($mutationCounts->isEmpty())
                <div class="text-center text-gray-500 mt-8">
                    Belum ada data mutasi.
                </div>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
@endsection
