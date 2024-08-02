<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white p-8 rounded shadow-md w-full max-w-3xl">
      <h1 class="text-2xl font-bold mb-6 text-center">All User Courses (Admin)</h1>
      <div class="space-y-4">
        <div v-for="course in courses" :key="course.course_id" class="bg-gray-100 p-6 rounded-lg shadow-md">
          <h2 class="text-lg font-semibold mb-2">{{ course.course_name }}</h2>
          <p class="text-gray-700 mb-2">{{ course.description }}</p>
          <p class="text-gray-500 mb-2">{{ course.course_hours }} hours - {{ course.course_classes }} classes</p>
          <p class="text-gray-500 mb-2">{{ course.user_name }} - {{ course.watched_classes }} watched classes</p>
          <div class="w-full bg-gray-200 rounded-full h-4 mb-4">
            <div class="bg-green-500 h-4 rounded-full" :style="{ width: `${(course.watched_classes / course.course_classes) * 100}%` }"></div>
          </div>
          <button 
            @click="removeUserFromCourse(course.id)" 
            class="bg-red-500 text-white font-bold py-2 px-4 rounded hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50"
          >
            Remove User from Course
          </button>
          
          <div class="mt-4">
            <input 
              v-model="newWatchedClasses[course.id]" 
              type="number" 
              min="0" 
              class="border border-gray-300 rounded-md py-2 px-4 w-full mb-2" 
              :placeholder="'Watched Classes for ' + course.course_name"
            />
            <button 
              @click="updateWatchedClasses(course.id)" 
              class="bg-blue-500 text-white font-bold py-2 px-4 rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50"
            >
              Update Watched Classes
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const courses = ref([]);
const newWatchedClasses = ref({});
const user = JSON.parse(localStorage.getItem('user'));
const authToken = localStorage.getItem('authToken');

const fetchCourses = async () => {
  try {
    const response = await axios.get('http://localhost/api/admin/courses', {
      headers: {
        Authorization: `Bearer ${authToken}`
      }
    });
    courses.value = response.data;
  } catch (error) {
    console.error('Failed to fetch courses:', error);
  }
};

const removeUserFromCourse = async (id) => {
  try {
    await axios.delete(`http://localhost/api/user/courses/${id}`, {
      headers: {
        Authorization: `Bearer ${authToken}`
      }
    });
    fetchCourses();
  } catch (error) {
    console.error('Failed to remove user from course:', error);
  }
};

const updateWatchedClasses = async (courseId) => {
  try {
    const watchedClasses = newWatchedClasses.value[courseId];
    if (watchedClasses !== undefined && watchedClasses !== '') {
      await axios.post(`http://localhost/api/courses/admin/${courseId}/watched`, {
        watched_classes: parseInt(watchedClasses, 10)
      }, {
        headers: {
          Authorization: `Bearer ${authToken}`
        }
      });
      fetchCourses();
    } else {
      alert('Please enter a valid number of watched classes.');
    }
  } catch (error) {
    console.error('Failed to update watched classes:', error);
  }
};

onMounted(fetchCourses);
</script>
