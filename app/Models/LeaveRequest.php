<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    use HasFactory;

    const TYPE_CASUAL = 1;
    const TYPE_SICK = 2;
    const TYPE_EMERGENCY = 3;

    const TYPES = [
        self::TYPE_CASUAL => "Casual Leave",
        self::TYPE_SICK => "Sick Leave",
        self::TYPE_EMERGENCY => "Emergency Leave"
    ];

    const STATUS_PENDING = 0;
    const STATUS_APPROVED = 1;
    const STATUS_REJECTED = 2;

    const STATUSES = [
        self::STATUS_PENDING => "Pending",
        self::STATUS_APPROVED => "Approved",
        self::STATUS_REJECTED => "Rejected"
    ];

    protected $guarded = [];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
