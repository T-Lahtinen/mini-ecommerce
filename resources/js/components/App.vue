<script setup lang="ts">
import { onMounted, ref } from "vue";
import Cart from "../components/Cart.vue";
import ProductList from "../components/ProductList.vue";
import type { CartItem } from "../types/CartItem";
import type { Product } from "../types/Product";

const products = ref<Product[]>([]);
const cartItems = ref<CartItem[]>([]);
const loading = ref(true);
const error = ref<string | null>(null);

function addToCart(product: Product): void {
    const existingItem = cartItems.value.find(
        (item) => item.product.product_no === product.product_no,
    );

    if (existingItem) {
        existingItem.quantity++;
        return;
    }

    cartItems.value.push({
        product,
        quantity: 1,
    });
}

function increaseQuantity(productNo: string): void {
    const item = cartItems.value.find(
        (item) => item.product.product_no === productNo,
    );

    if (item) {
        item.quantity++;
    }
}

function decreaseQuantity(productNo: string): void {
    const item = cartItems.value.find(
        (item) => item.product.product_no === productNo,
    );

    if (!item) {
        return;
    }

    if (item.quantity === 1) {
        removeFromCart(productNo);
        return;
    }

    item.quantity--;
}

function removeFromCart(productNo: string): void {
    cartItems.value = cartItems.value.filter(
        (item) => item.product.product_no !== productNo,
    );
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

        <div class="layout">
            <section>
                <p v-if="loading">Loading products...</p>
                <p v-else-if="error">{{ error }}</p>
                <p v-else-if="products.length === 0">No products found.</p>

                <ProductList
                    v-else
                    :products="products"
                    @add-to-cart="addToCart"
                />
            </section>

            <Cart
                :items="cartItems"
                @increase="increaseQuantity"
                @decrease="decreaseQuantity"
                @remove="removeFromCart"
            />
        </div>
    </main>
</template>
