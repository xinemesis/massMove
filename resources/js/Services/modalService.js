import { ref } from 'vue';
import bootstrap from 'bootstrap/dist/js/bootstrap.bundle.min.js';

// 🔹 Referencia al modal
export const modal = ref(null);

// 🔹 Abrir el modal del historial de pujas
export const openHistoryModal = async (auctionId, fetchBidHistory) => {
    await fetchBidHistory(auctionId);
    new bootstrap.Modal(modal.value).show();
};

// 🔹 Cerrar el modal
export const closeHistoryModal = () => {
    const modalInstance = bootstrap.Modal.getInstance(modal.value);
    if (modalInstance) modalInstance.hide();
};
