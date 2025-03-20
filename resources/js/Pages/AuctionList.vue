<template>
  <div class="container">
    <h2 class="text-center my-4">Subastas en Tiempo Real</h2>
    <table class="table table-bordered">
      <thead class="table-dark">
        <tr>
          <th>Auto</th>
          <th>Puja Actual</th>
          <th>Tiempo Restante</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="car in cars" :key="car.id">
          <td>{{ car.name }}</td>
          <td>${{ car.current_bid }}</td>
          <td>{{ calculateTimeLeft(car.end_time) }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
  
  <script setup>
import { ref, onMounted } from 'vue';

const cars = ref([]);
const userId = ref(null); // 🔹 Variable para almacenar el ID del usuario autenticado
const calculateTimeLeft = (endTime) => {
    const diff = new Date(endTime) - new Date();
    return diff > 0 ? Math.floor(diff / 1000) + " segundos" : "Finalizado";
};

onMounted(async () => {
    try {
        // 🔹 Obtener el usuario autenticado
        const userResponse = await fetch('http://127.0.0.1:8000/api/user', {
            headers: { 'Authorization': `Bearer ${localStorage.getItem('token')}` }
        });

        if (userResponse.ok) {
            const userData = await userResponse.json();
            userId.value = userData.id;
        }

        // 🔹 Cargar las subastas desde la API
        const response = await fetch('http://127.0.0.1:8000/api/auctions');
        cars.value = await response.json();

        // 🔹 Escuchar eventos en tiempo real para actualizar la puja
        window.Echo.channel('auctions').listen('NewBidPlaced', (event) => {
            const auction = cars.value.find(car => car.id === event.id);
            if (auction) {
                auction.current_bid = event.current_bid;
            }
        });

        // 🔹 Escuchar notificaciones privadas para el usuario
        if (userId.value) {
            window.Echo.private(`user.${userId.value}`).notification((notification) => {
                alert(notification.message);
            });
        }
    } catch (error) {
        console.error('Error al cargar datos:', error);
    }
});
</script>