<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Models\CourseHasUser;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CourseHasUserController extends Controller
{
    /**
     * Shows all CourseHasUser relations for admin
     * @return Collection
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->role === 'admin') {
             
            $coursesWithDetails = CourseHasUser::whereRaw('1 = 1')
            ->with('course', 'user')  // Eager load related course data
            ->get()
            ->map(function ($courseHasUser) {
                return [
                    'id' => $courseHasUser->id,
                    'user_name' => $courseHasUser->user->name,
                    'course_id' => $courseHasUser->course->id,
                    'course_name' => $courseHasUser->course->name,
                    'course_description' => $courseHasUser->course->description,
                    'course_hours' => $courseHasUser->course->hours,
                    'course_classes' => $courseHasUser->course->classes,
                    'watched_classes' => $courseHasUser->watched_classes,
                ];
            });
    
        return response()->json($coursesWithDetails);
        }
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    /**
     * Shows CourseHasUser relations for the authenticated user
     * @return CourseHasUser[]
     */
    public function userCourses()
    {
        $userId = Auth::id();
    
        // Fetch all courses with related course details
        $coursesWithDetails = CourseHasUser::where('user_id', $userId)
            ->with('course')  // Eager load related course data
            ->get()
            ->map(function ($courseHasUser) {
                return [
                    'id' => $courseHasUser->id,
                    'course_id' => $courseHasUser->course->id,
                    'course_name' => $courseHasUser->course->name,
                    'course_description' => $courseHasUser->course->description,
                    'course_hours' => $courseHasUser->course->hours,
                    'course_classes' => $courseHasUser->course->classes,
                    'watched_classes' => $courseHasUser->watched_classes,
                ];
            });
    
        return response()->json($coursesWithDetails);
    }

    /**
     * Registers a user in a course
     * @param Request $request
     * @return CourseHasUser
     */
    public function store(Request $request)
    {
        $alreadyRegistered = CourseHasUser::where(['user_id' => $request->user['id'], 'course_id' => $request->course_id])->count();

        if ($alreadyRegistered) {
            return response()->json('User already in', 400);
        }

        $data = $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);
        $data['user_id'] = Auth::id();
        $data['watched_classes'] = 0;
        
        $courseHasUser = CourseHasUser::create($data);
        return response()->json($courseHasUser, 201);
    }

    /**
     * Updates watched classes for a CourseHasUser
     * @param Request $request, $id
     * @return CourseHasUser
     */
    public function update(Request $request, $id)
    {
        $courseHasUser = CourseHasUser::findOrFail($id);
        if ($courseHasUser->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'watched_classes' => 'required|integer|min:0',
        ]);

        if($request->watched_classes == $courseHasUser->course->classes) {
            $data['watched_classes'] = $courseHasUser->course->classes;
        }
        $courseHasUser->update($data);
        return response()->json($courseHasUser);
    }

    /**
     * Removes a user from a course
     * @param $id
     * @return bool
     */
    public function destroy($id)
    {
        $courseHasUser = CourseHasUser::findOrFail($id);
        if ($courseHasUser->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $courseHasUser->delete();
        return response()->json(['message' => 'Course removed successfully']);
    }
    /**
     * Add watched classes to a course for the authenticated user
     * @param Request $request
     * @param int $courseId
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeWatchedClasses(Request $request, $courseHasUserId)
    {

        $courseHasUser = CourseHasUser::where(['id' => $courseHasUserId])->with('course')->first();
        $courseHasUser->watched_classes = $request->watched_classes;

        if($courseHasUser->watched_classes >= $courseHasUser->course->classes) {
            $courseHasUser->watched_classes  = $courseHasUser->course->classes;
        }

        $courseHasUser->save();

        return response()->json([
            'status' => true,
            'message' => 'Watched classes updated successfully.',
            'data' => $courseHasUser
        ], 200);
    }
    /**
     * Add watched classes to a course for the authenticated user
     * @param Request $request
     * @param int $courseId
     * @return \Illuminate\Http\JsonResponse
     */
    public function addWatchedClasses(Request $request, $courseId)
    {
        $user = Auth::user();

        // Validate the request
        $validated = $request->validate([
            'watched_classes' => 'required|integer|min:1'
        ]);

        // Find the course for the authenticated user
        $courseHasUser = CourseHasUser::where('user_id', $user->id)
            ->where('course_id', $courseId)
            ->first();

        if (!$courseHasUser) {
            return response()->json([
                'status' => false,
                'message' => 'Course not found for this user.'
            ], 404);
        }

        // Update watched classes
        $courseHasUser->watched_classes += $validated['watched_classes'];

        if($courseHasUser->watched_classes >= $courseHasUser->course->classes) {
            $courseHasUser->watched_classes  = $courseHasUser->course->classes;
        }

        $courseHasUser->save();

        return response()->json([
            'status' => true,
            'message' => 'Watched classes updated successfully.',
            'data' => $courseHasUser
        ], 200);
    }
}
