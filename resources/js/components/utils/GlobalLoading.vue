<template>
    <div v-if="loadingStore.isLoading" class="loading-overlay">
        <div class="spinner"></div>
        <p>Carregando...</p>
    </div>
</template>

<script setup>
import { loadingStore } from '../../store/LoadingStore';
</script>

<style scoped>
.loading-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.45);
    backdrop-filter: blur(4px);

    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;

    z-index: 9999;
    pointer-events: all;
}

.spinner {
    width: 50px;
    height: 50px;
    border: 5px solid #ffffff33;
    border-top: 5px solid white;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
    margin-bottom: 12px;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

p {
    color: white;
    font-weight: 600;
}
</style>
