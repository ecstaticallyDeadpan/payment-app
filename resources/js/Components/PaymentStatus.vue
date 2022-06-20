<script setup>
    import Notification from '@/Components/Notification.vue'
    import {onMounted, ref} from 'Vue'

    const statusResponse = ref(false)
    const paymentStatus = ref('pending')

    onMounted(() => {
        checkStatus();
    })

    const checkStatus = async () => {
        // Get id from url string
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        // Build url for processing
        let url = '/processing/check-status?' + new URLSearchParams({
            checkout_id: urlParams.get('id'),
        })
        // Query the api for status
        try{
            let returnedValue = await fetch(url)
            if (returnedValue.ok){
                // If status is returned then set values and run updateStatus
                let data = await returnedValue.json()
                statusResponse.value = data;
                updateStatus();
            }
        }catch(error){
            // If fails then set status to error
            paymentStatus.value = "error"
        }
    }
    const updateStatus = () =>{
        // Catch various status codes from the api
        if(statusResponse.value && statusResponse.value.result.code == '000.100.110' ){
            paymentStatus.value = 'successful';
        }else if( statusResponse.value
            && ( statusResponse.value.result.code == "200.300.404"
            || statusResponse.value.result.code == "200.300.404" ) ){
            paymentStatus.value = 'error';
        }else{
            paymentStatus.value = 'pending';
        }
    }
</script>
<template>
    <!-- This example requires Tailwind CSS v2.0+ -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div v-if="paymentStatus == 'pending'">
                We're checking the status of your payment
            </div>
            <Notification
                v-else-if="paymentStatus == 'successful'"
                text="Your payment has been successfuly processed" />
            <Notification
                v-else-if="paymentStatus == 'error'"
                notification-type="error"
                text="There has been an error with your payment" />

            <div class="py-4" v-if="statusResponse">
                <p><strong>Result code:</strong> {{ statusResponse.result.code }}</p>
                <p><strong>Description:</strong> {{ statusResponse.result.description }}</p>
            </div>
        </div>
    </div>
</template>