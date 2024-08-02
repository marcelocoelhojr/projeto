<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;

class CourseController extends Controller
{
     /**
     * List all Courses
     * @param none
     * @return \Response 
     */   
   public function index()
   {
       $courses = Course::all();
       return response()->json($courses);
   }

     /**
     * List all Courses
     * @param int $id
     * @return \Response 
     */   
    public function show($id)
    {
       $course = Course::find($id);
       if ($course) {
           return response()->json($course);
       } else {
           return response()->json(['message' => 'Course not found'], 404);
       }
   }

     /**
     * Saves Course
     * @param Request $request
     * @return \Response 
     */
    public function store(Request $request)
    {
       $this->validate($request, [
           'name' => 'required|string|max:255',
           'description' => 'required|string',
           'hours' => 'required|integer',
           'classes' => 'required|integer',
       ]);

       $course = Course::create($request->all());
       return response()->json($course, 201);
    }

     /**
     * Updates Course
     * @param Request $request
     * @param integer $id
     * @return \Response 
     */   
    public function update(Request $request, $id)
    {
       $this->validate($request, [
           'name' => 'string|max:255',
           'description' => 'string',
           'hours' => 'integer',
           'classes' => 'integer',
       ]);

       $course = Course::find($id);
       if ($course) {
           $course->update($request->all());
           return response()->json($course);
       } else {
           return response()->json(['message' => 'Course not found'], 404);
       }
    }

     /**
     * Removes Course
     * @param integer $id
     * @return \Response 
     */   public function destroy($id)
    {
       $course = Course::find($id);
       if ($course) {
           $course->delete();
           return response()->json(['message' => 'Course deleted']);
       } else {
           return response()->json(['message' => 'Course not found'], 404);
       }
    }
}
