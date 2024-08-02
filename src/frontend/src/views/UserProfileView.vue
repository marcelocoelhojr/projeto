<template>
    <div>
      <div class="container mx-auto py-10">
        <h1 class="text-2xl font-bold mb-4">User Profile</h1>
        <div v-if="user" class="bg-white p-6 rounded shadow-md">
          <p><strong>Name:</strong> {{ user.name }}</p>
          <p><strong>Email:</strong> {{ user.email }}</p>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue'
  import axios from 'axios'
  const user = ref(null)
  
  onMounted(() => {
    fetchUserInfo()
  })
  
  const fetchUserInfo = () => {
    axios.get('http://localhost/api/users/profile', {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('authToken')}`
      }
    })
    .then(response => {
      user.value = response.data.data
    })
    .catch(error => {
      console.error('Error fetching user info:', error.response?.data?.message || error.message)
    })
  }
  </script>
  
  <style scoped>
  .container {
    max-width: 600px;
  }
  </style>
  