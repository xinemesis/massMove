import { ref } from 'vue';
import { showNotification } from './notificationService';


// 🔹 Estado global para subastas
export const cars = ref([]);

// 🔹 Estado global para el estado de carga
export const loading = ref(true);


// 🔹 Obtener subastas desde la API
export const fetchAuctions = async () => {
    try {
        const response = await fetch('/api/auctions');
        const data = await response.json();
        cars.value = data.map(car => ({
            ...car,
            newBid: "",
            timeLeft: calculateTimeLeft(car.end_time)
        }));
    } catch (error) {
        showNotification("Error al cargar subastas.", "danger");
        //console.error('Error al cargar subastas:', error);
    } finally {
        loading.value = false;
    }
};


// 🔹 Escuchar nuevas subastas en tiempo real
export const listenForNewAuctions = () => {
    window.Echo.channel('auctions').listen('NewAuctionCreated', (event) => {
        const newAuction = {
            ...event,
            timeLeft: calculateTimeLeft(event.end_time) // 🔹 Iniciar cuenta regresiva de inmediato
        };
        cars.value.push(newAuction);
    });
};


// 🔹 Obtener detalles de una subasta
export const fetchAuctionDetails = async (auctionId) => {
    /*if (!auctionId) {
        console.error("Error: auctionId es undefined.");
        return null;
    }*/

    try {
        const response = await fetch(`/api/auctions/${auctionId}`);
        //if (!response.ok) throw new Error("Error en la API");

        const auction = await response.json(); // ✅ Extraemos los datos correctamente
        return auction;
    } catch (error) {
        //console.error("Error al cargar detalles de la subasta:", error);
        return null;
    }
};



// 🔹 Calcular tiempo restante en formato "DD:HH:MM:SS"
export const calculateTimeLeft = (endTime) => {
    const diff = new Date(endTime) - new Date();
    if (diff <= 0) return "00:00:00:00";

    const days = Math.floor(diff / (1000 * 60 * 60 * 24));
    const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((diff % (1000 * 60)) / 1000);

    return `${String(days).padStart(2, '0')}:${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
};


// 🔹 Actualizar contador de tiempo en tiempo real
export const startCountdown = () => {
    setInterval(() => {
        cars.value.forEach(car => {
            car.timeLeft = calculateTimeLeft(car.end_time);
        });
    }, 1000);
};

// 🔹 Iniciar cuenta regresiva para una subasta específica
export const startAuctionCountdown = (auction) => {
    setInterval(() => {
        auction.timeLeft = calculateTimeLeft(auction.end_time);
    }, 1000);
};


// 🔹 Escuchar pujas en tiempo real con Pusher
export const listenForBids = (updateAuction = null) => {
    window.Echo.channel('auctions').listen('NewBidPlaced', (event) => {
        const auction = cars.value.find(car => car.id === event.id);
        if (auction) {
            auction.current_bid = event.current_bid;
        }

        // Si `updateAuction` es una función, actualizar la subasta específica
        if (updateAuction) {
            updateAuction(event);
        }
    });
};


// 🔹 Enviar una puja
export const placeBid = async (auctionId, bidAmount) => {
    if (!bidAmount || bidAmount <= 0) {
        showNotification("Ingrese una cantidad válida para pujar.", "warning");
        return;
    }

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute("content");

    try {
        const response = await fetch(`/api/auctions/${auctionId}/bid`, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-CSRF-TOKEN": csrfToken,
                "Authorization": `Bearer ${localStorage.getItem('token')}`
            },
            body: JSON.stringify({ current_bid: bidAmount })
        });

        const result = await response.json();
        console.log("📡 Respuesta del servidor:", result);

        if (response.ok) {
            showNotification("Puja realizada con éxito!", "success");
        } else {
            showNotification("Error: " + result.message, "danger");
        }
    } catch (error) {
        showNotification("Error al realizar la puja.", "danger");
        //console.error("Error al pujar:", error);
    }
};
