<?php

namespace App\Http\Controllers;

use App\Models\Centrall;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MutationReportController extends Controller
{
    public function index()
    {
        $mutations = Centrall::where('user_id', Auth::id())->orderBy('created_at', 'desc')->paginate(20);
        $mutationCount = Centrall::where('user_id', Auth::id())->count();
        
        if($mutationCount > 0){
            $this->formatLaporan($mutations);
        }

        $headers = ['no','faktur','date','amount','account_holder','type','created_at','updated_at','action'];

        return view('mutation', ['mutationCount' => $mutationCount, 'mutations' => $mutations, 'headers' => $headers]);
    }
    private function formatLaporan($mutations)
    {
        // Loop melalui setiap laporan
        $columnsToFormat = ['amount'];
        // Lakukan pencarian berdasarkan rentang tanggal
        foreach ($mutations as $mutation) {
            // Loop melalui setiap kolom yang ingin diformat
            foreach ($columnsToFormat as $column) {
                // Lakukan pengecekan apakah kolom tersebut ada dalam laporan
                if (isset($mutation->{$column})) {
                    // Ubah format nilai kolom menjadi ribuan
                    $mutation->{$column} = number_format($mutation->{$column}, 0, ',', '.');
                }
            }
        }
        return $mutation;
    }

    public function create(Request $request)
    {
        $data = $request->validate([
            'items' => 'required|array',
            'items.*.faktur' => 'required|string',
            'items.*.date' => 'required|date',
            'items.*.amount' => 'required|numeric',
            'items.*.account_holder' => 'required|string',
            'items.*.type' => 'required|string',
        ]);

        Log::info($data);
        
        $userId = Auth::id();
        Log::info($userId);
        $records = [];

        foreach ($data['items'] as $item) {
            $records[] = [
                'faktur' => strtolower($item['faktur']),
                'date' => $item['date'],
                'amount' => $item['amount'],
                'account_holder' => strtolower($item['account_holder']),
                'type' => strtolower($item['type']),
                'user_id' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Centrall::insert($records);

        return redirect()->back()->with('success', 'Data berhasil disimpan.');
    }

    public function update(Request $request, $id)
    {
        Log::info('Memulai update data Centrall', [
            'request' => $request->all(),
            'id' => $id,
        ]);

        try {
            $data = $request->validate([
                'faktur' => 'required|string',
                'date' => 'required|date',
                'amount' => 'required|numeric',
                'type' => 'required|string',
                // 'account_holder' dihapus dari sini
            ]);

            // Ambil account_holder dari input utama atau dari items[0]
            $accountHolder = strtolower(
                $request->input('account_holder') ??
                optional($request->input('items')[0])['account_holder']
            );

            if (!$accountHolder) {
                Log::warning('Account holder kosong atau tidak ditemukan dalam request');
                return redirect()->back()->withErrors([
                    'account_holder' => 'Account holder is required.',
                ])->withInput();
            }

            $type = strtolower($data['type']);

            Log::info("Validasi relasi antara type dan account_holder", [
                'type' => $type,
                'account_holder' => $accountHolder,
            ]);

            $options = [
                'bca' => ['hamid', 'firda', 'salma', 'zhafran'],
                'mandiri' => ['hamid', 'lia'],
                'debit' => ['bca', 'mandiri', 'qris'],
            ];

            if (isset($options[$type]) && !in_array($accountHolder, $options[$type])) {
                Log::warning("Account holder tidak valid untuk tipe ini", [
                    'type' => $type,
                    'account_holder' => $accountHolder,
                ]);

                return redirect()->back()->withErrors([
                    'account_holder' => "Pemilik akun tidak valid untuk tipe '$type'.",
                ])->withInput();
            }

            DB::beginTransaction();

            $centrall = Centrall::findOrFail($id);
            $centrall->update([
                'faktur' => strtolower($data['faktur']),
                'date' => $data['date'],
                'amount' => $data['amount'],
                'account_holder' => $accountHolder,
                'type' => $type,
            ]);

            DB::commit();

            Log::info('Data Centrall berhasil diupdate', [
                'id' => $centrall->id,
                'data' => $centrall->toArray(),
            ]);

            return redirect()->back()->with('success', 'Data berhasil diupdate.');
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Gagal mengupdate data Centrall', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengupdate data.');
        }
    }

    public function delete($id)
    {
        $centrall = Centrall::findOrFail($id);
        $centrall->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus.');
    }

    public function search(Request $request)
    {
        try {
            $start = $request->input('start');
            $end = $request->input('end');

            // Logging untuk debugging
            Log::info('Search Mutation', ['start' => $start, 'end' => $end]);

            $mutationQuery = Centrall::query()
                ->when($start, function ($query) use ($start) {
                    $query->whereDate('date', '>=', $start);
                })
                ->when($end, function ($query) use ($end) {
                    $query->whereDate('date', '<=', $end);
                })
                ->latest('date');

            $mutations = $mutationQuery->paginate(20);
            $mutationCount = $mutationQuery->count();
        
            if($mutationCount > 0){
                $this->formatLaporan($mutations);
            }

            $headers = ['no','faktur','date','amount','account_holder','type','created_at','updated_at','action'];

            return view('mutation', ['mutationCount' => $mutationCount, 'mutations' => $mutations, 'headers' => $headers]);
        } catch (\Exception $e) {
            Log::error('Error during search-mutation', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->with('error', 'Terjadi kesalahan saat mencari data.');
        }
    }
}
