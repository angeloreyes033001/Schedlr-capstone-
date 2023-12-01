<template >
    <div class="t-select-none t-h-full t-overflow-hidden t-grid t-grid-rows-[1fr,150px] t-gap-5 t-p-5" >
        <div class=" t-overflow-hidden t-h-full t-grid t-grid-cols-[1fr,300px] t-gap-5 " >
            <div class="t-overflow-auto t-p-3 t-bg-slate-100 t-rounded-[10px] t-shadow" >
                <h1 class="t-text-[80px]" >lorem</h1>
               <pre>{{ departmentData }}</pre>
            </div>
            <div class="t-p-3 t-bg-slate-100 t-rounded-[10px] t-shadow t-h-full t-grid t-grid-rows-[auto,270px,1fr] t-gap-5" >
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
                <div class="t-h-full" >
                    <label class="text-muted" >Notify Other Department</label>
                    <div class="form-group" >
                        <input v-model="isSearchDepartment" type="text" placeholder="Search Department" class="form-control t-uppercase" >
                    </div>
                    <div class="t-mt-1 t-overflow-auto t-h-[200px]" >
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
                <div class="" >
                    <label>Last schedule added.</label>
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
                                <h4 class="t-font-bolder text-muted" >0</h4>
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
import {ref,onMounted,inject,computed} from 'vue';
import Swal from 'sweetalert2';
import {generalStore} from '../../services/general.js';

const isProcess = ref(false)
const isSending = ref(false);

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

// const getTotalClassroom = async ()=>{
//     await use_generalStore.read_classroom();
//     const response = use_generalStore.getClassroom;
//     totals.value.classroom = response;
// }

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

onMounted(()=>{
    getTotalSubject();
    getTotalClassroom();
    getTotalAdmin()
})

</script>