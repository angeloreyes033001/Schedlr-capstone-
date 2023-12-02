<template >
    <div class="t-select-none t-h-full t-overflow-hidden t-grid t-grid-rows-[1fr,150px] t-gap-5 t-p-5" >
        <div class=" t-overflow-hidden t-h-full t-grid t-grid-cols-[1fr,300px] t-gap-5 " >
            <div class="t-overflow-auto t-p-3 t-bg-slate-100 t-rounded-[10px] t-shadow" >
                <div class="" >
                    <div class=" t-p-3 t-grid t-grid-cols-[80px,1fr] t-gap-3 t-w-auto" >
                        <div class="" >
                            <img src="../../assets/images/profile.png" class="t-w-[100px]" >
                        </div>
                        <div class="t-flex t-items-center" >
                            <div class="" >
                                <div class="t-grid t-grid-cols-[80px,1fr]" >
                                    <div class="t-flex t-items-center" >
                                        <small class="t-font-extralight" >Fullname:</small>
                                    </div>
                                    <div class="t-flex t-items-center" >
                                        <select v-model="selectedProfessorID" @change="change_professor" class="form-select t-capitalize" >
                                            <option value="" selected disabled >Select Professor</option>
                                            <option v-for="professor in globalProfessorData" :value="professor.professorID" class="t-capitalize"  >{{ professor.fullname }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="t-grid t-grid-cols-[80px,1fr] t-mt-2" >
                                    <div class="t-flex t-items-center" >
                                        <small class="t-font-extralight" >ID:</small>
                                    </div>
                                    <div class="t-flex t-items-center" >
                                        <h6 class="t-m-0 t-h-0 t-text-[14px] t-uppercase" >
                                            <span v-if="selectedProfessorID == ''" >-------</span>
                                            <span v-else >{{ selectedProfessorID }}</span>
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-if="selectedProfessorID != ''" class="t-bg-white t-p-5 t-rounded-[10px]" >
                        <div class="t-flex t-justify-end" >
                            <div class="form-group" >
                                <select  v-model="selectedSemester"  @change="change_professor" class="form-select" >
                                    <option value="1st semester" >1st Semester</option>
                                    <option value="2nd semester" >2nd Semester</option>
                                </select>
                            </div>
                        </div>
                        <div class="t-mt-2" >
                            <scheduleTable :schedules="professorScheduleData" ></scheduleTable>
                        </div>
                    </div>
                    <div v-else class=" t-justify-center t-items-center t-w-full p-5 t-grid" >
                        <img class="t-w-[500px] t-border-2 t-border-slate-100 t-border-b-slate-200" src="../../assets/images/no-data.svg" alt="no-data">
                        <small class="text-center t-capitalize t-mt-2" >Please select professor first.</small>
                    </div> 
                </div>
            </div>
            <div class="t-p-3 t-bg-slate-100 t-rounded-[10px] t-shadow t-h-full t-grid t-grid-rows-[auto,1fr] t-gap-5" >
                <div >
                    <label class="text-muted" >Display Schedule public.</label>
                    <div class="form-group t-mt-2" >
                        <button @click="show_schedule_public" class="btn btn-primary w-100" >
                            <fa icon="eye" ></fa>
                             Display Schedule
                        </button>
                    </div>
                    <div class="form-group t-mt-2" >
                        <button @click="hidden_schedule_public" class="btn btn-secondary w-100" >
                            <fa icon="eye-slash" ></fa>
                             Hide Schedule
                        </button>
                    </div>
                </div>
                <div class="t-h-full t-overflow-auto" >
                    <label class="text-muted" >Notify Other Department</label>
                    <div class="form-group" >
                        <input v-model="isSearchDepartment" type="text" placeholder="Search Department" class="form-control t-uppercase" >
                    </div>
                    <div class="t-mt-1 t-h-[500px]" >
                        <div v-for="dep in computed_department" class="t-h-[50px] t-bg-white t-shadow t-mt-2 t-mb-2 t-rounded-[5px] t-grid t-grid-cols-[1fr,60px]" >
                            <div class="t-h-full t-grid t-items-center" >
                                <h6 class="t-m-0 t-p-0 t-pl-[10px] t-uppercase" >{{dep.department}}</h6>
                            </div>
                            <div class="t-h-full t-grid t-items-center t-justify-center" >
                                <button v-if="isSending"  disabled class="btn btn-primary" >
                                    <fa icon="spinner" ></fa>
                                </button>
                                <button @click="dean_to_otherDepartment(dep.department)" v-else  :disabled="isProcess||isSending" class="btn btn-primary" >
                                    <fa icon="paper-plane" ></fa>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="t-bg-white t-rounded-[10px] t-grid t-grid-cols-4 t-gap-5" >
            <div class="" >
                <div class="t-p-2 t-bg-slate-100 t-w-full t-h-full t-rounded-[10px] t-shadow" >
                    <div class="t-grid t-grid-cols-[80px,1fr] t-h-full" >
                        <div class="t-flex t-items-center t-justify-center" >
                            <div class="" >
                                <fa class="t-text-[50px] t-text-red-300" icon="book"></fa>
                            </div>
                        </div>
                        <div class="t-h-full t-flex t-items-center" >
                            <div>
                                <h4 class="t-font-bolder text-muted" >{{ totals.subject }}</h4>
                                <label class="text-muted t-font-extralight" >TOTAL SUBJECTS</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="" >
                <div class="t-p-2 t-bg-slate-100 t-w-full t-h-full t-rounded-[10px] t-shadow" >
                    <div class="t-grid t-grid-cols-[80px,1fr] t-h-full" >
                        <div class="t-flex t-items-center t-justify-center" >
                            <div class="" >
                                <fa class="t-text-[50px] t-text-yellow-300" icon="people-roof"></fa>
                            </div>
                        </div>
                        <div class="t-h-full t-flex t-items-center" >
                            <div>
                                <h4 class="t-font-bolder text-muted" >{{ totals.classroom }}</h4>
                                <label class="text-muted t-font-extralight" >TOTAL CLASSROOMS</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="" >
                <div class="t-p-2 t-bg-slate-100 t-w-full t-h-full t-rounded-[10px] t-shadow" >
                    <div class="t-grid t-grid-cols-[80px,1fr] t-h-full" >
                        <div class="t-flex t-items-center t-justify-center" >
                            <div class="" >
                                <fa class="t-text-[50px] t-text-green-300" icon="building"></fa>
                            </div>
                        </div>
                        <div class="t-h-full t-flex t-items-center" >
                            <div>
                                <h4 class="t-font-bolder text-muted" >{{ totals.section }}</h4>
                                <label class="text-muted t-font-extralight" >TOTAL SECTIONS</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="" >
                <div class="t-p-2 t-bg-slate-100 t-w-full t-h-full t-rounded-[10px] t-shadow" >
                    <div class="t-grid t-grid-cols-[80px,1fr] t-h-full" >
                        <div class="t-flex t-items-center t-justify-center" >
                            <div class="" >
                                <fa class="t-text-[50px] t-text-blue-300" icon="users"></fa>
                            </div>
                        </div>
                        <div class="t-h-full t-flex t-items-center" >
                            <div>
                                <h4 class="t-font-bolder text-muted" >{{ totals.admin }}</h4>
                                <label class="text-muted t-font-extralight" >TOTAL ADMINS</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup >
import {ref,onMounted,inject,computed,defineAsyncComponent} from 'vue';
import Swal from 'sweetalert2';
import {publicStore} from '../../services/public.js';
import {generalStore} from '../../services/general.js';

const isProcess = ref(false)
const isSending = ref(false);

const scheduleTable = defineAsyncComponent(()=>import('../partials/schedule-component/schedule.vue'));

const isSearchDepartment =ref('')
const globalDepartmentData = inject("globalDepartmentData");
 const departmentData = ref(globalDepartmentData);
 const computed_department = computed(() => {
    if (isSearchDepartment.value !== '') {
        return departmentData.value.filter((item) =>
            item.department.toLowerCase().includes(isSearchDepartment.value.toLowerCase())
        );
    } else {
        return departmentData.value;
    }
});

const use_generalStore = generalStore();
const use_publicStore = publicStore();

const totals = ref({
    subject: 0,
    classroom: 0,
    section: 0,
    admin: 0
})

const getTotalSubject = async ()=>{
    try {
        isProcess.value = true;
        await use_generalStore.read_subject();
        const response = use_generalStore.getSubject;
        totals.value.subject = response;
        isProcess.value = false;
    } catch (error) {
        console.error(error)
    }
}

const getTotalClassroom = async ()=>{
   try {
        isProcess.value = true;
        await use_generalStore.read_classroom();
        const response = use_generalStore.getClassroom;
        totals.value.classroom = response;
        isProcess.value = false;
   } catch (error) {
        console.error(error)
   }
}

const getTotalSection = async ()=>{
    await use_generalStore.read_section();
    const response = use_generalStore.getSection;
    totals.value.section = response;
}

const getTotalAdmin = async ()=>{
    try {
        isProcess.value = true;
        await use_generalStore.read_admin();
        const response = use_generalStore.getAdmin;
        totals.value.admin = response;
        isProcess.value = false;
    } catch (error) {
        console.log(error)
    }
}

const show_schedule_public = ()=>{
    Swal.fire({
        title: "NOTE",
        text: "Please click confirm if schedule is already finalize.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Confirm"
    }).then( async(result) => {
        if (result.isConfirmed) {
            isProcess.value = true;
            await use_generalStore.show_schedule_public();
            const response = use_generalStore.getResponse;
            if(response.status){
                Swal.fire("Success",response.msg,'success');
            }
            isProcess.value = true;   
        }
    });
}

const hidden_schedule_public = ()=>{
    Swal.fire({
        title: "Are you sure?",
        text: "Hide current schedule to everyone.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Hide now"
    }).then( async(result) => {
        if (result.isConfirmed) {
            isProcess.value =true;
            await use_generalStore.hidden_schedule_public();
            const response = use_generalStore.getResponse;
            if(response.status){
                Swal.fire("Success",response.msg,'success');
            }  
            isProcess.value =false;
        }
    });
}

const dean_to_otherDepartment =  (other_dep)=>{
    try {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, Send notification."
        }).then(async(result) => {
            if (result.isConfirmed) {
                isProcess.value = true;
                isSending.value = true;
                await use_generalStore.dean_to_otherDepartment(other_dep);
                const response  =use_generalStore.getResponse;
                if(response.status){
                    Swal.fire("Success",response.msg,"success");
                }
                isSending.value = false;
                isProcess.value = false;
            }
        });
    } catch (error) {
        console.error(error)
    }
}

const selectedProfessorID = ref("");
const selectedSemester = ref("1st semester");
const change_professor = ()=>{
    read_professor_schedule_dean();
}

const professorScheduleData = ref([]);
const read_professor_schedule_dean = async ()=>{
    try {
        isProcess.value= true;
        await use_publicStore.read_professor_schedule_dean({professor: selectedProfessorID.value, semester: selectedSemester.value});
        professorScheduleData.value = use_publicStore.getSchedule;
        isProcess.value = false;
    } catch (error) {
        console.error(error);
    }
}
const globalProfessorData = inject("globalProfessorData");

onMounted(()=>{
    getTotalSubject();
    getTotalClassroom();
    getTotalSection();
    getTotalAdmin();
})

</script>