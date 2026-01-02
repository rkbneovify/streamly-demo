<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;

class AdminServerLogController extends Controller
{
    public function index()
    {
        $files = glob(storage_path('logs/*.log'));

        if (empty($files)) {
            return 'No logs found';
        }

        $content = '';

        foreach ($files as $file) {
            $content .= "\n\n===== " . basename($file) . " =====\n\n";
            $content .= file_get_contents($file);
        }

        return response("<pre>{$content}</pre>");
    }

    public function clear()
    {
        $logPath = storage_path('logs');

        // Get all .log files
        $logFiles = collect(File::files($logPath))
            ->filter(fn($file) => $file->getExtension() === 'log');

        if ($logFiles->isEmpty()) {
            return response()->json(['status' => 'No log files found']);
        }

        try {
            foreach ($logFiles as $file) {
                File::delete($file->getPathname());
            }

            return response()->json(['status' => 'All log files deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'Failed to delete log files', 'error' => $e->getMessage()], 500);
        }
    }
}
