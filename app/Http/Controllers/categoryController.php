<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class categoryController extends Controller
{
    public function getAllCategories()
    {
        try {
            $categories = Category::all();
            return response()->json([
                'success' => true,
                'data' => $categories
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching categories: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error'
            ], 500);
        }
    }

    public function getCategoriesById($id)
    {
        try {
            $category = Category::findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $category
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Category tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error fetching category by ID: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error'
            ], 500);
        }
    }

    public function createCategory(Request $request)
    {
        try {
            $request->validate([
                'nama' => 'required|string|unique:categories,nama',
                'kode_kategori' => 'required|string|unique:categories,kode_kategori'
            ]);

            $category = Category::create([
                'nama' => $request->nama,
                'kode_kategori' => $request->kode_kategori,
                'slug' => Str::slug($request->nama)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Category berhasil dibuat',
                'data' => $category
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error creating category: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error'
            ], 500);
        }
    }

    public function updateCategory(Request $request, $id)
    {
        try {
            $request->validate([
                'nama' => 'required|string|unique:categories,nama,' . $id,
                'kode_kategori' => 'required|string|unique:categories,kode_kategori,' . $id
            ]);
            
            $category = Category::findOrFail($id);
            $category->update([
                'nama'=> $request->nama,
                'kode_kategori' => $request->kode_kategori,
                'slug' => Str::slug($request->nama)
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Category berhasil diperbarui',
                'data' => $category
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Category tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error updating category: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error'
            ], 500);
        }
    }

    public function deleteCategory($id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();
            return response()->json([
                'success' => true,
                'message' => 'Category berhasil dihapus'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Category tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error deleting category: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error'
            ], 500);
        }
    }

    public function getDeletedCategory()
    {
        try {
            $categories = Category::onlyTrashed()->get();
            return response()->json([
                'success' => true,
                'data' => $categories
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching deleted categories: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error'
            ], 500);
        }
    }

    public function restoreDeletedCategory($id)
    {
        try {
            $category = Category::onlyTrashed()->findOrFail($id);
            $category->restore();
            return response()->json([
                'success' => true,
                'message' => 'Category berhasil dikembalikan',
                'data' => $category
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Category sampah tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error restoring deleted category: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error'
            ], 500);
        }
    }
}