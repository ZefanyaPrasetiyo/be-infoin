<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notifications;

class NotificationController extends Controller
{
    public function getNotifications($id_user)
    {
        try{
        $notifications = Notifications::with("report")->where("id_user", $id_user)->latest()->get();
        return response()->json([
            "success" => true,
            "message" => "Berhasil Mengambil notifikasi",
            'data' => $notifications
        ],200);
        } catch(\Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil notifikasi',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function markAsRead($id)
    {
        try{
        $notifications = Notifications::findOrFail($id);
        if (!$notifications) {
                return response()->json([
                    'success' => false,
                    'message' => 'Notifikasi tidak ditemukan'
                ], 404);
            }
        $notifications->update(['is_read' => true]);
        return response()->json([
            "status" => 200,
            "message" => "Berhasil dibaca",
            "data" => $notifications,
        ], 200);
        } catch(\Exception $e){
    return response()->json([
                'success' => false,
                'message' => 'Gagal update notifikasi',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
