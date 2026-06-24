<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Location;
use Illuminate\Support\Facades\Log;

class locationController extends Controller
{
    public function getAllLocations()
    {
        try {
            $locations = Location::all();
            return response()->json([
                'success' => true,
                'data' => $locations
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching locations: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error'
            ], 500);
        }
    }

    public function getLocationById($id)
    {
        try {
            $location = Location::findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $location
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lokasi tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error fetching location by ID: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error'
            ], 500);
        }
    }

    public function createLocation(Request $request)
    {
        try {
            $request->validate([
                'nama_lokasi' => 'required|string|max:255',
                'latitude'    => 'required|numeric|between:-90,90',
                'longitude'   => 'required|numeric|between:-180,180',
                'radius_km'   => 'nullable|integer|min:1'
            ]);

            $location = Location::create([
                'nama_lokasi' => $request->nama_lokasi,
                'latitude'    => $request->latitude,
                'longitude'   => $request->longitude,
                'radius_km'   => $request->radius_km ?? 5
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Lokasi berhasil dibuat',
                'data' => $location
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error creating location: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error'
            ], 500);
        }
    }

    public function updateLocation(Request $request, $id)
    {
        try {
            $request->validate([
                'nama_lokasi' => 'required|string|max:255',
                'latitude'    => 'required|numeric|between:-90,90',
                'longitude'   => 'required|numeric|between:-180,180',
                'radius_km'   => 'nullable|integer|min:1'
            ]);
            
            $location = Location::findOrFail($id);
            $location->update([
                'nama_lokasi' => $request->nama_lokasi,
                'latitude'    => $request->latitude,
                'longitude'   => $request->longitude,
                'radius_km'   => $request->radius_km ?? 5
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Lokasi berhasil diperbarui',
                'data' => $location
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
                'message' => 'Lokasi tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error updating location: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error'
            ], 500);
        }
    }

    public function deleteLocation($id)
    {
        try {
            $location = Location::findOrFail($id);
            $location->delete();
            return response()->json([
                'success' => true,
                'message' => 'Lokasi berhasil dipindahkan ke tempat sampah'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lokasi tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error deleting location: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error'
            ], 500);
        }
    }

    public function getDeletedLocations()
    {
        try {
            $locations = Location::onlyTrashed()->get();
            return response()->json([
                'success' => true,
                'data' => $locations
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching deleted locations: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error'
            ], 500);
        }
    }

    public function restoreDeletedLocation($id)
    {
        try {
            $location = Location::onlyTrashed()->findOrFail($id);
            $location->restore();
            return response()->json([
                'success' => true,
                'message' => 'Lokasi berhasil dikembalikan',
                'data' => $location
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lokasi sampah tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error restoring deleted location: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error'
            ], 500);
        }
    }
}