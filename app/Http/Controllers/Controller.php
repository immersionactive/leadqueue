<?php

namespace App\Http\Controllers;

use Log;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * Class Controller.
 */
class Controller extends BaseController
{

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Write a message to the system log, automatically prepended with the
     * current user's ID and email address.
     * @param string $type The type of log entry to write (e.g., "info" or "warning").
     * @param string $message The log message to write.
     * @return void
     */
    protected function log($type, $message): void
    {

        $user = auth()->user();
        if ($user) {
            $message = 'User ' . $user->id . ' (' . $user->email . '): ' . $message;
        }

        Log::$type($message);

    }

}
