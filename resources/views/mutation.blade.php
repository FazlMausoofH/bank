@extends('layouts.main')

@section('title', 'Mutation')

@section('content')
    <div class="p-6">
        <div class="relative bg-white overflow-x-auto p-12">
            <div class="flex justify-center mb-7">
                <h2 class="text-3xl font-semibold uppercase">Rekap Mutasi SM</h2>
            </div>
            <div class="flex justify-between">
                <div class="">
                    <form action="{{ route('search-mutation') }}" method="get" class="flex items-center space-x-2">
                        <div>
                            <label for="start_date" class="sr-only">Start Date</label>
                            <input name="start" type="date" id="start_date"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5"
                                value="{{ request('start') }}">
                        </div>
                        <span class="text-gray-500">to</span>
                        <div>
                            <label for="end_date" class="sr-only">End Date</label>
                            <input name="end" type="date" id="end_date"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5"
                                value="{{ request('end') }}">
                        </div>
                        <button type="submit"
                                class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
                            Search
                        </button>
                    </form>
                </div>
                <div>
                    <!-- Modal toggle -->
                    <button data-modal-target="create-mutation" data-modal-toggle="create-mutation" class="block text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center" type="button">
                        Create Mutation
                    </button>
                    
                    <!-- Main modal -->
                    <div id="create-mutation" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed right-0 left-0 top-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                        <div class="relative p-4 w-full max-w-5xl max-h-full">
                            <!-- Modal content -->
                            <div class="relative bg-white rounded-lg shadow">
                                <!-- Modal header -->
                                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        Create New Mutation
                                    </h3>
                                </div>
                                <!-- Modal body -->
                                <form id="data-form" class="p-4 md:p-5" action="{{ route('create-mutation') }}" method="POST">
                                    @csrf

                                    <div id="items-container">
                                        <div class="grid grid-cols-5 gap-4 mb-2">
                                            <input name="items[0][faktur]" class="border p-2" placeholder="Faktur" required>
                                            <input name="items[0][date]" type="date" class="border p-2" required>
                                            <input name="items[0][amount]" type="text" class="border p-2 amount-input" placeholder="Nominal" required>

                                            {{-- Dropdown Type --}}
                                            <select name="items[0][type]" class="border p-2 type-select" data-index="0" required>
                                                <option value="">Pilih Tipe</option>
                                                <option value="bca">BCA</option>
                                                <option value="mandiri">Mandiri</option>
                                                <option value="debit">Debit</option>
                                            </select>

                                            {{-- Dropdown Account Holder --}}
                                            <select name="items[0][account_holder]" class="border p-2 account-holder-select" data-index="0" disabled required>
                                                <option value="">Pilih Pemilik</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="flex justify-end gap-3">
                                        <button type="button" onclick="addItem()" class="bg-blue-500 text-white px-4 py-2 rounded">+ Tambah Baris</button>
                                        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div> 
                </div>
            </div>
            <table class="w-full text-sm text-center rtl:text-right text-gray-500 mt-5">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        @foreach ($headers as $header)
                        <th scope="col" class="px-6 py-3 whitespace-nowrap {{ $header === 'tanggal' ? 'sticky bg-gray-100 -left-10 z-30' : '' }}">
                            {{ str_replace('_', ' ', $header) }}
                        </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($mutations as $mutation)
                    <tr class="bg-white border-b group hover:bg-gray-50">
                        <td class="px-6 py-4">
                            {{ ($mutations->currentPage() - 1) * $mutations->perPage() + $loop->index + 1 }}
                        </td>
                        @foreach ($headers as $header)
                            @if ($header != 'no' && $header != 'action')
                                <td class="px-6 py-4 text-gray-900 whitespace-nowrap {{ in_array($header, ['tanggal', 'bruto']) ? 'font-medium' : '' }} {{ $header === 'tanggal' ? 'sticky bg-white group-hover:bg-gray-100 -left-10 z-30' : '' }}">
                                    {{ $mutation->$header }}
                                </td>
                            @endif
                        @endforeach
                    
                        <td class="flex justify-center px-6 py-4">
                            <button data-modal-target="edit-mutation-{{ $mutation->id }}" data-modal-toggle="edit-mutation-{{ $mutation->id }}" class="font-medium text-blue-600 hover:underline" type="button">
                                Edit
                            </button>
                            <div id="edit-mutation-{{ $mutation->id }}" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed right-0 left-0 top-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                <div class="relative p-4 w-full max-w-5xl max-h-full">
                                    <!-- Modal content -->
                                    <div class="relative bg-white rounded-lg shadow">
                                        <!-- Modal header -->
                                        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                                            <h3 class="text-lg font-semibold text-gray-900">
                                                Edit Mutation
                                            </h3>
                                        </div>
                                        <!-- Modal body -->
                                        <form id="data-form" class="p-4 md:p-5" action="{{ route('edit-mutation', $mutation->id) }}" method="POST">
                                            @csrf
                                            @method('put')

                                            <div class="grid grid-cols-5 gap-4 mb-4">
                                                <input name="faktur" value="{{ $mutation->faktur }}" class="border-2 border-black p-2 text-black" placeholder="Faktur" required>
                                                <input name="date" type="date" value="{{ $mutation->date }}" class="border-2 border-black p-2 text-black" required>
                                                <input name="amount" type="text" value="{{ number_format($mutation->amount, 0, ',', '.') }}" class="border-2 border-black p-2 text-black amount-input" placeholder="Nominal" required>

                                                {{-- Dropdown Type --}}
                                                <select name="type" class="border-2 border-black p-2 text-black type-select" required data-index="0">
                                                    <option value="">Pilih Tipe</option>
                                                    <option value="bca" {{ $mutation->type == 'bca' ? 'selected' : '' }}>BCA</option>
                                                    <option value="mandiri" {{ $mutation->type == 'mandiri' ? 'selected' : '' }}>Mandiri</option>
                                                    <option value="debit" {{ $mutation->type == 'debit' ? 'selected' : '' }}>Debit</option>
                                                </select>

                                                {{-- Dropdown Account Holder --}}
                                                <select name="items[0][account_holder]" data-index="0" class="border-2 border-black p-2 text-black">
                                                    <option value="{{ $mutation->account_holder }}" selected>{{ ucfirst($mutation->account_holder) }}</option>
                                                    <option value="hamid">Hamid</option>
                                                    <option value="firda">Firda</option>
                                                    <option value="salma">Salma</option>
                                                    <option value="zhafran">Zhafran</option>
                                                    <option value="lia">Lia</option>
                                                    <option value="bca">BCA</option>
                                                    <option value="mandiri">Mandiri</option>
                                                    <option value="qris">QRIS</option>
                                                </select>
                                            </div>

                                            <div class="flex justify-end mt-2">
                                                <button type="submit" class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                                    Update
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div> 
                            <button data-modal-target="delete-mutation-{{ $mutation->id }}" data-modal-toggle="delete-mutation-{{ $mutation->id }}" class="font-medium text-red-600 hover:underline ms-3" type="button">
                                Delete
                            </button>
                            
                            <div id="delete-mutation-{{ $mutation->id }}" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                <div class="relative p-4 w-full max-w-md max-h-full">
                                    <div class="relative bg-white rounded-lg shadow">
                                        <div class="p-4 md:p-5 text-center">
                                            <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                            </svg>
                                            <h3 class="mb-5 text-lg font-normal text-gray-500">Are you sure you want to delete this product?</h3>
                                            <form action="{{ route('delete-mutation', $mutation->id) }}" method="post">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                                                    Yes, I'm sure
                                                </button>
                                                <button data-modal-hide="popup-modal" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100">No, cancel</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>    
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{-- <div class="flex justify-between items-center my-3">
                <div>
                    <span class="text-sm text-gray-700">
                        Showing 
                        <span class="font-semibold text-gray-900">{{ $mutations->firstItem() }}</span> 
                        to 
                        <span class="font-semibold text-gray-900">{{ $mutations->lastItem() }}</span> 
                        of 
                        <span class="font-semibold text-gray-900">{{ $mutations->total() }}</span> 
                        Entries
                    </span>
                </div>
            </div> --}}
            <div>
                {{ $mutations->links() }}
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    let index = 1;
    function addItem() {
        const container = document.getElementById('items-container');
        const html = `
            <div class="grid grid-cols-5 gap-4 mb-2">
                <input name="items[${index}][faktur]" class="border p-2" placeholder="Faktur" required>
                <input name="items[${index}][date]" type="date" class="border p-2" required>
                <input name="items[${index}][amount]" type="text" class="border p-2 amount-input" placeholder="Nominal" required>

                <select name="items[${index}][type]" class="border p-2 type-select" data-index="${index}" required>
                    <option value="">Pilih Tipe</option>
                    <option value="bca">BCA</option>
                    <option value="mandiri">Mandiri</option>
                    <option value="debit">Debit</option>
                </select>

                <select name="items[${index}][account_holder]" class="border p-2 account-holder-select" data-index="${index}" disabled required>
                    <option value="">Pilih Pemilik</option>
                </select>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
        attachEventsToNewRow(index);
        index++;
    }

    document.addEventListener('DOMContentLoaded', function () {
        const accountHolderOptions = {
            bca: ['hamid', 'firda', 'salma', 'zhafran'],
            mandiri: ['hamid', 'lia'],
            debit: ['bca', 'mandiri', 'qris']
        };

        // Handle dropdown dinamis
        document.querySelectorAll('.type-select').forEach(function (typeSelect) {
            typeSelect.addEventListener('change', function () {
                const index = this.dataset.index;
                const selectedType = this.value;
                const accountHolderSelect = document.querySelector(`.account-holder-select[data-index="${index}"]`);

                if (accountHolderOptions[selectedType]) {
                    accountHolderSelect.innerHTML = '<option value="">Pilih Pemilik</option>';
                    accountHolderOptions[selectedType].forEach(function (name) {
                        const option = document.createElement('option');
                        option.value = name;
                        option.textContent = name.charAt(0).toUpperCase() + name.slice(1);
                        accountHolderSelect.appendChild(option);
                    });
                    accountHolderSelect.disabled = false;
                } else {
                    accountHolderSelect.innerHTML = '<option value="">Pilih Pemilik</option>';
                    accountHolderSelect.disabled = true;
                }
            });
        });

        // Format amount to currency
        document.querySelectorAll('.amount-input').forEach(function (input) {
            input.addEventListener('input', function (e) {
                let value = e.target.value.replace(/[^\d]/g, '');
                if (!value) return e.target.value = '';

                let formatted = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(value);

                e.target.value = formatted;
            });

            // Clean format before submit
            input.form?.addEventListener('submit', function () {
                document.querySelectorAll('.amount-input').forEach(function (i) {
                    i.value = i.value.replace(/[^\d]/g, '');
                });
            });
        });
    });

    function attachEventsToNewRow(idx) {
        const typeSelect = document.querySelector(`.type-select[data-index="${idx}"]`);
        const accountHolderSelect = document.querySelector(`.account-holder-select[data-index="${idx}"]`);
        const amountInput = document.querySelector(`input[name="items[${idx}][amount]"]`);

        // Type → Account Holder dropdown
        typeSelect.addEventListener('change', function () {
            const selectedType = this.value;
            const options = {
                bca: ['hamid', 'firda', 'salma', 'zhafran'],
                mandiri: ['hamid', 'lia'],
                debit: ['bca', 'mandiri', 'qris']
            };

            if (options[selectedType]) {
                accountHolderSelect.innerHTML = '<option value="">Pilih Pemilik</option>';
                options[selectedType].forEach(function (name) {
                    const opt = document.createElement('option');
                    opt.value = name;
                    opt.textContent = name.charAt(0).toUpperCase() + name.slice(1);
                    accountHolderSelect.appendChild(opt);
                });
                accountHolderSelect.disabled = false;
            } else {
                accountHolderSelect.innerHTML = '<option value="">Pilih Pemilik</option>';
                accountHolderSelect.disabled = true;
            }
        });

        // Format Currency
        amountInput.addEventListener('input', function (e) {
            let value = e.target.value.replace(/[^\d]/g, '');
            if (!value) return e.target.value = '';

            let formatted = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(value);

            e.target.value = formatted;
        });

        // Pastikan value dikembalikan ke angka saat submit
        const form = document.getElementById('data-form');
        form.addEventListener('submit', function () {
            document.querySelectorAll('.amount-input').forEach(function (input) {
                input.value = input.value.replace(/[^\d]/g, '');
            });
        });
    }

    const accountHolderOptions = {
        bca: ['hamid', 'firda', 'salma', 'zhafran'],
        mandiri: ['hamid', 'lia'],
        debit: ['bca', 'mandiri', 'qris']
    };

@endsection