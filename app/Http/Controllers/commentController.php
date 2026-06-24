<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
   
    public function getByReport($id_report)
    {
        $comments = Comment::with(['user:id,nama_panjang', 'replies.user:id,nama_panjang']) // Ganti 'name' sesuai kolom nama di tabel users lu
            ->where('id_report', $id_report)
            ->whereNull('id_parent') 
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil mengambil komentar',
            'data'    => $comments
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_user'   => 'required|string|exists:users,id',
            'id_report' => 'required|string|exists:reports,id',
            'message'   => 'required|string',
            'id_parent' => 'nullable|string|exists:comments,id', 
        ]);
        try {
            $comment = Comment::create([
                'id_user'   => $request->id_user,
                'id_report' => $request->id_report,
                'message'   => $request->message,
                'id_parent' => $request->id_parent,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Komentar berhasil ditambahkan',
                'data'    => $comment
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan komentar',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json([
                'success' => false,
                'message' => 'Komentar tidak ditemukan'
            ], 404);
        }

        try {
            $comment->update([
                'message' => $request->message
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Komentar berhasil diupdate',
                'data'    => $comment
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate komentar',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * DELETE: Hapus komentar (Soft Delete)
     */
    public function destroy($id)
    {
        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json([
                'success' => false,
                'message' => 'Komentar tidak ditemukan'
            ], 404);
        }

        try {
            // Karena pakai softDeletes, ini gak akan ngilang permanen dari DB
            $comment->delete();

            return response()->json([
                'success' => true,
                'message' => 'Komentar berhasil dihapus'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus komentar',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

}