<script setup lang="ts">
defineProps<{
    price: string;
    salePrice: string | null;
    discountPercentage: number | null;
}>();
</script>

<template>
    <p v-if="salePrice">
        <strong class="sale-price">
            {{ salePrice }}€ -{{ discountPercentage }}%
        </strong>

        <span class="original-price">
            ({{ price }}€)
        </span>
    </p>

    <p v-else>
        {{ price }}€
    </p>
</template>

<style scoped>
.sale-price {
    font-size: 1.2em;
    color: orangered;
}

.original-price {
    text-decoration: line-through;
    margin-left: 8px;
}
</style>
