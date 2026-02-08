<?php

namespace App\Models;

use App\Enums\ComplianceReportStatus;
use App\Enums\ComplianceState;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComplianceReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'student_id',
        'state',
        'title',
        'period_start',
        'period_end',
        'summary_statistics',
        'report_metadata',
        'status',
        'generated_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'period_start' => 'date',
            'period_end' => 'date',
            'summary_statistics' => 'array',
            'report_metadata' => 'array',
            'state' => ComplianceState::class,
            'status' => ComplianceReportStatus::class,
            'generated_at' => 'datetime',
        ];
    }

    /**
     * Get the parent who generated this report
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    /**
     * Get the student this report is for
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
