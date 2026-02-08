<?php

use App\Enums\ContentStatus;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\UploadedFile;

beforeEach(function () {
    $this->admin = User::factory()->parent()->create(['email' => 'admin@learnwithventure.com']);
    config(['app.admin_emails' => [$this->admin->email]]);
});

function createCsvFile(array $rows, string $filename = 'courses.csv'): UploadedFile
{
    $headers = [
        'course_title',
        'course_description',
        'course_min_age',
        'course_max_age',
        'week_number',
        'week_title',
        'week_description',
        'day_number',
        'day_title',
        'day_description',
    ];

    $content = implode(',', $headers)."\n";

    foreach ($rows as $row) {
        $escapedRow = array_map(function ($value) {
            if (str_contains((string) $value, ',') || str_contains((string) $value, '"')) {
                return '"'.str_replace('"', '""', $value).'"';
            }

            return $value;
        }, $row);
        $content .= implode(',', $escapedRow)."\n";
    }

    return UploadedFile::fake()->createWithContent($filename, $content);
}

function sampleCourseRows(): array
{
    return [
        ['Intro to Science', 'A beginner course on science.', '5', '10', '1', 'The Scientific Method', 'Learn the steps.', '1', 'What is Science?', 'An introduction.'],
        ['Intro to Science', 'A beginner course on science.', '5', '10', '1', 'The Scientific Method', 'Learn the steps.', '2', 'Asking Questions', 'How to formulate.'],
        ['Intro to Science', 'A beginner course on science.', '5', '10', '2', 'States of Matter', 'Exploring solids.', '1', 'Solids', 'Properties of solids.'],
    ];
}

it('imports courses from a valid CSV', function () {
    $file = createCsvFile(sampleCourseRows());

    $response = $this->actingAs($this->admin)->post(route('admin.courses.import'), [
        'file' => $file,
    ]);

    $response->assertRedirect(route('admin.courses.index'));
    $response->assertSessionHas('success');

    $course = Course::query()->where('title', 'Intro to Science')->first();
    expect($course)->not->toBeNull();
    expect($course->length_in_weeks)->toBe(2);
    expect($course->coursePrompts)->toHaveCount(2);

    $week1 = $course->coursePrompts->where('week_number', 1)->first();
    expect($week1->title)->toBe('The Scientific Method');
    expect($week1->days_count)->toBe(2);
    expect($week1->days)->toHaveCount(2);

    $week2 = $course->coursePrompts->where('week_number', 2)->first();
    expect($week2->title)->toBe('States of Matter');
    expect($week2->days)->toHaveCount(1);
});

it('imports multiple courses from a single file', function () {
    $rows = [
        ...sampleCourseRows(),
        ['Math Basics', 'Learn math.', '6', '12', '1', 'Addition', 'Adding numbers.', '1', 'Single Digits', 'Adding single digits.'],
        ['Math Basics', 'Learn math.', '6', '12', '1', 'Addition', 'Adding numbers.', '2', 'Double Digits', 'Adding double digits.'],
    ];

    $file = createCsvFile($rows);

    $this->actingAs($this->admin)->post(route('admin.courses.import'), [
        'file' => $file,
    ]);

    expect(Course::query()->where('title', 'Intro to Science')->exists())->toBeTrue();
    expect(Course::query()->where('title', 'Math Basics')->exists())->toBeTrue();
});

it('skips courses with duplicate titles', function () {
    Course::factory()->create(['title' => 'Intro to Science']);

    $file = createCsvFile(sampleCourseRows());

    $response = $this->actingAs($this->admin)->post(route('admin.courses.import'), [
        'file' => $file,
    ]);

    $response->assertRedirect(route('admin.courses.index'));
    $response->assertSessionHas('success');

    expect(Course::query()->where('title', 'Intro to Science')->count())->toBe(1);
});

it('validates required columns exist in headers', function () {
    $content = "wrong_header,another_header\nval1,val2\n";
    $file = UploadedFile::fake()->createWithContent('bad.csv', $content);

    $response = $this->actingAs($this->admin)->post(route('admin.courses.import'), [
        'file' => $file,
    ]);

    $response->assertRedirect(route('admin.courses.index'));
    $response->assertSessionHas('error');
});

it('validates required fields per row', function () {
    $rows = [
        ['', 'A description.', '5', '10', '1', 'Week Title', 'Week desc.', '1', 'Day Title', 'Day desc.'],
    ];

    $file = createCsvFile($rows);

    $response = $this->actingAs($this->admin)->post(route('admin.courses.import'), [
        'file' => $file,
    ]);

    $response->assertRedirect(route('admin.courses.index'));
    $response->assertSessionHas('error');
});

it('validates unique day numbers within a week', function () {
    $rows = [
        ['Duplicate Days', 'A course.', '5', '10', '1', 'Week One', 'First week.', '1', 'Day One', 'First day.'],
        ['Duplicate Days', 'A course.', '5', '10', '1', 'Week One', 'First week.', '1', 'Day One Again', 'Duplicate.'],
    ];

    $file = createCsvFile($rows);

    $response = $this->actingAs($this->admin)->post(route('admin.courses.import'), [
        'file' => $file,
    ]);

    $response->assertRedirect(route('admin.courses.index'));
    $response->assertSessionHas('error');
});

it('rejects non-CSV/XLSX files', function () {
    $file = UploadedFile::fake()->create('document.pdf', 100);

    $response = $this->actingAs($this->admin)->post(route('admin.courses.import'), [
        'file' => $file,
    ]);

    $response->assertSessionHasErrors('file');
});

it('requires admin access', function () {
    config(['app.admin_emails' => ['other@test.com']]);

    $user = User::factory()->parent()->create();

    $file = createCsvFile(sampleCourseRows());

    $response = $this->actingAs($user)->post(route('admin.courses.import'), [
        'file' => $file,
    ]);

    $response->assertForbidden();
});

it('handles empty file with headers only', function () {
    $file = createCsvFile([]);

    $response = $this->actingAs($this->admin)->post(route('admin.courses.import'), [
        'file' => $file,
    ]);

    $response->assertRedirect(route('admin.courses.index'));
    $response->assertSessionHas('error');
});

it('sets correct min_age and max_age', function () {
    $file = createCsvFile(sampleCourseRows());

    $this->actingAs($this->admin)->post(route('admin.courses.import'), [
        'file' => $file,
    ]);

    $course = Course::query()->where('title', 'Intro to Science')->first();
    expect($course->min_age)->toBe(5);
    expect($course->max_age)->toBe(10);
});

it('sets content_status to pending on days', function () {
    $file = createCsvFile(sampleCourseRows());

    $this->actingAs($this->admin)->post(route('admin.courses.import'), [
        'file' => $file,
    ]);

    $course = Course::query()->where('title', 'Intro to Science')->first();
    $days = $course->coursePrompts->flatMap->days;

    $days->each(function ($day) {
        expect($day->content_status)->toBe(ContentStatus::Pending);
    });
});

it('downloads CSV template successfully', function () {
    $response = $this->actingAs($this->admin)->get(route('admin.courses.import-template'));

    $response->assertSuccessful();
    $response->assertHeader('content-type', 'text/csv; charset=utf-8');
    expect($response->streamedContent())->toContain('course_title');
});
