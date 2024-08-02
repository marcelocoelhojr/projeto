<template>
    <header class="bg-indigo-600 text-white py-4">
      <div class="container mx-auto flex justify-between items-center">
        <div class="text-lg font-bold">XYZ</div>
        <div v-if="isAuthenticated"  class="text-lg font-bold">Welcome {{ user.name }}</div>
        <nav class="flex space-x-4">
            <router-link to="/" class="hover:underline">My Courses</router-link>
            <router-link to="/profile" class="hover:underline">Profile</router-link>
            <router-link to="/update" class="hover:underline">Update Profile</router-link>
            <router-link to="/courses" class="hover:underline">Courses</router-link>
            <button v-if="isAuthenticated" @click="handleLogout" class="hover:underline">Logout</button>
            <router-link v-if="isAdmin" to="/users" class="hover:underline">All Users</router-link>
            <router-link v-if="isAdmin" to="/admin/courses" class="hover:underline">Cursing Users</router-link>

        </nav>
      </div>
    </header>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue'
  import { useRouter } from 'vue-router'
  
  const router = useRouter()
  const user = JSON.parse(localStorage.getItem('user'))
  const isAdmin = (user && user.role === 'admin')
  const isAuthenticated = ref(!!localStorage.getItem('authToken'))
  const handleLogout = () => {
    localStorage.removeItem('authToken')
    localStorage.removeItem('userRole') 
    router.push({ name: 'login' }) 
  }
  if (!localStorage.getItem('authToken')) {
    router.push({
      name: 'index'
    });
  }
  </script>
  