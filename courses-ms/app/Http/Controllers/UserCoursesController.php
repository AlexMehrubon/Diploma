<?php

namespace App\Http\Controllers;

use App\Http\Integrations\UserMicroservice\UserMicroserviceConnector;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use App\Models\CourseUser;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserCoursesController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, UserMicroserviceConnector $connector): AnonymousResourceCollection
    {
        $token = $request->bearerToken();
        $connector->token = $token;
        $user = json_decode($connector->getCurrent()->body());
        $userCoursers =  CourseUser::where('user_id', $user->id)->get();
        return CourseResource::collection(Course::whereIn('id', $userCoursers->pluck('course_id'))->get());
    }
}
