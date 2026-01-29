<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\DB;

$rows = DB::table('notifications')->whereNull('read_at')->orderBy('created_at','desc')->limit(20)->get();
if ($rows->isEmpty()) {
    echo "NO_UNREAD\n";
    exit;
}
foreach ($rows as $r) {
    echo "id: {$r->id}, notifiable_id: {$r->notifiable_id}, notifiable_type: {$r->notifiable_type}, data: {$r->data}, created_at: {$r->created_at}\n";
}

// Optionally mark first as read
$first = $rows->first();
$firstId = $first->id;
$notif = DB::table('notifications')->where('id', $firstId)->first();
echo "\nAttempting to mark id={$firstId} as read via Eloquent model...\n";
$notificationModel = \Illuminate\Notifications\DatabaseNotification::find($firstId);
if (! $notificationModel) {
    echo "Cannot find DatabaseNotification model for id {$firstId}\n";
    exit;
}
$notificationModel->markAsRead();
$now = DB::table('notifications')->where('id', $firstId)->value('read_at');
echo "read_at after mark: " . ($now ?? 'NULL') . "\n";
