<script setup>
    import Notification from '@/Components/Notification.vue';
    import {ref} from 'Vue';

    const stage = ref('amount')
    const amount = ref(null)
    const reference = ref(null)
    const checkoutResponse = ref(false)
    const error = ref(false)

    const submitForm = () => {
        // Quick validation
        if (!amount.value || !reference.value) {
            error.value = "Please make sure amount and reference are filled in";
            return false;
        } else {
            error.value = false;
        }
        // Fetch checkout
        return fetch('/processing/generate-checkout?' + new URLSearchParams({
            amount: amount.value.toFixed(2),
            reference: reference.value,
        }), {
            method: 'get',
            headers: {
                'content-type': 'application/json'
            }
        }).then(response => response.json())
            .then(data => {
                // Success
                checkoutResponse.value = data;
                addScript(checkoutResponse.value.id);
                stage.value = 'checkout';
            });
    }
    const addScript = (id) => {
        let doc = document.createElement('script');
        doc.setAttribute('src', "https://eu-test.oppwa.com/v1/paymentWidgets.js?checkoutId=" + id);
        document.head.appendChild(doc);
    }
</script>
<script>
// Custom styling for checkout widget
window.wpwlOptions = {
    style: 'plain',
}
</script>

<template>
    <div v-if="stage == 'amount'" class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form class="space-y-8 divide-y divide-gray-200"
                @submit.prevent="">
                <div class="space-y-8 divide-y divide-gray-200 sm:space-y-5">
                    <div>
                        <div>
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Payment form</h3>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500">Please enter your payment details</p>
                        </div>
                        <div class="mt-6 sm:mt-5 space-y-6 sm:space-y-5">
                            <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                                <label for="username" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">Amount</label>
                                <div class="mt-1 sm:mt-0 sm:col-span-2">
                                    <div class="max-w-lg flex rounded-md shadow-sm">
                                        <input v-model="amount" required type="number" step='0.01' name="name" autocomplete="amount" class="flex-1 block w-full focus:ring-indigo-500 focus:border-indigo-500 min-w-0 rounded-md sm:text-sm border-gray-300">
                                    </div>
                                </div>
                            </div>
                            <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                                <label for="username" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">Reference</label>
                                <div class="mt-1 sm:mt-0 sm:col-span-2">
                                    <div class="max-w-lg flex rounded-md shadow-sm">
                                        <input v-model="reference" required type="text" name="reference" autocomplete="reference" class="flex-1 block w-full focus:ring-indigo-500 focus:border-indigo-500 min-w-0 rounded-md sm:text-sm border-gray-300">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-if="error" class="pt-5 text-red-500">
                    {{ error }}
                </div>
                <div class="pt-5">
                    <div class="flex">
                        <button @click="submitForm" type="button" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Purchase</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div v-if="stage == 'checkout'" class="py-12">
        <div class="pt-5">
            <Notification text="The checkout has been successfully created"/>
        </div>
        <div class="pt-5">
            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg bg-gray-50 p-4">
                <form :action="route('confirmation')" class="paymentWidgets" data-brands="VISA MASTER AMEX"></form>
            </div>
        </div>
    </div>
</template>