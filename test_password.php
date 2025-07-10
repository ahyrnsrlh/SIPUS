<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$user = User::where('email', 'akhyar@example.com')->first();

if ($user) {
    echo "User found: " . $user->name . "\n";
    echo "Email: " . $user->email . "\n";
    echo "Role: " . $user->role . "\n";
    echo "Status: " . $user->status . "\n";
    echo "Password check (user123): " . (Hash::check('user123', $user->password) ? 'TRUE' : 'FALSE') . "\n";
} else {
    echo "User not found\n";
}

echo "\n";

$admin = User::where('email', 'admin@sipus.com')->first();

if ($admin) {
    echo "Admin found: " . $admin->name . "\n";
    echo "Email: " . $admin->email . "\n";
    echo "Role: " . $admin->role . "\n";
    echo "Status: " . $admin->status . "\n";
    echo "Password check (admin123): " . (Hash::check('admin123', $admin->password) ? 'TRUE' : 'FALSE') . "\n";
} else {
    echo "Admin not found\n";
}
