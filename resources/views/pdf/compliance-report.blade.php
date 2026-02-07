<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $report->title }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        @page { margin: 40px 50px; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1f2937; line-height: 1.5; padding: 20px; }
        .page-break { page-break-after: always; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #4f46e5; padding-bottom: 15px; }
        .header h1 { font-size: 20px; color: #1f2937; margin-bottom: 5px; }
        .header p { color: #6b7280; font-size: 10px; }
        .section { margin-bottom: 25px; }
        .section-title { font-size: 14px; font-weight: bold; color: #4f46e5; border-bottom: 1px solid #e5e7eb; padding-bottom: 5px; margin-bottom: 10px; }
        .info-grid { width: 100%; margin-bottom: 15px; }
        .info-grid td { padding: 4px 8px; vertical-align: top; }
        .info-grid .label { font-weight: bold; color: #374151; width: 150px; }
        .info-grid .value { color: #4b5563; }
        .stats-grid { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .stats-grid td { padding: 10px; text-align: center; border: 1px solid #e5e7eb; width: 33.33%; }
        .stats-grid .stat-value { font-size: 18px; font-weight: bold; color: #1f2937; }
        .stats-grid .stat-label { font-size: 9px; color: #6b7280; text-transform: uppercase; }
        table.data-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; font-size: 10px; }
        table.data-table th { background-color: #f9fafb; padding: 6px 8px; text-align: left; border-bottom: 2px solid #e5e7eb; font-weight: 600; color: #374151; }
        table.data-table td { padding: 5px 8px; border-bottom: 1px solid #f3f4f6; color: #4b5563; }
        table.data-table tr:nth-child(even) { background-color: #f9fafb; }
        .progress-bar { background-color: #e5e7eb; height: 8px; border-radius: 4px; overflow: hidden; }
        .progress-fill { background-color: #4f46e5; height: 100%; border-radius: 4px; }
        .footer { margin-top: 30px; border-top: 1px solid #e5e7eb; padding-top: 10px; font-size: 9px; color: #9ca3af; text-align: center; }
        .objectives-list { margin: 5px 0; padding-left: 20px; }
        .objectives-list li { margin-bottom: 2px; font-size: 10px; color: #4b5563; }
        .course-block { margin-bottom: 15px; padding: 10px; border: 1px solid #e5e7eb; border-radius: 4px; }
        .course-title { font-weight: bold; color: #1f2937; margin-bottom: 5px; }
        .course-meta { font-size: 9px; color: #6b7280; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $report->title }}</h1>
        <p>Homeschool Compliance Portfolio Report &mdash; {{ $report->state->label() }}</p>
        <p>Generated {{ $report->generated_at->format('F j, Y') }}</p>
    </div>

    {{-- Student & Parent Info --}}
    <div class="section">
        <div class="section-title">Student &amp; Parent Information</div>
        <table class="info-grid">
            <tr>
                <td class="label">Student Name:</td>
                <td class="value">{{ $student->name }}</td>
                <td class="label">Parent Name:</td>
                <td class="value">{{ $parent->name }}</td>
            </tr>
            <tr>
                <td class="label">Grade:</td>
                <td class="value">{{ $student->grade ?? 'N/A' }}</td>
                <td class="label">Parent Email:</td>
                <td class="value">{{ $parent->email }}</td>
            </tr>
            <tr>
                <td class="label">Age:</td>
                <td class="value">{{ $student->age ?? 'N/A' }}</td>
                <td class="label">Report Period:</td>
                <td class="value">{{ $report->period_start->format('M j, Y') }} &ndash; {{ $report->period_end->format('M j, Y') }}</td>
            </tr>
        </table>
    </div>

    {{-- Summary Statistics --}}
    <div class="section">
        <div class="section-title">Summary Statistics</div>
        <table class="stats-grid">
            <tr>
                <td>
                    <div class="stat-value">{{ $summary['total_instruction_days'] ?? 0 }}</div>
                    <div class="stat-label">Instruction Days</div>
                </td>
                <td>
                    <div class="stat-value">{{ $summary['total_instruction_hours'] ?? 0 }}</div>
                    <div class="stat-label">Instruction Hours</div>
                </td>
                <td>
                    <div class="stat-value">{{ ($summary['courses_active'] ?? 0) + ($summary['courses_completed'] ?? 0) }}</div>
                    <div class="stat-label">Total Courses</div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="stat-value">{{ $summary['courses_completed'] ?? 0 }}</div>
                    <div class="stat-label">Courses Completed</div>
                </td>
                <td>
                    <div class="stat-value">{{ $summary['average_trivia_score'] ?? 0 }}%</div>
                    <div class="stat-label">Avg Assessment Score</div>
                </td>
                <td>
                    <div class="stat-value">{{ $summary['total_interactions'] ?? 0 }}</div>
                    <div class="stat-label">Student Interactions</div>
                </td>
            </tr>
        </table>
    </div>

    {{-- Attendance Summary --}}
    @if(!empty($metadata['attendance_summary']))
    <div class="section">
        <div class="section-title">Attendance Summary</div>
        <table class="stats-grid">
            <tr>
                <td>
                    <div class="stat-value">{{ $metadata['attendance_summary']['present'] ?? 0 }}</div>
                    <div class="stat-label">Present</div>
                </td>
                <td>
                    <div class="stat-value">{{ $metadata['attendance_summary']['field_trip'] ?? 0 }}</div>
                    <div class="stat-label">Field Trips</div>
                </td>
                <td>
                    <div class="stat-value">{{ $metadata['attendance_summary']['offline_day'] ?? 0 }}</div>
                    <div class="stat-label">Offline Days</div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="stat-value">{{ $metadata['attendance_summary']['excused_absence'] ?? 0 }}</div>
                    <div class="stat-label">Excused Absences</div>
                </td>
                <td colspan="2">
                    <div class="stat-value">{{ $metadata['attendance_summary']['total_attendance_days'] ?? 0 }}</div>
                    <div class="stat-label">Total Attendance Days</div>
                </td>
            </tr>
        </table>
    </div>
    @endif

    {{-- Attendance Log --}}
    @if(!empty($metadata['attendance_log']))
    <div class="section">
        <div class="section-title">Attendance Log</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                @foreach($metadata['attendance_log'] as $entry)
                <tr>
                    <td>{{ $entry['date'] }}</td>
                    <td>{{ $entry['label'] }}</td>
                    <td>{{ $entry['notes'] ?? 'N/A' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- Daily Activity Log --}}
    @if(!empty($metadata['daily_activity_log']))
    <div class="section">
        <div class="section-title">Daily Activity Log</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Duration</th>
                    <th>Sessions</th>
                    <th>Reading Materials</th>
                </tr>
            </thead>
            <tbody>
                @foreach($metadata['daily_activity_log'] as $entry)
                <tr>
                    <td>{{ $entry['date'] }}</td>
                    <td>{{ floor($entry['total_seconds'] / 3600) }}h {{ floor(($entry['total_seconds'] % 3600) / 60) }}m</td>
                    <td>{{ $entry['sessions'] }}</td>
                    <td>{{ implode(', ', $entry['courses_studied'] ?? []) ?: 'N/A' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- Course Progress --}}
    @if(!empty($metadata['course_progress']))
    <div class="section">
        <div class="section-title">Course Progress &amp; Reading Materials</div>
        @foreach($metadata['course_progress'] as $course)
        <div class="course-block">
            <div class="course-title">{{ $course['title'] }}</div>
            <div class="course-meta">
                Progress: {{ $course['progress'] }}%
                @if(!empty($course['started_at'])) | Started: {{ $course['started_at'] }} @endif
                @if(!empty($course['completed_at'])) | Completed: {{ $course['completed_at'] }} @endif
                | Time Spent: {{ $course['time_spent_minutes'] }} minutes
            </div>
            <div class="progress-bar" style="margin-top: 5px;">
                <div class="progress-fill" style="width: {{ $course['progress'] }}%;"></div>
            </div>
            @if(!empty($course['learning_objectives']))
            <ul class="objectives-list">
                @foreach(array_slice($course['learning_objectives'], 0, 10) as $objective)
                <li>{{ $objective }}</li>
                @endforeach
                @if(count($course['learning_objectives']) > 10)
                <li style="color: #9ca3af;">...and {{ count($course['learning_objectives']) - 10 }} more objectives</li>
                @endif
            </ul>
            @endif
        </div>
        @endforeach
    </div>
    @endif

    {{-- Assessment Results --}}
    @if(!empty($metadata['assessment_results']))
    <div class="section">
        <div class="section-title">Assessment Results</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Course</th>
                    <th>Average Score</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                @foreach($metadata['assessment_results'] as $assessment)
                <tr>
                    <td>{{ $assessment['course_title'] }}</td>
                    <td>{{ $assessment['average'] }}%</td>
                    <td>
                        @foreach($assessment['scores'] as $key => $score)
                            {{ ucwords(str_replace('_', ' ', $key)) }}: {{ is_array($score) ? ($score['score'] ?? 'N/A') : $score }}%{{ !$loop->last ? ', ' : '' }}
                        @endforeach
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- Student Interactions --}}
    @if(!empty($metadata['interaction_log']))
    <div class="section">
        <div class="section-title">Student Interaction Log (Work Samples)</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 80px;">Date</th>
                    <th>Question / Prompt</th>
                    <th style="width: 80px;">Word Count</th>
                </tr>
            </thead>
            <tbody>
                @foreach($metadata['interaction_log'] as $interaction)
                <tr>
                    <td>{{ $interaction['date'] }}</td>
                    <td>{{ \Illuminate\Support\Str::limit($interaction['question'], 200) }}</td>
                    <td>{{ $interaction['word_count'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- Footer --}}
    <div class="footer">
        <p>This report was generated to satisfy the portfolio requirements under Florida Statute Section 1002.41, F.S.</p>
        <p>The portfolio must be preserved for two years after the date of termination of the home education program.</p>
        <p>Report generated on {{ $report->generated_at->format('F j, Y \a\t g:i A') }}</p>
    </div>
</body>
</html>
