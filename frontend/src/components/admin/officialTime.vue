<template>
    <div class="container t-select-none" >
        <h3 class="text-muted t-font-black" >Official Time</h3>
        <div class="t-mt-3 t-bg-slate-100 t-p-5 t-rounded-[10px]" >
            <div class="t-flex t-justify-end t-mb-2" >
                <div>
                    <input :disabled="isProcess" v-model="isSearch" class="form-control t-capitalize" placeholder="Search" type="text" >
                </div>
            </div>
            <div class=" t-w-full t-inline-block " >
                <div v-for="professor in computed_professor"  class="t-m-2 t-bg-white t-shadow t-rounded-[15px] t-w-[295px] t-inline-block" >
                    <div class="p-2 t-bg-white  t-rounded-[10px] t-grid t-grid-cols-[90px,1fr]"  >
                        <div class="t-flex t-justify-center t-items-center" >
                            <img src="../../assets/images/profile.png" class="t-w-[70px] t-bg-slaste-100 t-rounded-[50%]" >
                        </div>
                        <div  class="t-flex t-items-center t-pl-[10px]" >
                            <div>
                                <h6 class="t-capitalize t-font-semibold t-text-[18px] t-text- black t-m-0 t-p-0" >{{ professor.fullname }}</h6>
                                <small class="t-font-extralight t-text-[14px] t-text- black t-uppercase" >{{ professor.professorID }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="t-pl-2 t-pr-2 t-pb-2" >
                        <div class="t-text-[14px] t-text- black t-grid t-grid-cols-[90px,1fr]" >
                            <span>Rank</span>
                            <span class="t-capitalize" >{{ professor.rank }}</span>
                        </div>
                        <div class="t-text-[14px] t-text- black t-grid t-grid-cols-[90px,1fr]">
                            <span>Status</span>
                            <span class="t-capitalize" >{{professor.status}}</span>
                        </div>
                        <div class="t-text-[14px] t-text- black t-grid t-grid-cols-[90px,1fr]">
                            <span>Designated</span>
                            <span class="t-capitalize" >{{professor.designation}}</span>
                        </div>
                    </div>
                    <div class="p-2" >
                        <button @click="read_officialTime(professor.professorID)" class="btn btn-outline-primary t-w-full t-capitalize" data-bs-toggle="modal" data-bs-target="#official-time-modal" >Set Up</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- MODAL OFFICIAL TIME -->
    <div class="modal fade" id="official-time-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="official-time-modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title t-uppercase" id="official-time-modalLabel">{{ selectedProfessor }}</h5>
                <button @click="reset_data_official" :disabled="isProcess" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="t-select-none" >
                    <div class="t-flex t-justify-between" >
                        <div>
                            <form @submit.prevent="create_official_time" class="t-flex t-gap-2" v-show="selected_table !== 'delete'"  >
                                <div class="form-group">
                                    <select @change="read_officialTime(selectedProfessor)" class="form-select"  :disabled="isProcess" v-model="officials.semester" >
                                        <option value="1st semester" >1st Semester</option>
                                        <option value="2nd semester" >2nd Semester</option>
                                    </select>
                                </div>
                                <div class="form-group" >
                                    <select :disabled="isProcess" v-model="officials.day" class="t-capitalize form-select" >
                                        <option value="monday" class="t-capitalize " >monday</option>
                                        <option value="tuesday" class="t-capitalize " >tuesday</option>
                                        <option value="wednesday" class="t-capitalize " >wednesday</option>
                                        <option value="thursday" class="t-capitalize " >thursday</option>
                                        <option value="friday" class="t-capitalize " >friday</option>
                                        <option value="saturday" class="t-capitalize " >saturday</option>
                                    </select>
                                </div>
                                <div>
                                    <select v-model="officials.start" :disabled="isProcess" class="t-uppercase form-select" >
                                        <option v-for="time in times" :value="time.id" class="t-capitalize " >{{time.time}}</option>
                                    </select>
                                </div>
                                <div>
                                    <select v-model="officials.end" :disabled="isProcess" class="t-uppercase form-select" >
                                        <option v-for="time in computed_endtime" :value="time.id" class="t-capitalize " >{{time.time}}</option>
                                    </select>
                                </div>
                                <button class="btn btn-primary" :disabled="isProcess" >
                                    <fa icon="save" ></fa>
                                </button>
                            </form>
                        </div>
                        <div @click="change_tab" class="t-cursor-pointer t-grid t-grid-cols-[30px,1fr]" :class="{' text-danger': selected_table=='delete'}" >
                            <span class=" t-cursor-pointer t-flex t-items-center" >
                                <span v-if="selected_table == 'default'" >
                                    <fa  class=" t-cursor-pointer t-text-[25px]" icon="toggle-off" ></fa>
                                </span>
                                <span v-else >
                                    <fa class=" t-cursor-pointer t-text-[25px]" icon="toggle-on" ></fa>
                                </span>
                            </span>
                            <span class=" t-cursor-pointer t-flex t-items-center" >
                                <label class="t-cursor-pointer" >Delete Mode</label>
                            </span>
                        </div>
                    </div>
                    <div v-if="selected_table === 'default'" class="t-mt-5" >
                        <defaultMode :schedules="officialTimeData" ></defaultMode>
                    </div  >
                    <div v-else class="t-mt-5" >
                        <deleteMode @delete_official_time="delete_official_time" :officialList="officialDeleteListData" ></deleteMode>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import{ref,inject, computed, defineAsyncComponent} from "vue";
import Swal from "sweetalert2";
import { officialStore } from "../../services/officialTme";
const use_officialStore = officialStore();

const defaultMode = defineAsyncComponent(()=>import('../partials/official-component/default-mode.vue'));
const deleteMode = defineAsyncComponent(()=>import('../partials/official-component/delete-mode.vue'));

const isProcess = ref(false);
const isSearch = ref('');

const selectedProfessor = ref('');
const officials = ref({
    semester: '1st semester',
    day: 'monday',
    start: 0,
    end: 0,
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

const computed_endtime = computed(()=>{
    officials.value.end = officials.value.start + 1;
    return times.value.slice(officials.value.start + 1);
})

const globalProfessorData = inject('globalProfessorData');
const computed_professor = computed(()=>{
    if(isSearch.value != ""){
        return globalProfessorData.value.filter(professor=>{
            return professor.fullname.toLowerCase().includes(isSearch.value.toLowerCase());
        })
    }
    else{
        return globalProfessorData.value;
    }
});

const selected_table = ref('default');
const change_tab = () =>{
    if(selected_table.value ===  'default'){
        selected_table.value = 'delete';
    }
    else{
        selected_table.value = 'default';
    }
}

const officialTimeData = ref([])
const read_officialTime = async (professor)=>{
    try {
        isProcess.value = true;
        selectedProfessor.value = professor;
        await use_officialStore.read({professor:selectedProfessor.value,semester: officials.value.semester });
        officialTimeData.value = use_officialStore.getOfficial;
        isProcess.value = false;

        //show list all available to delete;
        read_officialTimeListDelete();

    } catch (error) {
        console.error(error);
    }
}


const reset_data_official = ()=>{
    selectedProfessor.value = '';
    officialTimeData.value = [];
    selected_table.value = 'default';
}

const officialDeleteListData = ref({
    'monday': {list: []},
    'tuesady': {list: []},
    'wednesday': {list: []},
    'thursday': {list: []},
    'friday': {list: []},
    'saturday': {list: []},
})
const read_officialTimeListDelete = async ()=>{
    try {
        isProcess.value = true;
        await use_officialStore.showDelete({professor: selectedProfessor.value , semester: officials.value.semester});
        officialDeleteListData.value = use_officialStore.getResponse;
        isProcess.value = false;

    } catch (error) {
        console.error(error);
    }
}

const create_official_time = ()=>{
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes,Save it!"
    }).then( async (result) => {
        if (result.isConfirmed) {
            isProcess.value = true;
            await use_officialStore.create({
                professor: selectedProfessor.value,
                semester: officials.value.semester,
                day: officials.value.day,
                start: officials.value.start,
                end: officials.value.end
            });

            const response = use_officialStore.getResponse;
            if(response.status){
                await use_officialStore.read({professor:selectedProfessor.value,semester: officials.value.semester });
                officialTimeData.value = use_officialStore.getOfficial;
                Swal.fire("Success",response.msg,'success');
            }

            isProcess.value = false;

        }
    });
}

const delete_official_time = (id)=>{
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes,Delete it!"
    }).then(async(result) => {
        if (result.isConfirmed) {
            await use_officialStore.delete(id);
            const response = use_officialStore.getResponse;

            if(response.status){

                Swal.fire("Success",response.msg,'success');

                await use_officialStore.read({professor:selectedProfessor.value, semester: officials.value.semester});
                officialTimeData.value = use_officialStore.getOfficial;

                await use_officialStore.showDelete({professor:selectedProfessor.value, semester: officials.value.semester});
                officialDeleteListData.value = use_officialStore.getResponse;
                
            }
        }
    });
}
</script>