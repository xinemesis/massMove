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

      <!-- 🔹 Nueva Distribución con Tarjetas -->
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
                ⏳ Tiempo restante: <span class="text-danger">{{ car.timeLeft ?? "Cargando..." }}</span>
              </p>

              <input v-model="car.newBid" type="number" class="form-control mb-2" placeholder="Ingrese su puja" />
              <button class="btn btn-success w-100" @click="placeBid(car.id, car.newBid)">
                Pujar <i class="bi bi-currency-dollar"></i>
              </button>

              <button class="btn btn-outline-info w-100 mt-2" @click="openHistoryModal(car.id, fetchBidHistory)">
                Ver Historial <i class="bi bi-clock-history"></i>
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal de Historial de Pujas -->
      <div class="modal fade" id="bidHistoryModal" tabindex="-1" aria-hidden="true" ref="modal">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Historial de Pujas</h5>
              <button type="button" class="btn-close" @click="closeHistoryModal"></button>
            </div>
            <div class="modal-body">
              <ul class="list-group">
                <li v-for="bid in bidHistory" :key="bid.id" class="list-group-item">
                  <i class="bi bi-person-circle"></i> {{ bid.user.name }} pujó <strong>${{ bid.amount }}</strong>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>

    </div>
  </AuthenticatedLayout>
  <div class="container">
    <footer class="py-5">
      <div class="row"></div>
      <div class="d-flex flex-column flex-sm-row justify-content-between py-4 my-4 border-top">
        <p class="text-muted text-center text-sm-start">© 2025 Mass Move Inc. All rights reserved</p>
        <ul class="list-unstyled d-flex">
          <li class="ms-3">
            <a class="link-body-emphasis" href="https://twitter.com/">
              <svg class="bi" width="24" height="24">
                <use xlink:href="#twitter" />
              </svg>
            </a>
          </li>
          <li class="ms-3">
            <a class="link-body-emphasis" href="https://www.facebook.com/">
              <svg width="24" height="24">
                <use xlink:href="#facebook" />
              </svg>
            </a>
          </li>
        </ul>
      </div>
    </footer>
  </div>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';
import { notifications } from '@/Services/notificationService';
import { cars, fetchAuctions, placeBid, startCountdown, listenForBids, listenForNewAuctions } from '@/Services/auctionService';
import { bidHistory, fetchBidHistory } from '@/Services/bidService';
import { modal, openHistoryModal, closeHistoryModal } from '@/Services/modalService';

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