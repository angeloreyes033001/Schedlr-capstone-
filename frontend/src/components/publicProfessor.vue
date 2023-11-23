<template >
    <section class="wrapper t-select-none t-h-screen t-relative t-overflow-hidden t-bg-slate-100" >
        <navbar :isColor="true" ></navbar>
        <div class="t-absolute t-h-full t-w-full t-overflow-hidden t-pt-[60px] t-pb-[60px]" >
            <div class="container t-h-full t-grid t-grid-rows-[80px,1fr,50px]" >
                <div class="" >
                    <h5 class="t t-font-extrabold text-uppercase" >Professor Schedule</h5>
                    <div class="t-flex t-justify-between t-gap-2" >
                        <div class="form-group" >
                            <select v-model="departments" @change="changeDepartment" name="" class="form-select text-uppercase" id="">
                                <option value="" disabled >Select Department</option>
                                <option class="text-uppercase" v-for="department in globalDepartmentData" :key="department.department" >{{ department.department }}</option>
                            </select>
                        </div>
                        <div class="form-group" >
                            <input v-model="isSearch" :disabled="departments === ''" type="text" class="form-control" placeholder="Seach Name" >
                        </div>
                    </div>
                </div>
                <div class="p-2 t-overflow-auto " >
                    <div class="col t-text-center t-h-full" >
                        <div v-if="computedProfessor.length > 0" data-bs-toggle="modal" data-bs-target="#scheduleModal" v-for="professor in computedProfessor" @click="showSchedule(professor.professorID)" class="t-relative t-h-[300px] t-w-[280px] t-cursor-pointer bg-white t-shadow t-rounded-[10px] t-inline-block t-m-[13px] t-rounded-tl-[15px] t-rounded-tr-[15px]" >
                            <div class="t-grid t-grid-rows-[1fr,0.7fr] t-h-full t-w-full t-rounded-[15px] t-overflow-hidden t-relative" >
                                <div class="t-bg-logoBlue t-rounded-bl-[30px] t-rounded-tl-[15px] t-rounded-tr-[15px]" >
                                    <div class="t-grid t-justify-center t-items-center t-h-full t-w-full" >
                                        <img src="../assets/images/user.png" class="t-h-[120px] t-w-120px t-p-[5px] t-bg-white rounded-circle" alt="">
                                    </div>
                                </div>
                                <div class="t-bg-logoBlue t-relative t-rounded-br-[15px] t-rounded-bl-[15px] t-overflow-hidden" >
                                    <div class="p-2 bg-white t-h-full t-w-full t-rounded-tr-[30px]  t-rounded-br-[15px] t-rounded-bl-[15px]" >
                                        <span class="" >
                                            <h6 class="t-text-left t-font-normal p-0 m-0" >ID</h6>
                                            <h6 class="t-text-left t-font-semibold text-uppercase p-0 m-0 t-truncate" >{{ professor.professorID }}</h6>
                                        </span>
                                        <span class="" >
                                            <h6 class="t-text-left t-font-normal p-0 m-0" >Fullname</h6>
                                            <h6 class="t-text-left t-font-semibold text-capitalize p-0 m-0" >{{ professor.professorFullname }}</h6>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-else-if="departments === ''" class="t-h-full t-flex t-justify-center t-items-center" >
                            <div >
                                <div class="t-flex t-justify-center t-items-center" >
                                    <img src="../assets/images/table.png" class="t-h-[200px]" alt="">
                                </div>
                                <h5 class="t t-font-light t-capitalize" >Please Select Department First!</h5>
                            </div>
                        </div>
                        <div v-else class="t-h-full t-flex t-justify-center t-items-center" >
                            <div >
                                <div class="t-flex t-justify-center t-items-center" >
                                    <img src="../assets/images/table.png" class="t-h-[200px]" alt="">
                                </div>
                                <h5 class="t t-font-light t-capitalize" >No professor's record</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="t-flex t-gap-[10px] t-justify-end t-pt-[10px]" >
                    <div class="d-flex t-gap-[10px]" >
                        <div>
                            <button @click="previousPage" :disabled="currentPage === 1 || isSearch != ''" class="btn btn-outline-primary t-w-[50px]" v-show="departments != ''" >
                                <fa icon="angle-left" ></fa>
                            </button>
                        </div>
                        <div :hidden="departments == ''" class=" t-bg-slate-200 t-w-[40px] rounded-circle t-h-[40px] t-flex t-justify-center t-items-center" >
                            <div class="text-dark" >
                                {{ pageCountNow}}/{{ totalpage }}
                            </div>
                        </div>
                        <div>
                            <button @click="nextPage()" :disabled="currentPage * itemPerPage > professorDatas.length || isSearch != '' " class="btn btn-outline-primary t-w-[50px]" v-show="departments != ''" >
                                <fa icon="angle-right" ></fa>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- modal parts -->
    <div class="modal-wrapper modal fade t-select-none" id="scheduleModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="scheduleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-capitalize" id="scheduleModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body t-relative">
                <div class="t-flex t-justify-between t-gap-2 t-relative" >
                    <div class="form-group">
                        <button @click="printNow" data-bs-dismiss="modal" class="btn btn-primary t-truncate" >
                            <fa icon="print" ></fa>
                            &nbsp;
                            <span class="" >Print Schedule</span>
                        </button>
                    </div>
                    <div class="form-group">
                        <select v-model="schedules.semester" @change="changeSemester" name="" class="form-select text-capitalize">
                            <option value="1st semester" selected class="text-capitalize" >1st semester</option>
                            <option value="2nd semester" class="text-capitalize" >2nd semester</option>
                        </select>
                    </div>
                </div>
                <div>
                    <schedule colors="green" :schedules="scheduleProfessor" ></schedule>
                </div>
            </div>
            </div>
        </div>
    </div>
    <professorPrint :schedules="scheduleDatas" ></professorPrint>
</template>
<script setup>
import navbar from './navbar.vue'
import schedule from './partials/schedule-component/singleSchedule.vue'
import professorPrint from './printTemplate/single.vue';

import {ref,onMounted, computed, inject} from 'vue';
import Swal from 'sweetalert2';

import { departmentStore } from '../services/departments';
import { publicStore } from '../services/public';

const use_departmentStore = departmentStore();
const use_publicStore = publicStore();


const isSearch = ref('');
const departments = ref('');

const schedules = ref({
    professor: '',
    semester: "1st semester",
})

const currentPage = ref(1);
const itemPerPage = ref(8);

const pageCountNow = ref(0);
const totalpage = ref(0);


const  globalDepartmentData = ref([]);
const globalFetchDepartments = async ()=>{
    await use_departmentStore.read();
    const response = use_departmentStore.getDepartments;
    globalDepartmentData.value = response;
}
onMounted(()=>{
    globalFetchDepartments();
})

const changeDepartment = ()=>{
    schedules.value.semester = "1st semester";
    readProfessors();
}
const professorDatas = ref([])
const readProfessors = async()=>{
    try{
        await use_publicStore.readProfessors(departments.value);
        professorDatas.value = use_publicStore.getResponse;
    }
    catch(error){
        console.error(error);
    }
}

const computedProfessor = computed(()=>{

    const c = Math.ceil(professorDatas.value.length / itemPerPage.value);

    if(professorDatas.value.length >  itemPerPage.value){
        totalpage.value = c;
    }
    else if(professorDatas.value.length > 1){
        totalpage.value = 1;
    }
    else{
        totalpage.value = 0;
    }

    pageCountNow.value = professorDatas.value.length > 0 ? pageCountNow.value = currentPage : pageCountNow.value = 0;

    if(isSearch.value != ""){
        return professorDatas.value.filter(professor=>{
            return professor.professorFullname.toLowerCase().includes(isSearch.value.toLowerCase());
        })
    }
    else{
        const startIndex = (currentPage.value - 1) * itemPerPage.value;
        const endIndex = startIndex + itemPerPage.value;
        return professorDatas.value.slice(startIndex,endIndex);
    }
});

const nextPage = ()=>{
    const allpage = Math.ceil(professorDatas.value.length / itemPerPage.value)
    if(currentPage.value < allpage){
        currentPage.value++;
    }
}

const previousPage = ()=>{
    if(currentPage.value > 1){
        currentPage.value--;
    }
}

const printNow = ()=>{
    setTimeout(()=>{
        print();
    },500);
}

const showSchedule = (professor)=>{
    schedules.value.professor = professor;
    readSchedule({professor: schedules.value.professor, semester: schedules.value.semester });
    readprintSchedule({professor: schedules.value.professor, semester: schedules.value.semester });
}

const changeSemester = ()=>{
    readSchedule({professor: schedules.value.professor, semester: schedules.value.semester });
    readprintSchedule({professor: schedules.value.professor, semester: schedules.value.semester });
}

const scheduleProfessor = ref([])
const readSchedule = async (data)=>{
    try {
        console.log(data)
        await use_publicStore.readProfessorSchedule(data);
        scheduleProfessor.value = use_publicStore.getSchedule;

        console.log(scheduleProfessor.value)
        
    } catch (error) {
        console.error(error)
    }
}

const scheduleDatas = ref([]);
const readprintSchedule = async (data)=>{
    try {
        await use_publicStore.readprintSchedule(data);
        scheduleDatas.value = use_publicStore.getDataPrint;
        
    } catch (error) {
        console.error(error)
    }
}




</script>
<style>

    @media print{
        .wrapper,.modal-wrapper{
            display: none;
        }
        body{
            background-color: white;
        }
        section{
            display: none;
        }
        img{
            height: 80px;
            width: 80px;
        } 

        .bgBlack{
            display: none;
        }

        .custom-shadow{
            box-shadow: none;
        }

        .print-trigger{
            display: none;
        }

        .my-space{
        padding: 0 20px;
    }
}
</style>