<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Popup;
use App\Models\Language;

$langs = Language::all();
foreach ($langs as $lang) {
    echo "Language: " . $lang->name . " (ID: " . $lang->id . ")\n";
    $popups = Popup::where('language_id', $lang->id)->get();
    echo "Popups count: " . $popups->count() . "\n";
    foreach ($popups as $p) {
        echo " - Popup: " . $p->name . " (Status: " . $p->status . ")\n";
    }
}
