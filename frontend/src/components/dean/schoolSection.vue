<template>
    <div class="container t-select-none" >
        <h3 class="text-muted t-font-black" >Section</h3>
        <div class="t-mt-3" >
            <div class="t-flex t-justify-end">
                <button :disabled="isProcess" class="btn btn-outline-primary w-25" data-bs-toggle="modal" data-bs-target="#addModal" >
                    <fa icon="plus" ></fa>
                    ADD SECTION
                </button>
            </div>
        </div>
        <div v-if="sectionData.length > 0" class="table-holder mt-3" >
            <div class="t-bg-slate-100 t-p-5 t-rounded-[10px]" >
                <div class="t-flex t-justify-end t-mb-2" >
                    <div>
                        <input :disabled="isProcess" v-model="isSearch" class="form-control t-capitalize" placeholder="Search" type="text" >
                    </div>
                </div>
                <div class="t-h-[60px] t-bg-logoBlue t-p-2 t-rounded-tl-[10px] t-rounded-tr-[10px] t-grid t-grid-cols-4">
                    <div class="t-h-full" >
                        <div class="t-flex t-items-center t-h-full" >
                            <label class="t-uppercase t-text-white t-font-bold" >Section</label>
                        </div>
                    </div>
                    <div class="t-h-full" >
                        <div class="t-flex t-items-center t-h-full" >
                            <label class="t-uppercase t-text-white t-font-bold" >Year</label>
                        </div>
                    </div>
                    <div class="t-h-full" >
                        <div class="t-flex t-items-center t-h-full" >
                            <label class="t-uppercase t-text-white t-font-bold" >Specialization</label>
                        </div>
                    </div>
                    <div class="t-h-full" >
                        <div class="t-flex t-items-center t-h-full" >
                            <label class="t-uppercase t-text-white t-font-bold" >Action</label>
                        </div>
                    </div>
                </div>
                <div  class="t-grid t-grid-cols-4 t-h-[60px] t-bg-slate-50 t-rounded-[5px] t-mt-2 t-mb-1 t-p-2 t-border"
                v-for="section in computed_section" :key="section.id" >
                <div class="t-flex t-items-center t-h-full" >
                        <label v-if="isEditMode != section.id" class="t-capitalize t-font-normal text-muted t-truncate" :title="section.section" >{{ section.section }}</label>
                        <div v-else  class="t-w-full t-pl-[3px] t-pr-[3px]" >
                            <input v-model="sections.sectionName" :disabled="isProcess" type="text" class="form-control t-capitalize" >
                        </div>
                    </div>
                    <div class="t-flex t-items-center t-h-full" >
                        <label v-if="isEditMode != section.id" class="t-capitalize t-font-normal text-muted t-truncate" :title="section.year" >{{ section.year }}</label>
                        <div v-else  class="t-w-full t-pl-[3px] t-pr-[3px]" >
                            <select v-model="sections.year"  :disabled="isProcess" class="t-capitalize form-select" >
                                <option class="t-capitalize" v-for="year in globalYearlevel" :value="year.id" >{{ year.year }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="t-flex t-items-center t-h-full" >
                        <label v-if="isEditMode != section.id" class="t-capitalize t-font-normal text-muted t-truncate" :title="section.specialization" >{{ section.specialization }}</label>
                        <div v-else  class="t-w-full t-pl-[3px] t-pr-[3px]" >
                            <select v-model="sections.specialization"  :disabled="isProcess" class="t-uppercase form-select" >
                                <option class="text-uppercase" v-for="specialization in specializationData" :value="specialization.id"  >{{ specialization.specialization }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="t-flex t-gap-3 t-items-center t-h-full" >
                            <button @click="getUpdateData({
                                                section: section.id ,
                                                sectionName: section.section,
                                                specialization: section.specializationID,
                                                year: section.yearID
                                            })" >
                                <fa icon="edit" class="t-text-[18px] t-font-semibold t-text-slate-400 hover:t-text-slate-200" ></fa>
                            </button>
                            <button @click="delete_section(section.id)" v-if="isEditMode !=  section.id"  v-show="isEditMode == ''" :disabled="isProcess" class="" >
                                <fa icon="trash" class="t-text-[18px] t-font-semibold t-text-red-400 hover:t-text-red-200" ></fa>
                            </button>
                            <button v-else @click="update_section"  v-show="isEditMode != ''" :disabled="isProcess" class="" >
                                <fa icon="save" class="t-text-[18px] t-font-semibold t-text-blue-400 hover:t-text-red-200" ></fa>
                            </button>
                        </div>
                </div>
            </div>
        </div>
        <div v-else class=" t-justify-center t-items-center t-w-full p-5 t-grid" >
            <img class="t-w-[700px] t-border-2 t-border-white t-border-b-slate-300" src="../../assets/images/no-data.svg" alt="no-data">
            <h6 class="text-center t-capitalize t-mt-2" > no record found</h6>
        </div>
    </div>
    <!-- modal add -->
    <div class="modal fade" id="addModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title t-font-bold text-muted" id="addModalLabel">ADD</h5>
                    <button @click="entireReset" :disabled="isProcess" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="create_section" >
                        <div class="mt-3">
                            <div class="form-group">
                                <label for="">Section</label>
                                <input :disabled="isProcess" type="text" class="form-control text-uppercase" v-model="sections.sectionName" >
                            </div>
                            <small class="text-danger" v-if="sectionError.sectionName != ''" >{{ sectionError.sectionName }}</small>
                        </div>
                        <div class="mt-3">
                            <div class="form-group">
                                <label for="">Yearlevels</label>
                                <select :disabled="isProcess" v-model="sections.year" class="form-select text-capitalize" >
                                    <option value="">SELECT YEARLEVEL</option>
                                    <option class="text-capitalize" v-for="year in globalYearlevel" :value="year.id" >{{ year.year }}</option>
                                </select>
                            </div>
                            <small class="text-danger" v-if="sectionError.year != ''" >{{ sectionError.year }}</small>
                        </div>
                        <div class="mt-3" >
                            <div class="form-group" >
                                <label for="">Specialization</label>
                                <select :disabled="isProcess" v-model="sections.specialization" class="form-select t-uppercase" >
                                    <option value="" class="t-uppercase" disabled selected >Select Specialization</option>
                                    <option class="t-uppercase" v-for="specialization in specializationData" :value="specialization.id"  >{{ specialization.specialization }}</option>
                                </select>
                            </div>
                            <small class="text-danger" v-if="sectionError.specialization != ''" >{{ sectionError.specialization }}</small>
                        </div>
                        <div class="mt-3 text-end">
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
<script setup >
import {ref,onMounted,computed, inject} from 'vue';
import {sectionStore} from '../../services/sections';
import Swal from 'sweetalert2';

const use_sectionStore = sectionStore();

const globalSectionData = inject('globalSectionData');
const sectionData = ref(globalSectionData);

const globalSpecialization = inject("globalSpecialization");
const specializationData = ref(globalSpecialization);

const globalYearlevel = inject('globalYearLevelData');

const isSearch = ref('');
const isProcess = ref(false);
const isEditMode =ref('');

const sections = ref({
    section: '',
    sectionName: '',
    specialization: '',
    year: '',
})

const sectionError = ref({
    sectionName: '',
    specialization:'',
    year: '',
})

const resetError = ()=>{
    sectionError.value = {
        sectionName: '',
        specialization:'',
        year: '',
    }
}

const reset = () =>{
    sections.value = {
        section: '',
        sectionName: '',
        specialization: '',
        year: '',
    }
}

const entireReset = ()=>{
    resetError();
    reset();
}

const getUpdateData = (data) => {
    sections.value.section = data.section;
    sections.value.sectionName = data.sectionName;
    sections.value.specialization = data.specialization;
    sections.value.year = data.year;
    if (isEditMode.value !== '') {
        if (isEditMode.value !== data.section) {
            isEditMode.value = data.section;
        } else {
            isEditMode.value = '';
            reset();
        }
    } else {
        isEditMode.value = data.section;
    }
}

const computed_section = computed(()=>{
    if(isSearch.value != ""){
        return sectionData.value.filter(section=>{
            return section.section.toLowerCase().includes(isSearch.value.toLowerCase());
        })
    }
    else{
        return sectionData.value;
    }
})

const create_section = ()=>{
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes,Save it!"
    }).then(async(result) => {
        if (result.isConfirmed) {
            resetError();
            isProcess.value = true;
            await use_sectionStore.create({...sections.value});
            const response = use_sectionStore.getResponse;
            if(response.status){
                await use_sectionStore.read();
                sectionData.value = use_sectionStore.getSections;
                entireReset();
                Swal.fire("Success",response.msg,'success');
            }else{
                if(response.error == "section"){
                    sectionError.value.sectionName = response.msg;
                }

                if(response.error == "yearlevel"){
                    sectionError.value.year = response.msg;
                }

                if(response.error == "specialization"){
                    sectionError.value.specialization = response.msg;
                }
            }
            isProcess.value = false;
        }
    });
}

const update_section = ()=>{
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
            isProcess.value = true;
            await use_sectionStore.update({...sections.value});
            const response =  use_sectionStore.getResponse;
            if(response.status){
                await use_sectionStore.read();
                sectionData.value = use_sectionStore.getSections;
                isEditMode.value = ''
                reset();
                Swal.fire("Success",response.msg,'success')
            }
            else{
                
            }
            isProcess.value = false;
        }
    });
}

const delete_section = (id)=>{
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes,Delete it!"
    }).then(async (result) => {
        if (result.isConfirmed) {
            isProcess.value = true;
            await use_sectionStore.delete(id);
            const response = use_sectionStore.getResponse;
            if(response.status){
                await use_sectionStore.read();
                sectionData.value = use_sectionStore.getSections;
                Swal.fire("Success",response.msg,'success')
            }
            isProcess.value = false;
        }
    });
}

// const createSection = async ()=>{
//     try{
//         Swal.fire({
//             title: 'Are you sure?',
//             text: "You won't be able to revert this!",
//             icon: 'warning',
//             showCancelButton: true,
//             confirmButtonColor: '#3085d6',
//             cancelButtonColor: '#d33',
//             confirmButtonText: 'Yes,Save it!'
//         }).then( async (result) => {
//             if (result.isConfirmed) {
//                 isProcess.value = true;
//                 await use_sectionStore.create({section: sections.value.section, yearlevel: sections.value.yearlevel , specialization:sections.value.specialization});
//                 const response = use_sectionStore.getResponse;
//                 if(response.status){
//                     sections.value.section = "";
//                     sections.value.yearlevel = "";
//                     sections.value.specialization = "";
//                     sections.value.section_error = "";
//                     sections.value.yearlevel_error = "";
//                     sections.value.specialization_error = "";
//                     Swal.fire("Success", response.msg,"success");
//                     await use_sectionStore.read();
//                     sectionData.value = use_sectionStore.getSections;
//                 }
//                 else{
//                     if(response.error == "section"){
//                         sections.value.section_error = response.msg;
//                     }
//                     if(response.error == "yearlevel"){
//                         sections.value.yearlevel_error = response.msg;
//                     }
//                     if(response.error == "specialization"){
//                         sections.value.specialization_error = response.msg;
//                     }
//                 }
//                 isProcess.value = false;
//             }
//         })
//     }
//     catch(error){
//         console.error(error);
//     }
// }

// const setData = (data)=>{
//     vUpdate.value.id = data.id;
//     vUpdate.value.section = data.section;
//     vUpdate.value.yearlevel = data.yearlevel;
//     vUpdate.value.specialization = data.specialization;
// }
// const vUpdate = ref({
//     id: '',
//     section: '',
//     section_error: '',
//     yearlevel: '',
//     yearlevel_error: '',
//     specialization: '',
//     specialization_error: '',
// });

// const updateSection = async ()=>{
//     try{
//         Swal.fire({
//             title: 'Are you sure?',
//             text: "You won't be able to revert this!",
//             icon: 'warning',
//             showCancelButton: true,
//             confirmButtonColor: '#3085d6',
//             cancelButtonColor: '#d33',
//             confirmButtonText: 'Yes,Update it!'
//         }).then( async (result) => {
//             if (result.isConfirmed) {
//                 isProcess.value = true;
//                 await use_sectionStore.update({
//                     id: vUpdate.value.id,
//                     section: vUpdate.value.section,
//                     yearlevel: vUpdate.value.yearlevel,
//                     specialization: vUpdate.value.specialization,
//                 });

//                 const response = use_sectionStore.getResponse;
//                 if(response.status){
//                     Swal.fire("Success",response.msg,"success");
//                     await use_sectionStore.read();
//                     sectionData.value = use_sectionStore.getSections;
//                 }
//                 else{
//                     if(response.error == "section"){
//                         vUpdate.value.section_error = response.msg;
//                     }

//                     if(response.error == "yearlevel"){
//                         vUpdate.value.yearlevel_error = response.msg;
//                     }

//                     if(response.error == "specialization"){
//                         vUpdate.value.specialization_error = response.msg;
//                     }
//                 }
//                 isProcess.value = false;
//             }
//         })
//     }
//     catch(error){
//         console.error(error);
//     }
// }

// const deleteSection = async (id)=>{
//     try{
//         Swal.fire({
//             title: 'Are you sure?',
//             text: "You won't be able to revert this!",
//             icon: 'warning',
//             showCancelButton: true,
//             confirmButtonColor: '#3085d6',
//             cancelButtonColor: '#d33',
//             confirmButtonText: 'Yes, delete it!'
//         }).then( async(result) => {
//             if (result.isConfirmed) {
//                 isProcess.value = true;
//                 await use_sectionStore.delete(id);
//                 const response = use_sectionStore.getResponse;
//                 if(response.status){
//                     Swal.fire("Success", response.msg,"success");
//                     await use_sectionStore.read();
//                     sectionData.value = use_sectionStore.getSections;
//                 }
//                 isProcess.value = false;
//             }
//         })
//     }
//     catch(error){
//         console.error(error);
//     }
// }




</script>