<script setup>
import { ref, onMounted } from 'vue';

const cars = ref([]);

onMounted(async () => {
    const response = await fetch('http://127.0.0.1:8000/api/auctions');
    cars.value = await response.json();

    // Escuchar eventos en tiempo real
    window.Echo.channel('auctions').listen('NewBidPlaced', (event) => {
        const auction = cars.value.find(car => car.id === event.id);
        if (auction) {
            auction.current_bid = event.current_bid;
        }
    });
});
</script>