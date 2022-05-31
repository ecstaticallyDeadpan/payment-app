<script>
    import Notification from '@/Components/Notification.vue'
    export default{
        data(){
            return {
                statusResponse: false,
                paymentStatus: 'pending'
            }
        },
        mounted(){
            this.checkStatus();
        },
        methods: {
            checkStatus(){
                const queryString = window.location.search;
                const urlParams = new URLSearchParams(queryString);
                return fetch('/processing/check-status?' + new URLSearchParams({
                        checkout_id: urlParams.get('id'),
                    }), {
                    method: 'get',
                    headers: {
                        'content-type': 'application/json'
                    }
                }).then(response => response.json())
                .then( data => {
                    // Success
                    this.statusResponse = data;
                    this.updateStatus();
                });
            },
            updateStatus(){
                if(this.statusResponse && this.statusResponse.result.code == '000.100.110' ){
                    this.paymentStatus = 'successful';
                }else if( this.statusResponse
                    && ( this.statusResponse.result.code == "200.300.404"
                    || this.statusResponse.result.code == "200.300.404" ) ){
                    this.paymentStatus = 'error';
                }else{
                    this.paymentStatus = 'pending';
                }
            }
        },
        components: {
            Notification
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