<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    /**
     * Get all notifications for admin dashboard
     */
    public function index(Request $request): JsonResponse
    {
        $limit = $request->get('limit', 10);
        
        $notifications = Notification::forAdmins()
            ->with(['user', 'peminjaman.barang'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'data' => $notification->data,
                    'is_read' => $notification->is_read,
                    'time_ago' => $notification->time_ago,
                    'icon' => $notification->icon,
                    'color' => $notification->color,
                    'created_at' => $notification->created_at->format('Y-m-d H:i:s'),
                    'user' => $notification->user ? [
                        'id' => $notification->user->id,
                        'name' => $notification->user->name,
                    ] : null,
                    'peminjaman' => $notification->peminjaman ? [
                        'id' => $notification->peminjaman->id,
                        'barang_nama' => $notification->peminjaman->barang->nama ?? 'Unknown',
                    ] : null,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $notifications,
        ]);
    }

    /**
     * Get unread notifications count
     */
    public function getUnreadCount(): JsonResponse
    {
        $count = Notification::forAdmins()
            ->unread()
            ->count();

        return response()->json([
            'success' => true,
            'count' => $count,
        ]);
    }

    /**
     * Mark a specific notification as read
     */
    public function markAsRead(Request $request, $id): JsonResponse
    {
        try {
            $notification = Notification::findOrFail($id);
            $notification->markAsRead();

            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Notification not found',
            ], 404);
        }
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(): JsonResponse
    {
        try {
            $updated = Notification::forAdmins()
                ->unread()
                ->update(['is_read' => true]);

            return response()->json([
                'success' => true,
                'message' => "Marked {$updated} notifications as read",
                'updated_count' => $updated,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark notifications as read',
            ], 500);
        }
    }

    /**
     * Delete a notification
     */
    public function destroy($id): JsonResponse
    {
        try {
            $notification = Notification::findOrFail($id);
            $notification->delete();

            return response()->json([
                'success' => true,
                'message' => 'Notification deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Notification not found',
            ], 404);
        }
    }

    /**
     * Get notification statistics for dashboard
     */
    public function getStats(): JsonResponse
    {
        $stats = [
            'total' => Notification::forAdmins()->count(),
            'unread' => Notification::forAdmins()->unread()->count(),
            'today' => Notification::forAdmins()->whereDate('created_at', today())->count(),
            'this_week' => Notification::forAdmins()->recent(7)->count(),
            'by_type' => Notification::forAdmins()
                ->selectRaw('type, COUNT(*) as count')
                ->groupBy('type')
                ->pluck('count', 'type')
                ->toArray(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
        ]);
    }

    /**
     * Create a test notification (for development/testing)
     */
    public function createTestNotification(): JsonResponse
    {
        try {
            $notification = Notification::create([
                'type' => 'peminjaman_baru',
                'title' => 'Test Notification',
                'message' => 'This is a test notification for development',
                'data' => [
                    'test' => true,
                    'created_by' => 'system',
                ],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Test notification created',
                'data' => $notification,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create test notification: ' . $e->getMessage(),
            ], 500);
        }
    }
}
