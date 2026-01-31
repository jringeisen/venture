<?php

namespace App\Services;

use App\Models\ComplianceReport;
use Barryvdh\DomPDF\Facade\Pdf;

class CompliancePdfService
{
    public function generate(ComplianceReport $report): \Barryvdh\DomPDF\PDF
    {
        $report->load(['student', 'parent']);

        return Pdf::loadView('pdf.compliance-report', [
            'report' => $report,
            'student' => $report->student,
            'parent' => $report->parent,
            'summary' => $report->summary_statistics,
            'metadata' => $report->report_metadata ?? [],
        ])->setPaper('letter', 'portrait');
    }

    public function download(ComplianceReport $report): \Symfony\Component\HttpFoundation\Response
    {
        $pdf = $this->generate($report);
        $filename = str_replace(' ', '_', $report->title).'_'.now()->format('Ymd').'.pdf';

        return $pdf->download($filename);
    }
}
