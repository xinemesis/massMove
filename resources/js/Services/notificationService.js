import { ref } from 'vue';

export const notifications = ref([]);

// 🔹 Mostrar una notificación
export const showNotification = (message, type = 'success') => {
    notifications.value.push({ message, type });

    // Eliminar la notificación después de 5 segundos
    setTimeout(() => {
        notifications.value.shift();
    }, 3000);
};
