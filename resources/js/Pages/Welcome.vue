<template>
    <div class="min-h-screen flex flex-col items-center justify-center bg-gray-100">
      <Head title="Bienvenido" />
  
      <h1 class="text-3xl font-bold">Bienvenido a la plataforma de subastas</h1>
      <p class="text-lg text-gray-700">Explora y participa en nuestras subastas en tiempo real.</p>
  
      <div v-if="canLogin" class="mt-4">
        <Link href="/dashboard" class="text-blue-600 hover:underline">Ir al Dashboard</Link>
      </div>
    </div>
  </template>
  
  <script setup>
  import { Head, Link } from '@inertiajs/vue3';
  
  defineProps({
      canLogin: Boolean,
      canRegister: Boolean,
      laravelVersion: String,
      phpVersion: String,
  });
  </script>