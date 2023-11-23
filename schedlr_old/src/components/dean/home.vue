<template>
    <section class="container-fluid t-h-full t-w-full t-select-none" >
        <div class="t-grid t-grid-rows-[auto,auto] t-gap-2 t-grid-cols-1 md:t-grid-rows-1 md:t-grid-cols-[1fr,300px] t-h-full t-overflow-auto md:t-overflow-hidden" >
            <div class="t-h-full t-order-2 md:t-order-1" >
                <div class="t-grid t-grid-rows-[30px,auto] t-h-full" >
                    <div class="" >
                        <div class="t-h-full t-flex t-relative" >
                            <div @click="changeDisplay('professor')" class="t-h-full t-absolute t-top-0 t-left-0 t-w-[100px] t-rounded-t-[18px] t-flex t-justify-center t-items-end " :class="{'t-bg-slate-100 t-text-logoBlue t-font-bold t-z-10 t-shadow-inner': computedDisplay==='professor', 't-bg-logoBlue t-text-white': computedDisplay!='professor'}" >
                                <h6 class="t-truncate" >Professor</h6  >
                            </div>
                            <div @click="changeDisplay('classroom')"  class="t-h-full t-absolute t-top-0 t-left-[90px] t-w-[100px] t-rounded-t-[18px] t-flex t-justify-center t-items-end" :class="{'t-bg-slate-100 t-text-logoBlue t-font-bold t-z-10 t-shadow-inner': computedDisplay==='classroom', 't-bg-logoBlue t-text-white': computedDisplay!='classroom'}" >
                                <h6 class="t-truncate" >Classroom</h6  >
                            </div>
                            <div @click="changeDisplay('section')" class="t-h-full t-absolute t-top-0 t-left-[180px] t-w-[100px] t-rounded-t-[18px] t-flex t-justify-center t-items-end" :class="{'t-bg-slate-100 t-text-logoBlue t-font-bold t-z-10 t-shadow-inner': computedDisplay==='section', 't-bg-logoBlue t-text-white': computedDisplay!='section'}" >
                                <h6 class="t-truncate" >Section</h6  >
                            </div>
                        </div>
                    </div>
                    <div class="t-bg-slate-100 t-p-[10px] t-shadow-inner t-overflow-auto" >
                        <div class="t-h-full" v-if="computedDisplay == 'professor'" >
                            <professorSchedule></professorSchedule>
                        </div>
                        <div class="t-h-full" v-else-if="computedDisplay == 'classroom'" >
                            <classroomSchedule></classroomSchedule>
                        </div>
                        <div class="t-h-full" v-else >
                            <sectionSchedule></sectionSchedule>
                        </div>
                    </div>
                </div>
            </div>
            <div class="t-h-full t-order-1 md:t-order-2 t-grid t-grid-rows-[auto,1fr]" >
                <div class="t-grid t-gap-2" >
                    <div v-for="total in totals" class="t-grid t-grid-rows-[1fr,auto] t-p-[10px] t-bg-slate-200 t-h-[70px] t-rounded-[10px] t-shadow" >
                        <div class="t-flex t-justify-center" >
                            <h3 class="t-font-bold" >{{ total.count }}</h3>
                        </div>
                        <small class="t- t-font-extralight text-capitalize" >{{ total.label }}</small>
                    </div>
                </div>
                <div class="p-2 t-overflow-hidden" >
                    <div  class="t-overflow-hidden t-h-full md:t-overflow-auto" >
                        <div class="mt-2 t-overflow-hidden" >
                            <h6 class=" t-font-bold t-text-[14px] t-truncate" >Last Schedule added.</h6>
                            <div class="t-grid t-grid-cols-[80px,1fr] t-mt-2" >
                                <div class="t-flex t-justify-center t-items-center" >
                                    <img class="t-rounded-[50%] t-h-[50px]" src="../../assets/images/profile.png" alt="profile" >
                                </div>
                                <div class="t-grid t-items-center t-overflow-hidden" >
                                    <h6 class=" t-truncate t-font-extralight t-capitalize" >
                                        <span v-if="last_added != ''" ><strong>{{last_added.fullname}}</strong></span>
                                        <span v-else ><strong>n/a</strong></span>
                                    </h6>
                                    <h6 class="t-font-extralight  t-truncate t-capitalize t-text-[14px]" >
                                        <span v-if="last_added != ''" >{{last_added.rank}}</span>
                                        <span v-else >n/a</span>
                                    </h6>
                                </div>
                            </div>
                            <div >
                                <div class="t-overflow-hidden" >
                                    <h6 :title="last_added.semester" class=" t-truncate t-text-[14px] t-font-extralight t-capitalize" >
                                        <strong class="text-capitalize" >Semester: </strong>
                                        <span v-if="last_added != ''" >{{last_added.semester}}</span>
                                        <span v-else >n/a</span>
                                    </h6>
                                </div>
                                <div class="t-overflow-hidden" >
                                    <h6 :title="last_added.section" class=" t-truncate t-text-[14px] t-font-extralight t-capitalize" >
                                        <strong class="text-capitalize" >section: </strong>
                                        <span v-if="last_added != ''" >{{last_added.section}}</span>
                                        <span v-else >n/a</span>
                                    </h6>
                                </div>
                                <div class="t-overflow-hidden" >
                                    <h6 :title="last_added.classroom" class=" t-truncate t-text-[14px] t-font-extralight t-capitalize" >
                                        <strong class="text-capitalize" >classroom: </strong>
                                        <span v-if="last_added != ''" >{{last_added.classroom}}</span>
                                        <span v-else >n/a</span>
                                    </h6>
                                </div>
                                <div class="t-overflow-hidden" >
                                    <h6 :title="last_added.subject" class=" t-truncate t-text-[14px] t-font-extralight t-capitalize" >
                                        <strong class="text-capitalize" >subject: </strong>
                                        <span v-if="last_added != ''" >{{last_added.subject}}</span>
                                        <span v-else >n/a</span>
                                    </h6>
                                </div>
                                <div class="t-overflow-hidden" >
                                    <h6 :title="last_added.day" class=" t-truncate t-text-[14px] t-font-extralight t-capitalize" >
                                        <strong class="text-capitalize" >day: </strong>
                                        <span v-if="last_added != ''" >{{last_added.day}}</span>
                                        <span v-else >n/a</span>
                                    </h6>
                                </div>
                                <div class="t-overflow-hidden" >
                                    <h6 :title="last_added.time" class=" t-truncate t-text-[14px] t-font-extralight t-capitalize" >
                                        <strong class="text-capitalize" >time: </strong>
                                        <span v-if="last_added != ''" >{{last_added.time}}</span>
                                        <span v-else >n/a</span>
                                    </h6>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="mt-2 t-truncate" >
                            <h6 class=" t-font-bold t-text-[14px] t-truncate" >Last Schedule added by <br> other department.</h6>
                            <div class="t-grid t-grid-cols-[80px,1fr] t-mt-2" >
                                <div class="t-flex t-justify-center t-items-center" >
                                    <img class="t-rounded-[50%] t-h-[50px]" src="../../assets/images/profile.png" alt="profile" >
                                </div>
                                <div class="t-grid t-items-center t-overflow-hidden" >
                                    <h6 class=" t-truncate t-font-extralight t-capitalize" >
                                        <span v-if="last_added_department != ''" ><strong>{{last_added_department.fullname}}</strong></span>
                                        <span v-else ><strong>n/a</strong></span>
                                    </h6>
                                    <h6 class="t-font-extralight  t-truncate t-capitalize t-text-[14px]" >
                                        <span v-if="last_added_department != ''" >{{last_added_department.rank}}</span>
                                        <span v-else >n/a</span>
                                    </h6>
                                </div>
                            </div>
                            <div >
                                <div class="t-overflow-hidden" >
                                    <h6 :title="last_added_department.department" class=" t-truncate t-text-[14px] t-font-extralight t-capitalize" >
                                        <strong class="text-capitalize" >department: </strong>
                                        <span v-if="last_added_department != ''" >{{last_added_department.department}}</span>
                                        <span v-else >n/a</span>
                                    </h6>
                                </div>
                                <div class="t-overflow-hidden" >
                                    <h6 :title="last_added_department.semester" class=" t-truncate t-text-[14px] t-font-extralight t-capitalize" >
                                        <strong class="text-capitalize" >semester: </strong>
                                        <span v-if="last_added_department != ''" >{{last_added_department.semester}}</span>
                                        <span v-else >n/a</span>
                                    </h6>
                                </div>
                                <div class="t-overflow-hidden" >
                                    <h6 :title="last_added_department.section" class=" t-truncate t-text-[14px] t-font-extralight t-capitalize" >
                                        <strong class="text-capitalize" >section: </strong>
                                        <span v-if="last_added_department != ''" >{{last_added_department.section}}</span>
                                        <span v-else >n/a</span>
                                    </h6>
                                </div>
                                <div class="t-overflow-hidden" >
                                    <h6 :title="last_added_department.classroom" class=" t-truncate t-text-[14px] t-font-extralight t-capitalize" >
                                        <strong class="text-capitalize" >classroom: </strong>
                                        <span v-if="last_added_department != ''" >{{last_added_department.classroom}}</span>
                                        <span v-else >n/a</span>
                                    </h6>
                                </div>
                                <div class="t-overflow-hidden" >
                                    <h6 :title="last_added_department.subject" class=" t-truncate t-text-[14px] t-font-extralight t-capitalize" >
                                        <strong class="text-capitalize" >subject: </strong>
                                        <span v-if="last_added_department != ''" >{{last_added_department.subject}}</span>
                                        <span v-else >n/a</span>
                                    </h6>
                                </div>
                                <div class="t-overflow-hidden" >
                                    <h6 :title="last_added_department.day" class=" t-truncate t-text-[14px] t-font-extralight t-capitalize" >
                                        <strong class="text-capitalize" >day: </strong>
                                        <span v-if="last_added_department != ''" >{{last_added_department.day}}</span>
                                        <span v-else >n/a</span>
                                    </h6>
                                </div>
                                <div class="t-overflow-hidden" >
                                    <h6 :title="last_added_department.time" class=" t-truncate t-text-[14px] t-font-extralight t-capitalize" >
                                        <strong class="text-capitalize" >time: </strong>
                                        <span v-if="last_added_department != ''" >{{last_added_department.time}}</span>
                                        <span v-else >n/a</span>
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
<script setup >

import { ref,onMounted, computed,inject } from 'vue';
import {userStore} from '../../services/users.js';
import { professorStore } from '../../services/professors.js';
import { subjectStore } from '../../services/subjects.js';
import { classroomStore } from '../../services/classrooms.js';
import {publicStore} from '../../services/public';
// import schedule from '../partials/schedule-component/singleSchedule'
import professorSchedule from './scheduleProfessors.vue'
import classroomSchedule from './scheduleClassrooms.vue';
import sectionSchedule from './scheduleSections.vue'

const use_userStore = userStore();
const use_professorStore = professorStore();
const use_subjectStore = subjectStore();
const use_classroomStore = classroomStore();
const use_publicStore = publicStore();

const professorData = ref('');
const subjectData = ref('');
const classroomData = ref('');
const userData = ref('');

const last_added = ref([]);
const last_added_department = ref([]);

const schedules = ref([]);
const displaySchedule = ref('professor');
const changeDisplay = (change)=>{
    displaySchedule.value = change;
}
const computedDisplay = computed(()=>{
    return displaySchedule.value;
})

const isSearch = ref('');
const isSemester = ref('1st semester');

const totals = ref([
    {
        label: "Total Professors",
        count: computed(() => professorData.value.length),
    },
    {
        label: "Total Subjects",
        count: computed(() => subjectData.value.length),
    },
    {
        label: "Total Classroom",
        count: computed(() => classroomData.value.length),
    },
    {
        label: "Total Admin",
        count: computed(() => userData.value.length),
    },
])

onMounted( async ()=>{

    await use_professorStore.read();
    professorData.value = use_professorStore.getProfessor;

    await use_subjectStore.read();
    subjectData.value = use_subjectStore.getSubjects;

    await use_classroomStore.read();
    classroomData.value = use_classroomStore.getClassrooms;

    await use_userStore.fetchUsers();
    userData.value = use_userStore.getState;

    await use_publicStore.last_added();
    last_added.value = use_publicStore.getResponse;

    await use_publicStore.last_added_department();
    last_added_department.value = use_publicStore.getResponse;
})

</script>

<!-- <template>
    <div class="container-fluid p-3">
        <div class="row">
            <div class="w-100 h-100 col p-2">
                <div class="bg-primary rounded-2 t-h-[120px] p-3 d-flex" >
                    <div class="icon fs-2 justify-content-center align-items-center d-flex t-w-[100px]">
                        <fa class="text-white" icon="user"></fa>
                    </div>
                    <div class="totals t-grid w-100">
                        <h3 class="text-white text-uppercase t-text-[18px] text-nowrap">Total Admins</h3>
                        <p class="text-white fs-4">
                            <span v-if="userData.length == 0" >0</span>
                            <span v-else >{{ userData.length }}</span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="w-100 h-100 col p-2">
                <div class="bg-success rounded-2 t-h-[120px] p-3 d-flex" >
                    <div class="icon fs-2 justify-content-center align-items-center d-flex t-w-[100px]">
                        <fa class="text-white" icon="user"></fa>
                    </div>
                    <div class="totals t-grid w-100">
                        <h3 class="text-white text-uppercase t-text-[18px] text-nowrap">Total professors</h3>
                        <p class="text-white fs-4">
                            <span v-if= " professorData.length == 0" >0</span>
                            <span v-else >{{ professorData.length}}</span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="w-100 h-100 col p-2">
                <div class="bg-secondary rounded-2 t-h-[120px] p-3 d-flex" >
                    <div class="icon fs-2 justify-content-center align-items-center d-flex t-w-[100px]">
                        <fa class="text-white" icon="user"></fa>
                    </div>
                    <div class="totals t-grid w-100">
                        <h3 class="text-white text-uppercase t-text-[18px] text-nowrap">Total subjects</h3>
                        <p class="text-white fs-4">
                            <span v-if="subjectData.length == 0" >0</span>
                            <span v-else >{{ subjectData.length }}</span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="w-100 h-100 col p-2">
                <div class="bg-danger  rounded-2 t-h-[120px] p-3 d-flex" >
                    <div class="icon fs-2 justify-content-center align-items-center d-flex t-w-[100px]">
                        <fa class="text-white" icon="user"></fa>
                    </div>
                    <div class="totals t-grid w-100">
                        <h3 class="text-white text-uppercase t-text-[18px] text-nowrap">Total classrooms</h3>
                        <p class="text-white fs-4">
                            <span v-if= " classroomData.length == 0" >0</span>
                            <span v-else >{{ classroomData.length}}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template> -->

<!-- <script setup >




</script> -->