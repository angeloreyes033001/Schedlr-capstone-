<template>
    <div class="t-h-screen t-w-full t-overflow-hidden t-select-none" >
        <div class="t-h-full t-grid t-grid-cols-[1fr,350px]" >
            <div class="t-overflow-hidden t-h-full" >
                <div class="p-4 t-h-full" >
                    <div class="t-flex t-justify-between t-relative" >
                        <div class="t-flex t-h-[40px]" >
                            <div @click="showClassroomSchedule" :class="{'t-bg-slate-200 t-font-semibold ':defaultData == 'classroom'}" class="t-w-[130px] t-bg-slate-100 t-h-full t-flex t-pl-[10px] t-items-center t-rounded-t-[10px]" >Classroom</div>
                            <div @click="showSectionSchedule" :class="{'t-bg-slate-200 t-font-semibold ':defaultData == 'section'}" class="t-w-[130px] t-bg-slate-100 t-h-full t-flex t-pl-[10px] t-items-center t-rounded-t-[10px]" >Section</div>
                        </div>
                        <div class="t-relative" >
                            <div class="t-relative " >
                                <button :disabled="selectedProfessorData.professor == '' " @click="showModalButton('delete')" class="btn btn-outline-danger" ><fa icon="trash" ></fa></button>&nbsp;
                                <button :disabled="selectedProfessorData.professor == '' " @click="showModalButton('form')" class="btn btn-outline-primary" ><fa icon="plus" ></fa></button>
                                <div v-show="showButtonModal !=''" class="t-overflow-auto t-p-3 t-absolute t-h-[550px] t-w-[300px] bg-white t-shadow t-right-0 t-top-[50px] t-z-50" >
                                    <div v-show="showButtonModal == 'form'" class="">
                                        <form @submit.prevent="createSchedule" >
                                            <div class="form-group mt-1">
                                                <label for="" class="t-uppercae fw-bold">Semester</label>
                                                <select v-model="schedules.semester" @change="changeSemester" class="form-select">
                                                    <option value="1st semester">1st Semester</option>
                                                    <option value="2nd semester">2nd Semester</option>
                                                </select>
                                            </div>
                                            <div class="form-group mt-1">
                                                <label for="" class="t-uppercae fw-bold">Subject</label>
                                                <select @change="changeSubject" v-model="schedules.subject" class="form-select text-uppercase">
                                                    <option select disabled class="text-uppercase" value="" >SELECT SUBJECT</option>
                                                    <option class="text-uppercase"  v-for="subject in subjectData" :value="subject.code">{{ subject.code }}</option>
                                                </select>
                                            </div><div class="form-group mt-1">
                                                <label for="" class="t-uppercae fw-bold">Classroom</label>
                                                <select :disabled="schedules.subject ==''" v-model="schedules.classroom" class="form-select text-uppercase">
                                                    <option select disabled value="" selected >SELECT Classroom</option>
                                                    <option v-for="classroom in newClassroomComputed" :key="classroom.id" :value="classroom.id">{{ classroom.room }} - <span class="text-uppercase" >{{ classroom.department }}</span> </option>
                                                </select>
                                            </div>
                                            <div class="form-group mt-1">
                                                <label for="" class="t-uppercae fw-bold">Section</label>
                                                <select :disabled="schedules.classroom ==''" v-model="schedules.section"  class="form-select text-uppercase">
                                                    <option select disabled value="">SELECT SECTION</option>
                                                    <option class="text-uppercase" v-for="section in newSectionComputed" :key="section.id" :value="section.id">{{ section.section }}</option>
                                                </select>
                                            </div>
                                            <div class="form-group mt-1">
                                                <label for="" class="t-uppercae fw-bold">Day</label>
                                                <select :disabled="schedules.section ==''" v-model="schedules.day" class="form-select text-capitalize">
                                                    <option value="monday" class="text-capitalize">monday</option>
                                                    <option value="tuesday" class="text-capitalize">tuesday</option>
                                                    <option value="wednesday" class="text-capitalize">wednesday</option>
                                                    <option value="thursday" class="text-capitalize">thursday</option>
                                                    <option value="friday" class="text-capitalize">friday</option>
                                                    <option value="saturday" class="text-capitalize">saturday</option>
                                                </select>
                                            </div>
                                            <div class="form-group mt-1">
                                                <label for="" class="t-uppercae fw-bold">Start</label>
                                                <select  :disabled="schedules.section ==''" v-model="schedules.start" class="form-select">
                                                    <option  v-for="time in times" :key="time.id" :value="time.id" >{{time.time}}</option>
                                                </select>
                                            </div>
                                            <div class="form-group mt-1">
                                                <label  class="t-uppercae fw-bold">End</label>
                                                <select  :disabled="schedules.section ==''" v-model="schedules.end" class="form-select">
                                                    <option  v-for="time in endTime" :key="time.id" :value="time.id" >{{time.time}}</option>
                                                </select>
                                            </div>
                                            <div class="form-group mt-3" >
                                                <button  :disabled="schedules.subject =='' || schedules.classroom =='' || schedules.section =='' " class="btn btn-primary w-100" >
                                                    <fa icon="save" ></fa> Save
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                    <div v-show="showButtonModal == 'delete'" class="">
                                        <div class="">
                                            <div v-for="data in showDeleteData " class="t-grid t-grid-cols-[1fr,40px] hover:t-bg-slate-200 p-1 mt-2" >
                                                <span>
                                                    <h6  class="text-uppercase p-0 m-0" >{{ data.day }}</h6>
                                                    <small class="p-0 m-0" >{{ data.time }}</small><br>
                                                    <small class="p-0 m-0" >Section - Year</small>
                                                </span>
                                                <span class="d-flex grid-justify-content-center align-items-end" >
                                                    <button  @click="deleteSchedule(data.id)" class="btn btn-outline-danger" ><fa icon="trash"></fa></button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="t-h-auto t-flex t-justify-between t-bg-slate-200 pt-3 p-2" >
                        <div class="t-flex row">
                            <div  class="col">
                                <select @change="changeDepartment" v-model="schedules.department" class="form-select text-uppercase" >
                                    <option value="" selected disabled >Select Department</option>
                                    <option v-for="dep in DepartmentData" :value="dep" class="text-uppercase" >{{dep}}</option>
                                </select>
                            </div>
                            <div class="col">
                                <select :disabled="schedules.department == ''" @change="changeSemester"  v-model="schedules.semester" class="form-select">
                                    <option value="1st semester">1st Semester</option>
                                    <option value="2nd semester">2nd Semester</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <button v-if="!isSending" @click="NotifyMinorToDean" :disabled="schedules.department == ''" type="button" class="btn btn-outline-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="">
                                <fa icon="calendar" ></fa>
                                Finish Schedule.
                            </button>
                            <div v-else class="spinner-border" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </div>
                    <div class="t-bg-slate-200 p-2 t-overflow-auto t-h-full" >
                        <div v-show="defaultData == 'classroom'" >
                            <div>
                                <classroomMultiple :schedule="computedClassroom"></classroomMultiple>
                                <br>
                            </div>
                        </div>
                        <div v-show="defaultData == 'section'" >
                            <div class="" >
                                <sectionMultiple :schedule="computedSection"></sectionMultiple>
                                <br>
                            </div>
                        </div>
                        <br><br>
                    </div>
                </div>
            </div>
            <div class="t-h-full" >
                <div class="p-4 t-h-full" >
                    <div class="t-relative t-border t-rounded-[5px]" >  
                        <div class="t-h-[35px] t-grid t-grid-cols-[1fr,30px]" >
                            <input @click="openModal" v-model="isSearch" class="t-pl-3 t-outline-none t-bg-transparent" placeholder="Search Professor" >
                            <div class="t-grid t-justify-center t-items-center">
                                <fa @click="openModal" icon="angle-down" class="t-text-[15px]" ></fa>
                            </div>
                        </div>
                        <div v-show="showModal"  class="t-absolute t-w-full t-z-50 bg-white shadow p-2 mt-2" >
                            <div class="text-end t-border-b" >
                                <fa @click="closeModal" class="text-danger t-cursor-pointer" icon="times" ></fa>
                            </div>
                            <div class="t-h-[160px]" :class="{'t-overflow-x-auto': computedProfessor.length}" >
                                <div v-if="computedProfessor.length > 0" class="d-flex mt-1 hover:t-bg-slate-200  t-overflow-hidden p-2 t-rounded-[5px] t-cursor-pointer" 
                                    v-for="professor in computedProfessor" @click="selectProfessor({professor: professor.professorID,fullname: professor.fullname, rank: professor.rank})" >
                                    <img src="../../assets/images/profile.png" style="height: 60px;" alt="">
                                    <span class="t-mt-1 t-ml-2 t-overflow-hidden" >
                                        <small class="t-text-[13px] t-m-0 t-p-0 t-truncate text-uppercase" ><strong>{{professor.professorID}}</strong></small><br>
                                        <small class="t-text-[13px] t-m-0 t-p-0 t-truncate text-capitalize" >{{professor.fullname}}</small>
                                    </span>
                                </div>
                                <div v-else class="d-grid justify-content-center align-items-center t-pt-3" >
                                    <div class="d-grid justify-content-center align-items-center" >
                                        <img src="../../assets/images/search.png" style="height: 100px;" alt="">
                                    </div>
                                    <b>NO DATA FOUND</b>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="t-flex mt-3">
                        <div class="t-grid t-grid-cols-[80px,1fr]" >
                            <div class="d-flex justify-center align-items-center " >
                                <img src="../../assets/images/profile.png" style="height: 60px;" alt="">
                            </div>
                            <div class="t-w-full t-overflow-hidden">
                                <h6 :title="selectedProfessorData.professor" class="t-text-[13px] text-capitalize t-truncate" >
                                    <strong>ID: </strong>
                                    <span v-if="selectedProfessorData.professor != ''" >
                                        {{selectedProfessorData.professor}}
                                    </span>
                                    <span v-else >N/A</span>
                                </h6>
                                <h6 :title="selectedProfessorData.fullname" class="t-text-[13px] text-capitalize t-truncate" >
                                    <strong>Fullname: </strong>
                                    <span v-if="selectedProfessorData.fullname != ''" >
                                        {{selectedProfessorData.fullname}}
                                    </span>
                                    <span v-else >N/A</span>
                                </h6>
                                <h6 :title="selectedProfessorData.rank" class="t-text-[13px] text-capitalize t-truncate" >
                                    <strong>Rank: </strong>
                                    <span v-if="selectedProfessorData.rank != ''" >
                                        {{selectedProfessorData.rank}}
                                    </span>
                                    <span v-else >N/A</span>
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup >
import {ref,onMounted,computed,inject} from 'vue';
import sectionMultiple from '../partials/sectionMultiple.vue';
import classroomMultiple from '../partials/classroomMultiple.vue';

import Swal from 'sweetalert2';

import { minorStore } from '../../services/minors.js';
import {scheduleStore} from '../../services/schedule.js';

const use_minorStore = minorStore();
const use_scheduleStore = scheduleStore();

const isSearch = ref('');

const showButtonModal = ref('');

const showModalButton = (data) =>{
    if(showButtonModal.value != ""){
        if(data == showButtonModal.value){
            showButtonModal.value = "";
        }
        else{
            showButtonModal.value = data;
        }
    }
    else{
        showButtonModal.value = data;
    }
}

const showModal = ref(false);
const openModal = ()=>{
    showModal.value = true;
}

const closeModal = ()=>{
    showModal.value = false;
}

const defaultData = ref("classroom");
const showClassroomSchedule = ()=>{
    defaultData.value = "classroom";
}

const showSectionSchedule = ()=>{
    defaultData.value = "section";
}

const globalProfessorData = inject('globalProfessorData');   
const professorData = ref(globalProfessorData);

// const globalDepartmentData = inject('globalDepartmentData');   
const DepartmentData = ref();
const fetchDepartment = async ()=>{
    try {
        await use_minorStore.departments();
        DepartmentData.value = use_minorStore.getResponse;
    } catch (error) {
        console.error(error)
    }
}
onMounted(()=>{
    fetchDepartment();
})

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

const selectedProfessorData = ref({
    professor: '',
    fullname: '',
    rank: '',
})

const selectProfessor = (data)=>{
    selectedProfessorData.value = {...data}
    showModal.value = false;
    showDelete();
}

const schedules = ref({
    department: '',
    semester: '1st semester',
    subject: '',
    classroom: '',
    section: '',
    day: 'monday',
    start: 0,
    end: '',
    yearlevelClassroom: '',
    yearlevelSection: '',
    classroom: '',
})

const times = ref([
    { id: 0 , time:"7:00 AM"}, {id: 1 , time:"7:30 AM"},
    { id: 2 , time:"8:00 AM"}, {id: 3 , time:"8:30 AM"},
    { id: 4 , time:"9:00 AM"}, {id: 5 , time:"9:30 AM"},
    { id: 6 , time:"10:00 AM"}, {id: 7 , time:"10:30 AM"},
    { id: 8 , time:"11:00 AM"}, {id: 9 , time:"11:30 AM"},
    { id: 10 , time:"12:00 PM"}, {id: 11 , time:"12:30 PM"},
    { id: 12 , time:"1:00 PM"}, {id: 13 , time:"1:30 PM"},
    { id: 14 , time:"2:00 PM"}, {id: 15 , time:"2:30 PM"},
    { id: 16 , time:"3:00 PM"}, {id: 17 , time:"3:30 PM"},
    { id: 18 , time:"4:00 PM"}, {id: 19 , time:"4:30 PM"},
    { id: 20 , time:"5:00 PM"}, {id: 21 , time:"5:30 PM"},
    { id: 22 , time:"6:00 PM"}, {id: 23 , time:"6:30 PM"},
    { id: 24 , time:"7:00 PM"}, {id: 25 , time:"7:30 PM"},
    { id: 26 , time:"8:00 PM"}, {id: 27 , time:"8:30 PM"},
    { id: 28 , time:"9:00 PM"}, {id: 29 , time:"9:30 PM"},
    { id: 30 , time:"10:00 PM"},

])

const endTime = computed(()=>{
    schedules.value.end = schedules.value.start + 2;
    return times.value.slice(schedules.value.start + 2);
})

const computedClassroom = computed(()=>{
    if(schedules.value.yearlevelClassroom != ""){
        return classroomScheduleData.value.filter((schedule)=>{
            return schedule.yearlevel.toLowerCase().includes(schedules.value.yearlevelClassroom)
        })
    }
    else{
        return classroomScheduleData.value;
    }
})
const classroomScheduleData = ref([])

const computedSection = computed(()=>{
    if(schedules.value.yearlevelSection != ""){
        return sectionScheduleData.value.filter((schedule)=>{
            return schedule.yearlevel.toLowerCase().includes(schedules.value.yearlevelSection)
        })
    }
    else{
        return sectionScheduleData.value;
    }
})
const sectionScheduleData = ref([])

const onMountedScheduleClassroom = async ()=>{
    try{
        await use_minorStore.classroomSchedule({department: schedules.value.department, semester: schedules.value.semester});
        const response = use_minorStore.getSchedule;
        classroomScheduleData.value = response;
    }
    catch(error){
        console.error(error);
    }
}

const onMountedScheduleSection = async ()=>{
    try{
        await use_minorStore.sectionSchedule({department: schedules.value.department, semester: schedules.value.semester});
        const response = use_minorStore.getSchedule;
        sectionScheduleData.value = response;
    }
    catch(error){
        console.error(error);
    }
}
const yearlevel = ref([]);
const changeDepartment = async()=>{
    onMountedScheduleClassroom();
    onMountedScheduleSection();
    //show year level
    await use_minorStore.yearlevel(schedules.value.department);
    yearlevel.value = use_minorStore.getResponse;

    subjectFetch();
    classroomFetch();
    sectionFetch();
}

const classroomData = ref([]);
const classroomFetch = async ()=>{
    try{
        await use_minorStore.showclassroom(schedules.value.department)
        classroomData.value =  use_minorStore.getResponse;
    }
    catch(error){
        console.error(error);
    }
}

const subjectData = ref([]);
const subjectFetch = async ()=>{
    try{
        await use_minorStore.showsubject()
        subjectData.value =  use_minorStore.getResponse;
    }
    catch(error){
        console.error(error);
    }
}

const sectionData = ref([]);
const sectionFetch = async ()=>{
    try{
        await use_minorStore.showsection(schedules.value.department)
        sectionData.value =  use_minorStore.getResponse;
    }
    catch(error){
        console.error(error);
    }
}

const newSubject = ref([]);
const changeSubject = computed(()=>{
    if(schedules.value.subject != ""){       
        newSubject.value = subjectData.value.find((subject)=>subject.code == schedules.value.subject)
    }
})

const newClassroomComputed = computed(()=>{
    if(schedules.value.subject != ""){
        return classroomData.value.filter((classroom)=>classroom.year == newSubject.value.year);
    }
})

const newSectionComputed = computed(()=>{
    if(schedules.value.subject != ""){
        return sectionData.value.filter((section)=>section.year == newSubject.value.year);
    }
})

const changeSemester = ()=>{
    onMountedScheduleClassroom();
    onMountedScheduleSection();
}

const createSchedule = ()=>{
    Swal.fire({
    title: 'Are you sure?',
    text: "You won't be able to revert this!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes, Save it!'
    }).then(async(result) => {
        if (result.isConfirmed) {
            try{
                await use_minorStore.createSchedule({
                    department: schedules.value.department,
                    professor: selectedProfessorData.value.professor,
                    semester: schedules.value.semester,
                    subject: schedules.value.subject,
                    classroom: schedules.value.classroom,
                    section: schedules.value.section,
                    day: schedules.value.day,
                    start: schedules.value.start,
                    end: schedules.value.end,
                });

                const response = use_minorStore.getResponse;
                if(response.status){
                    onMountedScheduleClassroom();
                    onMountedScheduleSection();
                    Swal.fire('Success',response.msg,"success")
                }
                else{
                    Swal.fire("Error", response.msg,"error");
                }
                
            }
            catch(error){
                console.error(error);
            }
        }
    })
}

const showDeleteData = ref([]);
const showDelete = async ()=>{
    try{
        await use_minorStore.showDelete({professor: selectedProfessorData.value.professor , semester: schedules.value.semester});
        showDeleteData.value = use_minorStore.getResponse;
    }
    catch(error){
        console.error(error)
    }
}

const deleteSchedule = (id)=>{
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then( async (result) => {
        if (result.isConfirmed) {
            try {
                await use_minorStore.delete(id);
                const response = use_minorStore.getResponse;
                if(response.status){
                    Swal.fire('Success',response.msg,'success');
                    showDelete();
                    onMountedScheduleClassroom();
                    onMountedScheduleSection();
                }
            } catch (error) {
                console.error(error)
            }
        }
    })
}

const isSending = ref(false);
const NotifyMinorToDean = ()=>{
    try {
        Swal.fire({
            title: "Are you sure?",
            text: "Perform this action if you already done adding entire schedule.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes,Submit it!"
        }).then( async (result) => {
            isSending.value = true;
            if (result.isConfirmed) {
                await use_scheduleStore.notifyMinorToDean(schedules.value.department);
                const response = use_scheduleStore.getResponse;

                if(response.status){
                    Swal.fire('Succes',response.msg,"success");
                }
            }
            isSending.value = false;
            });
    } catch (error) {
        console.error(error);
    }
}

</script>