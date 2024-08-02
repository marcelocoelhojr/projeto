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
              v-model="email" 
              required 
              class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
            />
          </div>
          <div class="mb-6">
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <input 
              type="password" 
              id="password" 
              v-model="password" 
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
  
  <script>  
  export default {
    data() {
      return {
        email: '',
        password: '',
        error: null
      }
    },
    methods: {
      async handleSubmit() {
        try {
          const response = await axios.post('http://localhost/api/auth/login', {
            email: this.email,
            password: this.password
          });
  
          // Handle successful login
          console.log('Login successful:', response.data);
          const token = response.data.token;
          localStorage.setItem('authToken', token);

          // You might want to store the token or navigate to a different route
          this.$router.push('/dashboard'); // Redirect to a different page after login
        } catch (error) {
          console.error('Login failed:', error.response?.data?.message || error.message);
          this.error = error.response?.data?.message || 'Login failed';
        }
      }
    }
  }
  </script>
