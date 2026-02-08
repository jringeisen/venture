<?php

namespace App\Services;

use App\DataTransferObjects\CourseImportResult;
use App\Enums\ContentStatus;
use App\Models\Course;
use App\Models\CourseDay;
use App\Models\CoursePrompt;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;
use OpenSpout\Reader\XLSX\Reader as XlsxReader;

class CourseImportService
{
    private const REQUIRED_HEADERS = [
        'course_title',
        'course_description',
        'week_number',
        'week_title',
        'day_number',
        'day_title',
    ];

    public function import(UploadedFile $file): CourseImportResult
    {
        $rows = $this->parseFile($file);

        if (empty($rows)) {
            return new CourseImportResult(validationErrors: ['The file contains no data rows.']);
        }

        $errors = $this->validateRows($rows);

        if (! empty($errors)) {
            return new CourseImportResult(validationErrors: $errors);
        }

        $groups = $this->groupRowsIntoCourses($rows);

        return $this->createCoursesFromGroups($groups);
    }

    /**
     * @return array<int, array<string, string>>
     */
    public function parseFile(UploadedFile $file): array
    {
        $extension = strtolower($file->getClientOriginalExtension());

        return match ($extension) {
            'csv' => $this->parseCsv($file),
            'xlsx' => $this->parseXlsx($file),
            default => throw new \InvalidArgumentException("Unsupported file type: {$extension}"),
        };
    }

    /**
     * @return array<int, array<string, string>>
     */
    private function parseCsv(UploadedFile $file): array
    {
        $reader = Reader::createFromPath($file->getRealPath());
        $reader->setHeaderOffset(0);

        $records = [];
        foreach ($reader->getRecords() as $record) {
            $records[] = array_map('trim', $record);
        }

        return $records;
    }

    /**
     * @return array<int, array<string, string>>
     */
    private function parseXlsx(UploadedFile $file): array
    {
        $reader = new XlsxReader;
        $reader->open($file->getRealPath());

        $records = [];
        $headers = [];

        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $rowIndex => $row) {
                $cells = array_map(function ($cell) {
                    $value = $cell->getValue();

                    return is_string($value) ? trim($value) : (string) $value;
                }, $row->getCells());

                if ($rowIndex === 1) {
                    $headers = $cells;

                    continue;
                }

                if (empty(array_filter($cells))) {
                    continue;
                }

                $record = [];
                foreach ($headers as $i => $header) {
                    $record[$header] = $cells[$i] ?? '';
                }

                $records[] = $record;
            }

            break; // Only read first sheet
        }

        $reader->close();

        return $records;
    }

    /**
     * @param  array<int, array<string, string>>  $rows
     * @return array<string, string>
     */
    public function validateRows(array $rows): array
    {
        $errors = [];

        // Validate headers exist
        if (! empty($rows)) {
            $headers = array_keys($rows[0]);
            $missingHeaders = array_diff(self::REQUIRED_HEADERS, $headers);

            if (! empty($missingHeaders)) {
                $errors['headers'] = 'Missing required columns: '.implode(', ', $missingHeaders);

                return $errors;
            }
        }

        // Validate each row
        foreach ($rows as $index => $row) {
            $rowNum = $index + 2; // +2 because index is 0-based and row 1 is headers

            if (empty($row['course_title'])) {
                $errors["row_{$rowNum}"] = "Row {$rowNum}: course_title is required.";
            }

            if (empty($row['course_description'])) {
                $errors["row_{$rowNum}_desc"] = "Row {$rowNum}: course_description is required.";
            }

            if (empty($row['week_number']) || ! is_numeric($row['week_number']) || (int) $row['week_number'] < 1) {
                $errors["row_{$rowNum}_week"] = "Row {$rowNum}: week_number must be a positive integer.";
            }

            if (empty($row['week_title'])) {
                $errors["row_{$rowNum}_week_title"] = "Row {$rowNum}: week_title is required.";
            }

            if (empty($row['day_number']) || ! is_numeric($row['day_number']) || (int) $row['day_number'] < 1) {
                $errors["row_{$rowNum}_day"] = "Row {$rowNum}: day_number must be a positive integer.";
            }

            if (empty($row['day_title'])) {
                $errors["row_{$rowNum}_day_title"] = "Row {$rowNum}: day_title is required.";
            }

            // Validate age fields if present
            if (! empty($row['course_min_age']) && ! is_numeric($row['course_min_age'])) {
                $errors["row_{$rowNum}_min_age"] = "Row {$rowNum}: course_min_age must be a number.";
            }

            if (! empty($row['course_max_age']) && ! is_numeric($row['course_max_age'])) {
                $errors["row_{$rowNum}_max_age"] = "Row {$rowNum}: course_max_age must be a number.";
            }
        }

        // Validate unique day numbers per week per course
        $courseWeekDays = [];
        foreach ($rows as $index => $row) {
            $rowNum = $index + 2;
            $key = ($row['course_title'] ?? '').'|'.($row['week_number'] ?? '');
            $dayNum = $row['day_number'] ?? '';

            if (! isset($courseWeekDays[$key])) {
                $courseWeekDays[$key] = [];
            }

            if (in_array($dayNum, $courseWeekDays[$key])) {
                $errors["row_{$rowNum}_dup_day"] = "Row {$rowNum}: Duplicate day_number {$dayNum} for week {$row['week_number']} in course '{$row['course_title']}'.";
            }

            $courseWeekDays[$key][] = $dayNum;
        }

        return $errors;
    }

    /**
     * @param  array<int, array<string, string>>  $rows
     * @return array<string, array{course: array, weeks: array<int, array{week: array, days: array}>}>
     */
    public function groupRowsIntoCourses(array $rows): array
    {
        $courses = [];

        foreach ($rows as $row) {
            $courseTitle = $row['course_title'];
            $weekNum = (int) $row['week_number'];

            if (! isset($courses[$courseTitle])) {
                $courses[$courseTitle] = [
                    'course' => [
                        'title' => $courseTitle,
                        'description' => $row['course_description'],
                        'min_age' => ! empty($row['course_min_age']) ? (int) $row['course_min_age'] : null,
                        'max_age' => ! empty($row['course_max_age']) ? (int) $row['course_max_age'] : null,
                    ],
                    'weeks' => [],
                ];
            }

            if (! isset($courses[$courseTitle]['weeks'][$weekNum])) {
                $courses[$courseTitle]['weeks'][$weekNum] = [
                    'week' => [
                        'week_number' => $weekNum,
                        'title' => $row['week_title'],
                        'description' => $row['week_description'] ?? null,
                    ],
                    'days' => [],
                ];
            }

            $courses[$courseTitle]['weeks'][$weekNum]['days'][] = [
                'day_number' => (int) $row['day_number'],
                'title' => $row['day_title'],
                'description' => $row['day_description'] ?? null,
            ];
        }

        return $courses;
    }

    /**
     * @param  array<string, array{course: array, weeks: array<int, array{week: array, days: array}>}>  $groups
     */
    public function createCoursesFromGroups(array $groups): CourseImportResult
    {
        $result = new CourseImportResult;

        DB::transaction(function () use ($groups, $result) {
            foreach ($groups as $courseTitle => $group) {
                // Skip courses that already exist
                if (Course::query()->where('title', $courseTitle)->exists()) {
                    $result->skippedCourses[] = $courseTitle;

                    continue;
                }

                $weekCount = count($group['weeks']);
                $daysPerWeek = ! empty($group['weeks'])
                    ? count(reset($group['weeks'])['days'])
                    : 0;

                $course = Course::create([
                    'title' => $group['course']['title'],
                    'description' => $group['course']['description'],
                    'length_in_weeks' => $weekCount,
                    'min_age' => $group['course']['min_age'],
                    'max_age' => $group['course']['max_age'],
                ]);

                $result->coursesCreated++;

                foreach ($group['weeks'] as $weekData) {
                    $week = CoursePrompt::create([
                        'course_id' => $course->id,
                        'week_number' => $weekData['week']['week_number'],
                        'title' => $weekData['week']['title'],
                        'description' => $weekData['week']['description'],
                        'days_count' => count($weekData['days']),
                    ]);

                    $result->weeksCreated++;

                    foreach ($weekData['days'] as $dayData) {
                        CourseDay::create([
                            'course_prompt_id' => $week->id,
                            'day_number' => $dayData['day_number'],
                            'title' => $dayData['title'],
                            'description' => $dayData['description'],
                            'content_status' => ContentStatus::Pending,
                        ]);

                        $result->daysCreated++;
                    }
                }
            }
        });

        return $result;
    }
}
