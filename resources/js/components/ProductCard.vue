<script setup lang="ts">
import type { Product } from "../types/Product";

defineProps<{
    product: Product;
}>();

const emit = defineEmits<{
    addToCart: [product: Product];
}>();
</script>

<template>
    <article class="product-card">
        <h2>{{ product.name }}</h2>
        <p>{{ product.description }}</p>

        <p v-if="product.sale_price">
            <span class="old-price">{{ product.price }}€</span>
            <strong>{{ product.sale_price }}€</strong>
            <span> -{{ product.discount_percentage }}%</span>
        </p>

        <p v-else>
            <strong>{{ product.price }}$</strong>
        </p>

        <button type="button" @click="emit('addToCart', product)">
            Add to cart
        </button>
    </article>
</template>
