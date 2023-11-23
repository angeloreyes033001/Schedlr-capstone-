<template>
    <div class="container t-select-none" >
        <h3 class="text-muted t-font-black" >Department</h3>
        <div class="t-mt-3" >
            <div class="t-flex t-justify-end" >
                <div>
                    <form @submit.prevent="createDepartment" class="d-flex t-gap-2 mt-1 ms-2" >
                        <div class="form-group">
                            <input :disabled="isProcess" v-model="departments.department" type="text" class="form-control" placeholder="ADD DEPARTMENT" >
                        </div>
                        <button :disabled="isProcess" class="btn btn-outline-primary" >
                            <fa icon="plus" ></fa>
                        </button>
                    </form>
                </div>
            </div>
            <div class="table-holder mt-3" >
                <div class="t-bg-slate-100 t-p-5 t-rounded-[10px]" >
                    <div class="t-flex t-justify-end t-mb-2" >
                        <div>
                            <input :disabled="isProcess" v-model="isSearch" class="form-control" placeholder="Search" type="text" >
                        </div>
                    </div>
                    <div class="t-h-[60px] t-bg-logoBlue t-p-2 t-rounded-tl-[10px] t-rounded-tr-[10px]" >

                        <div class="t-h-full" >
                            <div class="t-flex t-items-center t-h-full" >
                                <label class="t-uppercase t-text-white t-font-bold" >Department</label>
                            </div>
                        </div>
                    </div>
                    <div  class="t-grid t-grid-cols-1 t-h-[50px] t-bg-slate-50 t-rounded-[5px] t-mt-2 t-mb-1 t-p-2 t-border"
                    v-for="dep in computed_department" :key="dep.department" >
                        <div class="t-flex t-items-center t-h-full" >
                            <label class="t-uppercase t-font-normal text-muted" >{{ dep.department }}</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
 </template>
 <script setup >
 import { ref , inject, computed, defineAsyncComponent } from 'vue';
 import { departmentStore } from '../../services/departments';
 import Swal from 'sweetalert2';

 const globalDepartmentData = inject("globalDepartmentData");
 const departmentData = ref(globalDepartmentData);
 const use_departmentStore = departmentStore();

 const isProcess = ref(false);
 const isSearch = ref('');

 const departments = ref({
    department: '',
 });

 const computed_department = computed(()=>{
    if(isSearch.value != ''){
        return departmentData.value.filter((dep)=>dep.department.toLowerCase().includes(isSearch.value.toLowerCase()));
    }
    else{
        return departmentData.value;
    }
 })

 const createDepartment =  async () =>{
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
                await use_departmentStore.create(departments.value.department);
                const response = use_departmentStore.getResponse;
                if(response.status){
                    await use_departmentStore.read();
                    departmentData.value = use_departmentStore.getDepartments;
                    Swal.fire("Success",response.msg, "success");
                    departments.value.department = "";
                }
                else{
                    departments.value.department = '';
                    Swal.fire("Error", response.msg,"error");
                }
            }
        })
    }
    catch(error){
        console.error(error);
    }
 }

 
 </script>