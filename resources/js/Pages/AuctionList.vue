<template>
    <div class="container">
      <h2 class="text-center my-4">Subastas en Tiempo Real</h2>
      <table class="table table-bordered">
        <thead class="table-dark">
          <tr>
            <th>Auto</th>
            <th>Puja Actual</th>
            <th>Tiempo Restante</th>
            <th>Pujar</th>
            <th>Historial</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="car in auctions" :key="car.id">
            <td>{{ car.name }}</td>
            <td>${{ car.current_bid ?? 0 }}</td>
            <td>{{ car.timeLeft ?? "Cargando..." }}</td>
            <td>
              <input v-model="car.newBid" type="number" class="form-control" placeholder="Ingrese su puja" />
              <button class="btn btn-success mt-2" @click="placeBid(car.id, car.newBid)">Pujar</button>
            </td>
            <td>
              <button class="btn btn-info" @click="openHistoryModal(car.id, fetchBidHistory)">Ver Historial</button>
            </td>
          </tr>
        </tbody>
      </table>
  
      <!-- Modal de Historial de Pujas -->
      <div class="modal fade" id="bidHistoryModal" tabindex="-1" aria-hidden="true" ref="modal">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Historial de Pujas</h5>
              <button type="button" class="btn-close" @click="closeHistoryModal">X</button>
            </div>
            <div class="modal-body">
              <ul class="list-group">
                <li v-for="bid in bidHistory" :key="bid.id" class="list-group-item">
                  {{ bid.user.name }} pujó ${{ bid.amount }}
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import AppLayout from '@/Layouts/AppLayout.vue';
  import { onMounted, onUnmounted } from 'vue';
  import { cars, fetchAuctions, placeBid, startCountdown, listenForBids, listenForNewAuctions } from '@/Services/auctionService';
  import { bidHistory, fetchBidHistory } from '@/Services/bidService';
  import { modal, openHistoryModal, closeHistoryModal } from '@/Services/modalService';
  
  onMounted(async () => {
    try {
        await fetchAuctions();
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
  