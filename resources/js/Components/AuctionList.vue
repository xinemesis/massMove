<script setup>
import { ref, onMounted } from 'vue';

const cars = ref([]);

onMounted(async () => {
    const response = await fetch('http://127.0.0.1:8000/api/auctions');
    cars.value = await response.json();
});
</script>