<?php

namespace App\Http\Controllers;

use App\Models\BillEditLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BillEditLogController extends Controller
{
    /**
     * Display a listing of bill edit logs.
     */
    public function index(Request $request): View
    {
        $query = BillEditLog::with('user')
            ->orderBy('created_at', 'desc');

        // Filter by bill type
        if ($request->filled('bill_type')) {
            $query->where('bill_type', $request->bill_type);
        }

        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->paginate(20);

        return view('bill-edit-logs.index', compact('logs'));
    }

    /**
     * Display the specified bill edit log.
     */
    public function show(BillEditLog $billEditLog): View
    {
        $billEditLog->load('user');
        return view('bill-edit-logs.show', compact('billEditLog'));
    }
}