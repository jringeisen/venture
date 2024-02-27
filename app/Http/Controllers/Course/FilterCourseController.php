<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class FilterCourseController extends Controller
{
    public function __invoke(Request $request): LengthAwarePaginator
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
