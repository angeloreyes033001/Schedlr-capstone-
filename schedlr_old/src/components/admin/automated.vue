<template>
    <div class="t-h-screen t-relative" >
        <div class="t-absolute t-top-[30px] t-left-[20px]" >
            <h4 class="t-font-bold t-uppercase" >Auto Generate Schedule</h4>
            <p class="t-text-red-300" >
                <strong class="t-font-bold" >* Please note!</strong>, 
                <br>This auto generate schedule only work on your department schedule configure.<br>
            </p>
        </div>
        <div class="t-h-full t-grid t-justify-center t-items-center" >
            <div>
                <div>
                    <div class="t-grid t-justify-center" v-if="isProcess" >
                        <span class="text-center mb-3" >
                            <fa class="t-text-[60px] t-text-logoBlue" icon="robot" ></fa>
                        </span>
                        <h6 class=" mt-2 t-text-center" >
                            <span  class=" t-font-light" >
                                Generating Schedule, Please wait!!! 
                            </span>
                            <br>
                            <span class="text-danger" >
                                Do not reload the website during the process.
                            </span> 
                        </h6>
                    </div>
                    <div v-else class="t-grid t-justify-center" >
                        <div class="t-grid t-justify-center" >
                            <div class="t-grid t-justify-center" >
                                <img src="../../assets/images/generate.png" alt="">
                            </div>
                            <small class="t-text-extralight" >One-Click Scheduling.</small>
                        </div>
                        <div class="t-grid t-justify-center" >
                            <button @click="generateSchedule" class="btn btn-primary mt-3 " >
                                <fa icon="play" ></fa>
                                Generate
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import Swal from 'sweetalert2';
import {ref } from 'vue';
import {scheduleStore} from '../../services/schedule';
const use_scheduleStore = scheduleStore();

const isProcess = ref(false);


const generateSchedule = async ()=>{
    try{

        isProcess.value = true;

        await use_scheduleStore.automation();
        const response = use_scheduleStore.getResponse;

        if(response.status){
            Swal.fire("Success",response.msg,"success")
        }
        else{
            Swal.fire("Error",response.msg,"error");
        }

        isProcess.value = false;
    }
    catch(error){
        console.log(error);
    }
}


</script>