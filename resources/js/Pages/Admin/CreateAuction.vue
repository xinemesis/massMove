<template>
  <div class="container">
    <h2 class="text-center my-4">Nueva Subasta</h2>
    
    <form @submit.prevent="submitAuction">
      <div class="mb-3">
        <label class="form-label">Nombre del Auto</label>
        <input v-model="auction.name" type="text" class="form-control" required />
      </div>
      <div class="mb-3">
        <label class="form-label">Precio Inicial</label>
        <input v-model="auction.starting_price" type="number" class="form-control" required />
      </div>
      <div class="mb-3">
        <label class="form-label">Fecha de Cierre</label>
        <input v-model="auction.end_time" type="datetime-local" class="form-control" required />
      </div>
      <button type="submit" class="btn btn-primary">Guardar Subasta</button>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

const auction = ref({
  name: '',
  starting_price: '',
  end_time: ''
});

// 🔹 Función para enviar la subasta al backend
const submitAuction = async () => {
  try {
    const response = await fetch('/api/auctions', {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "Accept": "application/json",
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
        "Authorization": `Bearer ${localStorage.getItem('token')}`
      },
      body: JSON.stringify(auction.value)
    });

    const result = await response.json();

    if (response.ok) {
      alert("Subasta creada con éxito!");
      router.visit('/admin/auctions'); // ✅ Mantiene el uso correcto de router
    } else {
      alert("Error: " + result.message);
    }
  } catch (error) {
    console.error("Error al crear la subasta:", error);
  }
};
</script>
