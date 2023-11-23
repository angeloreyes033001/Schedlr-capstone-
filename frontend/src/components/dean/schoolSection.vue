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
        <div class="table-holder mt-3" >
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
                <div  class="t-grid t-grid-cols-4 t-h-[50px] t-bg-slate-50 t-rounded-[5px] t-mt-2 t-mb-1 t-p-2 t-border"
                v-for="section in computedSection" :key="section.id" >
                    <div class="t-flex t-items-center t-h-full" >
                        <label class="t-capitalize t-font-normal text-muted" >{{ section.section }}</label>
                    </div>
                    <div class="t-flex t-items-center t-h-full" >
                        <label class="t-capitalize t-font-normal text-muted" >{{ section.year }}</label>
                    </div>
                    <div class="t-flex t-items-center t-h-full" >
                        <label class="t-capitalize t-font-normal text-muted" >{{ section.specialization }}</label>
                    </div>
                    <div class="t-flex t-gap-3 t-items-center t-h-full" >
                        <button @click="setData({id: section.id , section: section.section , yearlevel: section.yearID,specialization: section.specializationID })" data-bs-toggle="modal" data-bs-target="#editModal" >
                            <fa icon="edit" class="t-text-[18px] t-font-semibold t-text-slate-400 hover:t-text-slate-200" ></fa>
                        </button>
                        <button  @click="deleteSection(section.id)" class="" >
                            <fa icon="trash" class="t-text-[18px] t-font-semibold t-text-red-400 hover:t-text-red-200" ></fa>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- modal add -->
    <div class="modal fade" id="addModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title t-font-bold text-muted" id="addModalLabel">ADD</h5>
                    <button :disabled="isProcess" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="createSection" >
                        <div class="mt-3">
                            <div class="form-group">
                                <label for="">Section</label>
                                <input :disabled="isProcess" type="text" class="form-control text-uppercase" v-model="sections.section" >
                            </div>
                            <small class="text-danger" v-if="sections.section_error != ''" >{{ sections.section_error }}</small>
                        </div>
                        <div class="mt-3">
                            <div class="form-group">
                                <label for="">Yearlevels</label>
                                <select :disabled="isProcess" v-model="sections.yearlevel" class="form-select text-capitalize" >
                                    <option value="">SELECT YEARLEVEL</option>
                                    <option class="text-capitalize" v-for="year in globalYearlevel" :value="year.id" >{{ year.year }}</option>
                                </select>
                            </div>
                            <small class="text-danger" v-if="sections.yearlevel_error != ''" >{{ sections.yearlevel_error }}</small>
                        </div>
                        <div class="mt-3" >
                            <div class="form-group" >
                                <label for="">Specialization</label>
                                <select :disabled="isProcess" v-model="sections.specialization" class="form-select text-capitalize" >
                                    <option value="" class="text-uppercase" disabled selected >Select Specialization</option>
                                    <option class="text-capitalize" v-for="specialization in specializationData" :value="specialization.id"  >{{ specialization.specialization }}</option>
                                </select>
                            </div>
                            <small class="text-danger" v-if="sections.specialization_error != ''" >{{ sections.specialization_error }}</small>
                        </div>
                        <div class="mt-3 text-end">
                            <button :disabled="isProcess" class="btn btn-primary" >
                                <fa icon="save" ></fa>
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
     <!-- modal update -->
     <div class="modal fade" id="editModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title t-font-bold text-muted" id="editModalLabel">UPDATE</h5>
                    <button :disabled="isProcess" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="updateSection" >
                        <div class="mt-3">
                            <div class="form-group">
                                <label for="">Section</label>
                                <input :disabled="isProcess" type="text" class="form-control text-uppercase" v-model="vUpdate.section" >
                            </div>
                            <small class="text-danger" v-if="vUpdate.section_error != ''" >{{ vUpdate.section_error }}</small>
                        </div>
                        <div class="mt-3">
                            <div class="form-group">
                                <label for="">Yearlevels</label>
                                <select :disabled="isProcess" v-model="vUpdate.yearlevel" class="form-select text-capitalize" >
                                    <option class="text-capitalize" v-for="year in globalYearlevel" :value="year.id" >{{ year.year }}</option>
                                </select>
                            </div>
                            <small class="text-danger" v-if="vUpdate.yearlevel_error != ''" >{{ vUpdate.yearlevel_error }}</small>
                        </div>
                        <div class="mt-3" >
                            <div class="form-group" >
                                <label for="">Specialization</label>
                                <select :disabled="isProcess" v-model="vUpdate.specialization" class="form-select text-capitalize" >
                                    <option class="text-capitalize" v-for="specialization in specializationData" :value="specialization.id"  >{{ specialization.specialization }}</option>
                                </select>
                            </div>
                            <small class="text-danger" v-if="vUpdate.specialization_error != ''" >{{ vUpdate.specialization_error }}</small>
                        </div>
                        <div class="mt-3 text-end">
                            <button :disabled="isProcess" class="btn btn-primary" >
                                <fa icon="save" ></fa>
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="p-4" >
       
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

const sections = ref({
    section: '',
    section_error: '',

    specialization: '',
    specialization_error: '',

    yearlevel: '',
    yearlevel_error: '',
})

const createSection = async ()=>{
    try{
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes,Save it!'
        }).then( async (result) => {
            if (result.isConfirmed) {
                isProcess.value = true;
                await use_sectionStore.create({section: sections.value.section, yearlevel: sections.value.yearlevel , specialization:sections.value.specialization});
                const response = use_sectionStore.getResponse;
                if(response.status){
                    sections.value.section = "";
                    sections.value.yearlevel = "";
                    sections.value.specialization = "";
                    sections.value.section_error = "";
                    sections.value.yearlevel_error = "";
                    sections.value.specialization_error = "";
                    Swal.fire("Success", response.msg,"success");
                    await use_sectionStore.read();
                    sectionData.value = use_sectionStore.getSections;
                }
                else{
                    if(response.error == "section"){
                        sections.value.section_error = response.msg;
                    }
                    if(response.error == "yearlevel"){
                        sections.value.yearlevel_error = response.msg;
                    }
                    if(response.error == "specialization"){
                        sections.value.specialization_error = response.msg;
                    }
                }
                isProcess.value = false;
            }
        })
    }
    catch(error){
        console.error(error);
    }
}

const setData = (data)=>{
    vUpdate.value.id = data.id;
    vUpdate.value.section = data.section;
    vUpdate.value.yearlevel = data.yearlevel;
    vUpdate.value.specialization = data.specialization;
}
const vUpdate = ref({
    id: '',
    section: '',
    section_error: '',
    yearlevel: '',
    yearlevel_error: '',
    specialization: '',
    specialization_error: '',
});

const updateSection = async ()=>{
    try{
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes,Update it!'
        }).then( async (result) => {
            if (result.isConfirmed) {
                isProcess.value = true;
                await use_sectionStore.update({
                    id: vUpdate.value.id,
                    section: vUpdate.value.section,
                    yearlevel: vUpdate.value.yearlevel,
                    specialization: vUpdate.value.specialization,
                });

                const response = use_sectionStore.getResponse;
                if(response.status){
                    Swal.fire("Success",response.msg,"success");
                    await use_sectionStore.read();
                    sectionData.value = use_sectionStore.getSections;
                }
                else{
                    if(response.error == "section"){
                        vUpdate.value.section_error = response.msg;
                    }

                    if(response.error == "yearlevel"){
                        vUpdate.value.yearlevel_error = response.msg;
                    }

                    if(response.error == "specialization"){
                        vUpdate.value.specialization_error = response.msg;
                    }
                }
                isProcess.value = false;
            }
        })
    }
    catch(error){
        console.error(error);
    }
}

const deleteSection = async (id)=>{
    try{
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then( async(result) => {
            if (result.isConfirmed) {
                isProcess.value = true;
                await use_sectionStore.delete(id);
                const response = use_sectionStore.getResponse;
                if(response.status){
                    Swal.fire("Success", response.msg,"success");
                    await use_sectionStore.read();
                    sectionData.value = use_sectionStore.getSections;
                }
                isProcess.value = false;
            }
        })
    }
    catch(error){
        console.error(error);
    }
}

const computedSection = computed(()=>{
    if(isSearch.value != ""){
        return sectionData.value.filter(section=>{
            return section.section.toLowerCase().includes(isSearch.value.toLowerCase());
        })
    }
    else{
        return sectionData.value;
    }
})


</script>