<template>
  <div>
    <div class="container mx-auto py-10">
      <h1 class="text-2xl font-bold mb-4">Edit User</h1>
      <form @submit.prevent="handleUpdate" class="bg-white p-6 rounded shadow-md">
        <div class="mb-4">
          <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
          <input type="text" id="name" v-model="user.name" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
        </div>
        <div class="mb-4">
          <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
          <input type="email" id="email" v-model="user.email" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
        </div>
        <div class="mb-4">
          <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
          <input type="password" id="password" v-model="user.password" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
        </div>
        <button type="submit" class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
          Update User
        </button>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useRoute, useRouter } from 'vue-router'

const route = useRoute()
const router = useRouter()

const user = ref({
  name: '',
  email: '',
  password: ''
})

onMounted(() => {
  fetchUser()
})

const fetchUser = () => {
  axios.get(`http://localhost/api/users/${route.params.id}`, {
    headers: {
      Authorization: `Bearer ${localStorage.getItem('authToken')}`
    }
  })
  .then(response => {
    user.value = response.data.data
  })
  .catch(error => {
    console.error('Error fetching user:', error.response?.data?.message || error.message)
  })
}

const handleUpdate = () => {
  axios.put(`http://localhost/api/users/${route.params.id}`, user.value, {
    headers: {
      Authorization: `Bearer ${localStorage.getItem('authToken')}`
    }
  })
  .then(response => {
    alert('User updated successfully')
    router.push({ name: 'users' })
  })
  .catch(error => {
    console.error('Error updating user:', error.response?.data?.message || error.message)
    alert('Failed to update user')
  })
}
</script>

<style scoped>
.container {
  max-width: 800px;
}
</style>
