<template>
    <div  class="container t-select-none" >
        <h3 class="text-muted t-font-black" >Manually</h3>
        <div class="t-mt-3 t-bg-slate-100 t-p-5 t-rounded-[10px]" >
            <div class="" >
                <div class="t-p-2" >
                    <div class="t-bg-white t-rounded-[10px] t-shadow t-p-5 t-h-full" >
                        <div class="t-w-[200px] t-m-3 t-relative" >
                           <input @focus="function_show_modal_professor" v-model="isSearch" placeholder="Search Professor" class="form-control" >
                           <div v-show="showModalProfessor" class="t-absolute t-top-[60px] t-w-[400px] t-shadow t-rounded-[10px] t-p-3 t-bg-slate-200" >
                                <div class="t-grid t-grid-cols-[1fr,60px]" >
                                    <span>All Professor</span>
                                    <button @click="function_hide_modal_professor" class="btn btn-danger" >
                                        <fa icon="close" ></fa>
                                    </button>
                                </div>
                                <hr>
                                <div v-for="professor in computedProfessor" @click="selectedProfessor(professor.professorID)" class=" t-cursor-pointer t-relative t-w-full t-mt-2 t-bg-slate-100 t-p-2 t-rounded-[10px]" >
                                    <div class="t-flex" >
                                        <div class="" >
                                            <img src="../../assets/images/profile.png"  class="t-h-[80px]">
                                        </div>
                                        <div class="t-flex t-items-center" >
                                            <div class="" >
                                                <div class="t-grid t-grid-cols-2 t-gap-1" >
                                                    <span>ID:</span>
                                                    <span>{{ professor.professorID }}</span>
                                                </div>
                                                <div class="t-grid t-grid-cols-2 t-gap-1" >
                                                    <span>Fulname:</span>
                                                    <span>{{ professor.fullname }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="t-mt-3 t-bg-white t-p-5 t-shadow t-rounded-[10px]">
                            <div class="t-grid t-grid-cols-4 t-h-[50px] t-bg-slate-300" >
                                <span class="t-h-full t-font-semibold t-flex t-items-center t-pl-[5px]">
                                    <label>Subject</label>
                                </span>
                                <span class="t-h-full t-font-semibold t-flex t-items-center t-pl-[5px]">
                                    <label>Assigned Hour</label>
                                </span>
                                <span class="t-h-full t-font-semibold t-flex t-items-center t-pl-[5px]">
                                    <label>Lec Hour's</label>
                                </span>
                                <span class="t-h-full t-font-semibold t-flex t-items-center t-pl-[5px]">
                                    <label>Lab Hour's</label>
                                </span>
                            </div>
                            <div v-for="loads in loadData"  class="t-grid t-grid-cols-4 t-h-[40px] even:t-bg-white odd:t-bg-slate-100" >
                                <span class="t-h-full t-font-extralight t-flex t-items-center t-pl-[5px]" >
                                    <label class="t-text-[14px]" >{{ loads.code }}</label>
                                </span>
                                <span class="t-h-full t-font-extralight t-flex t-items-center t-pl-[5px]" >
                                    <label class="t-text-[14px]" >{{ loads.givenHour }}</label>
                                </span>
                                <span class="t-h-full t-font-extralight t-flex t-items-center t-pl-[5px]" >
                                    <label class="t-text-[14px]" >{{ loads.lec }}</label>
                                </span>
                                <span class="t-h-full t-font-extralight t-flex t-items-center t-pl-[5px]" >
                                    <label class="t-text-[14px]" >{{ loads.lab }}</label>
                                </span>
                            </div>
                        </div>
                        <div class="t-flex t-justify-between t-mt-3" >
                            <form @submit.prevent="create_schedule" class="t-flex t-flex-auto t-gap-2" >
                                <div class="form-grooup" >
                                    <select class="form-select" :disabled="isProcess || schedules.professor == '' " v-model="schedules.subject"  @change="change_load" >
                                        <option value="" >Subject</option>
                                        <option v-for="loads in loadData" :value="loads.code" >{{loads.code}}</option>
                                    </select>
                                </div>
                                <div class="form-grooup" >
                                    <select @change="change_work" :disabled="isProcess || schedules.professor == '' || schedules.subject == '' "  class="form-select" v-model="schedules.work" >
                                        <option value="" disabled selected >Time</option>
                                        <option v-show="schedules.subject !='' && subjectHour.lec > 0 " value="lecture" >Lecture</option>
                                        <option v-show="schedules.subject !='' && subjectHour.lab > 0 " value="laboratory" >Laboratory</option>
                                    </select>
                                </div>
                                <div class="form-grooup" >
                                    <select @change="getClassroomSchedule" :disabled="isProcess||schedules.professor == ''||schedules.subject == ''||schedules.timeWork == ''" class="form-select t-capitalize" v-model="schedules.classroom" >
                                        <option class="t-capitalize" disabled select value="" >Classroom</option>
                                        <option class="t-capitalize" v-for="classroom in classroomData" :value="classroom.id" >{{ classroom.classroom }}</option>
                                    </select>
                                </div>
                                <div class="form-grooup" >
                                    <select @change="getSectionSchedule" :disabled="isProcess||schedules.professor == ''||schedules.subject == ''||schedules.timeWork == '' || schedules.classroom == ''" class="form-select" v-model="schedules.section" >
                                        <option value="" selected disabled >Section</option>
                                        <option v-for="section in sectionData" :value="section.section" >{{ section.section }}</option>
                                        
                                    </select>
                                </div>
                                <div class="form-grooup" >
                                    <select :disabled="isProcess||schedules.professor == ''||schedules.subject == ''||schedules.timeWork == '' || schedules.classroom == '' || schedules.section == '' " class="form-select" v-model="schedules.day" >
                                        <option value="" >Day</option>
                                        <option value="monday" >Monday</option>
                                        <option value="tuesday" >Tuesday</option>
                                        <option value="wednesday" >Wednesday</option>
                                        <option value="thursday" >Thursday</option>
                                        <option value="friday" >Friday</option>
                                        <option value="saturday" >Saturday</option>
                                    </select>
                                </div>
                                <div class="form-grooup" >
                                    <select @change="filterTime" :disabled="isProcess||schedules.professor == ''||schedules.subject == ''||schedules.timeWork == '' || schedules.classroom == '' || schedules.section == '' || schedules.day == '' " class="form-select t-capitalize" v-model="schedules.start" >
                                        <option class="t-capitalize" v-for="time in timers" :key="time.id" :value="time.id" >{{ time.label }}</option>
                                    </select>
                                </div>
                                <div class="form-grooup" >
                                    <select class="form-select" disabled v-model="schedules.end" >
                                        <option class="" value="" >End Time</option>
                                        <option class="t-capitalize" v-for="time in endTime" :key="time.id" :value="time.id" >{{ time.label }}</option>
                                    </select>
                                </div>
                                <div>
                                    <button :disabled="isProcess||schedules.professor == ''||schedules.subject == ''||schedules.timeWork == '' || schedules.classroom == '' || schedules.section == '' || schedules.day == '' " type="submit" class="btn btn-primary " >
                                        <fa icon="save" ></fa>
                                    </button>
                                </div>
                            </form>
                            <div class="t-relative" >
                                <button :disabled="isProcess || schedules.professor =='' " @click="showDeleteData" class="btn btn-danger w-100" >
                                    <fa icon="trash" ></fa>
                                    Remove Schedule
                                </button>
                                <div v-show="showModalDelete" class="t-z-10 t-top-[50px] t-left-[-110px] t-rounded-[10px] t-absolute t-p-5 t-h-[400px] t-overflow-auto t-w-[300px] t-bg-slate-300" >
                                    <div class="t-flex t-justify-end" >
                                        <button @click="function_hide_modal_delete" class="btn text-danger" >
                                            <fa icon="close" ></fa>
                                        </button>
                                    </div>
                                    <hr>
                                    <div v-for="schedule in ShowAvailableDeleteData" class="t-p-3 t-bg-white t-rounded-[10px] t-h-[85px] t-mt-2 t-mb-2 " >
                                        <div class=" t-grid t-grid-cols-[1fr,40px]" >
                                            <span>
                                                <h6 class="t-p-0 t-m-0 t-uppercase" >{{schedule.subject}}</h6>
                                                <h6 class=" t-font-extralight t-text-[14px] t-uppercase t-p-0 t-m-0" ><strong>{{schedule.section}}</strong>({{ schedule.classroom }})</h6>
                                                <h6 class=" t-font-extralight t-text-[14px] t-uppercase t-p-0 t-m-0" ><strong>{{schedule.day}}</strong>({{ schedule.time }})</h6>
                                            </span>
                                            <div  class="t-flex t-items-center t-justify-center" >
                                                <button @click="delete_schedule(schedule.id)" class="btn btn-danger" >
                                                    <fa icon="trash" ></fa>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="t-mt-3 t-bg-white t-rounded-[10px] t-shadow t-p-5" >
                <div class="t-flex t-gap-2" >
                    <button @click="change_tab('professor')" :disabled="isProcess" class="t-w-[200px] btn" :class="{'btn-primary': current_tab === 'professor' , 'btn-outline-secondary': current_tab != 'professor'}" >
                        <fa icon="users" ></fa>
                        professors
                    </button>
                    <button @click="change_tab('classroom')" :disabled="isProcess" class="t-w-[200px] btn" :class="{'btn-primary': current_tab === 'classroom', 'btn-outline-secondary': current_tab != 'classroom'}" >
                        <fa icon="people-roof" ></fa>
                        Classroom
                    </button>
                    <button @click="change_tab('section')" :disabled="isProcess" class="t-w-[200px] btn" :class="{'btn-primary': current_tab === 'section' , 'btn-outline-secondary': current_tab != 'section'}" >
                        <fa icon="layer-group" ></fa>
                        Section
                    </button>
                </div>
                <div class="" >
                    <!-- professor schedule -->
                    <div v-if="current_tab == 'professor' " class="t-mt-2" >
                        <div v-if="professorScheduleData.length > 0" class="" >
                            <scheduleComponent :schedules="professorScheduleData" ></scheduleComponent>
                        </div>
                        <div v-else class=" t-justify-center t-items-center t-w-full p-5 t-grid" >
                            <img class="t-w-[400px] t-border-2 t-border-white t-border-b-slate-300" src="../../assets/images/no-data.svg" alt="no-data">
                            <h6 class="text-center t-capitalize t-mt-2" >Please select professor first.</h6>
                        </div> 
                    </div>
                    <!-- classroom schedule -->
                    <div v-if="current_tab == 'classroom' " class="t-mt-2" >
                        <div v-if="classroomScheduleData.length > 0" class="" >
                            <scheduleComponent :schedules="classroomScheduleData" ></scheduleComponent>
                        </div>
                        <div v-else class=" t-justify-center t-items-center t-w-full p-5 t-grid" >
                            <img class="t-w-[400px] t-border-2 t-border-white t-border-b-slate-300" src="../../assets/images/no-data.svg" alt="no-data">
                            <h6 class="text-center t-capitalize t-mt-2" >Please select classroom first</h6>
                        </div> 
                    </div>
                    <!-- section schedule -->
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
            </div>
        </div>
    </div>
</template>
<script setup>
import { ref,inject,computed,defineAsyncComponent } from 'vue';
import Swal from 'sweetalert2';
import {scheduleStore} from '../../services/schedule';

const scheduleComponent = defineAsyncComponent(()=>import('../partials/schedule-component/schedule.vue'))
const use_scheduleStore = scheduleStore();


const isProcess = ref(false);
const isSearch = ref('');

const showModalProfessor = ref('');
const showModalDelete = ref('');

const function_show_modal_professor = ()=>{
    showModalProfessor.value= true;
}

const function_show_modal_delete = ()=>{
    showModalDelete.value= true;
}

const function_hide_modal_professor = ()=>{
    showModalProfessor.value = false;
}

const function_hide_modal_delete = ()=>{
    showModalDelete.value = false;
}


const globalProfessorData = inject('globalProfessorData');   
const professorData = ref(globalProfessorData);
const computedProfessor = computed(()=>{
    if(isSearch.value != ""){
        return professorData.value.filter(professor=>{
            return professor.fullname.toLowerCase().includes(isSearch.value.toLowerCase());
        })
    }
    else{
        return professorData.value;
    }
})

const current_tab = ref('professor');
const change_tab = (change)=>{
    current_tab.value = change;
}

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
    console.log(time)
    let total = (schedules.value.start + time) ;
    let data = timers.value.filter((item)=>item.id >= (schedules.value.start + time) && item.id <= 30 );
    console.log(data)
if(data.length ==0){
    data = timers.value.filter((item)=>item.id>=(30-time)&&item.id<=30)
    schedules.value.end = 30;
    schedules.value.start = data[0].id;
}else{

    schedules.value.end = total;
}
console.log(data)
endTime.value = data;

    

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

const selectedProfessor = (professor)=>{
    schedules.value.professor = professor;
    getLoads(professor)
    isSearch.value = "";
    getProfessorSchedule()
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
        await use_scheduleStore.getClassrooms(schedules.value.work);
        classroomData.value = use_scheduleStore.getResponse;
        isProcess.value= false;
    } catch (error) {
        console.error(error)
    }
}

const sectionData = ref([]);
const getSections = async()=>{
    try {
        isProcess.value = true;
        await use_scheduleStore.getSections(schedules.value.subject);
        sectionData.value = use_scheduleStore.getResponse;
        isProcess.value= false;
    } catch (error) {
        console.error(error)
    }
}

const loadData = ref([]);
const getLoads = async (professor)=>{
    try {
        isProcess.value = true;

        await use_scheduleStore.getLoads(professor);
        loadData.value = use_scheduleStore.getResponse;

        isProcess.value = false;
    } catch (error) {
        console.error(error)
    }
}

const create_schedule = async()=>{
    try {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes,Save it!"
        }).then(async(result) => {
            if (result.isConfirmed) {
                await  use_scheduleStore.create_schedule({
                    day:schedules.value.day,
                    start: schedules.value.start,
                    end: schedules.value.end,
                    professor: schedules.value.professor,
                    subject: schedules.value.subject,
                    classroom: schedules.value.classroom,
                    section: schedules.value.section
                });

                const response = use_scheduleStore.getResponse;
                if(response.status){
                    getProfessorSchedule();
                    getClassroomSchedule();
                    getSectionSchedule();
                    Swal.fire('Success',response.msg,'success');
                }
                else{
                    Swal.fire("Error",response.msg,"error");
                }
            }
        });
    } catch (error) {
        console.error(error)
    }
}


const showDeleteData = ()=>{
    getShowAvailableDelete()
    function_show_modal_delete()
}


const ShowAvailableDeleteData = ref([]);
const getShowAvailableDelete= async()=>{
    try {
        isProcess.value = true;
        await use_scheduleStore.getScheduleAvailbleDelete(schedules.value.professor);
        ShowAvailableDeleteData.value = use_scheduleStore.getResponse;
        isProcess.value = false;
    } catch (error) {
        console.error(error);
    }
}

const delete_schedule = async(id)=>{
    try {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then(async(result) => {
            if (result.isConfirmed) {
                isProcess.value = true;

                await use_scheduleStore.delete_schedule(id);
                const response = use_scheduleStore.getResponse;
                if(response.status){
                    getShowAvailableDelete();
                    getProfessorSchedule();
                    Swal.fire("Success",response.msg,'success');
                }

                isProcess.value = false;
            }
        });
    } catch (error) {
        console.error(error)
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