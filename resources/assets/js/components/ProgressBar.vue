<template>
    <div class="progress progress-bar-default" v-show="progress.state">
        <div :style="progress.style" aria-valuemax="100" aria-valuemin="0" :aria-valuenow="progress.percents" role="progressbar" class="progress-bar">
            <span>{{ progress.text }}</span>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'progress-bar',
        props: {
            progress: {
                type: Object,
                required: true
            }
        }
    }
</script>
