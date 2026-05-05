<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemLog extends Model
{
    use HasFactory;

    protected $table = 'system_logs';
    protected $primaryKey = 'LogID';

    protected $fillable = ['SuperAdminID', 'Action', 'IPAddress', 'Timestamp'];

    public function superAdmin()
    {
        return $this->belongsTo(User::class, 'SuperAdminID', 'UserID');
    }

    // Alias for views that use $log->user
    public function user()
    {
        return $this->belongsTo(User::class, 'SuperAdminID', 'UserID');
    }

    /**
     * Helper to log an action from any context.
     */
    public static function record($action, $request = null)
    {
        return self::create([
            'SuperAdminID' => auth()->id(),
            'Action'       => $action,
            'IPAddress'    => $request ? $request->ip() : request()->ip(),
            'Timestamp'    => now(),
        ]);
    }
}
