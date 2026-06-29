<script setup lang="ts">
import { computed } from 'vue';
import type { CartItem } from '../types';
import en from '../lang/en.json';

const props = defineProps<{
    item: CartItem;
}>();

const emit = defineEmits<{
    increase: [item: CartItem];
    decrease: [item: CartItem];
    remove: [productNo: string];
}>();

const t = en;

const hasQuantityError = computed(() => {
    return props.item.quantity < 1 || props.item.quantity > 99;
});
</script>

<template>
    <div class="cart-row">
        <span class="cart-name">
            {{ item.name }}
        </span>

        <button @click="emit('decrease', item)">-</button>

        <div>
            <input
                v-model.number="item.quantity"
                type="number"
                min="1"
                max="99"
                :class="{ 'input-error': hasQuantityError }"
                class="quantity-input"
            />

            <p v-if="hasQuantityError" class="error-text">
                {{ t.order.validation.invalidQuantity }}
            </p>
        </div>

        <button @click="emit('increase', item)">+</button>

        <button @click="emit('remove', item.productNo)">
            {{ t.order.remove }}
        </button>
    </div>
</template>

<style scoped>
.cart-row {
    display: flex;
    gap: 12px;
    align-items: flex-start;
    margin-bottom: 12px;
}

.cart-name {
    min-width: 160px;
}

.quantity-input {
    width: 70px;
}

.input-error {
    border: 1px solid red;
    outline-color: red;
}

.error-text {
    color: red;
    margin: 4px 0 0;
    font-size: 0.875rem;
}
</style>
