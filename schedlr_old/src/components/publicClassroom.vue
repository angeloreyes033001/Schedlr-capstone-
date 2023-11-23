<template >
    <section class="wrapper t-select-none t-h-screen t-relative t-overflow-hidden t-bg-slate-100" >
        <navbar :isColor="true" ></navbar>
        <div class="t-absolute t-h-full t-w-full t-overflow-hidden t-pt-[60px] t-pb-[60px]" >
            <div class="container t-h-full t-w-full" >
                <div class="t-h-full t-w-full" >
                    <h5 class="t t-font-extrabold text-uppercase" >Classroom Schedule</h5>
                    <div class="t-h-full t-grid t-grid-rows-[60px,1fr,60px]" >
                        <div class="t-flex t-justify-between" >
                            <div class="form-group" >
                                <select v-model="departments" @change="changeDepartment" class="form-select text-uppercase" >
                                    <option value="" selected disabled class="t-text-uppercase" >Select Department</option>
                                    <option class="t-text-uppercase" v-for="department in globalDepartmentData" >{{ department.department }}</option>
                                </select>
                            </div>
                            <div class="form-group" >
                                <input v-model="isSearch" :disabled="departments === ''" placeholder="Search Classroom" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="t-h-full t-w-full t-overflow-auto p-2" >
                            <div class="col t-overflow-auto t-h-full t-w-full text-center" >
                                <div data-bs-toggle="modal" data-bs-target="#scheduleModal" v-if="classroomDatas.length > 0" v-for="classroom in computedClassroom" @click="showSchedule(classroom.id)"  class="hover:t-shadow hover:t-rotate-2 t-cursor-pointer t-m-[15px] t-p-[5px] t-rounded-[10px] t-inline-block t-bg-white t-h-[100px] t-w-[280px]" >
                                    <div class="t-grid t-grid-cols-[80px,1fr] t-h-full" >
                                        <div class="t-grid t-justify-center t-items-center t-h-full " >
                                            <fa icon="building-user" class="t-text-[40px] text-muted" ></fa>
                                        </div>
                                        <div class="t-h-full t-w-full t-grid t-items-center" >
                                            <div class="t-overflow-hidden" >
                                                <h6 class=" t-text-left t-font-semibold t-truncate t-m-0 t-p-0" >{{classroom.classroom}}</h6>
                                                <h6 class=" t-text-[14px] t-font-extralight t-text-left p-0 m-0" >{{ classroom.type }}</h6>
                                                <h6 class=" t-text-[14px] t-font-extralight t-text-left p-0 m-0" >{{ classroom.yearlevel }}</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div v-else-if="departments === ''" class="t-h-full t-flex t-justify-center t-items-center" >
                                   <div>
                                        <div class="t-flex t-justify-center t-items-center" >
                                            <img src="../assets/images/table.png" class="t-h-[200px]" alt="">
                                        </div>
                                        <h6> Please select department first!</h6>
                                   </div>
                                </div>
                                <div  v-else class="t-h-full t-flex t-justify-center t-items-center" >
                                    <div>
                                        <div class="t-flex t-justify-center t-items-center" >
                                            <img src="../assets/images/table.png" class="t-h-[200px]" alt="">
                                        </div>
                                        <h6>No classroom record</h6>
                                   </div>
                                </div>
                            </div>
                        </div>
                        <div class="t-flex t-gap-[10px] t-justify-end t-pt-[10px]" > 
                            <div class="d-flex t-gap-[10px]" v-if="departments != ''" >
                                <div>
                                    <button @click="previousPage" :disabled="currentPage === 1 || isSearch != ''" class="btn btn-outline-primary" >
                                    <fa icon="angle-left" ></fa>
                                </button>
                                </div>
                                <div :hidden="departments == ''" class=" t-bg-slate-200 t-w-[40px] rounded-circle t-h-[40px] t-flex t-justify-center t-items-center" >
                                    <div class="text-dark" >
                                        {{ pageCountNow}}/{{ totalpage }}
                                    </div>
                                </div>
                                <div>
                                    <button @click="nextPage()" :disabled="currentPage * itemPerPage > classroomDatas.length || isSearch != '' " class="btn btn-outline-primary" >
                                    <fa icon="angle-right" ></fa>
                                </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- modal system -->
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
                        <select @click="changeSemester" name="" v-model="schedules.semester" class="form-select text-capitalize">
                            <option value="1st semester" selected class="text-capitalize" >1st semester</option>
                            <option value="2nd semester" class="text-capitalize" >2nd semester</option>
                        </select>
                    </div>
                </div>
                <div>
                    <schedule colors="green" :schedules="scheduleClassroom" ></schedule>
                </div>
            </div>
            </div>
        </div>
    </div>
    <professorPrint :schedules="scheduleDatas" ></professorPrint>
</template>
<script setup >
import {ref,onMounted, computed, inject} from 'vue'
import navbar from './navbar.vue';
import schedule from './partials/schedule-component/singleSchedule.vue'

import professorPrint from './printTemplate/single.vue';

import { departmentStore } from '../services/departments';
import { publicStore } from '../services/public';

const use_departmentStore = departmentStore();
const use_publicStore = publicStore();

const  globalDepartmentData = ref([]);
const globalFetchDepartments = async ()=>{
    await use_departmentStore.read();
    const response = use_departmentStore.getDepartments;
    globalDepartmentData.value = response;
}
onMounted(()=>{
    globalFetchDepartments();
})

const isSearch = ref('');
const departments = ref('');

const schedules = ref([{
    classroom: '',
    semester: "1st semester",
}]);

const currentPage = ref(1);
const itemPerPage = ref(24);

const pageCountNow = ref(0);
const totalpage = ref(0);

const changeDepartment = ()=>{
    schedules.value.semester = "1st semester";
    readClassroom();
}

const changeSemester = ()=>{
    readSchedule({classroom: schedules.value.classroom, semester: schedules.value.semester });
    readprintSchedule({classroom: schedules.value.classroom, semester: schedules.value.semester });
}

const classroomDatas = ref([])
const readClassroom = async()=>{
    try{
        await use_publicStore.readClassrooms(departments.value);
        classroomDatas.value = use_publicStore.getResponse;
    }
    catch(error){
        console.error(error);
    }
}

const computedClassroom = computed(()=>{

    const c = Math.ceil(classroomDatas.value.length / itemPerPage.value);

    if(classroomDatas.value.length >  itemPerPage.value){
        totalpage.value = c;
    }
    else if(classroomDatas.value.length > 1){
        totalpage.value = 1;
    }
    else{
        totalpage.value = 0;
    }

    pageCountNow.value = classroomDatas.value.length > 0 ? pageCountNow.value = currentPage : pageCountNow.value = 0;

    if(isSearch.value != ""){
        return classroomDatas.value.filter(classroom=>{
            return classroom.classroom.toLowerCase().includes(isSearch.value.toLowerCase());
        })
    }
    else{
        const startIndex = (currentPage.value - 1) * itemPerPage.value;
        const endIndex = startIndex + itemPerPage.value;
        return classroomDatas.value.slice(startIndex,endIndex);
    }
});

const nextPage = ()=>{
    const allpage = Math.ceil(classroomDatas.value.length / itemPerPage.value)
    if(currentPage.value < allpage){
        currentPage.value++;
    }
}

const previousPage = ()=>{
    if(currentPage.value > 1){
        currentPage.value--;
    }
}


const scheduleClassroom = ref([])
const readSchedule = async (data)=>{
    try {
        await use_publicStore.readClassroomSchedule(data);
        scheduleClassroom.value = use_publicStore.getSchedule;
        
    } catch (error) {
        console.error(error)
    }
}

const showSchedule = (classroom)=>{
    schedules.value.classroom = classroom;
    readSchedule({classroom: schedules.value.classroom, semester: schedules.value.semester });
    readprintSchedule({classroom: schedules.value.classroom, semester: schedules.value.semester });
}

const printNow = ()=>{
    setTimeout(()=>{
        print();
    },500);
}

const scheduleDatas = ref([]);
const readprintSchedule = async (data)=>{
    try {
        await use_publicStore.readprintClassroom(data);
        scheduleDatas.value = use_publicStore.getDataPrint;
        
    } catch (error) {
        console.error(error)
    }
}


</script>