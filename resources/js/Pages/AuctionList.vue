<template>

  <Head title="Subastas en Tiempo Real" />
  <AuthenticatedLayout>
    <div class="container">

      <!-- 🔹 Spinner de carga -->
      <div v-if="loading" class="text-center my-4">
        <div class="spinner-border text-primary" role="status">
          <span class="visually-hidden">Cargando...</span>
        </div>
      </div>

      <!-- 🔹 Notificaciones -->
      <div v-if="notifications.length" class="alert-container">
        <div v-for="(notification, index) in notifications" :key="index" :class="`alert alert-${notification.type}`">
          {{ notification.message }}
        </div>
      </div>

      <!-- 🔹 Distribución con Tarjetas -->
      <div v-if="!loading" class="row">
        <div v-for="car in cars" :key="car.id" class="col-md-4 mb-4">
          <div class="card shadow-sm">
            <div class="card-body">
              <h5 class="card-title">{{ car.name }}</h5>
              <p class="card-text">
                <strong>Puja actual:</strong>
                <span class="text-success">${{ car.current_bid ?? 0 }}</span>
              </p>
              <p class="card-text text-muted">
                Tiempo restante: <span class="text-danger">{{ car.timeLeft ?? "Cargando..." }}</span>
              </p>

              <input v-model="car.newBid" type="number" class="form-control mb-2" placeholder="Ingrese su puja" />
              <button class="btn btn-success w-100" @click="placeBid(car.id, car.newBid)">
                Pujar <i class="bi bi-currency-dollar"></i>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';
import { notifications } from '@/Services/notificationService';
import { cars, fetchAuctions, placeBid, startCountdown, listenForBids, listenForNewAuctions } from '@/Services/auctionService';

const loading = ref(true);

onMounted(async () => {
  try {
    await fetchAuctions();
    loading.value = false;
    startCountdown();
    listenForBids();
    listenForNewAuctions();
  } catch (error) {
    console.error("Error en AuctionList.vue:", error);
  }
});

onUnmounted(() => {
  if (intervalId) clearInterval(intervalId);
});
</script>