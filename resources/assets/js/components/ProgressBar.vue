<template>
    <div class="progress progress-bar-default" v-show="progressProp.state">
        <div :style="progressProp.style" aria-valuemax="100" aria-valuemin="0" :aria-valuenow="progressProp.percents" role="progressbar" class="progress-bar">
            <span>{{ progressProp.text }}</span>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'progress-bar',
        props: {
            progressProp: {
                type: Object,
                required: true
            }
        }
    }
</script>
