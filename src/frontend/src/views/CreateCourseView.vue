<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
    <div>
      <h1 class="text-2xl font-bold mb-6">Create Course</h1>
      <form @submit.prevent="createCourse">
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700">Name</label>
          <input v-model="form.name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required />
        </div>
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700">Description</label>
          <input v-model="form.description" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required />
        </div>
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700">Hours</label>
          <input v-model="form.hours" type="number" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required />
        </div>
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700">Classes</label>
          <input v-model="form.classes" type="number" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required />
        </div>
        <button type="submit" class="bg-indigo-600 text-white py-2 px-4 rounded">Create</button>
      </form>
    </div>
  </div>
</div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import axios from 'axios';
  import { useRouter } from 'vue-router';
  
  const router = useRouter();
  
  const form = ref({
    name: '',
    description: '',
    hours: 0,
    classes: 0
  });
  
  const createCourse = async () => {
    await axios.post('http://localhost/api/courses', form.value, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('authToken')}`
      }
    });
    router.push('/courses');
  };
  </script>
  