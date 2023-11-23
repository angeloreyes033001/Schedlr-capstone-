<template>
    <div class="container t-select-none" >
        <h3 class="text-muted t-font-black" >Year</h3>
        <div class="t-mt-3" >
            <div class="t-flex t-justify-end" >
                <form @submit.prevent="create_yearlevel" class="d-flex t-gap-2 ms-2" >
                    <div class="form-group">
                        <input  v-if="isEditMode == ''"  v-model="yearlevels.year" :disabled="isProcess" type="type" class="t-capitalize form-control" placeholder="Ex. 1st Year" >
                        <input v-else disabled type="type" class="form-control" placeholder="" >
                    </div>
                    <div class="form-group">
                        <button v-if="isEditMode == ''" type="submit" :disabled="isProcess" class="btn btn-outline-primary" > <fa  icon="save"></fa> </button>
                        <button v-else type="button" disabled class="btn btn-outline-primary" > <fa  icon="save"></fa> </button>
                    </div>
                </form>
            </div>
            <div v-if="yearLevelData.length > 0" class="table-holder mt-3" >
                <div class="t-bg-slate-100 t-p-5 t-rounded-[10px]" >
                    <div class="t-flex t-justify-end t-mb-2" >
                        <div>
                            <input :disabled="isProcess" v-model="isSearch" class="form-control" placeholder="Search" type="text" >
                        </div>
                    </div>
                    <div class="t-h-[60px] t-bg-logoBlue t-p-2 t-rounded-tl-[10px] t-rounded-tr-[10px] t-grid t-grid-cols-2">
                        <div class="t-h-full" >
                            <div class="t-flex t-items-center t-h-full" >
                                <label class="t-uppercase t-text-white t-font-bold" >Year</label>
                            </div>
                        </div>
                        <div class="t-h-full" >
                            <div class="t-flex t-items-center t-h-full" >
                                <label class="t-uppercase t-text-white t-font-bold" >Action</label>
                            </div>
                        </div>
                    </div>
                    <div  class="t-grid t-grid-cols-2 t-h-[60px] t-bg-slate-50 t-rounded-[5px] t-mt-2 t-mb-1 t-p-2 t-border"
                    v-for="year in computed_yearlevel" :key="year.id" >
                        <div class="t-flex t-items-center t-h-full" >
                            <label v-if="isEditMode != year.id" class="t-capitalize t-font-normal text-muted t-truncate" :title="year.year" >{{ year.year }}</label>
                            <div v-else  class="t-w-full t-pl-[3px] t-pr-[3px]" >
                                <input v-model="yearlevels.year" :disabled="isProcess" type="text" class="form-control t-capitalize" >
                            </div>
                        </div>
                        <div class="t-flex t-gap-3 t-items-center t-h-full" >
                            <button @click="getUpdateData({...year})" >
                                <fa icon="edit" class="t-text-[18px] t-font-semibold t-text-slate-400 hover:t-text-slate-200" ></fa>
                            </button>
                            <button @click="delete_yearlevel(year.id)" v-if="isEditMode != year.id"  v-show="isEditMode == ''" :disabled="isProcess" class="" >
                                <fa icon="trash" class="t-text-[18px] t-font-semibold t-text-red-400 hover:t-text-red-200" ></fa>
                            </button>
                            <button v-else @click="update_yearlevel"  v-show="isEditMode != ''" :disabled="isProcess" class="" >
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
import Swal from 'sweetalert2';
import { ref , computed , inject } from 'vue';
import { yearlevelStore } from '../../services/yearLevels';

const use_yearlevelStore = yearlevelStore();
const globalYearLevelData = inject("globalYearLevelData");

const isProcess = ref(false);
const isSearch = ref('');
const isEditMode =ref('');

const yearlevels = ref({
    id: '',
    year: '',
})

const reset = () =>{
    yearlevels.value ={
        id: '',
        year: '',
    }
}

const getUpdateData = (data) => {
    if (isEditMode.value !== '') {
        if (isEditMode.value !== data.id) {
            yearlevels.value = { ...data };
            isEditMode.value = data.id;
        } else {
            isEditMode.value = '';
            reset();
        }
    } else {
        yearlevels.value = { ...data };
        isEditMode.value = data.id;
    }
}

const yearLevelData = ref(globalYearLevelData);

const computed_yearlevel = computed(()=>{
    if(isSearch.value != ''){
        return yearLevelData.value.filter((year)=>year.year.toLowerCase().includes(isSearch.value.toLowerCase()));
    }
    else{
        return yearLevelData.value;
    }
})

const create_yearlevel = ()=>{
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
            isProcess.value = true;
            await use_yearlevelStore.create(yearlevels.value.year);
            const response = use_yearlevelStore.getResponse;
            if(response.status){
                await use_yearlevelStore.read();
                yearLevelData.value = use_yearlevelStore.getYearLevel;
                Swal.fire("Success",response.msg,'success');
                reset();
            }
            else{
                Swal.fire("Error",response.msg,'error');
            }
            isProcess.value = false;
        }
    });
}

const update_yearlevel = ()=>{
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
                await use_yearlevelStore.update({...yearlevels.value});
                const response = use_yearlevelStore.getResponse;

                if(response.status){
                    await use_yearlevelStore.read();
                    yearLevelData.value = use_yearlevelStore.getYearLevel;
                    Swal.fire("Success",response.msg,'success');
                    isEditMode.value = '';
                    reset();
                }
                else{
                    Swal.fire("Error",response.msg,'error');
                }
                isProcess.value = false;
            }
        });
    } catch (error) {
        console.log(error)
    }
}

const delete_yearlevel = (id)=>{
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
            isProcess.value = true;
            await use_yearlevelStore.delete(id);
            const response = use_yearlevelStore.getResponse;
            if(response.status){
                await use_yearlevelStore.read();
                yearLevelData.value = use_yearlevelStore.getYearLevel;
                Swal.fire('Success',response.msg,"success");
            }
            isProcess.value = false;
        }
    });
}
 
 </script>