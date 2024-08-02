<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
      <h2 class="text-2xl font-bold mb-6 text-center">Login</h2>
      <form @submit.prevent="handleSubmit">
        <div class="mb-4">
          <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
          <input 
            type="email" 
            id="email" 
            v-model="credentials.email" 
            required 
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
          />
        </div>
        <div class="mb-6">
          <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
          <input 
            type="password" 
            id="password" 
            v-model="credentials.password" 
            required 
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
          />
        </div>
        <button 
          type="submit" 
          class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        >
          Login
        </button>
        <p class="mt-4 text-center">
          <router-link 
            to="/signup" 
            class="text-indigo-600 hover:text-indigo-700"
          >
            Don't have an account? Sign up
          </router-link>
        </p>
      </form>
    </div>
  </div>
</template>

<script setup>  
import axios from 'axios'
import { onMounted, reactive, ref} from 'vue'
import { useRouter } from 'vue-router';

const router = useRouter()

const credentials = reactive({
    email: '',
    password: '',
    error: null
})

onMounted(() => {
  if (localStorage.getItem('authToken')) {
    router.push({
      name: 'dashboard'
    });
  }
})

const handleSubmit = () => {
    axios.post('http://localhost/api/auth/login', {
          email: credentials.email,
          password: credentials.password
    })
          .then((response) => {
            console.log('Login successful:', response.data);

            const { token, user } = response.data;
            localStorage.setItem('authToken', token);
            localStorage.setItem('user', JSON.stringify(user));
            localStorage.setItem('UserRole', user.role);
            router.push({
                name: 'dashboard'
            });
          })
          .catch((error) => {
            console.error('Login failed:', error.response?.data?.message || error.message);
            alert(error.response?.data?.message || 'Login failed');
          })
}

</script>
