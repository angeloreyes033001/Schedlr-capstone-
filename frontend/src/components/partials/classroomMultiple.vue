<template>
    <div v-if="schedule != '' " class="" v-for="classroom in schedule" :key="classroom.classroom" >
        <span class="" >
            <h6 class="text-uppercase" >
                <strong>Room: </strong>
                <span class="t-font-normal" > {{ classroom.classroom }}</span>
            </h6>
            <h6 class="text-uppercase" >
                <strong>Room Type: </strong>
                <span class="t-font-normal" > {{ classroom.type }}</span>
            </h6>
            <h6 class="text-uppercase" >
                <strong>Department: </strong>
                <span class="t-font-normal" > {{ classroom.department }}</span>
            </h6>
            <h6 class="text-uppercase" >
                <strong>Year: </strong>
                <span class="t-font-normal" > {{ classroom.yearlevel }}</span>
            </h6>
        </span>
        <scheduleTable :schedules="classroom.schedule" ></scheduleTable>
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

    import scheduleTable from '../partials/schedule-component/singleSchedule.vue';

    const props = defineProps({
    schedule: {
        required: true,
    },
})
</script>