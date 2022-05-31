<script setup>
    import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
    import PaymentForm from '@/Components/PaymentForm.vue';
    import { Head } from '@inertiajs/inertia-vue3';
</script>

<template>
    <Head title="Payment page" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Payment page
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <PaymentForm />
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>
