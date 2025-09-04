<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use App\Models\Pelapor;
use App\Models\Korban;
use App\Models\Terlapor;
use App\Models\Message;
use App\Models\User;
use App\Notifications\NewMessageNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;
use RealRashid\SweetAlert\Facades\Alert;
use App\Filament\Resources\PengaduanResource;
use Filament\Notifications\Notification as FilamentNotification;
use Filament\Notifications\Actions\Action as NotificationAction;

class PengaduanPublikController extends Controller
{

    public function index()
    {
        $userId = Auth::id();

        $semuaPengaduan = Pengaduan::where('user_id', $userId)
            ->orderBy('tanggal_pelaporan', 'desc')
            ->get();

        $statusSedangDikerjakan = [
            'Verifikasi',
            'Investigasi',
            'Penyusunan Kesimpulan dan Rekomendasi',
            'Tindak Lanjut Kesimpulan dan Rekomendasi'
        ];

        $pengaduanBelumDikerjakan = $semuaPengaduan->where('status_pengaduan', 'Menunggu');

        $pengaduanSedangDikerjakan = $semuaPengaduan->whereIn('status_pengaduan', $statusSedangDikerjakan);

        $pengaduanSelesai = $semuaPengaduan->where('status_pengaduan', 'Selesai');

        return view('pengaduan.index', [
            'pengaduanBelumDikerjakan' => $pengaduanBelumDikerjakan,
            'pengaduanSedangDikerjakan' => $pengaduanSedangDikerjakan,
            'pengaduanSelesai' => $pengaduanSelesai,
        ]);
    }
    public function show(Pengaduan $pengaduan)
    {
        if ($pengaduan->user_id !== Auth::id()) {
            abort(403, 'ANDA TIDAK MEMILIKI AKSES.');
        }
        return view('pengaduan.show', [
            'pengaduan' => $pengaduan
        ]);
    }


    public function create()
    {
        return view('lapor');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pelapor.nama' => 'required|string|max:255',
            'pelapor.nomor_telepon' => 'nullable|string|max:255',
            'pelapor.jenis_kelamin' => 'required|string',
            'pelapor.domisili' => 'required|string|max:255',
            'pelapor.status' => 'required|string',
            'pelapor.peran' => 'required|string|in:Korban,Saksi',
            'pelapor.memiliki_disabilitas' => 'required|boolean',

            'korbans' => 'required_if:pelapor.peran,Saksi|array|min:1',
            'korbans.*.nama' => 'required_if:pelapor.peran,Saksi|string|max:255',
            'korbans.*.nomor_telepon' => 'nullable|string|max:255',
            'korbans.*.jenis_kelamin' => 'required_if:pelapor.peran,Saksi|string',
            'korbans.*.domisili' => 'required_if:pelapor.peran,Saksi|string|max:255',
            'korbans.*.status' => 'required_if:pelapor.peran,Saksi|string',
            'korbans.*.memiliki_disabilitas' => 'required_if:pelapor.peran,Saksi|boolean',

            'terlapors' => 'nullable|array',
            'terlapors.*.nama' => 'nullable|string|max:255',
            'terlapors.*.nomor_telepon' => 'nullable|string|max:255',
            'terlapors.*.jenis_kelamin' => 'nullable|string',
            'terlapors.*.domisili' => 'nullable|string|max:255',
            'terlapors.*.status' => 'nullable|string',
            'terlapors.*.memiliki_disabilitas' => 'nullable|boolean',

            'jenis_kejadian' => 'required|string|max:255',
            'tanggal_kejadian' => 'required|date',
            'lokasi_kejadian' => 'required|string|max:255',
            'terjadi_saat_tridharma' => 'required|boolean',
            'terjadi_di_wilayah_kampus' => 'required|boolean',
            'jenis_tridharma' => 'required_if:terjadi_saat_tridharma,1|string|in:Pendidikan,Penelitian,Pengabdian',
            'deskripsi_pengaduan' => 'required|string',
            'alasan_pengaduan' => 'nullable|array',
            'identifikasi_kebutuhan_korban' => 'nullable|array',
            'bukti_pendukung' => 'nullable|file|mimes:zip,rar|max:10240',
            'url_bukti_tambahan' => 'nullable|url',
            'tanda_tangan_pelapor' => 'required|string',
        ]);

        try {
            DB::transaction(function () use ($validated, $request) {

                $pelapor = Pelapor::create([
                    'user_id' => Auth::id(),
                    'nama' => $validated['pelapor']['nama'],
                    'nomor_telepon' => $validated['pelapor']['nomor_telepon'] ?? null,
                    'jenis_kelamin' => $validated['pelapor']['jenis_kelamin'],
                    'domisili' => $validated['pelapor']['domisili'],
                    'status' => $validated['pelapor']['status'],
                    'memiliki_disabilitas' => $validated['pelapor']['memiliki_disabilitas'],
                ]);

                $pengaduan = Pengaduan::create([
                    'user_id' => Auth::id(),
                    'tanggal_pelaporan' => now(),
                    'jenis_kejadian' => $validated['jenis_kejadian'],
                    'tanggal_kejadian' => $validated['tanggal_kejadian'],
                    'lokasi_kejadian' => $validated['lokasi_kejadian'],
                    'terjadi_saat_tridharma' => $validated['terjadi_saat_tridharma'],
                    'terjadi_di_wilayah_kampus' => $validated['terjadi_di_wilayah_kampus'],
                    'jenis_tridharma' => $validated['jenis_tridharma'] ?? null,
                    'deskripsi_pengaduan' => $validated['deskripsi_pengaduan'],
                    'alasan_pengaduan' => $validated['alasan_pengaduan'] ?? [],
                    'identifikasi_kebutuhan_korban' => $validated['identifikasi_kebutuhan_korban'] ?? [],
                    'bukti_pendukung' => $request->hasFile('bukti_pendukung') ? $request->file('bukti_pendukung')->store('bukti-pengaduan', 'public') : null,
                    'url_bukti_tambahan' => $validated['url_bukti_tambahan'] ?? null,
                    'tanda_tangan_pelapor' => $validated['tanda_tangan_pelapor'],
                    'status_pengaduan' => 'Menunggu',
                ]);


                $pengaduan->pelapors()->attach($pelapor->id, ['peran_dalam_pengaduan' => $validated['pelapor']['peran']]);

                if ($validated['pelapor']['peran'] === 'Korban') {
                    $korbanData = $pelapor->toArray();
                    $korban = Korban::create($korbanData);
                    $pengaduan->korbans()->attach($korban->id);
                } elseif ($validated['pelapor']['peran'] === 'Saksi' && !empty($validated['korbans'])) {
                    foreach ($validated['korbans'] as $index => $dataKorban) {
                        $korban = Korban::create($dataKorban);
                        $pengaduan->korbans()->attach($korban->id);
                    }
                }
                if (!empty($validated['terlapors'])) {
                    foreach ($validated['terlapors'] as $index => $dataTerlapor) {
                        if (!empty($dataTerlapor['nama'])) {
                            $dataTerlapor['memiliki_disabilitas'] = $dataTerlapor['memiliki_disabilitas'] ?? false;
                            $terlapor = Terlapor::create($dataTerlapor);
                            $pengaduan->terlapors()->attach($terlapor->id);
                        }
                    }
                }
            });
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan pengaduan: ' . $e->getMessage() . ' - Line: ' . $e->getLine());
            Alert::error('Gagal', 'Laporan gagal diajukan. Silakan coba lagi atau hubungi administrator.');
            return back()->withInput();
        }
        Alert::success('Berhasil', 'Laporan berhasil diajukan.');
        return redirect()->route('dashboard');
    }

    public function storeMessage(Request $request, Pengaduan $pengaduan)
    {
        if ($pengaduan->user_id !== auth()->id()) {
            abort(403, 'Akses Ditolak');
        }

        $request->validate([
            'body' => 'required_without:file|max:5000',
            'file' => 'required_without:body|file|mimes:zip,rar,jpg,jpeg,png|max:5120',
        ]);

        $data = [
            'pengaduan_id' => $pengaduan->id,
            'user_id' => auth()->id(),
            'body' => $request->body,
        ];

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('attachments', 'public');
            $data['file_path'] = $path;
            $data['file_name'] = $file->getClientOriginalName();
            $data['file_mime_type'] = $file->getMimeType();
        }

        $message = Message::create($data);

        try {
            $adminsAndPetugas = User::role(['admin', 'petugas'])->get();

            if ($adminsAndPetugas->isNotEmpty()) {
                FilamentNotification::make()
                    ->title('Pesan baru dari ' . auth()->user()->name)
                    ->body('Pesan baru ditambahkan pada pengaduan ' . $pengaduan->nomor_pengaduan)
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->actions([
                        NotificationAction::make('Lihat')
                            ->url(PengaduanResource::getUrl('view', ['record' => $pengaduan->id]))
                            ->markAsRead(),
                    ])
                    ->sendToDatabase($adminsAndPetugas);
                Notification::send($adminsAndPetugas, new NewMessageNotification($pengaduan, $message));
            }
        } catch (\Exception $e) {
            Log::error('Gagal mengirim notifikasi dari pengguna ke admin: ' . $e->getMessage());
        }

        toast('Pesan berhasil dikirim!', 'success');

        return back();
    }
}
