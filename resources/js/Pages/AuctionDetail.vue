<template>
    <div class="container">
      <h2 class="text-center my-4">Detalles de la Subasta</h2>
  
      <div v-if="auction">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">{{ auction.name }}</h5>
            <p class="card-text">Precio inicial: ${{ auction.starting_price }}</p>
            <p class="card-text">Puja actual: ${{ auction.current_bid ?? 0 }}</p>
            <p class="card-text">Tiempo restante: {{ auction.timeLeft }}</p>
          </div>
        </div>
  
        <div class="mt-4">
          <h4>Historial de Pujas</h4>
          <ul class="list-group">
            <li v-for="bid in bidHistory" :key="bid.id" class="list-group-item">
              {{ bid.user.name }} pujó ${{ bid.amount }}
            </li>
          </ul>
        </div>
  
        <div class="mt-4">
          <h4>Realizar una Puja</h4>
          <input v-model="newBid" type="number" class="form-control" placeholder="Ingrese su puja" />
          <button class="btn btn-success mt-2" @click="placeBid(auction.id, newBid)">Pujar</button>
        </div>
      </div>
      <p v-else>Cargando detalles de la subasta...</p>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  import { usePage } from '@inertiajs/vue3';
  import { fetchAuctionDetails, placeBid, listenForBids } from '@/Services/auctionService';
  import { bidHistory, fetchBidHistory } from '@/Services/bidService';
  //import { fetchAuctionDetails, placeBid, bidHistory, fetchBidHistory, listenForBids } from '@/Services/auctionService';
  
  const page = usePage();
  const auction = ref(null);
  const newBid = ref('');
  
  // 🔹 Cargar datos de la subasta
  onMounted(async () => {
    auction.value = await fetchAuctionDetails(page.props.auctionId);
    await fetchBidHistory(page.props.auctionId);
    listenForBids();
  });
  </script>
  