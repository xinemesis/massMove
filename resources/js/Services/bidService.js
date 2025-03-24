import { ref } from 'vue';

export const bidHistory = ref([]);

export const fetchBidHistory = async (auctionId) => {
    try {
        const response = await fetch(`/api/auctions/${auctionId}/bids`);
        if (!response.ok) throw new Error("Error en la API");

        bidHistory.value = await response.json();
    } catch (error) {
        console.error("Error al cargar el historial de pujas:", error);
    }
};
