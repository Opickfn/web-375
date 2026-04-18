<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$approvedCount = DB::table('reports')->where('status','approved')->count();
echo "APPROVED_REPORTS={$approvedCount}\n";

$top = DB::table('points')
    ->join('reports','reports.id','=','points.report_id')
    ->where('reports.status','approved')
    ->join('users','users.id','=','points.user_id')
    ->select('users.full_name', DB::raw('SUM(points.amount) as total_points'))
    ->groupBy('users.full_name')
    ->orderByDesc('total_points')
    ->limit(5)
    ->get();

echo "TOP_COUNT=" . $top->count() . "\n";
foreach ($top as $row) {
    echo "{$row->full_name} => {$row->total_points}\n";
}
