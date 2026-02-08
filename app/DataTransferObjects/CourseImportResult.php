<?php

namespace App\DataTransferObjects;

class CourseImportResult
{
    public function __construct(
        public int $coursesCreated = 0,
        public int $weeksCreated = 0,
        public int $daysCreated = 0,
        public array $skippedCourses = [],
        public array $validationErrors = [],
    ) {}

    public function hasErrors(): bool
    {
        return count($this->validationErrors) > 0;
    }

    public function toFlashMessage(): string
    {
        $parts = [];

        if ($this->coursesCreated > 0) {
            $parts[] = "{$this->coursesCreated} course(s)";
        }

        if ($this->weeksCreated > 0) {
            $parts[] = "{$this->weeksCreated} week(s)";
        }

        if ($this->daysCreated > 0) {
            $parts[] = "{$this->daysCreated} day(s)";
        }

        $message = 'Successfully imported '.implode(', ', $parts).'.';

        if (count($this->skippedCourses) > 0) {
            $message .= ' Skipped '.count($this->skippedCourses).' course(s) that already exist: '.implode(', ', $this->skippedCourses).'.';
        }

        return $message;
    }
}
