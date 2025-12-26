<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class CacheController extends Controller
{
    /**
     * Get cache statistics
     */
    public function index()
    {
        $this->authorize('cache-clear');

        $stats = [
            'driver' => config('cache.default'),
            'stores' => array_keys(config('cache.stores')),
        ];

        return response()->json(['success' => true, 'data' => $stats]);
    }

    /**
     * Clear application cache
     */
    public function clearCache()
    {
        $this->authorize('cache-clear');

        try {
            Artisan::call('cache:clear');

            return response()->json([
                'success' => true,
                'message' => 'Application cache cleared successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear cache: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear configuration cache
     */
    public function clearConfig()
    {
        $this->authorize('cache-clear');

        try {
            Artisan::call('config:clear');

            return response()->json([
                'success' => true,
                'message' => 'Configuration cache cleared successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear config cache: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear route cache
     */
    public function clearRoute()
    {
        $this->authorize('cache-clear');

        try {
            Artisan::call('route:clear');

            return response()->json([
                'success' => true,
                'message' => 'Route cache cleared successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear route cache: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear view cache
     */
    public function clearView()
    {
        $this->authorize('cache-clear');

        try {
            Artisan::call('view:clear');

            return response()->json([
                'success' => true,
                'message' => 'View cache cleared successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear view cache: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear all caches
     */
    public function clearAll()
    {
        $this->authorize('cache-clear');

        try {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');

            return response()->json([
                'success' => true,
                'message' => 'All caches cleared successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear all caches: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Optimize application (cache config and routes)
     */
    public function optimize()
    {
        $this->authorize('cache-clear');

        try {
            Artisan::call('config:cache');
            Artisan::call('route:cache');
            Artisan::call('view:cache');

            return response()->json([
                'success' => true,
                'message' => 'Application optimized successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to optimize application: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear optimization
     */
    public function clearOptimization()
    {
        $this->authorize('cache-clear');

        try {
            Artisan::call('optimize:clear');

            return response()->json([
                'success' => true,
                'message' => 'Optimization cleared successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear optimization: ' . $e->getMessage()
            ], 500);
        }
    }
}
