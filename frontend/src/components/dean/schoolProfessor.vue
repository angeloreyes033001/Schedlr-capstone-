<template>
    <div class="container t-select-none" >
        <h3 class="text-muted t-font-black" >Professor</h3>
        <div class="t-mt-3" >
            <div class="t-flex t-justify-end" >
                <button :disabled="isProcess || isEditMode !='' ||isSelectLoad != ''" class="btn btn-outline-primary w-25" data-bs-toggle="modal" data-bs-target="#addModal" >
                    <fa icon="plus" ></fa>
                    ADD SECTION
                </button>
            </div>
            <div class="table-holder mt-3" >
            <div class="t-bg-slate-100 t-p-5 t-rounded-[10px]" >
                <div class="t-flex t-justify-between t-mb-2 t-pb-2 t-border t-border-t-slate-100 t-border-l-slate-100 t-border-r-slate-100 t-border-b-slate-300" >
                    <div class="t-flex t-gap-1" >
                        <button @click="change_tab('list')" :disabled="isProcess || isEditMode !='' ||isSelectLoad != ''" class="btn t-w-[150px]" :class="{'btn-primary': isSelectedTab ==='list'}" >
                            <fa icon="list" ></fa>
                            &nbsp;
                            List
                        </button>
                        <button @click="change_tab('assigned')" :disabled="isProcess || isEditMode !='' ||isSelectLoad != ''" class="btn t-w-[150px]" :class="{'btn-primary': isSelectedTab ==='assigned'}" >
                            <fa icon="check" ></fa>
                            &nbsp;
                            Assigned
                        </button>
                    </div>
                    <div >
                        <input v-show="isSelectedTab === 'list'" v-model="isSearch" :disabled="isProcess || isEditMode !='' " class="form-control t-capitalize" placeholder="Search" type="text" >
                    </div>
                </div>
                <div>
                    <div v-show="isSelectedTab === 'list'" class="t-grid t-grid-cols-1" :class="{'t-grid-cols-[620px,1fr]': isSelectLoad != ''}" >
                        <!-- list div -->
                        <div class="t-inline-block" >
                            <div v-for="professor in computed_professor" class="t-bg-white t-w-[300px] t-shadow t-h-auto t-inline-block t-m-1" >
                                <div class="t-grid t-rounded-[10px] parent-white t-w-full" >
                                    <div :class="{'t-bg-logoOrange':isSelectLoad == professor.professorID}" class="t-bg-logoBlue t-p-2 t-rounded-[10px] t-grid t-grid-cols-[80px,1fr] t-w-full" >
                                        <div class="t-flex t-justify-center t-items-center t-p-2" >
                                            <img src="../../assets/images/profile.png" class="t-bg-white t-rounded-[50%]" >
                                        </div>
                                        <div class="t-flex t-items-end t-overflow-hidden t-w-full" >
                                            <span class="t-grid t-w-full" >
                                                <div class="t-w-full" >
                                                    <small v-if="isEditMode != professor.professorID" :title="professor.fullname" class="t-font-bold t-text-white t-capitalize t-text-[20px] t-truncate" >{{professor.fullname}}</small>
                                                    <div v-else class="form-group" >
                                                        <input v-model="professors.fullname" class="form-control text-capitalize" >
                                                    </div>
                                                </div>
                                                <small class="t-font-extralight t-text-white t-lowercase" >{{ professor.professorID }}</small>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="t-bg-white t-p-2 t-rounded-[10px] child-white t-w-full" >
                                        <span class="t-grid t-grid-cols-[100px,1fr] t-p-1" >
                                            <div class="t-flex t-items-center" >
                                                <small class="t-font-[14px] text-muted" >Status</small>
                                            </div>
                                            <div class="t-flex t-items-center" >
                                                <small v-if="isEditMode != professor.professorID" class="t-font-[14px] text-muted t-capitalize" >{{professor.status}}</small>
                                                <div v-else class="form-group t-w-full" >
                                                    <select v-model="professors.status" class="form-select t-w-full" >
                                                        <option value="regular">Regular</option>
                                                        <option value="part-time">Part-Time</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </span>
                                        <span class="t-grid t-grid-cols-[100px,1fr] t-p-1" >
                                            <div class="t-flex t-items-center" >
                                                <small class="t-font-[14px] text-muted" >Rank</small>
                                            </div>
                                            <div class="t-flex t-items-center" >
                                                <small v-if="isEditMode != professor.professorID" class="t-font-[14px] text-muted t-capitalize" >{{professor.rank}}</small>
                                                <div v-else class="form-group t-w-full" >
                                                    <select v-model="professors.rank" class="form-select t-w-full t-capitalize" >
                                                        <option class="t-capitalize" v-for="rank in globalRankData" :value="rank.id">{{rank.rank}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </span>
                                        <span class="t-grid t-grid-cols-[100px,1fr] t-p-1" >
                                            <div class="t-flex t-items-center" >
                                                <small class="t-font-[14px] text-muted" >Designated</small>
                                            </div>
                                            <div class="t-flex t-items-center" >
                                                <small v-if="isEditMode != professor.professorID" class="t-font-[14px] text-muted t-capitalize" >{{professor.designation}}</small>
                                                <div v-else class="form-group t-w-full" >
                                                    <select v-model="professors.designation" class="form-select t-w-full t-capitalize" >
                                                        <option value="none" >None</option>
                                                        <option v-for="designated in globalDesignationData" class="t-capitalize" :value="designated.position">{{designated.position}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </span>
                                        <div class="t-grid t-grid-cols-[1fr,50px,50px] t-gap-1 t-mt-1 t-p-1" >
                                            <button v-if="isSelectLoad != professor.professorID" @click="select_view_load(professor.professorID)" :disabled="isProcess || isEditMode !=''" class="btn btn-primary" >
                                                <fa icon="scroll" ></fa>
                                                <label for="">&nbsp;Show Load</label>
                                            </button>
                                            <button  v-else @click="remove_view_load()" :disabled="isProcess || isEditMode !=''" class="btn btn-primary" >
                                                <fa icon="scroll" ></fa>
                                                <label for="">&nbsp;Hide Load</label>
                                            </button>
                                            <button  @click="getUpdateData(
                                                {
                                                    professorID: professor.professorID,
                                                    fullname: professor.fullname,
                                                    status: professor.status,
                                                    rank: professor.rankID,
                                                    designation: professor.designation
                                                }
                                                )" :disabled="isProcess || isSelectLoad != ''" class="btn btn-outline-secondary" >
                                                <fa icon="edit" ></fa>
                                            </button>
                                            <button v-if="isEditMode == ''" @click="delete_professor(professor.professorID)" :disabled="isProcess ||isSelectLoad != ''" class="btn btn-outline-danger" >
                                                <fa icon="trash" ></fa>
                                            </button>
                                            <button v-else @click="update_professor" :disabled="isProcess ||isSelectLoad != ''" class="btn btn-outline-primary" >
                                                <fa icon="save" ></fa>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="" v-show="isSelectLoad != ''" >
                            <div class="t-flex" >
                                <form @submit.prevent="create_load" class="t-flex t-gap-2" >
                                    <div class="form-group" >
                                        <select v-model="loads.subject" class="form-select" >
                                            <option disabled value="" >Select Subject</option>
                                            <option v-for="sub in globalSubjectData" :value="sub.code" >{{ sub.code }}</option>
                                        </select>
                                    </div>
                                    <div>
                                        <button class="btn btn-primary" >
                                            <fa icon="plus" ></fa>
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="table-holder" >
                                <div class="t-bg-slate-100 t-p-1 t-rounded-[10px]" >
                                    <div class="t-h-[60px] t-bg-logoBlue t-p-2 t-rounded-tl-[10px] t-rounded-tr-[10px] t-grid t-grid-cols-6">
                                        <div class="t-h-full" >
                                            <div class="t-flex t-items-center t-h-full" >
                                                <label class="t-uppercase t-text-white t-font-bold t-truncate" >Code</label>
                                            </div>
                                        </div>
                                        <div class="t-h-full" >
                                            <div class="t-flex t-items-center t-h-full" >
                                                <label class="t-uppercase t-text-white t-font-bold t-truncate" >Subject</label>
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
                                                <label class="t-uppercase t-text-white t-font-bold t-truncate" >Year</label>
                                            </div>
                                        </div>
                                        <div class="t-h-full" >
                                            <div class="t-flex t-items-center t-h-full" >
                                                <label class="t-uppercase t-text-white t-font-bold t-truncate" >Action</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div  class="t-grid t-grid-cols-6 t-h-[50px] t-bg-slate-50 t-rounded-[5px] t-mt-2 t-mb-1 t-p-2 t-border"
                                    v-for="load in loadData"  >
                                        <div class="t-flex t-items-center t-h-full" >
                                            <label class="t-uppercase t-font-normal text-muted" >{{ load.code }}</label>
                                        </div>
                                        <div class="t-flex t-items-center t-h-full" >
                                            <label class="t-capitalize t-font-normal text-muted  t-truncate" :title="load.subject" >{{ load.subject }}</label>
                                        </div>
                                        <div class="t-flex t-items-center t-h-full" >
                                            <label class="t-capitalize t-font-normal text-muted" >{{ load.lecture }}</label>
                                        </div>
                                        <div class="t-flex t-items-center t-h-full" >
                                            <label class="t-capitalize t-font-normal text-muted" >{{ load.laboratory }}</label>
                                        </div>
                                        <div class="t-flex t-items-center t-h-full" >
                                            <label class="t-capitalize t-font-normal text-muted" >{{ load.year }}</label>
                                        </div>
                                        <div class="t-flex t-gap-3 t-items-center t-h-full" >
                                            <!-- <button @click="setData({id: section.id , section: section.section , yearlevel: section.yearID,specialization: section.specializationID })" data-bs-toggle="modal" data-bs-target="#editModal" >
                                                <fa icon="edit" class="t-text-[18px] t-font-semibold t-text-slate-400 hover:t-text-slate-200" ></fa>
                                            </button> -->
                                            <button  @click="delete_load(load.id,load.professor)" class="" >
                                                <fa icon="trash" class="t-text-[18px] t-font-semibold t-text-red-400 hover:t-text-red-200" ></fa>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- assigned -->
                    <div  v-show="isSelectedTab === 'assigned'" class="t-w-full" >
                        <div class="t-flex t-justify-end" >
                            <div class="form-group" >
                                <select v-model="semester_load" @change="show_all_load" class="form-select" >
                                    <option value="1st semester">1st Semester</option>
                                    <option value="2nd semester">2nd Semester</option>
                                </select>
                            </div>
                        </div>
                        <div v-for="assigned in assignedData" class="t-w-full" >
                            <h5 class="t-font-bold text-muted t-uppercase" >{{ assigned.year }}</h5>
                            <div class="t-flex t-flex-row t-flex-wrap t-gap-3 t-w-full" >
                                <div v-for="subject in assigned.subjects" :key="subject.code" class="" >
                                    <div class="t-h-full t-w-[300px] t-rounded-[10px] t-bg-slate-200 p-3 " >
                                        <div class="t-border t-border-b-slate-300" >
                                            <h6 class="t-font-bold t-uppercase" >{{ subject.code }}</h6>
                                        </div>
                                        <div v-if="subject.professors.length > 0" >
                                            <div v-for="professor in subject.professors" >
                                                <div class="t-grid t-grid-cols-[0.7fr,1fr]" >
                                                    <small class="t-uppercase" >{{ professor.professorID }}</small>
                                                    <small class="t-capitalize" >{{ professor.fullname }}</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div v-else >
                                            <i class="t-text-[14px] t-text-gray-400" >No Professor</i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
    <!-- <div class="p-4" >
        <div class="t-flex">
            <h4 class="text-uppercase t-font-bold">Professors</h4>
        </div>
        <hr>
        <div class="navbar">
            <span></span>
            <form class="d-flex gap-2 w-" >
                <div class="form-group border t-flex rounded-2 t-shadow-md">
                    <span class="fs-6 p-1 text-secondary p-2"><fa icon="search"></fa></span>
                    <input v-model="isSearch" class="t-border-none t-outline-none fs-6 w-100" type="text" placeholder="Search">
                </div>
            </form>
        </div>
        <button type="button" class="btn btn-outline-primary ms-2 w-25" data-bs-toggle="modal" data-bs-target="#addModal"><fa icon="plus"></fa>ADD PROFESSOR</button>
        <div class="mt-3" v-if="professorData.length > 0" >
            <div v-for="professor in computedProfessor" :key="professor.professorID"  class=" professor-card t-w-[300px] bg-white shadow m-3 t-inline-block">
                <div class=" t-bg-logoBlue t-relative t-h-[100px] t-grid t-justify-center t-items-center t-rounded-md">
                    <div class="t-absolute t-right-2 t-top-2 text-white " >
                        <button class="" >
                            <fa @click="showOption(professor.professorID)" class="t-cursor-pointer t-text-[18px]" icon="ellipsis-v" ></fa>
                        </button>
                        <div class="t-relative t-bg-red-500" >
                            <div class="t-absolute bg-white t-w-[40px] t-right-0 t-grid t-rounded " v-show="options == professor.professorID" >
                                <span class="p-1 text-center" >
                                    <button data-bs-toggle="modal" data-bs-target="#editModal" @click="setUpdataData({id: professor.professorID, fullname: professor.fullname, status: professor.status , rank: professor.rankID , designation: professor.designation })" class="text-primary" >
                                        <fa icon="edit" ></fa>
                                    </button>
                                </span>
                                <span class="p-1 text-center" >
                                    <button @click="deleteProfessor(professor.professorID)" class="text-danger" >
                                        <fa icon="trash" ></fa>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <img src="../../assets/images/profile.png" alt="" class=" t-w-[120px] t-h-[120px] t-mt-[30px] t-shadow  t-bg-slate-200 rounded-circle" >
                </div>
                <div class="t-mt-[50px] p-2 ">
                    <span class="t-flex" >
                        <h6 class="text-capitalize t-text-[14px] ms-1 t-mb-[0px]" >
                            <strong class="t-font-semibold" >ID: </strong>
                            <small class="" >{{ professor.professorID }}</small>
                        </h6>
                    </span>
                    <span class="t-flex" >
                        <h6 class="text-capitalize t-text-[14px] ms-1 t-mb-[0px]" >
                            <strong class="t-font-semibold" >Fullname: </strong>
                            <small class="" >{{ professor.fullname }}</small>
                        </h6>
                    </span>
                    <span class="t-flex" >
                        <h6 class="text-capitalize t-text-[14px] ms-1 t-mb-[0px]" >
                            <strong class="t-font-semibold" >Status: </strong>
                            <small class="" >{{ professor.status }}</small>
                        </h6>
                    </span>
                    <span class="t-flex" >
                        <h6 class="text-capitalize t-text-[14px] ms-1 t-mb-[0px]" >
                            <strong class="t-font-semibold" >rank: </strong>
                            <small class="" >{{ professor.rank }}</small>
                        </h6>
                    </span>
                    <span class="t-flex" >
                        <h6 class="text-capitalize t-text-[14px] ms-1 t-mb-[0px]" >
                            <strong class="t-font-semibold" >designated: </strong>
                            <small class="" >{{ professor.designation }}</small>
                        </h6>
                    </span>
                    <div class="mt-1" >
                        <button @click="readLoad(professor.professorID)" data-bs-toggle="modal" data-bs-target="#loadModal" class="btn btn-outline-success w-100"><fa icon="eye" ></fa> SHOW LOAD</button>
                    </div>
                </div>
            </div>
        </div>
        <div v-else>
            <div class="t-grid t-justify-center t-items-center mt-5 pt-5" >
                <div  >
                    <div class="t-flex t-justify-center" >
                        <img src="../../assets/images/no-professor.png" >
                    </div>
                    <h6 class="t-font-extralight" >No professor recorded.</h6>
                </div>
            </div>
        </div>
    </div> -->
     <!-- modal add -->
     <div class="modal fade" id="addModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title t-font-bold text-muted" id="addModalLabel">ADD</h5>
                    <button @click="entireReset()" :disabled="isProcess" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="create_professor"  >
                        <div class="mt-2">
                            <div class="form-group">
                                <label for="">ID</label>
                                <input v-model="professors.professorID" :disabled="isProcess" type="text" class="form-control text-lowercase" >
                            </div>
                            <small class="text-danger" v-if="professorError.professorID != ''" >{{ professorError.professorID }}</small>
                        </div>
                        <div class="mt-2">
                            <div class="form-group">
                                <label for="">Fullname</label>
                                <input v-model="professors.fullname" :disabled="isProcess" type="text" class="form-control text-capitalize" >
                            </div>
                            <small class="text-danger" v-if="professorError.fullname != ''" >{{ professorError.fullname }}</small>
                        </div>
                        <div class="mt-2">
                            <div class="form-group">
                                <label for="">Status</label>
                                <select v-model="professors.status" class="form-select" >
                                    <option value="regular" >Regular</option>
                                    <option value="part-time" >Part-Time</option>
                                </select>
                            </div>
                            <small class="text-danger" v-if="professorError.status != ''" >{{ professorError.status }}</small>
                        </div>
                        <div class="mt-2">
                            <div class="form-group">
                                <label for="">Rank</label>
                                <select v-model="professors.rank" class="form-select t-capitalize" >
                                    <option value="" >Select Rank</option>
                                    <option v-for="rank in globalRankData"  :value="rank.id" class="t-capitalize" >{{ rank.rank }}</option>
                                </select>
                            </div>
                            <small class="text-danger" v-if="professorError.rank != ''" >{{ professorError.rank }}</small>
                        </div>
                        <div class="mt-2">
                            <div class="form-group">
                                <label for="">Designation</label>
                                <select v-model="professors.designation" class="form-select t-capitalize" >
                                    <option value="none" >None</option>
                                    <option v-for="designated in globalDesignationData" class="t-capitalize" :value="designated.position">{{designated.position}}</option>
                                </select>
                            </div>
                            <small class="text-danger" v-if="professorError.designation != ''" >{{ professorError.designation }}</small>
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
    <!-- edit modal -->
    <!-- load modal -->
    <!-- <div class="modal fade" id="loadModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="loadModalLabel" aria-hidden="true">
        <div class="modal-dialog  modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white text-uppercase" id="loadModalLabel">Professor Loads</h5>
                    <button type="button" class="text-white fs-4 " data-bs-dismiss="modal" aria-label="Close"> 
                        <fa icon="close"></fa>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="navbar">
                        <span></span>
                        <div class="d-flex gap-2 w-25" >
                               <select class="form-select text-uppercase"  v-model="loads.subject">
                                <option class="text-uppercase text-center" value="">--select subject--</option>
                                <option class="text-uppercase" v-for="subject in globalSubjectData" :key="subject.code" :value="subject.code">{{ subject.code }}</option>
                               </select>
                            <button  @click="createLoad(selectedProfessor)"  class="btn btn-outline-primary" ><fa icon="plus" ></fa></button>
                        </div>
                    </div>
                    <div class="table-holder t-shadow-md mt-2 t-overflow-x-auto">
                        <table class="table">
                            <thead>
                                <tr>
                                <th class="bg-secondary text-white p-2">Code</th>
                                <th class="bg-secondary text-white p-2">Subject</th>
                                <th class="bg-secondary text-white p-2">Semester</th>
                                <th class="bg-secondary text-white p-2">Laboratory</th>
                                <th class="bg-secondary text-white p-2">Year Level</th>
                                <th class="bg-secondary text-white p-2">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr v-for="load in loadData" :key="load.id" >
                                    <td class="text-uppercase">{{ load.code }}</td>
                                    <td class="text-capitalize">{{ load.subject }}</td>
                                    <td class="text-capitalize">{{ load.semester }}</td>
                                    <td class="text-capitalize">
                                        <fa v-if="load.laboratory == 1" icon="circle-check" class="text-success"></fa>&nbsp;
                                        <fa v-else icon="times-circle" class="text-danger"></fa>
                                    </td>
                                    <td class="text-capitalize">{{ load.yearlevel }}</td>
                                    <td class="">
                                        <button @click="deleteLoad({id:load.id , professor:load.professor})" class="btn btn-outline-danger" > <fa icon="trash"></fa></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
</template>
<script setup >
import { ref, defineAsyncComponent , computed, inject } from 'vue';
import Swal from 'sweetalert2';
import { professorStore } from '../../services/professors';
import { loadStore } from '../../services/Loads.js';

const use_professorStore = professorStore();
const globalProfessorData = inject("globalProfessorData");
const globalRankData = inject('globalRankData');
const professorData = ref(globalProfessorData);

const use_loadStore = loadStore();
const loadData = ref([]);
const loads = ref({
    professor: '',
    subject: ''
})

const globalSubjectData = inject('globalSubjectData');
const globalDesignationData = inject('globalDesignationData');

const computed_professor = computed(()=>{
    if(isSearch.value != ""){
        return professorData.value.filter(professor=>{
            return professor.fullname.toLowerCase().includes(isSearch.value.toLowerCase());
        })
    }
    else{
        return professorData.value;
    }
})

const isProcess = ref(false);
const isSearch = ref('');

const isSelectLoad = ref('');
const select_view_load = (professorID)=>{
    isSelectLoad.value = professorID;
    read_load(professorID)
}
const remove_view_load = ()=>{
    isSelectLoad.value = "";
}

const isSelectedTab = ref('list');
const change_tab = (tab) =>{
    isSelectedTab.value = tab;
    if(isSelectedTab.value != 'list'){
        show_all_load(semester_load.value);
    }
}

const professors = ref({
    professorID: '',
    fullname: '',
    status: 'regular',
    rank: '',
    designation: 'none',
})

const professorError = ref({
    professorID: '',
    fullname: '',
    status: '',
    rank: '',
    designation: '',
})

const entireReset = ()=>{
    reset();
    resetError();
}

const reset = ()=>{
    professors.value = {
    professorID: '',
        fullname: '',
        status: 'regular',
        rank: '',
        designation: 'none',
    }
}

const resetError = ()=>{
    professorError.value = {
        professorID: '',
        fullname: '',
        status: '',
        rank: '',
        designation: '',
    }
}

const isEditMode = ref('');
const getUpdateData = (data) => {
    if (isEditMode.value !== '') {
        if (isEditMode.value !== data.professorID) {
            professors.value = { ...data };
            isEditMode.value = data.professorID;
        } else {
            isEditMode.value = '';
            reset();
        }
    } else {
        professors.value = { ...data };
        isEditMode.value = data.professorID;
    }
}

const create_professor = ()=>{
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

                isProcess.value = true;

                resetError();
                await use_professorStore.create({...professors.value});
                const response = use_professorStore.getResponse;
                if(response.status){
                    await use_professorStore.read();
                    professorData.value = use_professorStore.getProfessor;
                    Swal.fire("Success",response.msg,'success');
                    entireReset();
                }
                else{
                    if(response.error == 'id'){
                        professorError.value.professorID = response.msg;
                    }

                    if(response.error == 'fullname'){
                        professorError.value.fullname = response.msg;
                    }

                    if(response.error == 'status'){
                        professorError.value.status = response.msg;
                    }

                    if(response.error == 'rank'){
                        professorError.value.rank = response.msg;
                    }

                    if(response.error == 'designation'){
                        professorError.value.designation = response.msg;
                    }
                }
                isProcess.value = false;
            }
        });
    } catch (error) {
        console.error(error)
    }
}

const update_professor = ()=>{
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
                await use_professorStore.update({...professors.value});
                const response = use_professorStore.getResponse;
                if(response.status){
                    await use_professorStore.read();
                    professorData.value = use_professorStore.getProfessor;
                    Swal.fire('Success',response.msg,'success')
                    isEditMode.value = '';
                    reset();
                }else{
                    Swal.fire("Error",response.msg,'error');
                }
                isProcess.value = false;
            }
        });
    } catch (error) {
        console.error(error);
    }
}

const delete_professor = (id)=>{
    try {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes,Delete it!"
        }).then( async(result) => {
            if (result.isConfirmed) {
                isProcess.value = true;
                await use_professorStore.delete(id);
                const response = use_professorStore.getResponse;
                if(response.status){
                    Swal.fire('Success',response.msg,'success');
                    await use_professorStore.read();
                    professorData.value = use_professorStore.getProfessor;
                }
                isProcess.value = false;
            }
        });
    } catch (error) {
        console.error(error);
    }
}

const create_load = ()=>{
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
            
            isProcess.value = true;

            await use_loadStore.create({...loads.value});
            const response = use_loadStore.getResponse;
            
            if(response.status){
                read_load(loads.value.professor);
                Swal.fire("Success",response.msg,'success');
            }
            else{
                Swal.fire("Error",response.msg,"error");
            }

            isProcess.value = false;

        });

    } catch (error) {
        console.error(error);
    }
}

const read_load = async (professorID)=>{
    try {
        isProcess.value = true;
        await use_loadStore.read(professorID);
        loadData.value =  use_loadStore.getLoad;

        loads.value.professor = professorID;

        isProcess.value = false;

    } catch (error) {
        console.error(error)
    }
}

const assignedData = ref([]);
const semester_load = ref('1st semester');
const show_all_load = async()=>{
    try {
        isProcess.value = true;
        await use_loadStore.show_all(semester_load.value);
        const response = use_loadStore.getResponse;
        assignedData.value = response;
        isProcess.value = false;
    } catch (error) {
        console.error(error);
    }
}

const delete_load = (id,professorID)=>{
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
                await use_loadStore.delete(id);
                const response = use_loadStore.getResponse;
                if(response.status){
                    await use_loadStore.read(professorID);
                    loadData.value = use_loadStore.getLoad;
                    Swal.fire("Success",response.msg,'success');
                }
            }
        });
    } catch (error) {
        console.error(error)
    }
}


// const options = ref('');
// const showOption = (id)=>{
//     if(options.value != ''){
//         options.value = ""
//     }
//     else{
//         options.value = id;
//     }
// }


// const createProfessor = ()=>{
//     try {
//         Swal.fire({
//             title: 'Are you sure?',
//             text: "You won't be able to revert this!",
//             icon: 'warning',
//             showCancelButton: true,
//             confirmButtonColor: '#3085d6',
//             cancelButtonColor: '#d33',
//             confirmButtonText: 'Yes, Save it!'
//         }).then( async (result) => {
//             if (result.isConfirmed) {
//                 //remove error message
//                 professors.value.id_error = "";
//                 professors.value.fullname_error = "";
//                 professors.value.status_error = "";
//                 professors.value.rank_error = "";
//                 professors.value.designation_error ="";
//                 await use_professorStore.create({
//                     id: professors.value.id,
//                     fullname: professors.value.fullname,
//                     status: professors.value.status,
//                     rank: professors.value.rank,
//                     designation: professors.value.designation,
//                 });
//                 const response = use_professorStore.getResponse;
//                 if(response.status){
//                     await use_professorStore.read();
//                     professorData.value = use_professorStore.getProfessor;
//                     Swal.fire("Success", response.msg, "success");
//                     reset();
//                 }
//                 else{
//                     if(response.error == "id"){
//                         professors.value.id_error = response.msg;
//                     }

//                     if(response.error == "fullname"){
//                         professors.value.fullname_error = response.msg;
//                     }

//                     if(response.error == "status"){
//                         professors.value.status_error = response.msg;
//                     }

//                     if(response.error == "rank"){
//                         professors.value.rank_error = response.msg;
//                     }

//                     if(response.error == "designation"){
//                         professors.value.designation_error = response.msg;
//                     }
//                 }
//             }
//         })
//     } catch (error) {
//         console.error(error)
//     }
// }


// const loads = ref({
//     subject: '',
// })

// const createLoad = async (professor)=>{
//     // const professor = data[0]['professor'];
//     const subject = loads.value.subject;
//     await use_loadStore.create({professor: professor , subject: subject });
//     const response = use_loadStore.getResponse;
//     if(response.status){
//         await use_loadStore.read(professor);
//         loadData.value = use_loadStore.getLoad;
//         loads.value.subject = '';
//         Swal.fire("Success", response.msg,"success");
//     }
//     else{
//         Swal.fire("Error", response.msg,"error");
//     }
// }
// const selectedProfessor = ref();
// const readLoad = async (id)=>{
//     try{
//         await use_loadStore.read(id);
//         loadData.value = use_loadStore.getLoad;
//         selectedProfessor.value = id;
//     }
//     catch(error){
//         console.error(error);
//     }
// }

// const deleteLoad = async (data)=>{
//     try{
//         Swal.fire({
//             title: 'Are you sure?',
//             text: "You won't be able to revert this!",
//             icon: 'warning',
//             showCancelButton: true,
//             confirmButtonColor: '#3085d6',
//             cancelButtonColor: '#d33',
//             confirmButtonText: 'Yes, delete it!'
//         }).then( async (result) => {
//             if (result.isConfirmed) {
//                 const { id , professor } = data;
//                 await use_loadStore.delete(id);
//                 const response = use_loadStore.getResponse;
//                 if(response.status){
//                     await use_loadStore.read(professor);
//                     loadData.value = use_loadStore.getLoad;
//                     Swal.fire("Success", response.msg,"success");
//                 }
//             }
//         })
//     }
//     catch(error){
//         console.error(error);
//     }
// }

</script>
<style scoped>

.parent-white .child-white{
    display: none;
}

.parent-white:hover .child-white{
    display: grid;
    transition: 3s;
}

</style>