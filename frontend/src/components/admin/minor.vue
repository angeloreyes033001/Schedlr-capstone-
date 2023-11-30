<template>
    <div  class="container t-select-none" >
        <h3 class="text-muted t-font-black" >SCHEDULE TO OTHER DEPARTMENT</h3>
        <div class="t-mt-3 t-bg-slate-100 t-p-5 t-rounded-[10px]" >
            <div class="" >
                <div class=" t-bg-white t-rounded-[10px] t-shadow t-p-5 t-h-full" >
                    <div class=" t-flex t-justify-end" >
                        <div>
                            <select :disabled="isProcess" class="form-select t-uppercase" v-model="selectedDepartment" >
                                <option class="t-uppercase" selected disabled value="" >Select Department</option>
                                <option v-for="dep in departmentData" class="t-uppercase" :value="dep.department"  >{{ dep.department }}</option>
                            </select>
                        </div>
                    </div>
                    <hr>
                    <div class="t-flex t-justify-between t-gap-2" >
                        <div class="t-flex t-gap-2" >
                            <button @click="change_tab('professor')" :disabled="isProcess || selectedDepartment == '' " class="t-w-[200px] btn" :class="{'btn-primary': current_tab === 'professor' , 'btn-outline-secondary': current_tab != 'professor'}" >
                                <fa icon="users" ></fa>
                                professors
                            </button>
                            <button @click="change_tab('classroom')" :disabled="isProcess || selectedDepartment == ''" class="t-w-[200px] btn" :class="{'btn-primary': current_tab === 'classroom', 'btn-outline-secondary': current_tab != 'classroom'}" >
                                <fa icon="people-roof" ></fa>
                                Classroom
                            </button>
                            <button @click="change_tab('section')" :disabled="isProcess || selectedDepartment == ''" class="t-w-[200px] btn" :class="{'btn-primary': current_tab === 'section' , 'btn-outline-secondary': current_tab != 'section'}" >
                                <fa icon="layer-group" ></fa>
                                Section
                            </button>
                        </div>
                        <div class="t-flex t-gap-2" >
                            <div class="t-relative" >
                                <button 
                                    @click="function_modal_add"
                                    :disabled="isProcess || selectedDepartment == ''"  
                                    class="btn btn-primary" >
                                        <fa icon="plus"></fa>
                                        Schedule
                                </button>
                                <div v-show="modal_add" class="t-absolute t-bg-slate-200 t-shadow  t-w-[300px] t-h-[560px] t-rounded-[10px] t-z-10 t-top-[50px] t-left-[-60px] t-p-3 t-grid t-grid-rows-[180px,1fr]" >
                                    <div class="t-relative" >
                                        <div class="t-p-2 t-grid t-justify-center" >
                                            <span class="text-center" >
                                                <div class="t-relative" >
                                                    <img src="../../assets/images/profile.png" alt="profile" class="t-h-[100px]" >
                                                    <div v-if="schedules.professor != ''" @click="remove_selected_professor" class="t-cursor-pointer t-absolute t-items-center t-justify-center t-rounded-[50%] t-top-0 t-right-0 t-h-[40px] t-w-[40px] t-bg-white t-shadow" >
                                                        <fa icon="close" class="t-mt-3 text-danger" ></fa>
                                                    </div>
                                                </div>
                                                <label class="t-mt-2" >
                                                    <span v-if="schedules.professor != ''" class="t-uppercase" >{{ schedules.professor }}</span>
                                                    <span v-else class="t-font-bold" >N/A</span>
                                                </label>
                                            </span>
                                        </div>
                                        <div v-if="schedules.professor ==''"  class="t-mb-1" >
                                            <input v-model="isSearch" class="form-control t-capitalize" placeholder="Search" >
                                        </div>
                                    </div>
                                    <div class="t-h-full t-overflow-auto t-mt-3" >
                                        <div class="t-h-full" v-if="schedules.professor == ''" >
                                            <div v-for="professor in computed_professor" @click="selectProfessor(professor.professorID)" class=" t-cursor-pointer t-mb-2 t-p-2 t-bg-white t-rounded-[5px] t-h-[80px] t-grid t-grid-cols-[50px,1fr] t-gap-2" >
                                                <div class="t-flex t-items-center t-justify-center" >
                                                    <img src="../../assets/images/profile.png" >
                                                </div>
                                                <div class="t-grid t-items-center" >
                                                    <div class="t-overflow-hidden" >
                                                        <h6 class="t-font-bold t-m-0 t-p-0 t-capitalize t-truncate" >{{ professor.fullname }}</h6>
                                                        <small class="t-uppercase" >{{ professor.professorID }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <form @submit.prevent="create_schedule" v-else class="p-1" >
                                            <div class="t-mt-1" >
                                                <select 
                                                    @change="change_load"
                                                    v-model="schedules.subject" 
                                                    class="form-select t-uppercase" 
                                                    :disabled= "isProcess || schedules.professor == ''"
                                                    >
                                                    <option class="" value="" selected disabled >Select Subject</option>
                                                    <option class="t-uppercase" v-for="load in loadData" >{{ load.code }}</option>
                                                </select>
                                            </div>
                                            <div class="t-mt-1" >
                                                <select @change="change_work" v-model="schedules.work"
                                                :disabled= "isProcess || schedules.professor == '' || schedules.subject ==''  "
                                                class="form-select" >
                                                    <option class="" value="" selected disabled >Select Time</option>
                                                    <option value="lecture" v-if="subjectHour.lec > 0" >Lecture</option>
                                                    <option value="laboratory"  v-if="subjectHour.lab > 0"  >Laboratory</option>
                                                </select>
                                            </div>
                                            <div class="t-mt-1" >
                                                <select 
                                                    @change="getClassroomSchedule" 
                                                    v-model="schedules.classroom" 
                                                    class="form-select"
                                                    :disabled="isProcess||schedules.professor == ''||schedules.subject == ''||schedules.timeWork == ''"
                                                        >
                                                        <option class="" value="" selected disabled >Select Classroom</option>
                                                        <option v-for="classroom in classroomData" :value="classroom.id"  >{{classroom.classroom}}</option>
                                                </select>
                                            </div>
                                            <div class="t-mt-1" >
                                                <select 
                                                    @change="getSectionSchedule" 
                                                    v-model="schedules.section" 
                                                    class="form-select"
                                                    :disabled="isProcess||schedules.professor == ''||schedules.subject == ''||schedules.timeWork == '' || schedules.classroom == ''"
                                                        >
                                                        <option class="" value="" selected disabled >Select Section</option>
                                                        <option class="t-uppercase" v-for="section in sectionData" >{{ section.section }}</option>
                                                </select>
                                            </div>
                                            <div class="t-mt-1" >
                                                <select  
                                                    class="t-capitalize form-select" 
                                                    v-model="schedules.day" 
                                                    :disabled="isProcess||schedules.professor == ''||schedules.subject == ''||schedules.timeWork == '' || schedules.classroom == '' || schedules.section == '' "
                                                            >
                                                        <option class="" value="" selected disabled >Day</option>
                                                        <option value="monday" class="t-capitalize" >monday</option>
                                                        <option value="tuesday" class="t-capitalize" >tuesday</option>
                                                        <option value="wednesday" class="t-capitalize" >wednesday</option>
                                                        <option value="thursday" class="t-capitalize" >thursday</option>
                                                        <option value="friday" class="t-capitalize" >friday</option>
                                                        <option value="saturday" class="t-capitalize" >saturday</option>
                                                </select>
                                            </div>
                                            <div class="t-mt-1" >
                                                <select 
                                                    @change="filterTime" 
                                                    :disabled="isProcess||schedules.professor == ''||schedules.subject == ''||schedules.timeWork == '' || schedules.classroom == '' || schedules.section == '' || schedules.day == '' " 
                                                    class="form-select t-capitalize" 
                                                    v-model="schedules.start" >
                                                        <option class="t-capitalize" v-for="time in timers" :key="time.id" :value="time.id" >{{ time.label }}</option>
                                                </select>
                                            </div>
                                            <div class="t-mt-1" >
                                                <select class="form-select" disabled v-model="schedules.end" >
                                                    <option class="" value="" >End Time</option>
                                                    <option class="t-capitalize" v-for="time in endTime" :key="time.id" :value="time.id" >{{ time.label }}</option>
                                                </select>
                                            </div>
                                            <div class="t-mt-1" >
                                                <button class="btn btn-primary t-w-full" >
                                                    <fa icon="save" ></fa>
                                                    Save
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <button 
                                :disabled="isProcess || selectedDepartment == '' || schedules.professor == ''" 
                                class="btn btn-danger" >
                                    <fa icon="trash"></fa>
                                    Remove
                            </button>
                        </div>
                    </div>
                    <hr>
                    <div v-if="selectedDepartment != ''" class="" >
                        <div v-if="current_tab == 'professor' " class="t-mt-2" >
                            <div v-if="professorScheduleData.length > 0" class="" >
                                <scheduleComponent :schedules="professorScheduleData" ></scheduleComponent>
                            </div>
                            <div v-else class=" t-justify-center t-items-center t-w-full p-5 t-grid" >
                                <img class="t-w-[400px] t-border-2 t-border-white t-border-b-slate-300" src="../../assets/images/no-data.svg" alt="no-data">
                                <h6 class="text-center t-capitalize t-mt-2" >Please select professor first.</h6>
                            </div> 
                        </div>

                        <div v-if="current_tab == 'classroom' " class="t-mt-2" >
                            <div v-if="classroomScheduleData.length > 0" class="" >
                                <scheduleComponent :schedules="classroomScheduleData" ></scheduleComponent>
                            </div>
                            <div v-else class=" t-justify-center t-items-center t-w-full p-5 t-grid" >
                                <img class="t-w-[400px] t-border-2 t-border-white t-border-b-slate-300" src="../../assets/images/no-data.svg" alt="no-data">
                                <h6 class="text-center t-capitalize t-mt-2" >Please select classroom first</h6>
                            </div> 
                        </div>

                        <div v-if="current_tab == 'section' " class="t-mt-2" >
                            <div v-if="sectionScheduleData.length > 0" class="" >
                                <scheduleComponent :schedules="sectionScheduleData" ></scheduleComponent>
                            </div>
                            <div v-else class=" t-justify-center t-items-center t-w-full p-5 t-grid" >
                                <img class="t-w-[400px] t-border-2 t-border-white t-border-b-slate-300" src="../../assets/images/no-data.svg" alt="no-data">
                                <h6 class="text-center t-capitalize t-mt-2" >Please select section first</h6>
                            </div> 
                        </div>
                    </div>
                    <div v-else class=" t-justify-center t-items-center t-w-full p-5 t-grid" >
                        <img class="t-w-[400px] t-border-2 t-border-white t-border-b-slate-300" src="../../assets/images/no-department.png" alt="no-data">
                        <h6 class="text-center t-capitalize t-mt-2" >Please select department.</h6>
                    </div> 
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import {ref,inject,computed,defineAsyncComponent} from 'vue';
import {minorStore} from '../../services/minors.js';
import {scheduleStore} from '../../services/schedule';
import Swal from 'sweetalert2';

const scheduleComponent = defineAsyncComponent(()=>import('../partials/schedule-component/schedule.vue'))

const use_minorStore = minorStore();
const use_scheduleStore = scheduleStore();

const globalDepartmentData = inject("globalDepartmentData");
 const departmentData = ref(globalDepartmentData);

const isProcess = ref(false);
const isSearch = ref('');
const selectedDepartment = ref('');
const current_tab = ref('professor');
const change_tab = (change)=>{
    current_tab.value = change;
}

const schedules = ref({
    day: '',
    start: 0,
    end: 1,
    work: "",
    timeWork: '',
    professor: '',
    subject: '',
    classroom: '',
    section: '',
    semester: '1st semester',
});

const timers = ref([
    {id: 0, label: "7:00 AM"},{id: 1, label: "7:30 AM"},
    {id: 2, label: "8:00 AM"},{id: 3, label: "8:30 AM"},
    {id: 4, label: "9:00 AM"},{id: 5, label: "9:30 AM"},
    {id: 6, label: "10:00 AM"},{id: 7, label: "10:30 AM"},
    {id: 8, label: "11:00 AM"},{id: 9, label: "11:30 AM"},
    {id: 10, label: "12:00 NN"},{id: 11, label: "12:30 PM"},
    {id: 12, label: "1:00 PM"},{id: 12, label: "1:30 PM"},
    {id: 14, label: "2:00 PM"},{id: 15, label: "2:30 PM"},
    {id: 16, label: "3:00 PM"},{id: 17, label: "3:30 PM"},
    {id: 18, label: "4:00 PM"},{id: 19, label: "4:30 PM"},
    {id: 20, label: "5:00 PM"},{id: 21, label: "5:30 PM"},
    {id: 22, label: "6:00 PM"},{id: 23, label: "6:30 PM"},
    {id: 24, label: "7:00 PM"},{id: 25, label: "7:30 PM"},
    {id: 26, label: "8:00 PM"},{id: 27, label: "8:30 PM"},
    {id: 28, label: "9:00 PM"},{id: 29, label: "9:30 PM"},
    {id: 30, label: "10:00 PM"},
]);

const endTime = ref([]);
const filterTime = ()=>{

    let time = 0;
    if(schedules.value.work == "lecture"){
        time = subjectHour.value.lec * 2;
    }else{
        time = subjectHour.value.lab * 2;
    }

    let total = (schedules.value.start + time) ;
    let data = timers.value.filter((item)=>item.id >= (schedules.value.start + time) && item.id <= 30 );
    if(data.length ==0){
        data = timers.value.filter((item)=>item.id>=(30-time)&&item.id<=30)
        schedules.value.end = 30;
        schedules.value.start = data[0].id;
    }else{

        schedules.value.end = total;
    }
    endTime.value = data;

    

}

const modal_add = ref(false);
const function_modal_add = ()=>{
    if(modal_add.value){
        modal_add.value =false;
    }
    else{
        modal_add.value = true;
        getProfessor();
    }
}

const professorData = ref([]);
const computed_professor = computed(() => {
    if (isSearch.value !== '') {
        return professorData.value.filter((item) => item.fullname.toLowerCase().includes(isSearch.value.toLowerCase()));
    } else {
        return professorData.value;
    }
});

const getProfessor = async ()=>{
    try {
        await use_minorStore.getProfessor();
        professorData.value = use_minorStore.getResponse;
    } catch (error) {
        console.log(error);
    }
}

const selectProfessor = (professor)=>{
    schedules.value.professor = professor;
    getLoads();
    getProfessorSchedule()
}

const remove_selected_professor= ()=>{
    schedules.value = {
    day: '',
    start: 0,
    end: 1,
    work: "",
    timeWork: '',
    professor: '',
    subject: '',
    classroom: '',
    section: '',
    semester: '1st semester',
}

}

const loadData = ref([]);
const getLoads = async ()=>{
    try {
        isProcess.value = true;

        await use_scheduleStore.getLoads(schedules.value.professor);
        loadData.value = use_scheduleStore.getResponse;

        isProcess.value = false;
    } catch (error) {
        console.error(error)
    }
}

const subjectHour = ref({
    lab: 0,
    lec: 0,
})

const change_load = ()=>{
    selectedSubject()
    getSections()
}

const selectedSubject = ()=>{
    const response = loadData.value.find(item => item.code === schedules.value.subject);
    subjectHour.value.lec  =response.lec;
    subjectHour.value.lab  =response.lab;
}

const change_work = ()=>{
    schedules.value.classclassroom = "";
    selectedHour()
    getClassrooms()
}

const selectedHour  = ()=>{
    
    if(schedules.value.work === "lecture"){
        schedules.value.timeWork = subjectHour.value.lec;
    }
    else{
        schedules.value.timeWork = subjectHour.value.lab;
    }
}

const classroomData = ref([]);
const getClassrooms = async()=>{
    try {
        isProcess.value = true;
        await use_minorStore.getClassrooms({
            department:selectedDepartment.value,
            type:schedules.value.work
        });
        classroomData.value = use_minorStore.getResponse;
        isProcess.value= false;
    } catch (error) {
        console.error(error)
    }
}

const sectionData = ref([]);
const getSections = async()=>{
    try {
        isProcess.value = true;
        await use_minorStore.getSections({
            subject: schedules.value.subject,
            department: selectedDepartment.value
        });
        sectionData.value = use_minorStore.getResponse;
        isProcess.value= false;
    } catch (error) {
        console.error(error)
    }
}

const create_schedule = ()=>{
    try {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes,Save it!"
        }).then((result) => {
            if (result.isConfirmed) {
                console.log({...schedules.value})
            }
        });
    } catch (error) {
        console.error(error);
    }
}

const professorScheduleData = ref([]);
const getProfessorSchedule = async ()=>{
    try {
        await use_scheduleStore.getProfessorSchedule(schedules.value.professor)
        professorScheduleData.value = use_scheduleStore.getSchedule;
        current_tab.value = "professor"
    } catch (error) {
        console.error(error);
    }
}

const classroomScheduleData = ref([]);
const getClassroomSchedule = async ()=>{
    try {
        await use_scheduleStore.getClassroomSchedule(schedules.value.classroom)
        classroomScheduleData.value = use_scheduleStore.getSchedule;
        current_tab.value = "classroom"
    } catch (error) {
        console.error(error);
    }
}

const sectionScheduleData = ref([]);
const getSectionSchedule = async ()=>{
    try {
        await use_scheduleStore.getSectionSchedule(schedules.value.section)
        sectionScheduleData.value = use_scheduleStore.getSchedule;
        current_tab.value = "section"
    } catch (error) {
        console.error(error);
    }
}

</script>