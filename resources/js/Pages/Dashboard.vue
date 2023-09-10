<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
defineProps({
  checkIfAdmin: {
    type: Boolean
  }
});

</script>

<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout :checkIfAdmin=checkIfAdmin>
    <template #header>
      <h3 class="font-semibold text-sm text-gray-800 leading-tight">Dashboard</h3>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">You're logged in!</div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
