<script setup lang="ts">
import type { Product } from '../types';
import ProductPrice from './ProductPrice.vue';
import en from '../lang/en.json';

defineProps<{
    product: Product;
}>();

const emit = defineEmits<{
    addToCart: [product: Product];
}>();

const t = en;
</script>

<template>
    <div class="product-card">
        <!-- TODO: implement a more responsive solution if product name or description end up being very long. -->
        <div class="product-content">
            <div class="product-main">
                <strong>
                    {{ product.name }}

                    <span
                        v-if="product.salePrice"
                        class="sale-badge"
                    >
                        {{ t.products.onSale }}
                    </span>
                </strong>

                <ProductPrice
                    :price="product.price"
                    :sale-price="product.salePrice"
                    :discount-percentage="product.discountPercentage"
                />

                <button @click="emit('addToCart', product)">
                    {{ t.products.addToOrder }}
                </button>
            </div>

            <div class="product-description">
                {{ product.description }}
            </div>
        </div>
    </div>
</template>

<style scoped>
.product-card {
    border: 1px solid #ddd;
    padding: 16px;
    margin-bottom: 12px;
}

.product-content {
    display: flex;
    align-items: center;
    gap: 1em;
}

.product-main {
    width: 200px;
}

.product-description {
    width: 100%;
}

.sale-badge {
    color: orangered;
}
</style>
