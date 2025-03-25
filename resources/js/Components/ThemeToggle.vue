<template>
    <div class="theme-toggle">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="themeToggle" v-model="isDarkMode" @change="toggleTheme">
            <label class="form-check-label" for="themeToggle">
                <i class="bi" :class="isDarkMode ? 'bi-moon-fill' : 'bi-sun-fill'"></i>
            </label>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

// Initialize theme state
const isDarkMode = ref(localStorage.getItem('theme') === 'dark');

// Toggle theme function
const toggleTheme = () => {
    isDarkMode.value = !isDarkMode.value;
    const theme = isDarkMode.value ? 'dark' : 'light';
    console.log('Setting theme to:', theme); // Debugging
    document.documentElement.setAttribute('data-theme', theme); // Use data-theme
    localStorage.setItem('theme', theme);
};

// Sync theme on page load
onMounted(() => {
    const savedTheme = localStorage.getItem('theme') || 'light';
    isDarkMode.value = savedTheme === 'dark';
    console.log('Loaded theme:', savedTheme); // Debugging
    document.documentElement.setAttribute('data-theme', savedTheme); // Use data-theme
});
</script>

<style scoped>
/* Estilos personalizados */
.theme-toggle {
    display: flex;
    align-items: center;
    justify-content: center;
}

.form-check-input {
    width: 2.5rem;
    height: 1.5rem;
    cursor: pointer;
}

.form-check-label i {
    font-size: 1.2rem;
    margin-left: 0.5rem;
}
</style>