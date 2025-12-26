<?php

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

if (!function_exists('setUnsetUniqueId')) {

    function setUnsetUniqueId($operationType = null)
    {
        // Session::put('unique_id', $uniqid);
        if ($operationType == 'get') {
            $session_data = $_SESSION['unique_id'];

            $_SESSION['unique_id'] = null;

            return $session_data;
        } else {
            $uniqid = Str::random(30);
            $_SESSION['unique_id'] = $uniqid;
        }
    }
}

// delete files
if (!function_exists('deleteFiles')) {
    function deleteFiles($file_paths)
    {
        foreach ($file_paths as $file_path) {
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }
    }
}

// generate asset path with id (filetime)
if (!function_exists('generateAssetPath')) {
    function generateAssetPath($asset_path)
    {
        $file = asset($asset_path);
        $filemtime = Storage::disk('assets')->lastModified($asset_path);
        $id = md5($filemtime);
        return $file . '?id=' . $id;
    }
}

// log activity helper
if (!function_exists('logActivity')) {
    function logActivity(
        string $description,
        ?Illuminate\Database\Eloquent\Model $subject = null,
        ?array $properties = null,
        ?string $event = null,
        string $logName = 'default'
    ) {
        return \App\Services\ActivityLogger::log($description, $subject, null, $properties, $event, $logName);
    }
}

// track login helper
if (!function_exists('trackLogin')) {
    function trackLogin(
        \App\Models\User $user,
        bool $isSuccessful = true,
        string $method = 'web'
    ) {
        return \App\Services\LoginTracker::track($user, $isSuccessful, $method);
    }
}
