<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== DEBUG RACKS DATA ===\n\n";

// Check total racks
$rackCount = \App\Models\Rack::count();
echo "Total Racks: {$rackCount}\n\n";

// Check racks with details
$racks = \App\Models\Rack::with(['subRacks', 'creator'])
    ->withCount(['files', 'subRacks'])
    ->latest()
    ->get();

echo "Racks List:\n";
foreach ($racks as $rack) {
    echo "- ID: {$rack->id}, Name: {$rack->name}, Sub-Racks: {$rack->sub_racks_count}, Files: {$rack->files_count}\n";
}

echo "\n=== PAGINATION TEST ===\n";
$paginatedRacks = \App\Models\Rack::with(['subRacks', 'creator'])
    ->withCount(['files', 'subRacks'])
    ->latest()
    ->paginate(10);

echo "Paginated Data Structure:\n";
echo "- Current Page: {$paginatedRacks->currentPage()}\n";
echo "- Total: {$paginatedRacks->total()}\n";
echo "- Per Page: {$paginatedRacks->perPage()}\n";
echo "- Data Count: " . count($paginatedRacks->items()) . "\n";

echo "\nJSON Structure:\n";
echo json_encode($paginatedRacks->toArray(), JSON_PRETTY_PRINT);
