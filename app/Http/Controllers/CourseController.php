<?php

namespace App\Http\Controllers;

use App\Models\CategoriesGrade;
use App\Models\Course;
use App\Services\Courses\CourseService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Inertia\Inertia;
use Inertia\Response;

class CourseController extends Controller
{
    public function __construct(private readonly CourseService $courseService)
    {
    }

    public function index(): Response
    {
        return Inertia::render('Teachers/Courses/Index', [
            'courses' => Course::paginate(12),
            'categories' => CategoriesGrade::all()->pluck('category')->sort()->all(),
            'lengths' => $this->courseService->getLengths(),
            'levels' => $this->courseService->getLevels(),
        ]);
    }

    public function show()
    {

    }

    public function enroll()
    {

    }

    public function filter(Request $request): LengthAwarePaginator
    {
        $categories = $request->input('categories');
        $lengths = $request->input('lengths');
        $levels = $request->input('levels');
        $search = $request->input('search');
        $searchBy = $request->input('searchBy');

        return Course::when(
            count($categories) > 0,
            static function (Builder $query) use ($categories) {
                $query
                    ->whereHas(
                        'categoryGrade',
                        fn(Builder $query) => $query->whereIn('category', $categories)
                    );
            }
        )
            ->when(
                count($lengths) > 0,
                static function (Builder $query) use ($lengths) {
                    $query->whereIn('length_in_weeks', $lengths);
                }
            )
            ->when(
                count($levels) > 0,
                static function (Builder $query) use ($levels) {
                    $query
                        ->whereHas(
                            'categoryGrade',
                            fn(Builder $query) => $query->whereIn('grade', $levels)
                        );
                }
            )
            ->when(
                strlen($search) > 3,
                static function (Builder $query) use ($search, $searchBy) {
                    switch ($searchBy) {
                        case 'Course':
                        {
                            $query
                                ->where('title', 'like', "%$search%")
                                ->orWhere('description', 'like', "%$search%");

                            break;
                        }

                        case 'Subject':
                        {
                            $query
                                ->whereHas(
                                    'categoryGrade',
                                    fn(Builder $query) => $query->where('category', 'like', "%$search%")
                                );

                            break;
                        }

                        case 'Topic':
                        {
                            $query
                                ->whereHas(
                                    'categoryGrade',
                                    fn(Builder $query) => $query->where('sub_category', 'like', "%$search%")
                                );

                            break;
                        }
                    }
                }
            )
            ->paginate(12);
    }
}
