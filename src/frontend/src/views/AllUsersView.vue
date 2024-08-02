<template>
  <div>
    <div class="container mx-auto py-10">
      <h1 class="text-2xl font-bold mb-4">All Users</h1>
      <table class="min-w-full bg-white">
        <thead>
          <tr>
            <th class="py-2 px-4 border-b border-gray-200">Name</th>
            <th class="py-2 px-4 border-b border-gray-200">Email</th>
            <th class="py-2 px-4 border-b border-gray-200">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="user in users" :key="user.id">
            <td class="py-2 px-4 border-b border-gray-200">{{ user.name }}</td>
            <td class="py-2 px-4 border-b border-gray-200">{{ user.email }}</td>
            <td class="py-2 px-4 border-b border-gray-200">
              <router-link :to="`/edit-user/${user.id}`" class="bg-blue-500 text-white font-bold py-2 px-4 rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">Edit</router-link>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const users = ref([])

onMounted(() => {
  fetchUsers()
})

const fetchUsers = () => {
  axios.get('http://localhost/api/users', {
    headers: {
      Authorization: `Bearer ${localStorage.getItem('authToken')}`
    }
  })
  .then(response => {
    users.value = response.data.data
  })
  .catch(error => {
    console.error('Error fetching users:', error.response?.data?.message || error.message)
  })
}
</script>

<style scoped>
.container {
  max-width: 800px;
}
</style>
