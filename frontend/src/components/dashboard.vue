<template>
    <template v-if="computingLoading" >
        <section class="t-h-screen t-grid t-justify-center t-items-center" >
            <div class="" >
                Loading...
            </div>
        </section>
    </template>
    <template  v-else  >
        <section class="t-h-screen t-w-screen t-overflow-hidden t-grid t-grid-cols-[280px,1fr]" >
            <sidebarComponent settingLink="/dean/dashboard/setting" :navigations="deanNavigations"></sidebarComponent>
            <div class="t-overflow-y-auto" >
                <div class="t-h-full t-w-full t-p-[10px]">
                    <router-view></router-view>
                </div>
            </div>
        </section>
    </template >
</template>
<script setup >
import { ref,onMounted, computed, provide, defineAsyncComponent } from 'vue';
import {departmentStore} from '../services/departments';
import {yearlevelStore} from '../services/yearLevels.js';
import {rankStore} from '../services/ranks.js';
import { designationStore } from '../services/designations';
import { specializationStore } from '../services/specialization';
import { classroomStore } from '../services/classrooms';
import { subjectStore } from '../services/subjects';
import { sectionStore } from '../services/sections';
import { professorStore } from '../services/professors';

const sidebarComponent = defineAsyncComponent(()=>import('./partials/sidebar.vue'))

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

const use_departmentStore = departmentStore();
const use_yearlevelStore = yearlevelStore();
const use_rankStore = rankStore();
const use_designation = designationStore();
const use_specializationStore = specializationStore();
const use_classroomStore = classroomStore();
const use_subjectStore = subjectStore();
const use_sectionStore = sectionStore();
const use_professorStore = professorStore();

const deanNavigations = ref([
    {
        path: '/dean/dashboard',
        icon:'home',
        title: 'home',
        children: [],
    },
    {
        path: '/login',
        icon:'chalkboard-user',
        title: 'School',
        children: [
            {path: '/dean/dashboard/school/professors' , icon:'users', title: 'Professor'},
            {path: '/dean/dashboard/school/subjects' , icon:'book', title: 'Subject'},
            {path: '/dean/dashboard/school/classrooms' , icon:'school', title: 'Classroom'},
            {path: '/dean/dashboard/school/sections' , icon:'person-shelter', title: 'Section'},
        ] ,
    },
    {
        path: '/dean/dashboard/account/admins',
        icon:'users-cog',
        title: 'accounts',
        children: [
            
        ] ,
    },
    {
        path: '/dean/dashboard/configure',
        icon:'wrench',
        title: 'configure',
        children: [
            {path: '/dean/dashboard/configure/ranks' , icon:'star', title: 'ranks'},
            {path: '/dean/dashboard/configure/years' , icon:'calendar', title: 'years'},
            {path: '/dean/dashboard/configure/specialization' , icon:'fingerprint' , title: 'specialization' },
            {path: '/dean/dashboard/configure/designation' , icon:'user' , title: 'designation' },
            {path: '/dean/dashboard/configure/departments' , icon:'building', title: 'departments'},
        ] ,
    }
])

const  globalDepartmentData = ref([]);
const globalFetchDepartments = async ()=>{
    startRequest();
    await use_departmentStore.read();
    const response = use_departmentStore.getDepartments;
    globalDepartmentData.value = response;
    endRequest();
}

const  globalYearLevelData = ref([]);
const globalFetchYearLevel = async ()=>{
    startRequest();
    await use_yearlevelStore.read();
    const response = use_yearlevelStore.getYearLevel;
    globalYearLevelData.value = response;
    endRequest();
}

const  globalRankData = ref([]);
const globalFetchRank = async ()=>{
    startRequest();
    await use_rankStore.read();
    const response = use_rankStore.getRanks;
    globalRankData.value = response;
    endRequest();
}

const  globalDesignationData = ref([]);
const globalFetchDesignation = async ()=>{
    startRequest();
    await use_designation.read();
    const response = use_designation.getDesignation;
    globalDesignationData.value = response;
    endRequest();
}

const globalSpecialization = ref([]);
const globalFetchSpecialization = async ()=>{
    startRequest();
    await use_specializationStore.read();
    const response =  use_specializationStore.getSpecialization;
    globalSpecialization.value = response;
    endRequest();
}

const globalClassroomData = ref([]);
const globalFetchClassroom = async ()=>{
    startRequest();
    await use_classroomStore.read();
    const response = use_classroomStore.getClassrooms;
    globalClassroomData.value = response;
    endRequest();
}

const globalSubjectData = ref([]);
const globalFetchSubject = async ()=>{
    startRequest();
    await use_subjectStore.read();
    const response = use_subjectStore.getSubjects;
    globalSubjectData.value = response;
    endRequest();
}

// const globalSectionData = ref([]);
// const globalFetchSection = async ()=>{
//     startRequest();
//     await use_sectionStore.read();
//     const response = use_sectionStore.getSections;
//     globalSectionData.value = response;
//     endRequest();
// }

const globalProfessorData = ref([]);
const globalFetchProfessor = async ()=>{
    startRequest();
    await use_professorStore.read();
    const response = use_professorStore.getProfessor;
    globalProfessorData.value = response;
    endRequest();
}

onMounted(()=>{
    globalFetchDepartments();
    globalFetchYearLevel();
    globalFetchRank();
    globalFetchDesignation();
    globalFetchSpecialization();
    globalFetchClassroom();
    globalFetchSubject();
    // globalFetchSection();
    globalFetchProfessor();
    // initialComponent();
})


provide("globalDepartmentData", globalDepartmentData);
provide("globalYearLevelData", globalYearLevelData);
provide("globalRankData", globalRankData);
provide("globalDesignationData", globalDesignationData);
provide("globalSpecialization", globalSpecialization);
provide("globalClassroomData", globalClassroomData);
provide("globalSubjectData", globalSubjectData);
// provide("globalSectionData", globalSectionData);
provide("globalProfessorData", globalProfessorData);


</script>