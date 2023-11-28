<template>
    <div v-if="computingLoading" class="t-w-screen t-h-screen t-flex t-items-center t-justify-center" >
        Loading
    </div>
    <section v-else class=" t-select-none t-relative bg-center t-top-0 t-left-0 t-bg-[url('./assets/images/login_background.png')] t-bg-cover t-bg-center t-bg-[../../../assets/images/images.login_background.png] t-h-screen t-w-screen" >
        <div class="t-absolute t-w-full t-h-full t-bg-gradient-to-r t-from-blue-400 t-opacity-60 t-to-green-300 t-bg-opacity-50" ></div>
        <div class="t-absolute t-z-10 t-w-full t-h-full" >
            <div class="container t-p-[20px]" >
                <div class="t-flex t-justify-between t-gap-2 t-bg-white t-p-5 t-rounded-[10px]" >
                    <div class="" >
                        <h3 class="t-font-bold t-p-0 t-hidden sm:t-flex text-muted" >Schedules</h3>
                    </div>
                    <div class="" >
                        <router-link to="/" class="btn btn-outline-secondary t-w-[200px]" >
                            <fa icon="reply"></fa>
                            Home
                        </router-link>
                    </div>
                </div>
                <div class="t-bg-white t-p-5 t-mt-3 t-rounded-[10px]" >
                    <div class="t-flex t-justify-between t-gap-2" >
                        <div class="" >
                            <input v-model="isSearch" type="text" class="form-control t-capitalize" placeholder="Search" >
                        </div>
                        <div class="" >
                            <select v-model="selectedDepartment" @change="getInformation" class="form-select t-uppercase" > 
                                <option class="text-uppercase" disabled selected value="" >Select Department</option>
                                <option class="text-uppercase" v-for="dep in departmentData" :value="dep.department"  >{{ dep.department }}</option>
                            </select>
                        </div>
                    </div>
                    <hr>
                    <div class="t-flex t-gap-3" >
                        <button @click="change_tab('professor')" :disabled="isProcess || selectedDepartment == ''" class="t-w-[50px] md:t-w-[200px] btn" :class="{'btn-primary': current_tab === 'professor', 'btn-outline-secondary': current_tab != 'professor' }" >
                            <fa icon="users" ></fa>
                            <span class="t-hidden md:t-inline" >&nbsp;Professors</span>
                        </button>
                        <button @click="change_tab('classroom')" :disabled="isProcess || selectedDepartment == ''" class="t-w-[50px] md:t-w-[200px] btn" :class="{'btn-primary': current_tab === 'classroom', 'btn-outline-secondary': current_tab != 'classroom' }" >
                            <fa icon="people-roof" ></fa>
                            <span class="t-hidden md:t-inline" >&nbsp;Classroom</span>
                        </button>
                        <button @click="change_tab('section')" :disabled="isProcess || selectedDepartment == ''" class="t-w-[50px] md:t-w-[200px] btn" :class="{'btn-primary': current_tab === 'section' , 'btn-outline-secondary': current_tab != 'section'}"  >
                            <fa icon="layer-group" ></fa>
                            <span class="t-hidden md:t-inline" >&nbsp;Section</span>
                        </button>
                    </div>
                    <div class="" >
                        <!-- professor schedule -->
                        <div v-if="current_tab == 'professor' " class="t-mt-2" >
                            <div class="" >
                                <professorSchedule 
                                    :lists="computed_list" 
                                    :department="selectedDepartment" 
                                    :is_modal="is_modal"
                                    :scheduleData="scheduleData"
                                    :selectedSemester="selectedSemester"
                                    :selectedID="selectedID"
                                    @change_id="change_id"
                                    @open_modal="open_modal"
                                    @read_schedule="read_schedule"
                                    @change_semester="change_semester"    >
                                </professorSchedule>
                            </div>
                        </div>
                        <!-- classroom schedule -->
                        <div v-if="current_tab == 'classroom' " class="t-mt-2" >
                            <div class="" >
                                <classroomSchedule 
                                    :lists="computed_list" 
                                    :department="selectedDepartment" 
                                    :is_modal="is_modal"
                                    :scheduleData="scheduleData"
                                    :selectedSemester="selectedSemester"
                                    :selectedID="selectedID"
                                    @change_id="change_id"
                                    @open_modal="open_modal"
                                    @read_schedule="read_schedule"
                                    @change_semester="change_semester"    >
                                </classroomSchedule>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
<script setup >
import {ref, defineAsyncComponent,computed,onMounted} from 'vue';
import {departmentStore} from '../../../services/departments.js'
import {publicStore} from '../../../services/public.js'
import {scheduleStore} from '../../../services/schedule.js'
const use_departmentStore = departmentStore();
const use_publicStore = publicStore();
const use_scheduleStore = scheduleStore();
const professorSchedule = defineAsyncComponent(()=>import('../public-component/professorSchedule.vue'));
const classroomSchedule = defineAsyncComponent(()=>import('../public-component/classroomSchedule.vue'));

const isProcess = ref(false);
const isSearch = ref('');

const selectedDepartment = ref('');
const selectedID = ref('');
const change_id = (id)=>{
    selectedID.value = id;
}
const selectedSemester = ref('1st semester');
const change_semester = (semester)=>{
    selectedSemester.value = semester;
    read_schedule(selectedID.value,selectedSemester.value)
}

const current_tab = ref('professor');
const change_tab = (tab)=>{
    
    isSearch.value = '';
    selectedSemester.value = "1st semester";
    lists.value = [];
    scheduleData.value = []

    current_tab.value = tab;
    isSearch.value = "";
    getInformation();
}

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

const  departmentData = ref([]);
const globalFetchDepartments = async ()=>{
    startRequest();
    await use_departmentStore.read();
    const response = use_departmentStore.getDepartments;
    departmentData.value = response;
    endRequest();
}

const scheduleData = ref([]);
const read_schedule = async (selected,semester)=>{
    try {
        if(current_tab.value == "professor"){
            await use_publicStore.read_professor_schedule({professor:selected , semester: semester});
        }

        if(current_tab.value == "classroom"){
            await use_publicStore.read_classroom_schedule({classroom:selected , semester: semester});
        }
        scheduleData.value = use_publicStore.getSchedule;
    } catch (error) {
        console.error(error)
    }
}

const lists = ref([]);
const computed_list = computed(() => {
  if (current_tab.value == "professor") {
    if (isSearch.value != '') {
      return lists.value.filter((item) =>
        item.fullname.toLowerCase().includes(isSearch.value.toLowerCase())
      );
    } else {
      return lists.value;
    }
  }

  if (current_tab.value == "classroom") {
    if (isSearch.value != '') {
      return lists.value.filter((item) =>
        item.classroom.toLowerCase().includes(isSearch.value.toLowerCase())
      );
    } else {
      return lists.value;
    }
  }
});
const getInformation = async ()=>{
    try {
        isProcess.value = true;
        switch (current_tab.value) {
            case "professor":
                await use_publicStore.read_professor(selectedDepartment.value);
                lists.value = use_publicStore.getResponse;
                break;
                
            case "classroom":
                await use_publicStore.read_classroom(selectedDepartment.value);
                lists.value = use_publicStore.getResponse;
                break;
            case "section":
                console.log("section",selectedDepartment.value)
                break;
    
            default:
                break;
        }
        isProcess.value = false;
    } catch (error) {
        console.log(error);
    }
}

const is_modal = ref(false);
const open_modal = ()=>{
    is_modal.value = (is_modal.value)  ? false : true;
}

onMounted(()=>{
    globalFetchDepartments();
})

</script>