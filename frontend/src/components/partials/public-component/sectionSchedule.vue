<template>
    <div class="t-grid t-w-full">
        <div class="t-w-full" v-if="department != ''" >
           <div v-if="lists.length > 0" >
                <div v-for="list in lists" @click="openModal_readSchedule(list.section)" class="t-cursor-pointer t-p-2 t-w-full sm:t-w-[300px]  t-text-white  t-inline-block"  >
                    <div class=" t-p-3  t-bg-logoBlue t-rounded-[5px]" >
                        <div class="t-grid t-items-start" >
                            <h5 class=" text-left t-truncate text-left t-capitalize"  >{{ list.section }}</h5>
                            <small class="text-left  t-uppercase" >{{ list.year }}</small>
                        </div>
                    </div>
                </div>
           </div>
           <div v-else class=" t-justify-center t-items-center t-w-full p-5 t-grid" >
                <img class="t-w-[400px] t-border-2 t-border-white t-border-b-slate-300" src="../../../assets/images/no-data.svg" alt="no-data">
                <small class="text-center t-capitalize t-mt-2" >No Data Found.</small>
            </div> 
        </div>
        <div v-else class=" t-justify-center t-items-center t-w-full p-5 t-grid" >
            <img class="t-w-[400px] t-border-2 t-border-white t-border-b-slate-300" src="../../../assets/images/no-department.png" alt="no-data">
            <small class="text-center t-capitalize t-mt-2" >Please select department first.</small>
        </div> 
    </div>
    <!-- modal -->
    <div v-show="props.is_modal" class="t-top-0 t-left-0 t-fixed t-w-screen t-h-screen t-bg-black/50 p-5" >
        <div class="t-bg-white t-w-full t-h-full t-rounded-[10px] t-p-5 t-grid t-grid-rows-[120px,1fr]" >
            <div class="" >
                <div class="t-flex t-justify-between" >
                    <div class="t-flex t-items-center t-font-bold" >
                        <!-- <h4 class="text-muted t-uppercase" >{{ selected_schedule }}</h4> -->
                    </div>
                    <button @click="closeModal" class="btn btn-danger" >
                        <fa icon="close" ></fa>
                    </button>
                </div>
                <hr>
                <div class="t-flex t-justify-between" >
                    <div class="" >
                        <select @change="change_semester" v-model="selected_semester" class="form-select" >
                            <option value="1st semester" >1st Semester</option>
                            <option value="2nd semester" >2nd Semester</option> 
                        </select>
                    </div>
                    <router-link :to="'/print/'+identifier+'/'+selected_schedule+'/'+selected_semester" target="_blank" class="btn btn-primary  " >
                        <span class="t-grid t-grid-cols-[30px,1fr]" >
                            <div class="t-flex t-items-center" >
                                <fa icon="print" ></fa>
                            </div>
                            <div class="t-flex t-items-center" >
                                <span class="t-capitalize t-m-0 t-p-0 w-100" >Print Schedule</span>
                            </div>
                        </span>
                    </router-link>
                </div>
            </div>
            <div class="t-grow t-overflow-auto" >
                <scheduleComponent :schedules="props.scheduleData" ></scheduleComponent>
            </div>
        </div>
    </div>
</template>

<script setup >
import { ref,defineAsyncComponent} from 'vue';
const scheduleComponent = defineAsyncComponent(()=>import('../schedule-component/schedule.vue'));

const props = defineProps({
    lists: {
        type: Array,
        required: true
    },
    department: {
        type: String,
        required: true,
    },
    is_modal: {
        type:Boolean,
        required: true,
    },
    scheduleData: {
        type:Array,
        required: true,
    },
    selectedSemester:{
        type:String,
        required: true,
    },
    selectedID:{
        type:null,
        required: true
    },
    identifier:{
        type:String,
        required:true,
    }
});

const selected_schedule = ref('');
const selected_semester = ref(props.selectedSemester)

const openModal_readSchedule = (selected)=>{
    read_schedule(selected,selected_semester.value)
    selected_schedule.value = selected;
    change_id( selected_schedule.value)
    openModal();
}

const emits = defineEmits(['open_modal','read_schedule','change_semester','change_id']);

const openModal = () => {
    emits('open_modal');
};

const closeModal = ()=>{
    emits('open_modal');
    selected_schedule.value = '';
}

const read_schedule = (selected,semester)=>{
    emits('read_schedule',selected,semester)
}

const change_semester = ()=>{
    emits('change_semester',selected_semester.value)
}

const change_id = (id)=>{
    emits('change_id',id);
}
</script>