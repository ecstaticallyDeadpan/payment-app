<script setup>
    import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
    import PaymentStatus from '@/Components/PaymentStatus.vue';
    import { Head } from '@inertiajs/inertia-vue3';
</script>

<template>
    <Head title="Confirmation" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Confirmation
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <PaymentStatus />
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>