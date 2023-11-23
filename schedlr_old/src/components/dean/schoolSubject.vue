<template>
    <div class="container t-select-none" >
        <h3 class="text-muted t-font-black" >Subject</h3>
        <div class="t-mt-3" >
            <div class="t-flex t-justify-end" >
                <button :disabled="isProcess || isEditMode != ''" class="btn btn-outline-primary w-25" data-bs-toggle="modal" data-bs-target="#addModal" >
                    <fa icon="plus" ></fa>
                    ADD SSUBJECT
                </button>
            </div>
            <div class="table-holder mt-3" >
                <div class="t-bg-slate-100 t-p-5 t-rounded-[10px]" >
                    <div class="t-flex t-justify-end t-mb-2" >
                        <div>
                            <input :disabled="isProcess || isEditMode != ''" v-model="isSearch" class="form-control" placeholder="Search" type="text" >
                        </div>
                    </div>
                    <div class="t-h-[60px] t-bg-logoBlue t-p-2 t-rounded-tl-[10px] t-rounded-tr-[10px] t-grid t-grid-cols-8">
                        <div class="t-h-full" >
                            <div class="t-flex t-items-center t-h-full" >
                                <label class="t-uppercase t-text-white t-font-bold t-truncate" >code</label>
                            </div>
                        </div>
                        <div class="t-h-full" >
                            <div class="t-flex t-items-center t-h-full" >
                                <label class="t-uppercase t-text-white t-font-bold t-truncate" >Subject</label>
                            </div>
                        </div>
                        <div class="t-h-full" >
                            <div class="t-flex t-items-center t-h-full" >
                                <label class="t-uppercase t-text-white t-font-bold t-truncate" >Semester</label>
                            </div>
                        </div>
                        <div class="t-h-full" >
                            <div class="t-flex t-items-center t-h-full" >
                                <label class="t-uppercase t-text-white t-font-bold t-truncate" >Lec Hour's</label>
                            </div>
                        </div>
                        <div class="t-h-full" >
                            <div class="t-flex t-items-center t-h-full" >
                                <label class="t-uppercase t-text-white t-font-bold t-truncate" >Lab Hour's</label>
                            </div>
                        </div>
                        <div class="t-h-full" >
                            <div class="t-flex t-items-center t-h-full" >
                                <label class="t-uppercase t-text-white t-font-bold t-truncate" >Specialization</label>
                            </div>
                        </div>
                        <div class="t-h-full" >
                            <div class="t-flex t-items-center t-h-full" >
                                <label class="t-uppercase t-text-white t-font-bold t-truncate" >Year</label>
                            </div>
                        </div>
                        <div class="t-h-full" >
                            <div class="t-flex t-items-center t-h-full" >
                                <label class="t-uppercase t-text-white t-font-bold" >Action</label>
                            </div>
                        </div>
                    </div>
                    <div  class="t-grid t-grid-cols-8 t-h-[60px] t-bg-slate-50 t-rounded-[5px] t-mt-2 t-mb-1 t-p-2 t-border"
                    v-for="subject in computed_subject" :key="subject.code" >
                        <div class="t-flex t-items-center t-h-full" >
                            <label class="t-uppercase t-font-normal text-muted t-truncate" :title="subject.code" >{{ subject.code }}</label>
                        </div>
                        <div class="t-flex t-items-center t-h-full" >
                            <label v-if="isEditMode != subject.code" class="t-capitalize t-font-normal text-muted t-truncate" :title="subject.subject" >{{ subject.subject }}</label>
                            <div v-else  class="form-group t-pl-[3px] t-pr-[3px]" >
                                <input v-model="subjects.subject" :disabled="isProcess" class="form-control t-capitalize" type="text" >
                            </div>
                        </div>
                        <div class="t-flex t-items-center t-h-full" >
                            <label  v-if="isEditMode != subject.code"  :disabled="isProcess" class="t-capitalize t-font-normal text-muted t-truncate" :title="subject.semester" >{{ subject.semester }}</label>
                            <div v-else  class="form-group t-pl-[3px] t-pr-[3px]" >
                                <select  v-model="subjects.semester"  class="form-select w-100" >
                                    <option value="1st semester" >1st Semester</option>
                                    <option value="2nd semester" >2nd Semester</option>
                                </select>
                            </div>
                        </div>
                        <div class="t-flex t-items-center t-h-full" >
                            <label  v-if="isEditMode != subject.code" class="t-capitalize t-font-normal text-muted t-truncate" :title="subject.lecture" >{{ subject.lecture }}</label>
                            <div v-else  class="form-group t-pl-[3px] t-pr-[3px] t-w-full" >
                                <select  v-model="subjects.lecture" :disabled="isProcess"  class="form-select w-100" >
                                    <option value="1" >1</option>
                                    <option value="2" >2</option>
                                    <option value="3" >3</option>
                                    <option value="4" >4</option>
                                    <option value="5" >5</option>
                                </select>
                            </div>
                        </div>
                        <div class="t-flex t-items-center t-h-full" >
                            <label v-if="isEditMode != subject.code" class="t-capitalize t-font-normal text-muted t-truncate" :title="subject.laboratory" >{{ subject.laboratory }}</label>
                            <div v-else  class="form-group t-pl-[3px] t-pr-[3px] t-w-full" >
                                <select v-model="subjects.laboratory" :disabled="isProcess" class="form-select w-100" >
                                    <option value="0" >0</option>
                                    <option value="1" >1</option>
                                    <option value="2" >2</option>
                                    <option value="3" >3</option>
                                    <option value="4" >4</option>
                                    <option value="5" >5</option>
                                </select>
                            </div>
                        </div>
                        <div class="t-flex t-items-center t-h-full" >
                            <label v-if="isEditMode != subject.code" class="t-capitalize t-font-normal text-muted t-truncate" :title="subject.specialization" >{{ subject.specialization }}</label>
                            <div v-else  class="form-group t-pl-[3px] t-pr-[3px] t-w-full" >
                                <select v-model="subjects.specialization" :disabled="isProcess" class="form-select" >
                                    <option v-for="specialization in specializationData" :key="specialization.id" :value="specialization.id" >{{ specialization.specialization }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="t-flex t-items-center t-h-full" >
                            <label v-if="isEditMode != subject.code" class="t-capitalize t-font-normal text-muted t-truncate" :title="subject.year" >{{ subject.year }}</label>
                            <div v-else  class="form-group t-pl-[3px] t-pr-[3px] t-w-full" >
                                <select v-model="subjects.year" :disabled="isProcess" class="form-select" >
                                    <option v-for="year in yearlevelData" :key="year.id" :value="year.id" >{{ year.yearName }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="t-flex t-gap-3 t-items-center t-h-full" >
                            <button @click="getUpdateData({
                                code:subject.code,
                                subject:subject.subject,
                                semester:subject.semester,
                                lecture: subject.lecture,
                                laboratory: subject.laboratory,
                                specialization: subject.specializationID,
                                year: subject.yearID
                                })" class="" >
                                <fa icon="edit" class="t-text-[18px] t-font-semibold t-text-slate-400 hover:t-text-slate-200" ></fa>
                            </button>
                            <button @click="delete_subject(subject.code)"  v-if="isEditMode != subject.code"  v-show="isEditMode == ''" :disabled="isProcess" class="" >
                                <fa icon="trash" class="t-text-[18px] t-font-semibold t-text-red-400 hover:t-text-red-200" ></fa>
                            </button>
                            <button v-else  @click="update_subject"  v-show="isEditMode != ''" :disabled="isProcess" class="" >
                                <fa icon="save" class="t-text-[18px] t-font-semibold t-text-blue-400 hover:t-text-red-200" ></fa>
                            </button>
                        </div>
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
                    <button @click="entireReset()" :disabled="isProcess" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="create_subject"  >
                        <div class="mt-2">
                            <div class="form-group">
                                <label for="">Code</label>
                                <input v-model="subjects.code" :disabled="isProcess" type="text" class="form-control text-uppercase" >
                            </div>
                            <small class="text-danger" v-if="subjectError.code != ''" >{{ subjectError.code }}</small>
                        </div>
                        <div class="mt-2">
                            <div class="form-group">
                                <label for="">Subject</label>
                                <input v-model="subjects.subject" :disabled="isProcess" type="text" class="form-control text-capitalize" >
                            </div>
                            <small class="text-danger" v-if="subjectError.subject != ''" >{{ subjectError.subject }}</small>
                        </div>
                        <div class="mt-2">
                            <div class="form-group">
                                <div class="t-grid t-grid-cols-2 t-gap-2" >
                                    <div>
                                        <div class="form-group">
                                            <label for="">Lecture Hour's</label>
                                            <select v-model="subjects.lecture" :disabled="isProcess" class="form-select" >
                                                <option value="1" >1</option>
                                                <option value="2" >2</option>
                                                <option value="3" >3</option>
                                                <option value="4" >4</option>
                                                <option value="5" >5</option>
                                            </select>
                                        </div>
                                        <small class="text-danger" v-if="subjectError.lecture != ''" >{{ subjectError.lecture }}</small>
                                    </div>
                                    <div>
                                        <div class="form-group">
                                            <label for="">laboratory Hour's</label>
                                            <select v-model="subjects.laboratory" :disabled="isProcess" class="form-select" >
                                                <option value="0" >0</option>
                                                <option value="1" >1</option>
                                                <option value="2" >2</option>
                                                <option value="3" >3</option>
                                                <option value="4" >4</option>
                                                <option value="5" >5</option>
                                            </select>
                                        </div>
                                        <small class="text-danger" v-if="subjectError.laboratory != ''" >{{ subjectError.laboratory }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-2">
                            <div class="form-group">
                                <label for="">Semester</label>
                                <select v-model="subjects.semester" :disabled="isProcess" class="form-select" >
                                    <option value="1st semester" >1st Semester</option>
                                    <option value="2nd semester" >2nd Semester</option>
                                </select>
                            </div>
                            <small class="text-danger" v-if="subjectError.semester != ''" >{{ subjectError.semester }}</small>
                        </div>
                        <div class="mt-2">
                            <div class="form-group">
                                <label for="">specialization</label>
                                <select v-model="subjects.specialization" :disabled="isProcess"  class="form-select" >
                                    <option value="" selected >Select Specialization</option>
                                    <option v-for="specialization in specializationData" :key="specialization.id" :value="specialization.id" >{{ specialization.specialization }}</option>
                                </select>
                            </div>
                            <small class="text-danger" v-if="subjectError.specialization != ''" >{{ subjectError.specialization }}</small>
                        </div>
                        <div class="mt-2">
                            <div class="form-group">
                                <label for="">Year</label>
                                <select v-model="subjects.year" :disabled="isProcess" class="form-select" >
                                    <option value="" >Select Year</option>
                                    <option v-for="year in yearlevelData" :key="year.id" :value="year.id" >{{ year.yearName }}</option>
                                </select>
                            </div>
                            <small class="text-danger" v-if="subjectError.year != ''" >{{ subjectError.year }}</small>
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
import {ref,computed, inject} from 'vue';
import Swal from 'sweetalert2';
import { subjectStore } from '../../services/subjects';

const isSearch = ref('');
const isProcess = ref(false);


const subjects = ref({
    code: '',
    subject: '',
    semester: "1st semester",
    lecture: 1,
    laboratory: 0,
    specialization: '',
    year: '',
});

const entireReset =()=>{
    reset();
    resetError();
}

const reset = ()=>{
    subjects.value = {
        code: '',
        subject: '',
        semester: "1st semester",
        lecture: 1,
        laboratory: 0,
        specialization: '',
        year: '',
    }
}

const subjectError = ref({
    code: '',
    subject: '',
    semester: "",
    lecture: '',
    laboratory: '',
    specialization: '',
    year: '',
});

const resetError = ()=>{
    subjectError.value = {
        code: '',
        subject: '',
        semester: "",
        lecture: '',
        laboratory: '',
        specialization: '',
        year: '',
    }
}

const isEditMode = ref('');
const getUpdateData = (data) => {
    if (isEditMode.value !== '') {
        if (isEditMode.value !== data.code) {
            subjects.value = { ...data };
            isEditMode.value = data.code;
        } else {
            isEditMode.value = '';
            reset();
        }
    } else {
        subjects.value = { ...data };
        isEditMode.value = data.code;
    }
}

const globalYearlevelData = inject('globalYearLevelData');
const yearlevelData = ref(globalYearlevelData);

const globalSpecialization = inject("globalSpecialization");
const specializationData = ref(globalSpecialization);

const use_subjectStore = subjectStore();
const globalSubjectData = inject('globalSubjectData');
const subjectData = ref(globalSubjectData);
const computed_subject = computed(()=>{
    if(isSearch.value != ''){
        return subjectData.value.filter((subject)=>subject.code.toLowerCase().includes(isSearch.value.toLowerCase()) || subject.subject.toLowerCase().includes(isSearch.value.toLowerCase()) );
    }
    else{
        return subjectData.value;
    }
})

const create_subject = ()=>{
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

                await use_subjectStore.create({...subjects.value});
                const response = use_subjectStore.getResponse;
                if(response.status){
                    Swal.fire('Success',response.msg,'success');
                    await use_subjectStore.read();
                    subjectData.value = use_subjectStore.getSubjects;
                    entireReset();
                }
                else{
                    if(response.error == 'code'){
                        subjectError.value.code = response.msg;
                    }

                    if(response.error == 'subject'){
                        subjectError.value.subject = response.msg;
                    }

                    if(response.error == 'semester'){
                        subjectError.value.semester = response.msg;
                    }

                    if(response.error == 'lecture'){
                        subjectError.value.lecture = response.msg;
                    }

                    if(response.error == 'laboratory'){
                        subjectError.value.laboratory = response.msg;
                    }

                    if(response.error == 'specialization'){
                        subjectError.value.specialization = response.msg;
                    }

                    if(response.error == 'year'){
                        subjectError.value.year = response.msg;
                    }
                }
                
            }
        });
    } catch (error) {
        console.error(error);
    }
}

const update_subject = () =>{
    try{
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

                await use_subjectStore.update({...subjects.value});
                const response = use_subjectStore.getResponse;
                if(response.status){
                    Swal.fire("Success",response.msg,"success");
                    await use_subjectStore.read();
                    subjectData.value = use_subjectStore.getSubjects;
                    isEditMode.value = ''
                    reset();
                }
                else{
                    Swal.fire("Error",response.msg,"error");
                }

                isProcess.value = false;
            }
        });
    }
    catch(error){
        console.error(error);
    }

    // console.log(subjects.value)
}

const delete_subject = (code) =>{
    try{
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

                await use_subjectStore.delete(code);
                const response = use_subjectStore.getResponse;
                if(response.status){
                    Swal.fire("Success",response.msg,"success");
                    await use_subjectStore.read();
                    subjectData.value = use_subjectStore.getSubjects;
                }

                isProcess.value = false;
            }
        });
    }
    catch(error){
        console.error(error);
    }

    // console.log(subjects.value)
}


</script>