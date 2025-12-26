<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of activity logs.
     */
    public function index(Request $request)
    {
        $this->authorize('activity-log-list');

        $query = ActivityLog::with(['causer', 'subject'])
            ->orderBy('created_at', 'desc');

        // Filter by log name
        if ($request->filled('log_name')) {
            $query->where('log_name', $request->log_name);
        }

        // Filter by causer
        if ($request->filled('causer_id')) {
            $query->where('causer_id', $request->causer_id);
        }

        // Filter by event
        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Search in description
        if ($request->filled('search')) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }

        $logs = $query->paginate(50);

        // Get unique log names for filter
        $logNames = ActivityLog::distinct()->pluck('log_name')->filter();

        // Get unique events for filter
        $events = ActivityLog::distinct()->pluck('event')->filter();

        return view('pages.activity-logs.index', compact('logs', 'logNames', 'events'));
    }

    /**
     * Display the specified activity log.
     */
    public function show(ActivityLog $activityLog)
    {
        $this->authorize('activity-log-view');

        $activityLog->load(['causer', 'subject']);

        return view('pages.activity-logs.show', compact('activityLog'));
    }

    /**
     * Remove old activity logs.
     */
    public function cleanup(Request $request)
    {
        $this->authorize('activity-log-delete');

        $request->validate([
            'days' => 'required|integer|min:1',
        ]);

        $deleted = ActivityLog::where('created_at', '<', now()->subDays($request->days))->delete();

        return redirect()->route('activity-logs.index')
            ->with('success', "Deleted {$deleted} activity logs older than {$request->days} days.");
    }
}
