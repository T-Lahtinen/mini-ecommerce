<script setup lang="ts">
import { computed } from "vue";
import type { CartItem } from "../types/CartItem";

const props = defineProps<{
    items: CartItem[];
}>();

const emit = defineEmits<{
    increase: [productNo: string];
    decrease: [productNo: string];
    remove: [productNo: string];
}>();

function itemPrice(item: CartItem): number {
    return Number(item.product.sale_price ?? item.product.price);
}

const total = computed(() => {
    return props.items.reduce((sum, item) => {
        return sum + itemPrice(item) * item.quantity;
    }, 0);
});
</script>

<template>
    <aside class="cart">
        <h2>Cart</h2>

        <p v-if="items.length === 0">Your cart is empty.</p>

        <div
            v-for="item in items"
            :key="item.product.product_no"
            class="cart-item"
        >
            <div>
                <strong>{{ item.product.name }}</strong>
                <p>€{{ itemPrice(item).toFixed(2) }} × {{ item.quantity }}</p>
            </div>

            <div class="cart-actions">
                <button
                    type="button"
                    @click="emit('decrease', item.product.product_no)"
                >
                    -
                </button>
                <button
                    type="button"
                    @click="emit('increase', item.product.product_no)"
                >
                    +
                </button>
                <button
                    type="button"
                    @click="emit('remove', item.product.product_no)"
                >
                    Remove
                </button>
            </div>
        </div>

        <p v-if="items.length > 0">
            <strong>Total: {{ total.toFixed(2) }}€</strong>
        </p>
    </aside>
</template>
