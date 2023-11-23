<template>
    <div class="container t-select-none" >
        <h3 class="text-muted t-font-black" >Specialization</h3>
        <div class="t-mt-3" >
            <div class="t-flex t-justify-end" >
                <form @submit.prevent="create_specialization" class="t-flex t-gap-2" >
                    <div class="form-group">
                        <input  v-if="isEditMode == ''" v-model="specializations.specialization" :disabled="isProcess" type="text" placeholder="Add Specialization" class="t-uppercase form-control"  >
                        <input v-else disabled type="text" class="form-control" placeholder="" >
                    </div>
                    <div class="form-group">
                        <button v-if="isEditMode == ''" type="submit" :disabled="isProcess" class="btn btn-outline-primary" > <fa  icon="save"></fa> </button>
                        <button v-else type="button" disabled class="btn btn-outline-primary" > <fa  icon="save"></fa> </button>
                    </div>
                </form>
            </div>
            <div v-if="specializationData.length > 1" class="table-holder mt-3" >
                <div class="t-bg-slate-100 t-p-5 t-rounded-[10px]" >
                    <div class="t-flex t-justify-end t-mb-2" >
                        <div>
                            <input :disabled="isProcess" v-model="isSearch" class="form-control" placeholder="Search" type="text" >
                        </div>
                    </div>
                    <div class="t-h-[60px] t-bg-logoBlue t-p-2 t-rounded-tl-[10px] t-rounded-tr-[10px] t-grid t-grid-cols-2">
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
                    <!-- special senaryo why theres template tag here to hold the loop, para di i display ang common dito-->
                    <template v-for="specialization in computed_specialization" :key="specialization.specialization" > 
                        <div  class="t-grid t-grid-cols-2 t-h-[60px] t-bg-slate-50 t-rounded-[5px] t-mt-2 t-mb-1 t-p-2 t-border"
                        v-if="specialization.specialization != 'common'" >
                            <div class="t-flex t-items-center t-h-full" >
                                <label v-if="isEditMode != specialization.id" class="t-uppercase t-font-normal text-muted t-truncate" :title="specialization.specialization" >{{ specialization.specialization }}</label>
                                <div v-else  class="t-w-full t-pl-[3px] t-pr-[3px]" >
                                    <input v-model="specializations.specialization" :disabled="isProcess" type="type" class="form-control t-uppercase" >
                                </div>
                            </div>
                            <div class="t-flex t-gap-3 t-items-center t-h-full" >
                                <button @click="getUpdateData({id: specialization.id , specialization: specialization.specialization})" >
                                    <fa icon="edit" class="t-text-[18px] t-font-semibold t-text-slate-400 hover:t-text-slate-200" ></fa>
                                </button>
                                <button @click="delete_specialization(specialization.id)" v-if="isEditMode != specialization.id"  v-show="isEditMode == ''" :disabled="isProcess" class="" >
                                    <fa icon="trash" class="t-text-[18px] t-font-semibold t-text-red-400 hover:t-text-red-200" ></fa>
                                </button>
                                <button v-else @click="update_specialization"  v-show="isEditMode != ''" :disabled="isProcess" class="" >
                                    <fa icon="save" class="t-text-[18px] t-font-semibold t-text-blue-400 hover:t-text-red-200" ></fa>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
            <div v-else class=" t-justify-center t-items-center t-w-full p-5 t-grid" >
                <img class="t-w-[700px] t-border-2 t-border-white t-border-b-slate-300" src="../../assets/images/no-data.svg" alt="no-data">
                <h6 class="text-center t-capitalize t-mt-2" > no record found</h6>
            </div>
        </div>
    </div>
</template>
<script setup>

import Swal from 'sweetalert2';
import { ref , onMounted , computed,inject } from 'vue';
import { specializationStore } from '../../services/specialization';

const use_specializationStore = specializationStore();

const isSearch = ref('');
const isProcess = ref(false);
const isEditMode =ref('');


const specializations = ref({
    id: '',
    specialization: ''
})

const reset = () =>{
    specializations.value = {
        id: '',
        specialization: ''
    }
}

const getUpdateData = (data) => {
    if (isEditMode.value !== '') {
        if (isEditMode.value !== data.id) {
            specializations.value = { ...data };
            isEditMode.value = data.id;
        } else {
            isEditMode.value = '';
            reset();
        }
    } else {
        specializations.value = { ...data };
        isEditMode.value = data.id;
    }
}


const globalSpecialization = inject("globalSpecialization");
const specializationData = ref(globalSpecialization);

const computed_specialization = computed(()=>{
    if(isSearch.value != ''){
        return specializationData.value.filter( item => item.specialization.toLowerCase().includes(isSearch.value.toLowerCase()));
    }
    else{
        return specializationData.value;
    }
})

const create_specialization = ()=>{
    try {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes,Save it!"
        }).then(async(result) => {
            if(result.isConfirmed){ 
                isProcess.value = true;
                await use_specializationStore.create(specializations.value.specialization);
                const response = use_specializationStore.getResponse;
                if(response.status){
                    Swal.fire('Success',response.msg,'success');
                    await use_specializationStore.read();
                    specializationData.value = use_specializationStore.getSpecialization;
                    reset();
                }
                else{
                    Swal.fire("Error",response.msg,"error");
                }
                isProcess.value = false;
            }

        });
    } catch (error) {
        console.log(error);
    }
}

const update_specialization = ()=>{
    try{
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes,Update it!'
        }).then(async(result) => {
            if (result.isConfirmed) {

                isProcess.value = true;

                await use_specializationStore.update({id: specializations.value.id ,specialization: specializations.value.specialization});
                const response = use_specializationStore.getResponse;
                if(response.status){
                    await use_specializationStore.read();
                    specializationData.value = use_specializationStore.getSpecialization;
                    Swal.fire("Success",response.msg,'success');
                    isEditMode.value = '';
                    reset();

                }
                else{
                    Swal.fire("Error",response.msg,'error');
                }
                isProcess.value = false;
            }
        })
    }
    catch(error){
        console.log(error);
    }
}

const delete_specialization = (id)=>{
    try{
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes,Update it!'
        }).then(async(result) => {
            if (result.isConfirmed) {
                isProcess.value = true;
                await use_specializationStore.delete(id);
                const response = use_specializationStore.getResponse;
                if(response.status){
                    await use_specializationStore.read();
                    specializationData.value = use_specializationStore.getSpecialization;
                    Swal.fire('Success',response.msg,'success');
                }
                isProcess.value = false;
            }
        })
    }
    catch(error){
        console.log(error);
    }
}

// const createSpecialization = ()=>{
//     Swal.fire({
//         title: 'Are you sure?',
//         text: "You won't be able to revert this!",
//         icon: 'warning',
//         showCancelButton: true,
//         confirmButtonColor: '#3085d6',
//         cancelButtonColor: '#d33',
//         confirmButtonText: 'Yes,Save it!'
//         }).then( async (result) => {
//         if (result.isConfirmed) {
//             if(specialization.value != ''){

//                 isProcess.value = true;

//                 await use_specializationStore.create(specialization.value)
//                 const response = use_specializationStore.getResponse;

//                 if(response.status){
//                     await use_specializationStore.read();
//                     specializationData.value = use_specializationStore.getSpecialization;
//                     specialization.value = '';
//                     Swal.fire('Success',response.msg,"success");
//                 }
//                 else{
//                     specialization.value = '';
//                     Swal.fire("Error",response.msg,'error');
//                 }

//                 isProcess.value = false;

//             }
//             else{
//                 Swal.fire('Error!','Please fill the field!','error')
//             }
//         }
//     })
// }

// const deleteSpecialization = (id)=>{
//     Swal.fire({
//         title: 'Are you sure?',
//         text: "You won't be able to revert this!",
//         icon: 'warning',
//         showCancelButton: true,
//         confirmButtonColor: '#3085d6',
//         cancelButtonColor: '#d33',
//         confirmButtonText: 'Yes,Delete it!'
//     }).then(async(result) => {
//         if (result.isConfirmed) {
//             await use_specializationStore.delete(id);
//             const response = use_specializationStore.getResponse;
//             if(response.status){
//                 await use_specializationStore.read();
//                 specializationData.value = use_specializationStore.getSpecialization;
//                 Swal.fire('Success',response.msg,'success');
//             }
//             else{
//                 Swal.fire('Error',response.msg,'error');
//             }
//         }
//     })
// }


// const onMountedCreateCommon = async()=>{
//     try {
//         isProcess.value = true;
//         await use_specializationStore.autocreatecommon();
//         isProcess.value = false;
//     } catch (error) {
//         console.error(error);
//     }
// }

// onMounted(()=>{
//     onMountedCreateCommon();
// })


</script>