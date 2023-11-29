<template>
    <div v-if="computingLoading" >
        loading
    </div>
    <section v-else class=" t-bg-slate-100 print:t-bg-white
     md:print:t-p-[20px] lg:t-pl-[180px] lg:t-pr-[180px] lg:t-pt-[80px] lg:t-pb-[80px]" >
        <div class="t-bg-white t-p-3 t-flex t-justify-end t-mb-2 t-rounded-[10px] print:t-hidden" >
            <button @click="printPage" class="btn btn-primary t-w-[200px]" >
                <fa icon="print" ></fa>
                Print
            </button>
        </div>
        <div v-for="print in computed_printData" class="t-bg-white t-rounded-[10px] print:t-rounded-[0px] t-shadow-md print:t-shadow-none t-p-5 print:t-p-0" >
            <!-- <pre>{{ computed_printData }}</pre> -->
            <div class=" t-h-[100px] t-grid t-grid-cols-[120px,1fr] " >
                <div class="t-h-full t-flex t-items-center t-justify-center" > 
                    <img src="../../../assets/images/icon.jpg" class="t-h-[80px] t-rounded-[50%]"  >
                </div>
                <div class="t-h-full t-flex t-items-center" >
                    <div>
                        <small>Republic of the Philippines</small>
                        <h4 class="t-p-o t-m-0" >NUEVA ECIJA UNIVERSITY OF SCIENCE AND TECHNOLOGY</h4>
                        <small>Cabanatua City,Nueva Ecija, Philippines</small>
                    </div>
                </div>
            </div>
            <hr>
            <h6 class="text-center t-p-0 t-m-0" >{{ print.semester }}, Academic Year {{ print.year }}</h6>
            <div>
                <small class="t-uppercase" >{{ identifier }}:&nbsp;</small>
                <strong class="t-uppercase" >{{ print.name }}</strong>
            </div>
            <div class="t-border-2 t-border-slate-300 t-p-2" >
                <scheduleComponent  :schedules="print.schedules" ></scheduleComponent>
            </div>
        </div>
    </section>
</template>

<script setup>
import { ref, onMounted,computed,defineAsyncComponent} from 'vue';
import { useRoute } from 'vue-router';
import { publicStore } from '../../../services/public.js';

const scheduleComponent = defineAsyncComponent(()=>import('../schedule-component/schedule_print.vue'));

const use_publicStore = publicStore();

const activeRequests = ref(0);
const computingLoading = computed(() => {
    return activeRequests.value > 0;
});
const startRequest = () => {
    activeRequests.value++;
};

const endRequest = () => {
    activeRequests.value--;
};

const route = useRoute();

const id = ref(route.params?.id);
const semester = ref(route.params?.semester);
const identifier = ref(route.params?.identifier);

const printData = ref([]);
const computed_printData = computed(()=>{
    return printData.value;
})
const read_print = async ()=>{
    try {
        startRequest()
        await use_publicStore.read_print({
            id: id.value,
            semester: semester.value,
            identifier: identifier.value
        });
        printData.value = use_publicStore.getDataPrint;
        endRequest();
    } catch (error) {
        console.error(error)
    }
}
onMounted(()=>{
    read_print()
})

const printPage = () => {
print()
};

</script>