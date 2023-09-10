<script setup>
import { ref, h } from 'vue';
import { usePage } from '@inertiajs/vue3';
import Swal from 'sweetalert2';
defineProps({
  id: {
    type: Number
  },
  code: {
    type: String
  }
});



const redirect = (code) => {
  location.href = "/crypto/" + code;
}
</script>

<template>
  <button @click="redirect(code)"
    class="inline-flex items-center px-3 py-1 text-xs  text-gray-900 bg-green-300 border border-gray-200 rounded hover:bg-green-500 hover:text-white focus:z-10 focus:ring-2 focus:ring-blue-400 focus:text-blue-700 ">
    View
  </button>
</template>