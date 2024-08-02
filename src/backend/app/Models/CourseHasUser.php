<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseHasUser extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'course_id',
        'watched_classes',
    ];
    protected $table = 'course_has_user';
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    /**
     * Get all.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getAllCourseHasUsers()
    {
        return self::all();
    }
}
