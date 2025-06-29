<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Notification extends Model
{
    protected $fillable = [
        'type',
        'title',
        'message',
        'data',
        'is_read',
        'notifiable_type',
        'notifiable_id',
        'user_id',
        'peminjaman_id',
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the notifiable entity that the notification belongs to.
     */
    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the user that triggered this notification.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the peminjaman that this notification relates to.
     */
    public function peminjaman(): BelongsTo
    {
        return $this->belongsTo(Peminjaman::class);
    }

    /**
     * Mark the notification as read.
     */
    public function markAsRead()
    {
        if (!$this->is_read) {
            $this->update(['is_read' => true]);
        }
    }

    /**
     * Mark the notification as unread.
     */
    public function markAsUnread()
    {
        if ($this->is_read) {
            $this->update(['is_read' => false]);
        }
    }

    /**
     * Scope to get unread notifications.
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope to get read notifications.
     */
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    /**
     * Scope to get notifications for admins (global notifications).
     */
    public function scopeForAdmins($query)
    {
        return $query->whereNull('notifiable_type')
                    ->whereNull('notifiable_id');
    }

    /**
     * Scope to get recent notifications.
     */
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Get the formatted time ago.
     */
    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Get the notification icon based on type.
     */
    public function getIconAttribute()
    {
        return match($this->type) {
            'peminjaman_baru' => 'fas fa-plus-circle',
            'pengembalian' => 'fas fa-check-circle',
            'terlambat' => 'fas fa-exclamation-triangle',
            'rusak' => 'fas fa-tools',
            default => 'fas fa-bell'
        };
    }

    /**
     * Get the notification color based on type.
     */
    public function getColorAttribute()
    {
        return match($this->type) {
            'peminjaman_baru' => 'text-blue-600',
            'pengembalian' => 'text-green-600',
            'terlambat' => 'text-red-600',
            'rusak' => 'text-orange-600',
            default => 'text-gray-600'
        };
    }

    /**
     * Create a new peminjaman notification.
     */
    public static function createPeminjamanNotification($peminjaman, $type = 'peminjaman_baru')
    {
        $titles = [
            'peminjaman_baru' => 'Peminjaman Baru',
            'pengembalian' => 'Pengembalian Barang',
            'terlambat' => 'Peminjaman Terlambat',
        ];

        $messages = [
            'peminjaman_baru' => "{$peminjaman->user->name} mengajukan peminjaman {$peminjaman->barang->nama}",
            'pengembalian' => "{$peminjaman->user->name} telah mengembalikan {$peminjaman->barang->nama}",
            'terlambat' => "{$peminjaman->user->name} terlambat mengembalikan {$peminjaman->barang->nama}",
        ];

        return self::create([
            'type' => $type,
            'title' => $titles[$type] ?? 'Notifikasi',
            'message' => $messages[$type] ?? 'Ada aktivitas baru',
            'data' => [
                'barang_nama' => $peminjaman->barang->nama,
                'user_nama' => $peminjaman->user->name,
                'tanggal_pinjam' => $peminjaman->tanggal_pinjam,
                'tanggal_kembali' => $peminjaman->tanggal_kembali,
            ],
            'user_id' => $peminjaman->user_id,
            'peminjaman_id' => $peminjaman->id,
        ]);
    }
}
