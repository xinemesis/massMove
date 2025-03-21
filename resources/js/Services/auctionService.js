import { ref } from 'vue';


// 🔹 Estado global para subastas
export const cars = ref([]);


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
        console.error('Error al cargar subastas:', error);
    }
};


// 🔹 Escuchar nuevas subastas en tiempo real con Pusher
export const listenForNewAuctions = () => {
    window.Echo.channel('auctions').listen('NewAuctionCreated', (event) => {
        console.log("Nueva subasta recibida:", event); // 🔹 Verificar si se recibe el evento

        cars.value = [...cars.value, {
            id: event.id,
            name: event.name,
            current_bid: event.current_bid,
            end_time: event.end_time,
            timeLeft: calculateTimeLeft(event.end_time)
        }];
    });
};


// 🔹 Obtener detalles de una subasta
export const fetchAuctionDetails = async (auctionId) => {
    try {
        const response = await fetch(`/api/auctions/${auctionId}`);
        const data = await response.json();
        return {
            ...data,
            timeLeft: calculateTimeLeft(data.end_time)
        };
    } catch (error) {
        console.error('Error al cargar detalles de la subasta:', error);
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


// 🔹 Escuchar pujas en tiempo real con Pusher
export const listenForBids = () => {
    window.Echo.channel('auctions').listen('NewBidPlaced', (event) => {
        const auction = cars.value.find(car => car.id === event.id);
        if (auction) {
            auction.current_bid = event.current_bid;
        }
    });
};


// 🔹 Enviar una puja
export const placeBid = async (auctionId, bidAmount) => {
    if (!bidAmount || bidAmount <= 0) {
        alert("Ingrese una cantidad válida para pujar.");
        return;
    }

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute("content");

    try {
        const response = await fetch(`/api/auctions/${auctionId}`, {
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

        if (response.ok) {
            alert("Puja realizada con éxito!");
        } else {
            alert("Error al realizar la puja: " + result.message);
        }
    } catch (error) {
        console.error("Error al pujar:", error);
    }
};
