<template>
    <div class="container t-select-none" >
        <h3 class="text-muted t-font-black" >Rank</h3>
        <div class="t-mt-3" >
            <div class="t-flex t-justify-end " >
                <form  @submit.prevent="createRank" class="d-flex gap-2" >
                    <div class="form-group">
                        <input v-model="ranks.rank" type="text" class="form-control" placeholder="Rank" >
                    </div>
                    <div class="form-group">
                        <input v-model="ranks.hour" type="text" class="form-control" placeholder="Hour" >
                    </div>
                    <div class="form-group">
                        <button class="btn btn-outline-primary" > <fa  icon="plus"></fa> </button>
                    </div>
                </form>
            </div>
            <div class="table-holder mt-3" >
                <div class="t-bg-slate-100 t-p-5 t-rounded-[10px]" >
                    <div class="t-flex t-justify-end t-mb-2" >
                        <div>
                            <input :disabled="isProcess" v-model="isSearch" class="form-control" placeholder="Search" type="text" >
                        </div>
                    </div>
                    <div class="t-h-[60px] t-bg-logoBlue t-p-2 t-rounded-tl-[10px] t-rounded-tr-[10px] t-grid t-grid-cols-3">
                        <div class="t-h-full" >
                            <div class="t-flex t-items-center t-h-full" >
                                <label class="t-uppercase t-text-white t-font-bold" >Rank</label>
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
                    <div  class="t-grid t-grid-cols-3 t-h-[50px] t-bg-slate-50 t-rounded-[5px] t-mt-2 t-mb-1 t-p-2 t-border"
                    v-for="rank in computed_rank" :rank="rank.id" >
                        <div class="t-flex t-items-center t-h-full" >
                            <label class="t-capitalize t-font-normal text-muted" >{{ rank.rank }}</label>
                        </div>
                        <div class="t-flex t-items-center t-h-full" >
                            <label class="t-capitalize t-font-normal text-muted" >{{ rank.hour }}</label>
                        </div>
                        <div class="t-flex t-gap-3 t-items-center t-h-full" >
                            <button  @click="setUpdateData(rank.id,rank.rank,rank.hour)" :disabled="isProcess"  data-bs-toggle="modal" data-bs-target="#staticBackdrop" class="" >
                                <fa icon="edit" class="t-text-[18px] t-font-semibold t-text-slate-400 hover:t-text-slate-200" ></fa>
                            </button>
                            <button  @click="deleteRank(rank.id)" :disabled="isProcess"  class="" >
                                <fa icon="trash" class="t-text-[18px] t-font-semibold t-text-red-400 hover:t-text-red-200" ></fa>
                            </button>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    <!-- modal update -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title t-font-bold text-muted" id="staticBackdropLabel">UPDATE</h5>
                    <button  @click="reset" :disabled="isProcess" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="updateRank">
                        <div class="form-group mt-3">
                            <label for="">Rank</label>
                            <input :disabled="isProcess" v-model="vUpdate.rank" type="text" class="form-control t-capitalize" placeholder="" >
                            <small class="text-danger" v-if="vUpdate.rank_error != ''" >{{ vUpdate.rank_error }}</small>
                        </div>
                        <div class="form-group mt-3">
                            <label for="">Hour</label>
                            <input :disabled="isProcess" v-model.number="vUpdate.hour" type="text" class="form-control" placeholder="" >
                            <small class="text-danger" v-if="vUpdate.hour_error != ''" >{{ vUpdate.hour_error }}</small>
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
import { rankStore } from '../../services/ranks';
import { ref, computed , inject } from 'vue';
import Swal from 'sweetalert2';

const use_rankStore = rankStore();
const globalRankData = inject('globalRankData')
const rankData = ref(globalRankData);


const isProcess = ref(false);
const isSearch = ref('');
const option = ref("");

const computed_rank = computed(()=>{
    if(isSearch.value != ''){
        return rankData.value.filter((rank)=>rank.rank.toLowerCase().includes(isSearch.value.toLowerCase()));
    }
    else{
        return rankData.value;
    }
})

const ranks = ref({
    id: '',
    rank: '',
    rank_error: '',
    hour:'',
    hour_error: '',
})

const vUpdate = ref({
    rank: '',
    rank_error: '',
    hour:'',
    hour_error: '',
})

const reset = ()=>{
    option.value = "";
    ranks.value.id = '';
    ranks.value.rank = '';
    ranks.value.rank_error = '';
    ranks.value.hour = '';
    ranks.value.hour_error = "";
    vUpdate.value.rank = '';
    vUpdate.value.rank_error = "";
    vUpdate.value.hour = '';
    vUpdate.value.hour_error = "";
}


const setUpdateData = (id,rank,hour)=>{
    ranks.value.id = id;
    vUpdate.value.rank = rank;
    vUpdate.value.hour = hour;
}

const createRank = async ()=>{
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
                await use_rankStore.create({
                    rank: ranks.value.rank,
                    hour: ranks.value.hour,
                });


                const response = use_rankStore.getResponse;

                if(response.status){
                    await use_rankStore.read();
                    ranks.value.rank = '';
                    ranks.value.hour = ''
                    rankData.value =  use_rankStore.getRanks;
                    Swal.fire("Success", response.msg,"success");
                }
                else{
                    if(response.error == "rank"){
                        Swal.fire('Error', response.msg, 'error');
                    }
                    if(response.error == "hour"){
                        Swal.fire('Error', response.msg, 'error');
                    }
                }

            }
        })
    }
    catch(error){
        console.error(error);
    }
}

const updateRank = async ()=>{
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
                
                await use_rankStore.update({
                    id:  ranks.value.id,
                    rank: vUpdate.value.rank,
                    hour: vUpdate.value.hour,
                });
                const response = use_rankStore.getResponse;
                if(response.status){
                    await use_rankStore.read(); 
                    rankData.value = use_rankStore.getRanks;
                    Swal.fire("Success", response.msg,"success");
                }
                else{
                    if(response.error == "rank"){
                        vUpdate.value.rank_error = response.msg;
                    }

                    if(response.error == "hour"){
                        vUpdate.value.hour_error = response.msg;
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

const deleteRank = async (id)=>{
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

                await use_rankStore.delete(id);
                const response = use_rankStore.getResponse;
                if(response.status){
                    await use_rankStore.read();
                    rankData.value = use_rankStore.getRanks;
                    Swal.fire("Success", response.msg,"success");
                }
                isProcess.value = false;
            }
        })
    }
    catch(error){
        console.error(error)
    }
}

</script>