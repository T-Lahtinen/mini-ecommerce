<script setup lang="ts">
import { computed, onMounted, ref } from 'vue';
import en from '../lang/en.json';
import type {
    Product,
    CartItem,
    OrderResponse,
} from '../types';
import ProductCard from './ProductCard.vue';
import CartItemRow from './CartItemRow.vue';
import LoadingSkeleton from './LoadingSkeleton.vue';

type TouchedFields = {
    firstName: boolean;
    lastName: boolean;
    customerEmail: boolean;
};

const t = en;

const products = ref<Product[]>([]);
const cart = ref<CartItem[]>([]);
const loading = ref<boolean>(true);
const submitting = ref<boolean>(false);
const error = ref<string>('');
const orderError = ref<string>('');
const successMessage = ref<string>('');

const firstName = ref<string>('');
const lastName = ref<string>('');
const customerEmail = ref<string>('');

const touched = ref<TouchedFields>({
    firstName: false,
    lastName: false,
    customerEmail: false,
});

const emailIsValid = computed(() => {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(customerEmail.value);
});

const emailHasError = computed(() => {
    return touched.value.customerEmail && !emailIsValid.value;
});

const cartHasInvalidQuantity = computed(() => {
    return cart.value.some((item) => item.quantity < 1 || item.quantity > 99);
});

const formIsValid = computed(() => {
    return (
        firstName.value.length > 0 &&
        lastName.value.length > 0 &&
        emailIsValid.value &&
        cart.value.length > 0 &&
        !cartHasInvalidQuantity.value
    );
});

const cartTotal = computed(() => {
    return cart.value.reduce((sum, item) => {
        return sum + Number(item.price) * item.quantity;
    }, 0);
});

onMounted(async () => {
    try {
        const response = await fetch('/api/products');
        products.value = await response.json() as Product[];
    } catch {
        error.value = 'Could not load products.';
    } finally {
        loading.value = false;
    }
});

function addToCart(product: Product) {
    const existingItem = cart.value.find((item) => item.productNo === product.productNo);

    if (existingItem) {
        existingItem.quantity++;
        return;
    }

    cart.value.push({
        productNo: product.productNo,
        name: product.name,
        price: product.salePrice ?? product.price,
        quantity: 1,
    });
}

function increaseQuantity(item: CartItem) {
    if (item.quantity < 99) {
        item.quantity++;
    }
}

function decreaseQuantity(item: CartItem) {
    if (item.quantity > 1) {
        item.quantity--;
    }
}

function removeFromCart(productNo: string) {
    cart.value = cart.value.filter((item) => item.productNo !== productNo);
}

async function placeOrder() {
    submitting.value = true;
    orderError.value = '';
    successMessage.value = '';

    try {
        const response = await fetch('/api/orders', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                Accept: 'application/json',
            },
            body: JSON.stringify({
                first_name: firstName.value,
                last_name: lastName.value,
                customer_email: customerEmail.value,
                items: cart.value.map((item) => ({
                    product_no: item.productNo,
                    quantity: item.quantity,
                })),
            }),
        });

        if (!response.ok) {
            const data = await response.json();

            if (data.message) {
                throw new Error(data.message);
            }

            throw new Error(t.order.validation.genericOrderError);
        }

        const order = await response.json() as OrderResponse;

        /* TODO: The success message should go away or user should be redirected.
        Not necessary for this demo. */
        successMessage.value = t.order.orderSuccess.replace('{id}', String(order.id));

        /* Reset order values. */
        cart.value = [];
        firstName.value = '';
        lastName.value = '';
        customerEmail.value = '';

        touched.value = {
            firstName: false,
            lastName: false,
            customerEmail: false,
        };
    } catch (err) {
        orderError.value = err instanceof Error
            ? err.message
            : t.order.validation.genericOrderError;
    } finally {
        submitting.value = false;
    }
}
</script>

<template>
    <main style="max-width: 900px; margin: 40px auto; font-family: Arial, sans-serif;">
        <h1>{{ t.appTitle }}</h1>

        <section>
            <h2>{{ t.products.productsTitle }}</h2>

            <LoadingSkeleton v-if="loading" />
            <p v-if="error">{{ error }}</p>

            <ProductCard
                v-for="product in products"
                :key="product.productNo"
                :product="product"
                @add-to-cart="addToCart"
            />
        </section>

        <section style="margin-top: 32px;">
            <h2>{{ t.order.orderTitle }}</h2>

            <p v-if="cart.length === 0">{{ t.order.noItemsSelected }}</p>

            <CartItemRow
                v-for="item in cart"
                :key="item.productNo"
                :item="item"
                @increase="increaseQuantity"
                @decrease="decreaseQuantity"
                @remove="removeFromCart"
            />

            <p>
                <strong>{{ t.order.total }}:</strong>
                {{ cartTotal.toFixed(2) }}€
            </p>

            <div style="margin-top: 16px;">
                <div style="display: flex; margin-bottom: 8px; gap: 1em;">
                    <input
                        v-model="firstName"
                        :placeholder="t.order.firstName"
                    />

                    <input
                        v-model="lastName"
                        :placeholder="t.order.lastName"
                    />
                </div>

                <input
                    v-model="customerEmail"
                    :placeholder="t.order.email"
                    :class="{ 'input-error': emailHasError }"
                    @blur="touched.customerEmail = true"
                    style="display: block; margin-bottom: 8px;"
                />

                <p v-if="emailHasError" style="color: red;">
                    {{ t.order.validation.invalidEmail }}
                </p>
            </div>

            <!-- TODO: Implement a hover tooltip to inform user why the button is disabled. -->
            <button
                :disabled="!formIsValid || submitting"
                @click="placeOrder"
            >
                {{ submitting ? t.order.placingOrder : t.order.placeOrder }}
            </button>

            <p v-if="successMessage" style="color: green;">
                {{ successMessage }}
            </p>

            <p v-if="orderError" style="color: red;">
                {{ orderError }}
            </p>
        </section>
    </main>
</template>

<style scoped>
.input-error {
    border: 1px solid red;
    outline-color: red;
}
</style>
