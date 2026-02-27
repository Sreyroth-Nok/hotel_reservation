<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    /**
     * Display all audit logs
     */
    public function index(Request $request)
    {
        $query = $request->get('search');
        $action = $request->get('action');
        $model_type = $request->get('model_type');
        $date_from = $request->get('date_from');
        $date_to = $request->get('date_to');

        $logs = AuditLog::with('user')
            ->when($query, function($q) use ($query) {
                $q->where('description', 'LIKE', "%{$query}%")
                  ->orWhereHas('user', function($userQuery) use ($query) {
                      $userQuery->where('full_name', 'LIKE', "%{$query}%")
                               ->orWhere('username', 'LIKE', "%{$query}%");
                  });
            })
            ->when($action, function($q) use ($action) {
                $q->where('action', $action);
            })
            ->when($model_type, function($q) use ($model_type) {
                $q->where('model_type', 'LIKE', "%{$model_type}%");
            })
            ->when($date_from, function($q) use ($date_from) {
                $q->whereDate('created_at', '>=', $date_from);
            })
            ->when($date_to, function($q) use ($date_to) {
                $q->whereDate('created_at', '<=', $date_to);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->appends($request->all());

        // Get unique model types for filter
        $modelTypes = AuditLog::select('model_type')
            ->distinct()
            ->pluck('model_type')
            ->map(function($type) {
                $parts = explode('\\', $type);
                return ['value' => $type, 'label' => end($parts)];
            });

        return view('audit-logs.index', compact('logs', 'modelTypes'));
    }

    /**
     * Display the specified audit log
     */
    public function show($id)
    {
        $log = AuditLog::with('user')->findOrFail($id);

        return view('audit-logs.show', compact('log'));
    }

    /**
     * Get audit logs for a specific model
     */
    public function forModel(Request $request, $modelType, $modelId)
    {
        $logs = AuditLog::with('user')
            ->where('model_type', $modelType)
            ->where('model_id', $modelId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($logs);
    }
}
