import { ref } from 'vue';

// 🔹 Estado global para el historial de pujas
export const bidHistory = ref([]);

// 🔹 Obtener historial de pujas de una subasta
export const fetchBidHistory = async (auctionId) => {
    try {
        const response = await fetch(`/api/auctions/${auctionId}/bids`);
        bidHistory.value = await response.json();
    } catch (error) {
        console.error("Error al cargar el historial de pujas:", error);
    }
};
