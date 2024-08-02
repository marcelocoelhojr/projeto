<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white p-8 rounded shadow-md w-full max-w-3xl">
      <div>
        <h1 class="text-2xl font-bold mb-6 text-center">Courses</h1>
        <div class="space-y-4">
          <div
            v-for="(course, index) in courses"
            :key="index"
            class="bg-gray-100 p-6 rounded-lg shadow-md"
          >
            <h2 class="text-lg font-semibold mb-2">{{ course.course_name }}</h2>
            <p class="text-gray-700 mb-2">{{ course.course_description }}</p>
            <p class="text-gray-500 mb-2">{{ course.course_hours }} hours - {{ course.course_classes }} classes</p>
            <p class="text-gray-500 mb-2">Watched Classes: {{ course.watched_classes }}</p>
            <div class="w-full bg-gray-200 rounded-full h-4 mb-4">
              <div class="bg-green-500 h-4 rounded-full" :style="{ width: `${(course.watched_classes / course.course_classes) * 100}%` }"></div>
            </div>
            <div class="flex justify-between space-x-2 mt-4">
              <button
                @click="confirmLeaveCourse(course.id)"
                class="bg-red-500 text-white font-bold py-2 px-4 rounded hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50"
              >
                Leave Course
              </button>
              <button
                @click="showAddWatchedClasses(course.course_id)"
                class="bg-green-500 text-white font-bold py-2 px-4 rounded hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50"
              >
                Add Watched Classes
              </button>
              <router-link
                v-if="isAdmin"
                :to="{ name: 'updateCourse', params: { id: course.course_id }}"
                class="bg-blue-500 text-white font-bold py-2 px-4 rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50"
              >
                Edit
              </router-link>
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
const isAdmin = (user && user.role === 'admin');

const fetchCourses = async () => {
  try {
    const response = await axios.get('http://localhost/api/user/courses', {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('authToken')}`
      }
    });
    courses.value = response.data;
  } catch (error) {
    console.error('Error fetching courses:', error.response?.data?.message || error.message);
  }
};

const confirmLeaveCourse = (courseId) => {
  if (confirm('Are you sure you want to leave this course?')) {
    leaveCourse(courseId);
  }
};

const leaveCourse = async (courseId) => {
  try {
    await axios.delete(`http://localhost/api/user/courses/${courseId}`, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('authToken')}`
      }
    });
    fetchCourses(); // Refresh the course list
  } catch (error) {
    console.error('Error leaving course:', error.response?.data?.message || error.message);
  }
};

const showAddWatchedClasses = (courseId) => {
  const watchedClasses = prompt('Enter the number of watched classes:');
  if (watchedClasses && !isNaN(watchedClasses) && watchedClasses > 0) {
    addWatchedClasses(courseId, parseInt(watchedClasses, 10));
  }
};

const addWatchedClasses = async (courseId, watchedClasses) => {
  try {
    await axios.post(`http://localhost/api/user/courses/${courseId}/watched`, { watched_classes: watchedClasses }, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('authToken')}`
      }
    });
    fetchCourses(); // Refresh the course list
  } catch (error) {
    console.error('Error adding watched classes:', error.response?.data?.message || error.message);
  }
};


onMounted(() => {
  fetchCourses();
});
</script>
