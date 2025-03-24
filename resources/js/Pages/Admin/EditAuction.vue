<template>
    <div class="container">
      <h2 class="text-center my-4">Editar Subasta</h2>
      
      <form @submit.prevent="updateAuction">
        <div class="mb-3">
          <label class="form-label">Nombre del Auto</label>
          <input v-model="auction.name" type="text" class="form-control" required />
        </div>
        <div class="mb-3">
          <label class="form-label">Precio Inicial</label>
          <input v-model="auction.starting_price" type="number" class="form-control" required />
        </div>
        <button type="submit" class="btn btn-success">Actualizar</button>
      </form>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  import { usePage, router } from '@inertiajs/vue3';
  
  const page = usePage();
  const auctionId = page.props.auctionId; // 🔹 Obtener ID de la subasta
  const auction = ref({
    name: '',
    starting_price: ''
  });
  
  // 🔹 Cargar los datos de la subasta al montar el componente
  onMounted(async () => {
    try {
      const response = await fetch(`/api/auctions/${auctionId}`);
      const data = await response.json();
      auction.value = {
        name: data.name,
        starting_price: data.starting_price
      };
    } catch (error) {
      console.error("Error al cargar la subasta:", error);
    }
  });
  
  // 🔹 Función para actualizar la subasta
  const updateAuction = async () => {
    try {
      const response = await fetch(`/api/auctions/${auctionId}`, {
        method: "PUT",
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
        alert("Subasta actualizada con éxito!");
        router.visit('/admin/auctions'); // 🔹 Redirigir a la lista de subastas
      } else {
        alert("Error: " + result.message);
      }
    } catch (error) {
      console.error("Error al actualizar la subasta:", error);
    }
  };
  </script>
  