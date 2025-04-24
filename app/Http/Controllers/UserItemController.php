<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Event;
use App\Models\UserItem;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserItemController extends Controller
{

    public function savedItem($itemType)
    {
        $userId = Auth::id(); // Ambil user yang sedang login

        if ($itemType === 'asset') {
            // Ambil daftar asset_id yang telah dibookmark oleh user
            $bookmarkedAssetIds = UserItem::where('user_id', $userId)->pluck('asset_id');
            // Ambil aset berdasarkan ID yang sudah dibookmark
            $assets = Asset::whereIn('id', $bookmarkedAssetIds)->paginate(10);

            return view('homepage.assets.saved-asset', compact('assets'));
        } else {
            // Ambil daftar asset_id yang telah dibookmark oleh user
            $bookmarkedEventIds = UserItem::where('user_id', $userId)->pluck('event_id');
            // Ambil aset berdasarkan ID yang sudah dibookmark
            $events = Event::whereIn('id', $bookmarkedEventIds)->paginate(10);

            return view('homepage.events.saved-event', compact('events'));
        }
    }
    public function checkSavedItem(Request $request)
    {
        $user = Auth::user();
        $itemType = $request->itemType;

        if ($itemType === 'asset') {
            $assetIds = $request->asset_ids;
            $bookmarkedAssets = UserItem::where('user_id', $user->id)
                ->whereIn('asset_id', $assetIds)
                ->pluck('asset_id')
                ->toArray();

            return response()->json($bookmarkedAssets);
        } else {
            $eventIds = $request->event_ids;
            $bookmarkEvents = UserItem::where('user_id', $user->id)
                ->whereIn('event_id', $eventIds)
                ->pluck('event_id')
                ->toArray();

            return response()->json($bookmarkEvents);
        }
    }

    // Toggle bookmark (tambah/hapus)
    public function toggleSavedItem(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Anda harus login terlebih dahulu.'], 403);
        }
        $user = Auth::user();
        $itemType = $request->itemType;

        if ($itemType === 'asset') {
            $assetId = $request->asset_id;

            $bookmarkAsset = UserItem::where('user_id', $user->id)
                ->where('asset_id', $assetId)
                ->first();

            if ($bookmarkAsset) {
                $bookmarkAsset->delete();
                return response()->json(['status' => 'removed']);
            } else {
                UserItem::create([
                    'id' => Str::uuid(),
                    'user_id' => $user->id,
                    'asset_id' => $assetId,
                ]);
                return response()->json(['status' => 'added']);
            }
        } else {
            $eventId = $request->event_id;


            $bookmarkEvent = UserItem::where('user_id', $user->id)
                ->where('event_id', $eventId)
                ->first();

            if ($bookmarkEvent) {
                $bookmarkEvent->delete();
                return response()->json(['status' => 'removed']);
            } else {
                UserItem::create([
                    'id' => Str::uuid(),
                    'user_id' => $user->id,
                    'event_id' => $eventId,
                ]);
                return response()->json(['status' => 'added']);
            }
        }
    }
    public function removeAllItem()
    {
        $user = Auth::user();
        UserItem::where('user_id', $user->id)->delete();

        return response()->json(['status' => 'all_removed']);
    }
}
