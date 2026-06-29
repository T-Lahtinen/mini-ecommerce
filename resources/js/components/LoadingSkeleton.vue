<template>
    <div>
        <div class="skeleton"></div>
        <div class="skeleton"></div>
        <div class="skeleton"></div>
    </div>
</template>

<style scoped>
.skeleton {
    height: 80px;
    border-radius: 6px;
    background: #e5e7eb;
    margin-bottom: 12px;
    animation: pulse 1.5s ease-in-out infinite;
}

@keyframes pulse {
    0% {
        opacity: 0.5;
    }

    50% {
        opacity: 1;
    }

    100% {
        opacity: 0.5;
    }
}
</style>
