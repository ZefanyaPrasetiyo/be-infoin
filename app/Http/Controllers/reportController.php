<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\DetailReport;
use App\Services\CloudinaryService;
use App\Services\SightengineService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Notifications;
use App\Models\User;
use Illuminate\Support\Str;

class ReportController extends Controller
{
    protected $cloudinary;
    protected $sightengine;

    public function __construct(CloudinaryService $cloudinary, SightengineService $sightengine)
    {
        $this->cloudinary = $cloudinary;
        $this->sightengine = $sightengine;
    }

    public function getReport()
    {
        try {
            $reports = Report::with('detail')->latest()->get();

            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengambil semua data laporan',
                'data'    => $reports
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error get all reports: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error'
            ], 500);
        }
    }

    public function getReportById($id)
    {
        try {
            $report = Report::with('detail')->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengambil detail laporan',
                'data'    => $report
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data laporan tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error get report by ID: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error'
            ], 500);
        }
    }

    public function createReport(Request $request)
    {

        try {
            $request->validate([
                'id_user'       => 'required|string|exists:users,id',
                'id_kategori'   => 'required|string|exists:categories,id',
                'id_location'   => 'required|string|exists:locations,id',
                'judul_laporan' => 'required|string',
                'deskripsi'     => 'required|string',
                'latitude'      => 'required|numeric',
                'longitude'     => 'required|numeric',
                'alamat'        => 'required|string',
                'gambar'        => 'required|array|max:5',
                'gambar.*'      => 'required|image|mimes:jpeg,png,jpg|max:5120',
                'status'  => 'nullable|in:menunggu,diproses,disetujui,ditolak',
                'catatan' => 'nullable|string'
            ]);

            $files = $request->file('gambar'); 
            
            $firstFile = $files[0];
            $aiCheck = $this->sightengine->checkImage($firstFile->getRealPath());
            $aiScore = isset($aiCheck['type']['ai_generated']) ? $aiCheck['type']['ai_generated'] : 0;

            if ($aiScore > 0.5) {
                $labelAi = 'Ilustrasi AI';
                $kepercayaanAi = round($aiScore * 100, 2) . '%';
            } else {
                $labelAi = 'Gambar Asli (Non-AI)';
                $kepercayaanAi = round((1 - $aiScore) * 100, 2) . '%';
            }

            $imageUrls = [];
            foreach ($files as $file) {
                $url = $this->cloudinary->uploadImage($file, 'laporan_warga');
                $imageUrls[] = $url; // Masukin link URL-nya ke array
            }

            DB::beginTransaction();

            $kodeReport = 'REP-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

            $report = Report::create([
                'kode_report'   => $kodeReport,
                'id_user'       => $request->id_user,
                'id_kategori'   => $request->id_kategori,
                'judul_laporan' => $request->judul_laporan,
                'deskripsi'     => $request->deskripsi,
                'status'        => 'menunggu',
                'bukti_laporan' => $imageUrls,
                'catatan'       => $request->catatan ?? ''
            ]);

            DetailReport::create([
                'id_report'      => $report->id,
                'latitude'       => $request->latitude,
                'longitude'      => $request->longitude,
                'alamat'         => $request->alamat,
                'id_location'    => $request->id_location,
                'kepercayaan_ai' => $kepercayaanAi,
                'label_ai'       => $labelAi
            ]);

            $admins = User::whereIn('role', ['admin', 'petugas'])
                          ->where('id_location', $request->id_location)
                          ->get();

            $notifications = [];
            foreach ($admins as $admin) {
                $notifications[] = [
                    'id'         => (string) Str::ulid(),
                    'id_user'    => $admin->id,
                    'id_report'  => $report->id,
                    'type'       => 'new_report',
                    'is_read'    => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            
            if (count($notifications) > 0) {
                Notifications::insert($notifications); 
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Laporan berhasil dibuat',
                'data'    => $report
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi form gagal',
                'errors'  => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error create report: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error',
                'error_detail' => env('APP_DEBUG') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function updateReportStatus(Request $request, $id)
    {
        try {
            $request->validate([
                'status'  => 'required|in:menunggu,diproses,disetujui,ditolak',
                'catatan' => 'nullable|string'
            ]);

            $report = Report::findOrFail($id);

            $report->update([
                'status'  => $request->status,
                'catatan' => $request->catatan ?? $report->catatan
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status laporan berhasil diperbarui',
                'data'    => $report
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi form gagal',
                'errors'  => $e->errors()
            ], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data laporan tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error update report status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error'
            ], 500);
        }
    }

    public function deleteReport($id)
    {
        try {
            $report = Report::findOrFail($id);

            $report->delete();

            return response()->json([
                'success' => true,
                'message' => 'Laporan berhasil dihapus (Soft Delete)'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data laporan tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error delete report: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error'
            ], 500);
        }
    }
}
