<script setup lang="ts">
import { onMounted, ref } from "vue";

type Product = {
    product_no: string;
    name: string;
    description: string | null;
    price: string;
    sale_price: string | null;
    discount_percentage: number | null;
};

const products = ref<Product[]>([]);
const loading = ref(true);
const error = ref<string | null>(null);

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

        <section v-else class="product-grid">
            <article
                v-for="product in products"
                :key="product.product_no"
                class="product-card"
            >
                <h2>{{ product.name }}</h2>
                <p>{{ product.description }}</p>

                <p v-if="product.sale_price">
                    <span class="old-price">{{ product.price }}€</span>
                    <strong>{{ product.sale_price }}€</strong>
                    <span> -{{ product.discount_percentage }}%</span>
                </p>

                <p v-else>
                    <strong>{{ product.price }}€</strong>
                </p>
            </article>
        </section>
    </main>
</template>
