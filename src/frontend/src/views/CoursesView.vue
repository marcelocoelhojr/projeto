<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
      <div class="bg-white p-8 rounded shadow-md w-full max-w-3xl">
        <div>
          <h1 class="text-2xl font-bold mb-6 text-center">Courses</h1>
          <div class="space-y-4">
            <div
              v-for="course in courses"
              :key="course.id"
              class="bg-gray-100 p-6 rounded-lg shadow-md"
            >
              <h2 class="text-lg font-semibold mb-2">{{ course.name }}</h2>
              <p class="text-gray-700 mb-2">{{ course.description }}</p>
              <p class="text-gray-500 mb-2">{{ course.hours }} hours - {{ course.classes }} classes</p>
              <div class="flex justify-between space-x-2">
                <div class="flex space-x-2">
                  <button
                    v-if="isAdmin"
                    @click="deleteCourse(course.id)"
                    class="bg-blue-500 text-white font-bold py-2 px-4 rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50"
                  >
                    Delete
                  </button>
                  <router-link
                    v-if="isAdmin"
                    :to="{ name: 'updateCourse', params: { id: course.id }}"
                    class="bg-blue-500 text-white font-bold py-2 px-4 rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50"
                  >
                    Edit
                  </router-link>
                </div>
                <button
                  v-if="!isEnrolled(course.id)"
                  @click="enrollCourse(course.id)"
                  class="bg-green-500 text-white font-bold py-2 px-4 rounded hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50"
                >
                  Enroll
                </button>
              </div>
            </div>
          </div>
          <div class="mt-6 flex justify-end">
            <router-link
              v-if="isAdmin"
              to="/courses/create"
              class="bg-blue-500 text-white font-bold py-2 px-4 rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50"
            >
              Add New Course
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </template>
<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const courses = ref([]);
const user = JSON.parse(localStorage.getItem('user'));
const authToken = localStorage.getItem('authToken');
const isAdmin = (user && user.role === 'admin');

const fetchCourses = async () => {
  try {
    const response = await axios.get('http://localhost/api/courses', {
      headers: {
        Authorization: `Bearer ${authToken}`
      }
    });
    courses.value = response.data;
  } catch (error) {
    console.error('Failed to fetch courses:', error);
  }
};

const deleteCourse = async (id) => {
  try {
    await axios.delete(`http://localhost/api/courses/${id}`, {
      headers: {
        Authorization: `Bearer ${authToken}`
      }
    });
    fetchCourses(); // Refresh the course list
  } catch (error) {
    console.error('Failed to delete course:', error);
  }
};

const enrollCourse = async (courseId) => {
  try {
    await axios.post('http://localhost/api/user/courses', {
      course_id: courseId,
      user: user
    }, {
      headers: {
        Authorization: `Bearer ${authToken}`
      }
    });
    fetchCourses(); 
  } catch (error) {
    console.error('Failed to enroll in course:', error);
    alert(error.response.data);
  }
};

const isEnrolled = (courseId) => {
  return courses.value.some(course => course.id === courseId && course.user_id === user.id);
};

onMounted(() => {
  fetchCourses();
});
</script>
  