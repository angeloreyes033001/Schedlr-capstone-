<template>
    <div class="container t-select-none" >
        <h3 class="text-muted t-font-black" >Designation</h3>
        <div class="t-mt-3" >
            <div class="t-flex t-justify-end" >
                <form @submit.prevent="create_designation" class="d-flex gap-2" >
                    <div class="form-group">
                        <input v-if="isEditMode == ''" v-model="designations.position" :disabled="isProcess" type="text" class="form-control t-capitalize" placeholder="Position" >
                        <input v-else disabled type="text" class="form-control" placeholder="" >
                    </div>
                    <div class="form-group">
                        <input  v-if="isEditMode == ''" v-model="designations.hour" :disabled="isProcess" type="number" min="0" max="30" class="form-control" placeholder="Hour" >
                        <input v-else disabled type="number" min="0" max="30" class="form-control" placeholder="" >
                    </div>
                    <div class="form-group">
                        <button v-if="isEditMode == ''" type="submit" :disabled="isProcess" class="btn btn-outline-primary" > <fa  icon="save"></fa> </button>
                        <button v-else type="button" disabled class="btn btn-outline-primary" > <fa  icon="save"></fa> </button>
                    </div>
                </form>
            </div>
            <div v-if="designationData.length > 0" class="table-holder mt-3" >
                <div class="t-bg-slate-100 t-p-5 t-rounded-[10px]" >
                    <div class="t-flex t-justify-end t-mb-2" >
                        <div>
                            <input :disabled="isProcess" v-model="isSearch" class="form-control" placeholder="Search" type="text" >
                        </div>
                    </div>
                    <div class="t-h-[60px] t-bg-logoBlue t-p-2 t-rounded-tl-[10px] t-rounded-tr-[10px] t-grid t-grid-cols-3">
                        <div class="t-h-full" >
                            <div class="t-flex t-items-center t-h-full" >
                                <label class="t-uppercase t-text-white t-font-bold" >POSITION</label>
                            </div>
                        </div>
                        <div class="t-h-full" >
                            <div class="t-flex t-items-center t-h-full" >
                                <label class="t-uppercase t-text-white t-font-bold" >Hour's</label>
                            </div>
                        </div>
                        <div class="t-h-full" >
                            <div class="t-flex t-items-center t-h-full" >
                                <label class="t-uppercase t-text-white t-font-bold" >Action</label>
                            </div>
                        </div>
                    </div>
                    <div  class="t-grid t-grid-cols-3 t-h-[60px] t-bg-slate-50 t-rounded-[5px] t-mt-2 t-mb-1 t-p-2 t-border"
                    v-for="des in computed_designation" :key="des.position" >
                        <div class="t-flex t-items-center t-h-full" >
                            <label class="t-capitalize t-font-normal text-muted t-truncate" :title="des.position" >{{ des.position }}</label>
                        </div>
                        <div class="t-flex t-items-center t-h-full" >
                            <label v-if="isEditMode != des.position" class="t-capitalize t-font-normal text-muted t-truncate" :title="des.hour" >{{ des.hour }}</label>
                            <div v-else  class="t-w-full t-pl-[3px] t-pr-[3px]" >
                                <input v-model="designations.hour" :disabled="isProcess" type="number" min="0" max="30" class="form-control t-capitalize" >
                            </div>
                        </div>
                        <div class="t-flex t-gap-3 t-items-center t-h-full" >
                            <button @click="getUpdateData({position: des.position , hour: des.hour})" >
                                <fa icon="edit" class="t-text-[18px] t-font-semibold t-text-slate-400 hover:t-text-slate-200" ></fa>
                            </button>
                            <button @click="delete_designation(des.position)" v-if="isEditMode != des.position"  v-show="isEditMode == ''" :disabled="isProcess" class="" >
                                <fa icon="trash" class="t-text-[18px] t-font-semibold t-text-red-400 hover:t-text-red-200" ></fa>
                            </button>
                            <button v-else @click="update_designation"  v-show="isEditMode != ''" :disabled="isProcess" class="" >
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
    </div>
</template>
<script setup >
import { ref , inject, computed } from 'vue';
import { designationStore } from '../../services/designations';
import Swal from 'sweetalert2';

const globalDesignationData = inject("globalDesignationData");
const designationData = ref(globalDesignationData);
const use_designationStore = designationStore();
const isProcess = ref(false);

const isSearch = ref('');
const isEditMode =ref('');

const designations = ref({
    position: '',
    hour: '',
});

const reset = () =>{
    designations.value = {
        position: '',
        hour: '',
    }
}

const getUpdateData = (data) => {
    if (isEditMode.value !== '') {
        if (isEditMode.value !== data.position) {
            designations.value = { ...data };
            isEditMode.value = data.position;
        } else {
            isEditMode.value = '';
            reset();
        }
    } else {
        designations.value = { ...data };
        isEditMode.value = data.position;
    }
}

const computed_designation = computed(()=>{
    if(isSearch.value != ''){
        return designationData.value.filter((des)=>des.position.toLowerCase().includes(isSearch.value.toLowerCase()));
    }
    else{
        return designationData.value;
    }
});

const create_designation = ()=>{
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
                
                await use_designationStore.create({...designations.value});
                const response = use_designationStore.getResponse;
                if(response.status){
                    await use_designationStore.read();
                    designationData.value = use_designationStore.getDesignation;
                    Swal.fire("Success",response.msg,"success");
                    reset();
                }else{
                    Swal.fire("Error",response.msg,"error");
                }

                isProcess.value = false;
            }
        })
    }
    catch(error){
        console.error(error);
    }
}

const update_designation = ()=>{
    try {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes,Update it!"
        }).then( async (result) => {
            if (result.isConfirmed) {
                isProcess.value = true; 
                await use_designationStore.update({position: designations.value.position, hour: designations.value.hour});
                const response = use_designationStore.getResponse;
                if(response.status){
                    reset();
                    isEditMode.value = '';
                    await use_designationStore.read();
                    designationData.value = use_designationStore.getDesignation;
                    Swal.fire("Success", response.msg , "success")
                }
                else{
                    Swal.fire("Error",response.msg,"error");
                }
                isProcess.value = false;
            }
        });
    } catch (error) {
        console.error(error)
    }
}

const delete_designation = (position)=>{
    try {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes,Delete it!"
        }).then( async (result) => {
            if (result.isConfirmed) {
                isProcess.value = true;

                await use_designationStore.delete(position);
                const response = use_designationStore.getResponse;
                if(response.status){
                    await use_designationStore.read();
                    designationData.value = use_designationStore.getDesignation;
                    Swal.fire("Success", response.msg , "success")
                }

                isProcess.value = false;
            }
        });
    } catch (error) {
        console.error(error)
    }
}





</script>