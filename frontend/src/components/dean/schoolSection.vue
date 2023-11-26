<template>
    <div class="container t-select-none" >
        <div class="t-bg-slate-100 t-p-5 t-rounded-[10px]" >
            <h3 class="text-muted t-font-black" >Section</h3>
            <div class="t-mt-5" >
                <div class="t-flex t-justify-between"  >
                    <div class="" >
                        <div class="form-group t-w-[300px]" >
                            <select @change="read_slot" :disabled="isProcess || isEditMode !=''" v-model="sections.year" class="form-select t-capitalize" >
                                <option value="" disabled selected >Select Year</option>
                                <option v-for="year in globalYearlevel" class="t-capitalize" :key="year.id" :value="year.id" >{{ year.year }}</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <div class="t-flex t-gap-3" >
                            <div class="form-group t-w-[200px]" >
                                <select @change="read_slot" :disabled=" isProcess || sections.year == '' || isEditMode !='' " v-model="sections.semester" class="form-select t-capitalize" >
                                    <option value="1st semester" >1st Semester</option>
                                    <option value="2nd semester" >2nd Semester</option>
                                </select>
                            </div>
                            <form @submit.prevent="update_slot" class="t-flex t-h-full t-gap-2" >
                                <div class="t-flex t-items-center" >
                                    <label>Open Slot: &nbsp;</label>
                                </div>
                                <div class="form-group" >
                                    <input type="number" min="0" max="30" :disabled="isProcess || sections.year == '' || isEditMode !=''" :readonly="!is_edit_slot " v-model="slot" class="form-control" >
                                </div>
                                <div class="form-group" >
                                    <button :disabled="isProcess || sections.year == '' || isEditMode !=''" v-if="!is_edit_slot" @click="edit_slot_mode" type="button"  class="btn btn-outline-secondary" >
                                        <fa icon="edit" ></fa>
                                    </button>
                                    <div v-else class="t-flex t-gap-2" >
                                        <button :disabled="isProcess || sections.year == '' || isEditMode !=''" @click="edit_slot_mode" type="button"  class="btn btn-outline-secondary" >
                                            <fa icon="edit" ></fa>
                                        </button>
                                        <button :disabled="isProcess || sections.year == '' || isEditMode !=''" type="submit" class="btn btn-outline-primary" >
                                            <fa icon="save" ></fa>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="t-mt-[20px]" v-if="sections.year != ''"  >
                <div class="t-flex t-justify-between" >
                    <div class="form-group" >
                        <div class="t-flex t-w-[300px]" >
                            <input v-model="isSearch" :disabled="isProcess || sections.year == '' | isEditMode != ''" type="text" placeholder="Search" class="form-control" >
                        </div>
                    </div>
                    <div>
                        <button :disabled="isProcess || isEditMode !=''" class="btn btn-outline-primary w-100"  data-bs-toggle="modal" data-bs-target="#addModal" >
                            <fa icon="plus" ></fa>
                            ADD PROFESSOR
                        </button>
                    </div>
                </div>
                <div v-if="sectionData.length > 0" class="table-holder" >
                    <div class="t-h-[50px] t-bg-logoBlue t-mt-2 t-grid t-grid-cols-3" >
                        <span class="t-flex t-h-full t-pl-[5px] t-items-center t-text-white t-font-semibold" >
                            <label>Name</label>
                        </span>
                        <span class="t-flex t-h-full t-pl-[5px] t-items-center t-text-white t-font-semibold" >
                            <label>Specialization</label>
                        </span>
                        <span class="t-flex t-h-full t-pl-[5px] t-items-center t-text-white t-font-semibold" >
                            <label>Action</label>
                        </span>
                    </div>
                    <div v-for="section in computed_section" class="t-grid t-grid-cols-3 t-h-[60px]" >
                        <span class="t-p-2 t-flex t-items-center" >
                            <label v-if="isEditMode != section.section" class="t-uppercase t-font-normal text-muted t-truncate" :title="section.section" >{{ section.section }}</label>
                            <div v-else  class="t-w-full t-pl-[3px] t-pr-[3px]" >
                                <input v-model="sections.section" :disabled="isProcess" type="type" class="form-control t-uppercase" >
                            </div>
                        </span>
                        <span class="t-p-2 t-flex t-items-center" >
                            <label v-if="isEditMode != section.section" class="t-uppercase t-font-normal text-muted t-truncate" :title="section.specialization" >{{ section.specialization }}</label>
                            <div v-else  class="t-w-full t-pl-[3px] t-pr-[3px]" >
                                <select v-model="sections.specialization" :disabled="isProcess" class="form-select t-uppercase" > 
                                    <option v-for="specialization in globalSpecialization" :value="specialization.id" class="t-uppercase" >{{ specialization.specialization }}</option>
                                </select>
                            </div>
                        </span>
                        <div class="t-flex t-gap-3 t-items-center t-h-full t-p-2" >
                            <button @click="getUpdateData({id: section.id, section: section.section,semester: section.semester, specialization: section.specializationID, year: section.year})" >
                                <fa icon="edit" class="t-text-[18px] t-font-semibold t-text-slate-400 hover:t-text-slate-200" ></fa>
                            </button>
                            <button @click="delete_section(section.section)" v-if="isEditMode != section.section"  v-show="isEditMode == ''" :disabled="isProcess" class="" >
                                <fa icon="trash" class="t-text-[18px] t-font-semibold t-text-red-400 hover:t-text-red-200" ></fa>
                            </button>
                            <button @click="update_section" v-else   v-show="isEditMode != ''" :disabled="isProcess" class="" >
                                <fa icon="save" class="t-text-[18px] t-font-semibold t-text-blue-400 hover:t-text-red-200" ></fa>
                            </button>
                        </div>
                    </div>
                </div>
                <div v-else class=" t-justify-center t-items-center t-w-full p-5 t-grid" >
                    <img class="t-w-[400px] t-border-2 t-border-slate-100 t-border-b-slate-300" src="../../assets/images/no-data.svg" alt="no-data">
                    <h6 class="text-center t-capitalize t-mt-2" > no record found</h6>
                </div>
            </div>
            <div v-else class=" t-justify-center t-items-center t-w-full p-5 t-grid" >
                <img class="t-w-[400px] t-border-2 t-border-slate-100 t-border-b-slate-300" src="../../assets/images/no-selected.png" alt="no-data">
                <h6 class="text-center t-capitalize t-mt-2" >please select year first</h6>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title t-font-bold text-muted" id="addModalLabel">ADD</h5>
                    <button @click="entireReset" :disabled="isProcess" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="create_section" >
                        <div class="t-mt-3" >
                            <div class="form-group" >
                                <label for="">Section</label>
                                <input v-model="sections.section" type="text" class="form-control" >
                            </div>
                            <small v-if="sectionError.section" class="t-text-red-400" >{{sectionError.section}}</small>
                        </div>
                        <div class="t-mt-3" >
                            <div class="form-group" >
                                <label for="">Specialization</label>
                                <select v-model="sections.specialization" class="form-select t-capitalize" >
                                    <option value="">Select Specialization</option> 
                                    <option class="t-capitalize" v-for="specialize in specializationData" :key="specialize.id" :value="specialize.id" >{{ specialize.specialization }}</option>
                                </select>
                            </div>
                            <small v-if="sectionError.specialization" class="t-text-red-400" >{{sectionError.specialization}}</small>
                        </div>
                        <div class="t-mt-3 t-flex t-justify-end" >
                            <button :disabled="isProcess" type="submit" class="btn btn-primary w-50" >
                                <fa icon="save" ></fa>
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import { ref, inject, computed, onMounted } from 'vue';
import {sectionStore} from '../../services/sections';
import Swal from 'sweetalert2';

const use_sectionStore = sectionStore();
const globalYearlevel = inject('globalYearLevelData');

import { specializationStore } from '../../services/specialization';
const use_specializationStore = specializationStore();
const globalSpecialization = inject("globalSpecialization");
const specializationData = ref(globalSpecialization);

const isEditMode = ref('');

const getUpdateData = (data) => {
    if (isEditMode.value !== '') {
        if (isEditMode.value !== data.section) {
            sections.value = { id:data.id, section: data.section, semester: data.semester, specialization: data.specialization };
            isEditMode.value = data.section;
        } else {
            isEditMode.value = '';
            reset();
        }
    } else {
        sections.value = { id:data.id, section: data.section, semester: data.semester, specialization: data.specialization };
        isEditMode.value = data.section;
    }
    sections.value.year = data.year;
}

const mounted_create_common = async ()=>{
    try {
        await use_specializationStore.autocreatecommon();
        specializationData.value = use_specializationStore.getSpecialization;
    }catch (error) {
        console.error(error)
    }
}
onMounted( async()=>{
    await mounted_create_common();
})

const isSearch = ref('')
const isProcess = ref(false)

const slot = ref(0);
const is_edit_slot = ref(false);
const edit_slot_mode = ()=>{
    is_edit_slot.value = (is_edit_slot.value) ? false : true;
}

const sections = ref({
    id: '',
    section: '',
    specialization: '',
    year: '',
    semester: '1st semester',
});

const sectionError = ref({
    section: '',
    specialization: ''
});

const entireReset = ()=>{
    section.value.section = "";
    section.value.specialization = "";
    resetError();
    isEditMode.value = '';
}

const reset = ()=>{
    sections.value = {
        id: '',
        section: '',
        specialization: '',
        year: '',
        semester: '1st semester',
    }
}

const resetError = ()=>{
    sectionError.value = {
        section: '',
        specialization: ''
    }
}

const sectionData = ref([]);
const computed_section = computed(()=>{
    if(isSearch.value != ''){
        sectionData.value.filter((search)=>search.section.toLowerCase().includes(isSearch.value.toLowerCase()));
    }
    else{
        return sectionData.value;
    }
})

const read_slot = async ()=>{
    try {
        await use_sectionStore.read_slot({year: sections.value.year, semester: sections.value.semester});
        const response = use_sectionStore.getSlot;
        slot.value = response.slot;
        is_edit_slot.value = false;
    } catch (error) {
        console.error(error)
    }

    read_section();
}

const update_slot = async()=>{
    try {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes,Update it!"
        }).then(async(result) => {
            if (result.isConfirmed) {
                await use_sectionStore.update_slot({slot: slot.value, year: sections.value.year, semester: sections.value.semester});
                const response = use_sectionStore.getResponse;
                if(response.status){
                    read_slot();
                    Swal.fire("Success",response.msg,'success');
                }
                else{
                    Swal.fire("Error",response.msg,'error')
                }
            }
        });
    } catch (error) {
        console.error(error);
    }
}

const create_section = ()=>{
    try {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes,Save it!"
        }).then(async (result) => {
            if (result.isConfirmed) {
                isProcess.value = true;
                resetError();
                await use_sectionStore.create_section({
                    year: sections.value.year,
                    semester: sections.value.semester,
                    section: sections.value.section,
                    specialization: sections.value.specialization
                });
                const response = use_sectionStore.getResponse;
                if(response.status){
                    read_section()
                    Swal.fire("Success",response.msg,'success')
                }
                else{
                    if(response.error == 'section'){
                        sectionError.value.section = response.msg;
                    }
                    else if(response.error == 'specialization'){
                        sectionError.value.specialization = response.msg;
                    }
                    else{
                        Swal.fire("Error",response.msg,'error')
                    }
                }
                isProcess.value = false;
            }
        });
    } catch (error) {
        console.log(error);
    }
}

const read_section = async ()=>{
    try {
        isProcess.value = true;
        await use_sectionStore.read_section({year: sections.value.year, semester: sections.value.semester});
        sectionData.value = use_sectionStore.getSections;
        isProcess.value =false;
    } catch (error) {
        console.error(error)
    }
}

const update_section = async ()=>{
    try {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes,Update it!"
        }).then(async(result) => {
            if (result.isConfirmed) {
                await use_sectionStore.update_section({
                    id: sections.value.id,
                    section: sections.value.section,
                    specialization: sections.value.specialization,
                    semester: sections.value.semester,
                });
                const response = use_sectionStore.getResponse;
                if(response.status){
                    read_section();
                    isEditMode.value = '';
                    section.value.section = "";
                    section.value.specialization = "";
                    Swal.fire("Success",response.msg,'success');
                }
                else{
                    Swal.fire("Error",response.msg,'error');
                }
                // console.log(response);
            }
        });
    } catch (error) {
        console.error(error);
    }
}

const delete_section = async (section)=>{
    try {
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
                await use_sectionStore.delete_section(section)
                const response = use_sectionStore.getResponse;
                if(response.status){
                    read_section();
                    Swal.fire("Success",response.msg,'success');
                }
            }
        });
    } catch (error) {
        console.error(error);
    }
}

const autogenerated = async ()=>{
    try{
        await use_sectionStore.auto_generated_slot()
    }
    catch(error){
        console.error(error)
    }
}

onMounted(()=>{
    autogenerated();
})

</script>
