<template  >
    <template v-if="computingLoading" >
        <section class="t-h-screen t-grid t-justify-center t-items-center t-bg-slate-400" >
            <div class="" >
                <animation></animation>
            </div>
        </section>
    </template>
    <template  v-else  >
        <section class="t-h-screen t-w-screen t-overflow-hidden t-grid t-grid-cols-[280px,1fr]" >
            <sidebarComponent settingLink="/dashboard/setting" :navigations="adminNavigations"></sidebarComponent>
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
import { classroomStore } from '../services/classrooms';
import { subjectStore } from '../services/subjects';
// import { sectionStore } from '../services/sections';
import { professorStore } from '../services/professors';
import {rankStore } from '../services/ranks.js';
const sidebarComponent = defineAsyncComponent(()=>import('./partials/sidebar.vue'));
const animation = defineAsyncComponent(()=>import('./partials/animation/animation.vue'));

const use_departmentStore = departmentStore();
const use_yearlevelStore = yearlevelStore();
const use_rankStore = rankStore();
const use_classroomStore = classroomStore();
const use_subjectStore = subjectStore();
const use_professorStore = professorStore();
// const use_sectionStore = sectionStore();

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

const adminNavigations = ref([
   
    {
        path: '/dashboard',
        icon:'calendar',
        title: 'Sechedule',
        children: [
            {path: '/dashboard/manually' , icon:'hand-rock', title: 'Manually'},
            {path: '/dashboard/automated' , icon:'robot', title: 'Automated'},
        ],
    },
    {
        path: '/dashboard/official-time',
        icon:'calendar-check',
        title: 'Official Time',
        children: [
            
        ],
    },
    {
        path: '/dashboard/minor',
        icon:'building',
        title: 'Other Department',
        children: [
            
        ],
    },
])

const  globalDepartmentData = ref([]);
const globalFetchDepartments = async ()=>{
    startRequest()
    await use_departmentStore.read();
    const response = use_departmentStore.getDepartments;
    globalDepartmentData.value = response;
    endRequest();
}

const  globalYearLevelData = ref([]);
const globalFetchYearLevel = async ()=>{
    startRequest()
    await use_yearlevelStore.read();
    const response = use_yearlevelStore.getYearLevel;
    globalYearLevelData.value = response;
    endRequest();
}

const  globalRankData = ref([]);
const globalFetchRank = async ()=>{
    startRequest()
    await use_rankStore.read();
    const response = use_rankStore.getRanks;
    globalRankData.value = response;
    endRequest();
}

const globalClassroomData = ref([]);
const globalFetchClassroom = async ()=>{
    startRequest()
    await use_classroomStore.read();
    const response = use_classroomStore.getClassrooms;
    globalClassroomData.value = response;
    endRequest();
}

const globalSubjectData = ref([]);
const globalFetchSubject = async ()=>{
    startRequest()
    await use_subjectStore.read();
    const response = use_subjectStore.getSubjects;
    globalSubjectData.value = response;
    endRequest();
}

// const globalSectionData = ref([]);
// const globalFetchSection = async ()=>{
//     startRequest()
//     await use_sectionStore.read();
//     const response = use_sectionStore.getSections;
//     globalSectionData.value = response;
//     endRequest();
// }

const globalProfessorData = ref([]);
const globalFetchProfessor = async ()=>{
    startRequest()
    await use_professorStore.read();
    const response = use_professorStore.getProfessor;
    globalProfessorData.value = response;
    endRequest();
}

onMounted(()=>{
    globalFetchDepartments();
    globalFetchYearLevel();
    globalFetchRank();
    globalFetchClassroom();
    globalFetchSubject();
    // globalFetchSection();
    globalFetchProfessor();
    // initialComponent();
})

provide("globalDepartmentData", globalDepartmentData);
provide("globalYearLevelData", globalYearLevelData);
provide("globalRankData", globalRankData);
provide("globalClassroomData", globalClassroomData);
provide("globalSubjectData", globalSubjectData);
// provide("globalSectionData", globalSectionData);
provide("globalProfessorData", globalProfessorData);
</script>