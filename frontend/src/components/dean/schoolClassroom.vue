<template>
    <div class="container t-select-none" >
        <h3 class="text-muted t-font-black" >Classroom</h3>
        <div class="t-mt-3" >
            <div class="t-flex t-justify-end">
                <button :disabled="isProcess || isEditMode != ''" class="btn btn-outline-primary w-25" data-bs-toggle="modal" data-bs-target="#addModal" >
                    <fa icon="plus" ></fa>
                    ADD CLASSROOM
                </button>
            </div>
            <div v-if="classroomData.length > 0" class="table-holder mt-3" >
                <div class="t-bg-slate-100 t-p-5 t-rounded-[10px]" >
                    <div class="t-flex t-justify-end t-mb-2" >
                        <div>
                            <input :disabled="isProcess || isEditMode != ''" v-model="isSearch" class="form-control t-capitalize" placeholder="Search" type="text" >
                        </div>
                    </div>
                    <div class="t-h-[60px] t-bg-logoBlue t-p-2 t-rounded-tl-[10px] t-rounded-tr-[10px] t-grid t-grid-cols-5">
                        <div class="t-h-full" >
                            <div class="t-flex t-items-center t-h-full" >
                                <label class="t-uppercase t-text-white t-font-bold" >Classroom</label>
                            </div>
                        </div>
                        <div class="t-h-full" >
                            <div class="t-flex t-items-center t-h-full" >
                                <label class="t-uppercase t-text-white t-font-bold" >Year</label>
                            </div>
                        </div>
                        <div class="t-h-full" >
                            <div class="t-flex t-items-center t-h-full" >
                                <label class="t-uppercase t-text-white t-font-bold" >Type</label>
                            </div>
                        </div>
                        <div class="t-h-full" >
                            <div class="t-flex t-items-center t-h-full" >
                                <label class="t-uppercase t-text-white t-font-bold" >overwrite</label>
                            </div>
                        </div>
                        <div class="t-h-full" >
                            <div class="t-flex t-items-center t-h-full" >
                                <label class="t-uppercase t-text-white t-font-bold" >Action</label>
                            </div>
                        </div>
                    </div>
                    <div  class="t-grid t-grid-cols-5 t-h-[60px] t-bg-slate-50 t-rounded-[5px] t-mt-2 t-mb-1 t-p-2 t-border"
                    v-for="classroom in computed_classroom" :key="classroom.id" >
                        <div class="t-flex t-items-center t-h-full" >
                            <label v-if="isEditMode != classroom.id" class="t-capitalize t-font-normal text-muted t-truncate" :title="classroom.room" >{{ classroom.room }}</label>
                            <div v-else  class="form-group t-pl-[3px] t-pr-[3px]" >
                                <input v-model="classrooms.room" :disabled="isProcess" class="form-control t-capitalize" type="text" >
                            </div>
                        </div>
                        <div class="t-flex t-items-center t-h-full" >
                            <label v-if="isEditMode != classroom.id" class="t-capitalize t-font-normal text-muted t-truncate" :title="classroom.type" >{{ classroom.type }}</label>
                            <div v-else  class="form-group t-pl-[3px] t-pr-[3px] t-w-full" >
                                <select  v-model="classrooms.type" class="form-select">
                                    <option value="lecture" >Lecture</option>
                                    <option value="laboratory" >Laboratory</option>
                                </select>
                            </div>
                        </div>
                        <div class="t-flex t-items-center t-h-full" >
                            <label v-if="isEditMode != classroom.id" class="t-capitalize t-font-normal text-muted t-truncate" >
                                <span v-if="classroom.multi == 1" >
                                    <fa icon="circle-check" class="t-text-green-500" ></fa>
                                </span>
                                <span v-else >
                                    <fa icon="circle-xmark" class="t-text-red-500" ></fa>
                                </span>
                            </label>
                            <div v-else  class="form-group t-pl-[3px] t-pr-[3px] t-w-full" >
                                <select  v-model="classrooms.overwrite" class="form-select">
                                    <option value="1" >Yes</option>
                                    <option value="0" >No</option>
                                </select>
                            </div>
                        </div>
                        <div class="t-flex t-gap-3 t-items-center t-h-full" >
                            <button  @click="getUpdateData({
                                id: classroom.id,
                                room: classroom.room,
                                type: classroom.type,
                                overwrite: classroom.multi
                            })" >
                                <fa icon="edit" class="t-text-[18px] t-font-semibold t-text-slate-400 hover:t-text-slate-200" ></fa>
                            </button>
                            <button  @click="delete_classroom(classroom.id)" v-if="isEditMode != classroom.id"  v-show="isEditMode == ''" :disabled="isProcess" class="" >
                                <fa icon="trash" class="t-text-[18px] t-font-semibold t-text-red-400 hover:t-text-red-200" ></fa>
                            </button>
                            <button v-else @click="update_classroom"  v-show="isEditMode != ''" :disabled="isProcess" class="" >
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
     <!-- modal add -->
     <div class="modal fade" id="addModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title t-font-bold text-muted" id="addModalLabel">ADD</h5>
                    <button @click="entireReset()" :disabled="isProcess" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="create_classroom"  >
                        <div class="mt-2">
                            <div class="form-group">
                                <label for="">Room</label>
                                <input v-model="classrooms.room" :disabled="isProcess" type="text" class="form-control text-capitalize" >
                            </div>
                            <small class="text-danger" v-if="classroomError.room != ''" >{{ classroomError.room }}</small>
                        </div>
                        <div class="mt-2">
                            <div class="form-group">
                                <label for="">Type</label>
                                <select v-model="classrooms.type" :disabled="isProcess" class="form-select" >
                                    <option value="lecture" >Lecture</option>
                                    <option value="lecture" >laboratory</option>
                                </select>
                            </div>
                            <small class="text-danger" v-if="classroomError.type != ''" >{{ classroomError.type }}</small>
                        </div>
                        <div class="mt-2">
                            <div class="form-group">
                                <label for="">Overwrite</label>
                                <select v-model="classrooms.overwrite" :disabled="isProcess" class="form-select" >
                                    <option value="1" >Yes</option>
                                    <option value="0" >No</option>
                                </select>
                            </div>
                            <small class="text-danger" v-if="classroomError.overwrite != ''" >{{ classroomError.overwrite }}</small>
                        </div>
                        <div class="t-flex t-justify-end mt-2" >
                            <button class="btn btn-primary" >
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
import Swal from 'sweetalert2';
import {ref,computed, inject} from 'vue';
import {classroomStore} from '../../services/classrooms';
const use_classroomStore = classroomStore();
const globalClassroomData = inject('globalClassroomData');

const classroomData = ref(globalClassroomData);

const isSearch = ref('');
const isProcess = ref(false);
const isEditMode =ref('');

const classrooms = ref({
    id: '',
    room: '',
    type: 'lecture',
    overwrite: 0,
});

const classroomError = ref(
    {
        id: '',
        room: '',
        type: '',

        overwrite: '',
    }
)

const entireReset = () =>{
    reset();
    resetError();
}

const reset = ()=>{
    classrooms.value = {
        id: '',
        room: '',
        type: 'lecture',
        overwrite: 0,
    }
}

const resetError = ()=>{
    classroomError.value = {
        id: '',
        room: '',
        type: '',
        overwrite: 0,
    }
}

const getUpdateData = (data) => {
    if (isEditMode.value !== '') {
        if (isEditMode.value !== data.id) {
            classrooms.value = { ...data };
            isEditMode.value = data.id;
        } else {
            isEditMode.value = '';
            reset();
        }
    } else {
        classrooms.value = { ...data };
        isEditMode.value = data.id;
    }
}

const computed_classroom = computed(()=>{
    if(isSearch.value != ""){
        return classroomData.value.filter(classroom=>{
            return classroom.room.toLowerCase().includes(isSearch.value.toLowerCase());
        })
    }
    else{
        return classroomData.value;
    }
})

const create_classroom = ()=>{
    try {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes,Save it!"
        }).then( async (result) => {
            if (result.isConfirmed) {
                resetError();
                await use_classroomStore.create({...classrooms.value});
                const response = use_classroomStore.getResponse;
                if(response.status){
                    Swal.fire("Success",response.msg,'success');
                    await use_classroomStore.read();
                    classroomData.value = use_classroomStore.getClassrooms;
                    entireReset();
                }
                else{
                    if(response.error == 'room'){
                        classroomError.value.room = response.msg;
                    }

                    if(response.error == 'type'){
                        classroomError.value.type = response.msg;
                    }

                    if(response.error == 'overwrite'){
                        classroomError.value.overwrite = response.msg;
                    }
                }
                console.log(response)
            }
        });
    } catch (error) {
        console.error(error);
    }
}

const update_classroom = ()=>{
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
                await use_classroomStore.update({...classrooms.value});
                const response = use_classroomStore.getResponse;

                if(response.status){
                    isEditMode.value = '';
                    Swal.fire("Success",response.msg,"success");
                    await use_classroomStore.read();
                    classroomData.value = use_classroomStore.getClassrooms;
                    entireReset();
                }
                else{
                    Swal.fire("Error",response.msg,"error");
                }
            }
        });
    } catch (error) {
        console.error(error)
    }
}

const delete_classroom = (id)=>{
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
                await use_classroomStore.delete(id);
                const response = use_classroomStore.getResponse;
                if(response.status){
                    await use_classroomStore.read();
                    classroomData.value = use_classroomStore.getClassrooms;
                    Swal.fire("Success",response.msg,'success');
                }
            }
        });       
    } catch (error) {
        console.error(error)
    }
}


</script>