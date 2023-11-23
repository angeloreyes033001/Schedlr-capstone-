<template>
    <div class="container t-select-none" >
        <h3 class="text-muted t-font-black" >Year</h3>
        <div class="t-mt-3" >
            <div class="t-flex t-justify-end" >
                <form @submit.prevent="createYear" class="d-flex t-gap-2 ms-2" >
                    <div class="form-group">
                        <input v-model="yearlevels.yearlevel" type="text" class="form-control t-capitalize" placeholder="ex. 1st Year" >
                    </div>
                    <button :disabled="isProcess" class="btn btn-outline-primary" ><fa icon="plus" ></fa></button>
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
                                <label class="t-uppercase t-text-white t-font-bold" >Year</label>
                            </div>
                        </div>
                        <div class="t-h-full" >
                            <div class="t-flex t-items-center t-h-full" >
                                <label class="t-uppercase t-text-white t-font-bold" >Action</label>
                            </div>
                        </div>
                    </div>
                    <div  class="t-grid t-grid-cols-2 t-h-[50px] t-bg-slate-50 t-rounded-[5px] t-mt-2 t-mb-1 t-p-2 t-border"
                    v-for="year in computed_yearlevel" :key="year.id" >
                        <div class="t-flex t-items-center t-h-full" >
                            <label class="t-capitalize t-font-normal text-muted" >{{ year.yearName }}</label>
                        </div>
                        <div class="t-flex t-gap-3 t-items-center t-h-full" >
                            <button   @click="setUpdateData({id:year.id, yearlevel: year.yearName})" data-bs-toggle="modal" data-bs-target="#staticBackdrop" class="" >
                                <fa icon="edit" class="t-text-[18px] t-font-semibold t-text-slate-400 hover:t-text-slate-200" ></fa>
                            </button>
                            <button  @click="deleteYear(year.id)" :disabled="isProcess"  class="" >
                                <fa icon="trash" class="t-text-[18px] t-font-semibold t-text-red-400 hover:t-text-red-200" ></fa>
                            </button>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title t-font-bold text-muted" id="staticBackdropLabel">UPDATE</h5>
                    <button  @click="reset" :disabled="isProcess" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="updateYear">
                        <div class="form-group mt-3">
                            <label for="">Year</label>
                            <input :disabled="isProcess" v-model="vUpdate.yearlevel" type="text" class=" t-capitalize form-control" placeholder="ex 1st Year" >
                            <small class="text-danger" v-if="vUpdate.yearlevel_error != ''" >{{ vUpdate.yearlevel_error }}</small>
                        </div>
                        <div class="text-end mt-3">
                            <button :disabled="isProcess" class="w-50 btn btn-primary" >
                                <fa icon="save" ></fa> Save
                            </button>
                        </div>
                    </form>
                </div>
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

const yearLevelData = ref(globalYearLevelData);

const computed_yearlevel = computed(()=>{
    if(isSearch.value != ''){
        return yearLevelData.value.filter((year)=>year.yearName.toLowerCase().includes(isSearch.value.toLowerCase()));
    }
    else{
        return yearLevelData.value;
    }
})

const yearlevels = ref({
    id: '',
    yearlevel: '',
    yearlevel_error: '',
})

const vUpdate = ref({
    yearlevel: '',
    yearlevel_error: '',
})

const reset  = ()=>{
    yearlevels.value.id = '';
    yearlevels.value.yearlevel ='';
    yearlevels.value.yearlevel_error = '';
    vUpdate.value.yearlevel = '';
    vUpdate.value.yearlevel_error = '';
}

const createYear = async () =>{
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

                await use_yearlevelStore.create({yearlevel: yearlevels.value.yearlevel});
                const response  = use_yearlevelStore.getResponse;
                if(response.status){
                    await use_yearlevelStore.read();
                    yearLevelData.value = use_yearlevelStore.getYearLevel;;
                    Swal.fire("Success", response.msg,"success");
                    reset();
                }
                else{
                    if(response.error == "yearlevel"){
                        Swal.fire('Error', response.msg, "error");
                    }
                }
                console.log(response)

                isProcess.value = false;
            }
        })
    }
    catch(error){
        console.error(error);
    }
}

const deleteYear = async (id) =>{
    try{

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes,Delete it!'
            }).then( async (result) => {
            if (result.isConfirmed) {
                isProcess.value = true;

                await use_yearlevelStore.delete(id);
                const response  = use_yearlevelStore.getResponse;

                if(response.status){
                    await use_yearlevelStore.read();
                    yearLevelData.value = use_yearlevelStore.getYearLevel;;
                    Swal.fire("Success", response.msg,"success");
                    reset();
                }

                isProcess.value = false;
            }
        })
    }
    catch(error){
        console.error(error);
    }
}

const setUpdateData = (data)=>{
    const {id , yearlevel} = data;
    yearlevels.value.id = id;
    vUpdate.value.yearlevel = yearlevel;
    console.log(data)
}
const updateYear = () =>{
    try{

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Update it!'
            }).then( async (result) => {
            if (result.isConfirmed) {
                isProcess.value = true;

                await use_yearlevelStore.update({id: yearlevels.value.id, yearlevel: vUpdate.value.yearlevel});
                const response  = use_yearlevelStore.getResponse;

                if(response.status){
                    vUpdate.value.yearlevel_error = '';

                    await use_yearlevelStore.read();
                    const response = use_yearlevelStore.getYearLevel;
                    yearLevelData.value = response;
                    Swal.fire("Success", response.msg,"success");
                }
                else{
                    if(response.error = "yearlevel"){
                        vUpdate.value.yearlevel_error = response.msg;
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
 
 </script>