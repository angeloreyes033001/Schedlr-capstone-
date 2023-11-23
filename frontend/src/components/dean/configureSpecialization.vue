<template>
    <div class="container t-select-none" >
        <h3 class="text-muted t-font-black" >Specialization</h3>
        <div class="t-mt-3" >
            <div class="t-flex t-justify-end" >
                <form @submit.prevent="createSpecialization" class="t-flex t-gap-2" >
                    <div>
                        <input v-model="specialization" :disabled="isProcess" type="text" placeholder="Add Specialization" class="form-control"  >
                    </div>
                    <button type="submit" :disabled="isProcess" class="btn btn-outline-primary" >
                        <fa icon="plus" ></fa>
                    </button>
                </form>
            </div>
            <div class="table-holder mt-3" >
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
                    <template v-for="specialization in computedSpecialization" :key="specialization.specialization" >
                        <div  class="t-grid t-grid-cols-2 t-h-[50px] t-bg-slate-50 t-rounded-[5px] t-mt-2 t-mb-1 t-p-2 t-border"
                        v-if="specialization.specialization != 'common'" >
                            <div class="t-flex t-items-center t-h-full" >
                                <label class="t-uppercase t-font-normal text-muted" >{{ specialization.specialization }}</label>
                            </div>
                            <div class="t-flex t-gap-3 t-items-center t-h-full" >
                                <button @click="deleteSpecialization(specialization.id)" :disabled="isProcess" class="" >
                                    <fa icon="trash" class="t-text-[18px] t-font-semibold t-text-red-400 hover:t-text-red-200" ></fa>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
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
const specialization = ref('')

const globalSpecialization = inject("globalSpecialization");
const specializationData = ref(globalSpecialization);

const computedSpecialization = computed(()=>{
    if(isSearch.value != ''){
        return specializationData.value.filter( item => item.specialization.toLowerCase().includes(isSearch.value.toLowerCase()));
    }
    else{
        return specializationData.value;
    }
})

const createSpecialization = ()=>{
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
            if(specialization.value != ''){

                isProcess.value = true;

                await use_specializationStore.create(specialization.value)
                const response = use_specializationStore.getResponse;

                if(response.status){
                    await use_specializationStore.read();
                    specializationData.value = use_specializationStore.getSpecialization;
                    specialization.value = '';
                    Swal.fire('Success',response.msg,"success");
                }
                else{
                    specialization.value = '';
                    Swal.fire("Error",response.msg,'error');
                }

                isProcess.value = false;

            }
            else{
                Swal.fire('Error!','Please fill the field!','error')
            }
        }
    })
}

const deleteSpecialization = (id)=>{
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes,Delete it!'
    }).then(async(result) => {
        if (result.isConfirmed) {
            await use_specializationStore.delete(id);
            const response = use_specializationStore.getResponse;
            if(response.status){
                await use_specializationStore.read();
                specializationData.value = use_specializationStore.getSpecialization;
                Swal.fire('Success',response.msg,'success');
            }
            else{
                Swal.fire('Error',response.msg,'error');
            }
        }
    })
}


const onMountedCreateCommon = async()=>{
    try {
        isProcess.value = true;
        await use_specializationStore.autocreatecommon();
        isProcess.value = false;
    } catch (error) {
        console.error(error);
    }
}

onMounted(()=>{
    onMountedCreateCommon();
})


</script>