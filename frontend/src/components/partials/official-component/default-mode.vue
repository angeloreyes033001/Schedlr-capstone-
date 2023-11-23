<template>
    <div>
        <div  class="t-mb-2" >
            <span>
                <fa icon="circle" class="t-text-available" ></fa>
                &nbsp;
                <label for="" class="t-font-semibold t-uppercase" >Official Time</label>
            </span>
        </div>
        <div class="table-holder" >
            <div class="t-bg-logoBlue t-h-[50px] t-grid t-grid-cols-7 t-rounded-tl-[10px] t-rounded-tr-[10px]" >
                <div class="t-flex t-items-center t-pl-[10px] t-font-bold t-border-r-[1px] t-border-gray-200 t-text-white t-truncate" >Time</div>
                <div class="t-flex t-items-center t-pl-[10px] t-font-bold t-border-r-[1px] t-border-gray-200 t-text-white t-truncate" >Monday</div>
                <div class="t-flex t-items-center t-pl-[10px] t-font-bold t-border-r-[1px] t-border-gray-200 t-text-white t-truncate" >Tuesday</div>
                <div class="t-flex t-items-center t-pl-[10px] t-font-bold t-border-r-[1px] t-border-gray-200 t-text-white t-truncate" >Wednesday</div>
                <div class="t-flex t-items-center t-pl-[10px] t-font-bold t-border-r-[1px] t-border-gray-200 t-text-white t-truncate" >Thursday</div>
                <div class="t-flex t-items-center t-pl-[10px] t-font-bold t-border-r-[1px] t-border-gray-200 t-text-white t-truncate" >Friday</div>
                <div class="t-flex t-items-center t-pl-[10px] t-font-bold t-text-white t-truncate" >Saturday</div>
            </div>
            <div class="t-bg-gray t-border-b-[1px] t-h-[50px] t-grid t-grid-cols-7" v-for="schedule in props.schedules" >
                <div class="t-flex t-items-center t-pl-[10px] t-font-semibold  t-border-r-[1px] t-border-l-[1px] t-border-gray-200" >
                    {{ schedule.time }}
                </div>
                <div class="t-flex t-items-center t-pl-[10px] t-font-semibold  t-border-r-[1px] t-border-gray-200"  :class="{'t-bg-available': schedule.mon == 1 }" ></div>
                <div class="t-flex t-items-center t-pl-[10px] t-font-semibold  t-border-r-[1px] t-border-gray-200"  :class="{'t-bg-available': schedule.tue == 1 }" ></div>
                <div class="t-flex t-items-center t-pl-[10px] t-font-semibold  t-border-r-[1px] t-border-gray-200"  :class="{'t-bg-available': schedule.wed == 1 }" ></div>
                <div class="t-flex t-items-center t-pl-[10px] t-font-semibold  t-border-r-[1px] t-border-gray-200"  :class="{'t-bg-available': schedule.thu == 1 }" ></div>
                <div class="t-flex t-items-center t-pl-[10px] t-font-semibold  t-border-r-[1px] t-border-gray-200"  :class="{'t-bg-available': schedule.fri == 1 }" ></div>
                <div class="t-flex t-items-center t-pl-[10px] t-font-semibold  t-border-r-[1px] t-border-gray-200 "  :class="{'t-bg-available': schedule.sat == 1 }" ></div>
            </div>
        </div>
    </div>
</template>
<script setup>

const props = defineProps({
    schedules: {
        type: Object,
        required: true
    }
})

</script>