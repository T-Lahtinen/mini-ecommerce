<script setup lang="ts">
import ProductCard from "./ProductCard.vue";
import type { Product } from "../types/Product";

defineProps<{
    products: Product[];
}>();

const emit = defineEmits<{
    addToCart: [product: Product];
}>();
</script>

<template>
    <section class="product-grid">
        <ProductCard
            v-for="product in products"
            :key="product.product_no"
            :product="product"
            @add-to-cart="emit('addToCart', product)"
        />
    </section>
</template>
