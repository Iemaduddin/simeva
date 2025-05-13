<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetBooking;
use App\Models\AssetTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardAdminAssetBookingController extends Controller
{
    public function indexRTPU()
    {
        $query = AssetBooking::where('status', 'LIKE', 'submission%')
            ->whereHas('asset', function ($query) {
                $query->where('facility_scope', 'umum');
            });

        $role = Auth::user()->getRoleNames()->first();

        if ($role == 'Kaur RT') {
            $query->whereNull('asset_category_id');
        } elseif ($role == 'UPT PU') {
            $query->whereNotNull('asset_category_id');
        }

        $assetBookings = $query->get();

        return view('dashboardPage.dashboard.kaur-rt-upt-pu', compact('assetBookings'));
    }
    public function indexAdminJurusan($kode_jurusan)
    {

        if (Auth::user()->roles()->where('name', 'Admin Jurusan')->exists()) {
            if (optional(Auth::user()->jurusan)->kode_jurusan !== $kode_jurusan) {
                return abort(403, 'Unauthorized');
            }
        }
        $totalAsset = Asset::where('facility_scope', 'jurusan')
            ->whereHas('jurusan', function ($query) use ($kode_jurusan) {
                $query->where('kode_jurusan', $kode_jurusan);
            })->count();

        $assetBookings = AssetBooking::where('status', 'LIKE', 'submission%')
            ->whereHas('asset.jurusan', function ($query) use ($kode_jurusan) {
                $query->where('kode_jurusan', $kode_jurusan);
            })
            ->get();

        return view('dashboardPage.dashboard.admin-jurusan', compact('totalAsset', 'kode_jurusan', 'assetBookings'));
    }

    public function getDataAsset($type)
    {
        try {
            if ($type !== 'all') {
                $total = Asset::where('facility_scope', 'umum')->where('booking_type', $type)->count();
            } else {
                $total = Asset::where('facility_scope', 'umum')->count();
            }

            return response()->json([
                'total' => $total,
                'status' => 'success',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function getAssetBookingIncome($year)
    {
        try {
            $totalIncome = AssetTransaction::where('status', 'full_paid')
                ->whereYear('created_at', $year)
                ->with('booking') // pastikan relasi 'booking' sudah didefinisikan
                ->get()
                ->sum(function ($transaction) {
                    if ($transaction->booking) {
                        return $transaction->amount == $transaction->booking->total_amount
                            ? $transaction->amount
                            : $transaction->booking->total_amount;
                    }
                    return 0; // jika tidak ada relasi booking, abaikan
                });
            return response()->json([
                'total' => $totalIncome,
                'status' => 'success',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getStatusAssetBookingChart()
    {
        if (Auth::user()->hasRole('Kaur RT')) {
            $query = AssetBooking::where('asset_category_id',  null);
        } elseif (Auth::user()->hasRole('UPT PU')) {
            $query = AssetBooking::where('asset_category_id', '!=', null);
        }
        $statusCounts = $query->selectRaw('
            SUM(CASE WHEN status = "booked" THEN 1 ELSE 0 END) as booked_count,
            SUM(CASE WHEN status NOT IN ("booked","approved", "rejected", "cancelled") THEN 1 ELSE 0 END) as submission_count,
            SUM(CASE WHEN status = "approved" THEN 1 ELSE 0 END) as approved_count,
            SUM(CASE WHEN status = "rejected" THEN 1 ELSE 0 END) as rejected_count,
            SUM(CASE WHEN status = "cancelled" THEN 1 ELSE 0 END) as cancelled_count
        ')
            ->first();
        return response()->json([
            'labels' => ['Booking Disetujui', 'Perlu Dikonfirmasi', 'Disetujui', 'Ditolak', 'Dibatalkan'],
            'colors' => ['#487fff', '#ff9f29', '#16a34a', '#dc2626', '#000000'],
            'statusTotal' => [
                $statusCounts->booked_count ?? 0,
                $statusCounts->submission_count ?? 0,
                $statusCounts->approved_count ?? 0,
                $statusCounts->rejected_count ?? 0,
                $statusCounts->cancelled_count ?? 0
            ]
        ]);
    }
    public function getStatusAssetBookingJurusanChart($kode_jurusan)
    {
        $query = AssetBooking::whereHas('asset.jurusan', function ($q) use ($kode_jurusan) {
            $q->where('kode_jurusan', $kode_jurusan);
        });
        $statusCounts = $query->selectRaw('
            SUM(CASE WHEN status = "booked" THEN 1 ELSE 0 END) as booked_count,
            SUM(CASE WHEN status NOT IN ("booked","approved", "rejected", "cancelled") THEN 1 ELSE 0 END) as submission_count,
            SUM(CASE WHEN status = "approved" THEN 1 ELSE 0 END) as approved_count,
            SUM(CASE WHEN status = "rejected" THEN 1 ELSE 0 END) as rejected_count,
            SUM(CASE WHEN status = "cancelled" THEN 1 ELSE 0 END) as cancelled_count
        ')
            ->first();
        return response()->json([
            'labels' => ['Booking Disetujui', 'Perlu Dikonfirmasi', 'Disetujui', 'Ditolak', 'Dibatalkan'],
            'colors' => ['#487fff', '#ff9f29', '#16a34a', '#dc2626', '#000000'],
            'statusTotal' => [
                $statusCounts->booked_count ?? 0,
                $statusCounts->submission_count ?? 0,
                $statusCounts->approved_count ?? 0,
                $statusCounts->rejected_count ?? 0,
                $statusCounts->cancelled_count ?? 0
            ]
        ]);
    }

    public function getUsageAssetChart(Request $request)
    {
        $year = $request->input('year', now()->year);
        $role = Auth::user()->getRoleNames()->first();

        $bookingsQuery = DB::table('asset_bookings')
            ->join('assets', 'asset_bookings.asset_id', '=', 'assets.id')
            ->select(
                'assets.name as asset_name',
                DB::raw('MONTH(asset_bookings.usage_date_start) as month'),
                DB::raw('COUNT(*) as total')
            )
            ->where('assets.facility_scope', 'umum')
            ->whereYear('asset_bookings.usage_date_start', $year);

        // Tambahkan kondisi untuk aset_category berdasarkan role
        if ($role !== 'Kaur RT') {
            $bookingsQuery->whereNotNull('asset_bookings.asset_category_id')->where('asset_bookings.status', 'approved_full_payment');
        } else {
            $bookingsQuery->whereNull('asset_bookings.asset_category_id')->where('asset_bookings.status', 'approved');
        }

        $bookings = $bookingsQuery
            ->groupBy('assets.name', DB::raw('MONTH(asset_bookings.usage_date_start)'))
            ->get()
            ->groupBy('asset_name');


        $response = [];
        if ($bookings->isEmpty()) {
            // Kirim placeholder agar chart tetap muncul
            $response[] = [
                'name' => 'Tidak ada data',
                'data' => array_fill(0, 12, 0)
            ];
        } else {

            foreach ($bookings as $assetName => $entries) {
                $monthlyData = array_fill(1, 12, 0);

                foreach ($entries as $entry) {
                    $monthlyData[$entry->month] = $entry->total;
                }

                $response[] = [
                    'name' => $assetName,
                    'data' => array_values($monthlyData)
                ];
            }
        }

        return response()->json([
            'series' => $response,
            'categories' => ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']
        ]);
    }
    public function getUsageAssetJurusanChart(Request $request, $kode_jurusan)
    {
        $year = $request->input('year', now()->year);


        $bookingsQuery = DB::table('asset_bookings')
            ->join('assets', 'asset_bookings.asset_id', '=', 'assets.id')
            ->join('jurusan', 'assets.jurusan_id', '=', 'jurusan.id')
            ->select(
                'assets.name as asset_name',
                DB::raw('MONTH(asset_bookings.usage_date_start) as month'),
                DB::raw('COUNT(*) as total')
            )
            ->where('assets.facility_scope', 'jurusan')
            ->where('jurusan.kode_jurusan', $kode_jurusan)
            ->whereYear('asset_bookings.usage_date_start', $year);

        $bookings = $bookingsQuery
            ->groupBy('assets.name', DB::raw('MONTH(asset_bookings.usage_date_start)'))
            ->get()
            ->groupBy('asset_name');

        $response = [];
        if ($bookings->isEmpty()) {
            // Kirim placeholder agar chart tetap muncul
            $response[] = [
                'name' => 'Tidak ada data',
                'data' => array_fill(0, 12, 0)
            ];
        } else {

            foreach ($bookings as $assetName => $entries) {
                $monthlyData = array_fill(1, 12, 0);

                foreach ($entries as $entry) {
                    $monthlyData[$entry->month] = $entry->total;
                }

                $response[] = [
                    'name' => $assetName,
                    'data' => array_values($monthlyData)
                ];
            }
        }

        return response()->json([
            'series' => $response,
            'categories' => ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']
        ]);
    }
}
