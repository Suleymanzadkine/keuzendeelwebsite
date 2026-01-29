<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Notifications\StudentRemovedNotification;
use Illuminate\Support\Facades\DB;

// Find a non-admin user
$user = User::where('is_admin', false)->orWhereNull('is_admin')->first();
if (! $user) {
    echo "No non-admin user found.\n";
    exit(1);
}

echo "Using user: {$user->id} - {$user->email}\n";

// Create a notification via notify()
$user->notify(new StudentRemovedNotification('Test Keuzedeel', 1, 1));

$notif = DB::table('notifications')->where('notifiable_id', $user->id)->orderBy('created_at','desc')->first();
if (! $notif) {
    echo "Failed to create notification.\n";
    exit(1);
}

echo "Created notification id: {$notif->id}, read_at: " . ($notif->read_at ?? 'NULL') . "\n";

// Simulate controller action: mark as read
$notificationModel = \Illuminate\Notifications\DatabaseNotification::find($notif->id);
if (! $notificationModel) {
    echo "Cannot find DatabaseNotification model.\n";
    exit(1);
}
$notificationModel->markAsRead();
$now = DB::table('notifications')->where('id', $notif->id)->value('read_at');
echo "After mark read: read_at = " . ($now ?? 'NULL') . "\n";

// Check unread count
$unreadCount = $user->unreadNotifications()->count();
$total = $user->notifications()->count();
echo "User unread: {$unreadCount}, total: {$total}\n";
