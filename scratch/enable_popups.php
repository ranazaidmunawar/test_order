<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Popup;

$count = Popup::where('status', 0)->update(['status' => 1]);
echo "Updated " . $count . " popups to status 1 (Enabled).\n";
