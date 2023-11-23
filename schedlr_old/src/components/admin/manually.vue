<template>
    <div class="t-h-screen t-w-full t-overflow-hidden t-select-none" >
        <div class="t-h-full t-grid t-grid-cols-[1fr,350px]" >
            <div class="t-overflow-hidden" >
                <div class="p-4  t-h-full t-pt-[160px] t-relative t-overflow-auto" >
                    <div class="t-bg-logoYellow t-rounded-[10px] t-p-[10px]  t-h-[150px]  " >
                        <h3 class="t-pl-[10px] t-pt-[20px]" >Welcome to Schedlr,<br>Now venturing Manual Scheduling</h3>
                    </div>
                    <div class="t-h-[40px] t-mt-[20px]" >
                        <div class="t-flex t-justify-between t-h-full" >
                            <div class="t-flex t-h-full" >
                                <div @click="changeContentSchedule('professor')" :class="{'t-bg-slate-200 t-font-semibold': computedContentShower == 'professor'}" class="t-cursor-pointer  t-rounded-t-[10px] t-h-full t-flex t-items-center t-pl-[10px] t-w-[120px]" >Professor</div>
                                <div @click="changeContentSchedule('classroom')" :class="{'t-bg-slate-200 t-font-semibold': computedContentShower == 'classroom'}" class="t-cursor-pointer  t-rounded-t-[10px] t-h-full t-flex t-items-center t-pl-[10px] t-w-[120px] " >Classroom</div>
                                <div @click="changeContentSchedule('section')" :class="{'t-bg-slate-200 t-font-semibold': computedContentShower == 'section'}" class="  t-cursor-pointert-rounded-t-[10px] t-h-full t-flex t-items-center t-pl-[10px] t-w-[120px]" >Section</div>
                            </div>
                            <div class="t-flex t-gap-2 t-relative" >
                                <div class="form-group" >
                                    <select :disabled="selected.professorID == '' || isProcess" @change="changeSemester" v-model="semester" class="form-select"  >
                                        <option value="1st semester">1st Semester</option>
                                        <option value="2nd semester">2nd Semester</option>
                                    </select>
                                </div>
                                <div class="d-flex t-gap-2" >
                                    <button :disabled="selected.professorID == '' || isProcess" @click="showModalButton('delete')" class="btn btn-outline-danger" ><fa icon="trash" ></fa></button>
                                    <button :disabled="selected.professorID == '' || isProcess" @click="showModalButton('form')" class="btn btn-outline-primary" ><fa icon="plus" ></fa></button>
                                    <div v-show="showModal != ''" class=" t-overflow-auto t-p-3 t-absolute t-h-[560px] t-w-[300px] bg-white t-shadow t-right-0 t-top-[50px] t-z-50" >
                                        <div v-show="showModal == 'form' " >
                                            <form @submit.prevent="createSchedule" >
                                                <div class="form-group mt-2">
                                                    <label for="" class="fw-bold">Semester</label>
                                                    <select :disabled="isProcess" @change="changeSemester" v-model="semester" class="form-select">
                                                        <option value="1st semester">1st Semester</option>
                                                        <option value="2nd semester">2nd Semester</option>
                                                    </select>
                                                </div>
                                                <div class="form-group mt-2">
                                                    <label for="" class="fw-bold">Subject</label>
                                                    <select :disabled="isProcess" v-model="schedule.subject" class="form-select text-uppercase">
                                                        <option  value="" selected >Select Subject</option>
                                                        <option class="text-uppercase" v-for="subject in globalLoadData" :key="subject.code" :value="subject.code">{{subject.code}}</option>
                                                    </select>
                                                </div>
                                                <div class="form-group mt-2">
                                                    <label for="" class="fw-bold">Classroom</label>
                                                    <select :disabled="schedule.subject == '' || isProcess" v-model="schedule.room" @change="changeClassroom" class="form-select text-uppercase">
                                                        <option selected disabled value="" class="text-uppercase" >Select Room</option>
                                                        <option class="text-capitalize text-uppercase" v-for="classroom in globalClassroomData" :key="classroom.id" :value="classroom.id" >{{classroom.room}}</option>
                                                    </select>
                                                </div>
                                                <div class="form-group mt-2">
                                                    <label for="" class="fw-bold">Section</label>
                                                    <select :disabled="schedule.room == '' || isProcess" v-model="schedule.section" @change="changeSection" class="form-select text-uppercase">
                                                        <option selected disabled value="">Select Section</option>
                                                        <option v-for="section in globalSectionData" :key="section.id" :value="section.id" >{{section.section}}</option>
                                                    </select>
                                                </div>
                                                <div class="form-group mt-2">
                                                    <label for="" class="fw-bold">Day</label>
                                                    <select :disabled="schedule.section == '' || isProcess" v-model="schedule.day" class="form-select">
                                                        <option value="monday">Monday</option>
                                                        <option value="tuesday">Tuesday</option>
                                                        <option value="wednesday">Wednesday</option>
                                                        <option value="thursday">Thursday</option>
                                                        <option value="friday">Friday</option>
                                                        <option value="saturday">Saturday</option>
                                                    </select>
                                                </div>
                                                <div class="form-group mt-2">
                                                    <label for="" class="fw-bold">Start</label>
                                                    <select :disabled="schedule.section == '' || isProcess" v-model="schedule.start" class="form-select">
                                                        <option  v-for="time in times" :key="time.id" :value="time.id" >{{time.time}}</option>
                                                    </select>
                                                </div>
                                                <div class="form-group mt-2">
                                                    <label for="" class="fw-bold">End</label>
                                                    <select :disabled="schedule.section == '' || isProcess" v-model="schedule.end" class="form-select">
                                                        <option  v-for="time in endTime" :key="time.id" :value="time.id" >{{time.time}}</option>
                                                    </select>
                                                </div>
                                                <div class="form-group mt-2" >
                                                    <button  :disabled="schedule.subject == '' || schedule.room =='' || schedule.section == ''  || isProcess " class="btn btn-primary w-100" >
                                                        <fa icon="save" ></fa> Save
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                        <div v-show="showModal == 'delete'" >
                                            <div class="">
                                                <div v-for="data in showDeleteData" :key="data.id" class="t-grid t-grid-cols-[1fr,40px] hover:t-bg-slate-200 p-2" >
                                                    <span>
                                                        <h6 class="text-uppercase t-m-0" >{{data.day}}</h6>
                                                        <small>{{data.time}}</small> <br>
                                                        <small class="text-capitalize" >{{ data.section}} - {{ data.classroom }}</small>
                                                    </span>
                                                    <span class="d-flex grid-justify-content-center align-items-end" >
                                                        <button :disabled="isProcess" @click="deleteSchedule(data.id)" class="btn btn-outline-danger" ><fa icon="trash"></fa></button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>    
                        </div>
                        <div class="t-bg-slate-200 p-3" >
                            <div v-if="computedContentShower =='professor'" >
                                <div v-if="selected.professorID != ''" >
                                    <scheduleTable :schedules="scheduleProfessor" ></scheduleTable>
                                </div>
                                <div v-else class="d-grid justify-content-center align-items-center t-pt-3" >
                                    <div class="d-grid justify-content-center align-items-center" >
                                        <img src="../../assets/images/table.png" style="height: 130px;" alt="">
                                    </div>
                                    <small class="t-text-fint-extralight">Please select professor first!.</small>
                                </div>
                            </div>
                            <div v-else-if="computedContentShower =='classroom'" >
                                <div v-if="schedule.room != ''" >
                                    <scheduleTable :schedules="scheduleClassroom" ></scheduleTable>
                                </div>
                                <div v-else class="d-grid justify-content-center align-items-center t-pt-3" >
                                    <div class="d-grid justify-content-center align-items-center" >
                                        <img src="../../assets/images/table.png" style="height: 130px;" alt="">
                                    </div>
                                    <small class="t-text-fint-extralight">Please select classroom first!.</small>
                                </div>
                            </div>
                            <div v-else >
                                <div v-if="schedule.section != ''" >
                                    <scheduleTable :schedules="scheduleSection" ></scheduleTable>
                                </div>
                                <div v-else class="d-grid justify-content-center align-items-center t-pt-3" >
                                    <div class="d-grid justify-content-center align-items-center" >
                                        <img src="../../assets/images/table.png" style="height: 130px;" alt="">
                                    </div>
                                    <small class="t-text-fint-extralight">Please select section first!.</small>
                                </div>
                            </div>
                        </div>
                        <br>
                    </div>
                </div>
            </div>
            <div class="t-h-auto p-4" >
                <div class="form-group t-relative t-z-50" >
                    <input :disabled="isProcess" @focus="focusShow" v-model="isSearch" placeholder="Search Professor" type="text" class="form-control">
                    <div v-show="isFocusSearch" class="bg-white shadow-lg mt-2 p-2 t-h-[400px] t-w-full t-overflow-x-auto t-z-50 t-absolute" >
                        <button :disabled="isProcess" @click="hideSearch" class="t-absolute t-top-0 t-right-[5px] t-text-[18px] text-danger" ><fa icon="times" ></fa></button>
                        <div v-for="professor in computedProfessor" class="t-pt-[20px] t-grid t-grid-cols-[50px,1fr] t-cursor-pointer t-bg-white" @click="selectedProfessor(professor)" >
                            <div class="d-flex justify-center align-items-center" >
                                <img src="../../assets/images/profile.png" style="height: 40px;" alt="">
                            </div>
                            <div class="t-overflow-hidden t-w-full" >
                                <h6 class="t-text-[13px] t-truncate text-capitalize" >
                                    <strong>ID: </strong>
                                    <span>{{ professor.professorID }}</span>
                                </h6>
                                <h6 class="t-text-[13px] t-truncate text-capitalize" >
                                    <strong>Fullname: </strong>
                                    <span>{{ professor.fullname }}</span>
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-2 t-w-[300px] p-2 t-grid t-grid-cols-[80px,1fr]" >
                    <div class="d-flex justify-center align-items-center" >
                        <img src="../../assets/images/profile.png" style="height: 63px;" alt="">
                    </div>
                    <div class="t-w-full t-overflow-hidden" >
                        <h6 :title="selected.professorID" class="t-text-[13px] text-capitalize t-truncate" >
                            <strong>ID: </strong>
                            <span v-if="selected.professorID != ''" >
                                {{selected.professorID}}
                            </span>
                            <span v-else >N/A</span>
                        </h6>
                        <h6 :title="selected.fullname" class="t-text-[13px] text-capitalize t-truncate" >
                            <strong>Fullname: </strong>
                            <span v-if="selected.fullname != ''" >
                                {{selected.fullname}}
                            </span>
                            <span v-else >N/A</span>
                        </h6>
                        <h6 :title="selected.rank" class="t-text-[13px] text-capitalize t-truncate" >
                            <strong>Rank: </strong>
                            <span v-if="selected.rank != ''" >
                                {{selected.rank}}
                            </span>
                            <span v-else >N/A</span>
                        </h6>
                    </div>
                </div>
                <div class="mt-3" >
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
                </div>
                <div class="mt-3" >
                    <h6 class="t-font-bold" >Notify other department.</h6>
                    <small class="t-font-extralight text-danger" >
                        *Take note<br>
                        Please notify others if you already done.
                    </small>
                    
                    <div class="t-h-[350px] t-relative" >
                        <div class="form-group" >
                            <input v-model="isSearchDepartment" class="text-uppercase form-control" placeholder="Search department" >
                        </div>
                        <div class="t-overflow-auto t-h-[300px] mt-2" >
                            <div v-for="dep in computedDepartment" class="t-flex t-justify-between mt-1 mb-1 bg-white t-h-[40px] t-pl-[10px] t-pr-[10px]" >
                                <div class="t-flex t-items-center" >
                                    <strong class="text-uppercase" >{{dep.department}}</strong>
                                </div>
                                <div class="t-flex t-items-center" >
                                    <button v-if="!isSending" @click="sendNotification(dep.department)" class="btn" :disabled="isProcess" >
                                        <fa icon="paper-plane" class="t-text-logoBlue hover:t-text-logoBluePartner t-cursor-pointer" ></fa>
                                    </button>
                                    <div v-else class="spinner-border" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import {ref,onMounted,computed,inject} from 'vue';
import Swal from 'sweetalert2';
import {scheduleStore} from '../../services/schedule';
import {loadStore} from '../../services/Loads';
import {publicStore} from '../../services/public';
import scheduleTable from "../partials/schedule-component/singleSchedule.vue";

const isProcess = ref(false);

const globalDepartmentData = inject("globalDepartmentData");
const departmentData = ref(globalDepartmentData);

const isSearchDepartment = ref('');

const computedDepartment =  computed(()=>{
    if(isSearchDepartment.value != ''){
        return departmentData.value.filter((dep)=>{
            return dep.department.toLowerCase().includes(isSearchDepartment.value.toLowerCase());
        })
    }
    else{
        return departmentData.value;
    }
})

const scheduleContentShower = ref('professor');
const changeContentSchedule = (scheduleShow)=>{
    scheduleContentShower.value = scheduleShow;
}
const computedContentShower = computed(()=>{
    return scheduleContentShower.value;
})

const use_scheduleStore = scheduleStore();
const use_loadStore = loadStore();
const use_publicStore = publicStore();

const globalLoadData = ref([]);
const last_added = ref([]);

const showModal = ref('');
const showModalButton = (data) =>{
    if(showModal.value != ""){
        if(data == showModal.value){
            showModal.value = "";
        }
        else{
            showModal.value = data;
        }
    }
    else{
        showModal.value = data;
    }
}

const scheduleProfessor = ref([]);
const scheduleClassroom = ref([]);
const scheduleSection = ref([]);

const isSearch = ref('');
const isFocusSearch = ref(false);
const focusShow = ()=>{
    isFocusSearch.value = true;
}

const hideSearch = ()=>{
    isFocusSearch.value = false;
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

const globalClassroomData = inject('globalClassroomData');
const globalSectionData = inject('globalSectionData');

const selectedProfessor = async(data)=>{
    const {professor, fullname,status,rankID,rank,designation} = data;
    isFocusSearch.value = false;
    isSearch.value = "";
    selected.value = {...data};

    await use_scheduleStore.professor({professor:selected.value.professorID , semester: semester.value});
    scheduleProfessor.value = use_scheduleStore.getSchedule;
    readProfessorLoad()
    showDelete();
}

const readProfessorLoad = async ()=>{
    try{
        isProcess.value = true;
        schedule.value.subject = "";
        await use_scheduleStore.showLoad({professor:selected.value.professorID , semester: semester.value});
        globalLoadData.value = use_scheduleStore.getResponse;
        isProcess.value = false;
    }
    catch(error){
        console.error(error)
    }
}

const semester = ref('1st semester')
const selected = ref({
    professorID: '', fullname: '', status: '', rankID: '', rank: '', designation: '',
});

const schedule = ref({
    professor: selected.value.professorID,
    day: 'monday',
    start: 0,
    end: '',
    semester: semester.value,
    subject: '',
    section: '',
    room: '',
    status: '',
    review: '',

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
    schedule.value.end = schedule.value.start + 2;
    return times.value.slice(schedule.value.start + 2);
})

const changeSemester = async ()=>{
    await use_scheduleStore.professor({professor:selected.value.professorID , semester: semester.value});
    scheduleProfessor.value = use_scheduleStore.getSchedule;

    changeClassroom();
    changeSection();
    showDelete();
    readProfessorLoad();
}

const changeClassroom = async ()=>{
    try{
        if(schedule.value.room != ""){
            isProcess.value = true;
            await use_scheduleStore.classroom({classroom: schedule.value.room , semester: semester.value});
            scheduleClassroom.value = use_scheduleStore.getSchedule;
            isProcess.value = false;
        }
    }
    catch(error){
        console.log(error)
    }
}

const changeSection = async ()=>{
    try{
        if(schedule.value.section != ""){
            isProcess.value = true;
            await use_scheduleStore.section({section: schedule.value.section , semester: semester.value});
            scheduleSection.value = use_scheduleStore.getSchedule;
            isProcess.value = false;
        }
    }
    catch(error){
        console.log(error)
    }
}

const showDeleteData = ref([]);
const showDelete = async ()=>{
    try{
        isProcess.value = true;
        await use_scheduleStore.showDelete({professor: selected.value.professorID , semester: semester.value});
        showDeleteData.value = use_scheduleStore.getResponse;

        //update last added schude
        last_added_department_function();
        isProcess.value = false;
    }
    catch(error){
        console.log(error)
    }
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
    }).then( async(result) => {
        if (result.isConfirmed) {

            isProcess.value = true;

            await use_scheduleStore.create({professor: selected.value.professorID, semester: semester.value, subject: schedule.value.subject,
                room: schedule.value.room, section: schedule.value.section,
                day: schedule.value.day, start:schedule.value.start , end: schedule.value.end});

            const response = use_scheduleStore.getResponse;
            
            if(response.status){
                changeSemester();
                Swal.fire("Success", response.msg,"success")
            }
            else{
                Swal.fire("Error",response.msg,"error");
            }

            isProcess.value = false;
        }
    })
}

const deleteSchedule = (id)=>{
    try{
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

                isProcess.value = true;

                await use_scheduleStore.delete(id);
                const response = use_scheduleStore.getResponse;
                if(response.status){
                    Swal.fire('Success', response.msg,"success");
                    changeSemester();
                }

                isProcess.value = false;
            }  
        })
        
    }
    catch(error){
        console.error(error)
    }
}

const isSending = ref(false);
const sendNotification = (department) =>{
    try {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes,Sent it!"
        }).then( async (result) => {
            isSending.value = true;
            await use_scheduleStore.notifyOtherDepartment(department);
            isSending.value = false;
        });

    } catch (error) {
        console.error(error);
    }
}

const last_added_department_function = async ()=>{
    await use_publicStore.last_added();
    last_added.value = use_publicStore.getResponse;
}

onMounted(async()=>{
    last_added_department_function()
})

</script>