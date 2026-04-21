<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Services\ResourceAllocator;

class ResourceController extends Controller
{
    public function allocation()
    {
        $weekStart = $_GET['week'] ?? date('Y-m-d', strtotime('monday this week'));

        $allocator = new ResourceAllocator($this->pdo);
        $data = $allocator->getWeeklyAllocation($weekStart);
        $alerts = $allocator->detectOverbooking($weekStart);

        $prevWeek = date('Y-m-d', strtotime($weekStart . ' -7 days'));
        $nextWeek = date('Y-m-d', strtotime($weekStart . ' +7 days'));

        $this->render('resources/allocation.tpl', [
            'page_title' => 'Phân bổ Nguồn lực',
            'data'       => $data,
            'alerts'     => $alerts,
            'week_start' => $weekStart,
            'prev_week'  => $prevWeek,
            'next_week'  => $nextWeek,
        ]);
    }
}
