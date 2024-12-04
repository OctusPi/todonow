<template>
  <footer class="container mx-auto px-4 my-4 text-center md:text-end">
    <p class="text-xs text-gray-500">
      {{ sysapp.name }} - Todos os direiros reservados {{ sysapp.copy }} &copy;
    </p>
  </footer>
</template>
<script setup>
import { inject } from 'vue';

const sysapp = inject('sysapp')
</script>
