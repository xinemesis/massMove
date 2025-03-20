<template>
    <div class="container">
      <h2>Prueba de Pusher en Vue</h2>
      <p>{{ message }}</p>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  
  const message = ref('Esperando eventos...');
  
  onMounted(() => {
      window.Echo.channel('test-channel').listen('TestPusherEvent', (event) => {
          console.log('Evento recibido:', event);
          message.value = event.message;
      });
  });
  </script>