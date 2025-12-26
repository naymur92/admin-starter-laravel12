<?php

namespace App\Http\Controllers;

use App\Models\LoginHistory;
use Illuminate\Http\Request;

class LoginHistoryController extends Controller
{
    /**
     * Display a listing of login history.
     */
    public function index(Request $request)
    {
        $this->authorize('login-history-list');

        $query = LoginHistory::with('user')
            ->orderBy('login_at', 'desc');

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by success status
        if ($request->filled('is_successful')) {
            $query->where('is_successful', $request->is_successful === '1');
        }

        // Filter by login method
        if ($request->filled('login_method')) {
            $query->where('login_method', $request->login_method);
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('login_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('login_at', '<=', $request->end_date);
        }

        // Search by IP address
        if ($request->filled('search')) {
            $query->where('ip_address', 'like', '%' . $request->search . '%');
        }

        $logins = $query->paginate(50);

        // Get statistics
        $stats = [
            'total' => LoginHistory::count(),
            'successful' => LoginHistory::successful()->count(),
            'failed' => LoginHistory::failed()->count(),
            'today' => LoginHistory::whereDate('login_at', today())->count(),
        ];

        return view('pages.login-history.index', compact('logins', 'stats'));
    }

    /**
     * Display the specified login history.
     */
    public function show(LoginHistory $loginHistory)
    {
        $this->authorize('login-history-view');

        $loginHistory->load('user');

        return view('pages.login-history.show', compact('loginHistory'));
    }

    /**
     * Display login history for current user.
     */
    public function myHistory(Request $request)
    {
        $logins = LoginHistory::where('user_id', auth()->id())
            ->orderBy('login_at', 'desc')
            ->paginate(20);

        return view('pages.login-history.my-history', compact('logins'));
    }
}
