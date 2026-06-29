<script setup lang="ts">
import { ref } from "vue";
import type { CartItem } from "../types/CartItem";

const props = defineProps<{
    items: CartItem[];
}>();

const emit = defineEmits<{
    orderPlaced: [];
}>();

const firstName = ref("");
const lastName = ref("");
const email = ref("");
const loading = ref(false);
const error = ref<string | null>(null);
const success = ref<string | null>(null);

async function submitOrder(): Promise<void> {
    error.value = null;
    success.value = null;

    if (props.items.length === 0) {
        error.value = "Your cart is empty.";
        return;
    }

    loading.value = true;

    try {
        const response = await fetch("/api/orders", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
            },
            body: JSON.stringify({
                first_name: firstName.value,
                last_name: lastName.value,
                customer_email: email.value,
                items: props.items.map((item) => ({
                    product_no: item.product.product_no,
                    quantity: item.quantity,
                })),
            }),
        });

        const json = await response.json();

        if (!response.ok) {
            error.value = json.message ?? "Order could not be placed.";
            return;
        }

        success.value = `Order #${json.order.id} placed successfully.`;
        firstName.value = "";
        lastName.value = "";
        email.value = "";
        emit("orderPlaced");
    } catch {
        error.value = "Order could not be placed.";
    } finally {
        loading.value = false;
    }
}
</script>

<template>
    <section class="checkout">
        <h2>Checkout</h2>

        <form @submit.prevent="submitOrder">
            <label>
                First name
                <input v-model="firstName" type="text" required />
            </label>

            <label>
                Last name
                <input v-model="lastName" type="text" required />
            </label>

            <label>
                Email
                <input v-model="email" type="email" required />
            </label>

            <button type="submit" :disabled="loading">
                {{ loading ? "Placing order..." : "Place order" }}
            </button>
        </form>

        <p v-if="error" class="error">{{ error }}</p>
        <p v-if="success" class="success">{{ success }}</p>
    </section>
</template>
