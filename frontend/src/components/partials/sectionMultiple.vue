<template>
    <div class="" v-for="section in schedule" v-if="schedule.length > 0" >
        <span>
            <h6 class="text-uppercase" >
                <strong>Section: </strong>
                <span class="t-font-normal" > {{ section.section }}</span>
            </h6>
            <h6 class="text-uppercase" >
                <strong>Year: </strong>
                <span class="t-font-normal" > {{ section.yearlevel }}</span>
            </h6>
            <h6 class="text-uppercase" >
                <strong>Department: </strong>
                <span class="t-font-normal" > {{ section.department }}</span>
            </h6>
        </span>
        <!-- section.schedule -->
        <scheduleTable :schedules="section.schedule" ></scheduleTable>
    </div>
    <div v-else class=" d-flex justify-content-center align-items-center" >
        <div class="text-center " >
            <div class="t-grid t-justify-center items-center">
                <img src="../../assets/images/table.png" class="t-h-[200px] t-w-[200px] " alt="">
            </div>
            <h4 class="text-uppercase" >No Schedule Display.</h4>
        </div>
    </div>
</template>
<script setup >
    import scheduleTable from '../partials/schedule-component/singleSchedule.vue'
    const props = defineProps({
    schedule: {
        type: Array,
        required: true,
    },
})
</script>