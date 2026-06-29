<script setup lang="ts">
import { onMounted, ref } from "vue";
import ProductList from "../components/ProductList.vue";
import type { Product } from "../types/Product";

const products = ref<Product[]>([]);
const loading = ref(true);
const error = ref<string | null>(null);

function addToCart(product: Product): void {
    alert(`${product.name} will be added to the cart in the next step.`);
}

onMounted(async () => {
    try {
        const response = await fetch("/api/products");

        if (!response.ok) {
            throw new Error("Failed to load products.");
        }

        const json = await response.json();
        products.value = json.data;
    } catch {
        error.value = "Products could not be loaded.";
    } finally {
        loading.value = false;
    }
});
</script>

<template>
    <main class="page">
        <h1>Mini E-commerce</h1>

        <p v-if="loading">Loading products...</p>
        <p v-else-if="error">{{ error }}</p>
        <p v-else-if="products.length === 0">No products found.</p>

        <ProductList v-else :products="products" @add-to-cart="addToCart" />
    </main>
</template>
