<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CommentHistory; 
use App\Models\History;

class historyController extends Controller
{
    public function getHistory()
    {
        try {
            $history = History::with([
                'report' 
            ])
            ->where("id_user")
            ->latest()
            ->get();

            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengambil semua riwayat',
                'data'    => $history
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil riwayat',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function deleteHistory($id)
    {
        try {
            $history = History::FindOrfail($id);

            if (!$history) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data riwayat tidak ditemukan'
                ], 404);
            }

            $history->delete();

            return response()->json([
                'success' => true,
                'message' => 'Riwayat berhasil dihapus'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus riwayat',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}