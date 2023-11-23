<template>
    <div class="container t-select-none" >
        <h3 class="text-muted t-font-black" >Account</h3>
        <div class="t-mt-3" >
            <div class="t-flex t-justify-end t-gap-2" >
                <span>
                    <button :disabled="isProcess" data-bs-toggle="modal" data-bs-target="#modalDean" class="btn btn-success w-100" ><fa icon="user-plus" ></fa> DEAN ACCOUNTS</button>
                </span>
                <span>
                    <button  :disabled="isProcess"  data-bs-toggle="modal" data-bs-target="#modalAdmin" class="btn btn-primary w-100" ><fa icon="user-plus" ></fa> ADMIN ACCOUNTS</button>
                </span>
            </div>
        </div>
        <div class="table-holder mt-3" >
            <div class="t-bg-slate-100 t-p-5 t-rounded-[10px]" >
                <div class="t-flex t-justify-end t-mb-2" >
                    <div>
                        <input :disabled="isProcess" v-model="isSearch" class="form-control" placeholder="Search Name" type="text" >
                    </div>
                </div>
                <div class="t-h-[60px] t-bg-logoBlue t-p-2 t-rounded-tl-[10px] t-rounded-tr-[10px] t-grid t-grid-cols-3">
                    <div class="t-h-full" >
                        <div class="t-flex t-items-center t-h-full" >
                            <label class="t-uppercase t-text-white t-font-bold" >Email</label>
                        </div>
                    </div>
                    <div class="t-h-full" >
                        <div class="t-flex t-items-center t-h-full" >
                            <label class="t-uppercase t-text-white t-font-bold" >Fullname</label>
                        </div>
                    </div>
                    <div class="t-h-full" >
                        <div class="t-flex t-items-center t-h-full" >
                            <label class="t-uppercase t-text-white t-font-bold" >Action</label>
                        </div>
                    </div>
                </div>
                <div  class="t-grid t-grid-cols-3 t-h-[50px] t-bg-slate-50 t-rounded-[5px] t-mt-2 t-mb-1 t-p-2 t-border"
                v-for="account in computed_accounts" :key="account.id" >
                    <div class="t-flex t-items-center t-h-full" >
                        <label class="t-lowecase t-font-normal text-muted" >{{ account.email }}</label>
                    </div>
                    <div class="t-flex t-items-center t-h-full" >
                        <label class="t-capitalize t-font-normal text-muted" >{{ account.fullname }}</label>
                    </div>
                    <div class="t-flex t-gap-3 t-items-center t-h-full" >
                        <button  :disabled="isProcess"  @click="changeStatus({id:account.id, status:account.status})"  v-if="account.status == 1" class="text-success" >
                            <fa icon="unlock" ></fa>
                            Active
                        </button>
                        <button  :disabled="isProcess"  @click="changeStatus({id:account.id, status:account.status})"  v-else class="text-danger" >
                            <fa icon="lock" ></fa>
                            Deactive
                        </button>
                    </div>
                </div>
            </div>  
        </div>
    </div>
    <!-- modal admin -->
    <div class="modal fade" id="modalAdmin" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalAdminLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title t-font-bold text-muted" id="modalAdminLabel">ADD ADMIN ACCOUNT</h5>
                    <button  @click="reset" :disabled="isProcess" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="createAccountAdmin" >
                        <div class="mt-3">
                            <div class="form-group">
                                <input  :disabled="isProcess"  class="form-control" type="text" v-model="accounts.email" placeholder="Email Address" >
                            </div>
                            <small class="text-danger" v-if="accounts.email_error != '' " >{{ accounts.email_error }}</small>
                        </div>
                        <div class="mt-3">
                            <div class="form-group">
                                <input  :disabled="isProcess"  class="form-control" type="text" v-model="accounts.fullname" placeholder="Fullname" >
                            </div>
                            <small class="text-danger" v-if="accounts.fullname_error != '' " >{{accounts.fullname_error}}</small>
                        </div>
                        <div class="text-end mt-3">
                            <button  :disabled="isProcess"  class="w-50 btn btn-primary text-uppercase fw-bold" >
                                <span v-if="accounts.onSending" >Please wait</span>
                                <span v-else >Save</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal dean-->
    <!-- modal update -->
    <div class="modal fade" id="modalDean" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalDeanLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title t-font-bold text-muted" id="modalDeanLabel">ADD DEAN ACCOUNT</h5>
                    <button  @click="reset" :disabled="isProcess" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger text-center" v-show="accounts.globalError != '' " >
                        {{ accounts.globalError }}
                    </div>
                    <form @submit.prevent="createAccountDean" >
                        <div class="mt-3">
                            <div class="form-group">
                                <input  :disabled="isProcess"  class="form-control" type="text" v-model="accounts.email" placeholder="Email Address" >
                            </div>
                            <small class="text-danger" v-if="accounts.email_error != '' " >{{accounts.email_error}}</small>
                        </div>
                        <div class="mt-3">
                            <div class="form-group">
                                <input  :disabled="isProcess"  class="form-control" type="text" v-model="accounts.fullname" placeholder="Fullname" >
                            </div>
                            <small class="text-danger" v-if="accounts.fullname_error != '' " >{{accounts.fullname_error}}</small>
                        </div>
                        <div class="mt-3">
                            <div class="form-group">
                                <select  :disabled="isProcess"  class="form-select text-uppercase" v-model="accounts.department"  >
                                    <option value="">-Select Department-</option>
                                    <option v-for="dep in globalDepartmentData" :key="dep.department"  class="text-uppercase" :value="dep.department">{{ dep.department }}</option>
                                </select>
                            </div>
                            <small class="text-danger" v-if="accounts.fullname_error != '' " >{{accounts.fullname_error}}</small>
                        </div>
                        <hr>
                        <div class="">
                            <div class="form-group">
                                <input  :disabled="isProcess"  class="form-control" type="password" v-model="accounts.password" placeholder="Enter Your Password" >
                            </div>
                            <small class="text-danger" v-if="accounts.password_error != '' " >{{accounts.password_error}}</small>
                        </div>
                        <div class="text-end mt-3">
                            <button  :disabled="isProcess"  class="w-50 btn btn-primary text-uppercase fw-bold" >
                                <span v-if="accounts.onSending" >Please wait</span>
                                <span v-else >Save</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup >
import {ref, onMounted, computed, inject} from 'vue';
import { userStore } from '../../services/users';
import Swal from 'sweetalert2';

const use_userStore = userStore();

const isProcess = ref(false);
const isSearch = ref('')

const accounts = ref({
    email: '',
    email_error: '',
    fullname: '',
    fullname_error:  '',
    department: '',
    department_error: '',
    password: '',
    password_error: '',
    onSending: false,
    globalError: '',
    collection: [],
});

const fetchAccounts = async ()=>{

    isProcess.value = true;

    await use_userStore.fetchUsers();
    const response = use_userStore.getState;
    accounts.value.collection = response;
    console.log(response)

    isProcess.value = false;
}

const computed_accounts = computed(()=>{
    if(isSearch.value != ''){
        return accounts.value.collection.filter((item)=>item.fullname.toLowerCase().includes(isSearch.value.toLowerCase()));
    }
    else{
        return accounts.value.collection;
    }
})

const changeStatus =  (data)=>{
    
    const message = data.status == 1 ? 'Active Account' : "Deactive Account!";
    console.log(data.status)

    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: `Yes, ${message} `
    }).then(async(result) => {
        if (result.isConfirmed) {

            isProcess.value = true;

            await use_userStore.changeStatus(data);
            const response = use_userStore.getState;

            if(response.status){
                Swal.fire({
                    position: 'top-end',
                    icon: '',
                    title:`<span class='t-text-[15px] text-uppercase' >${response.msg}</span>`,
                    showConfirmButton: false,
                    timer: 1200,
                    width: '280px',
                })
                fetchAccounts()
            }

            isProcess.value = false;

        }
    })
}

const resetData = ()=>{
    accounts.value.email = "";
    accounts.value.email_error = '';
    accounts.value.fullname ="";
    accounts.value.fullname_error = '';
    accounts.value.department = "";
    accounts.value.department_error = '';
    accounts.value.password = "";
    accounts.value.password_error = '';
    accounts.value.onSending = false;
    accounts.value.globalError = '';
}

const createAccountAdmin = ()=>{
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes,Save Account!'
        }).then( async (result) => {
        if (result.isConfirmed) {

            isProcess.value = true;
            accounts.value.onSending = true;

            accounts.value.email_error = '';
            accounts.value.fullname_error = '';
            accounts.value.password_error = '';

            await use_userStore.createAccountAdmin({
                email: accounts.value.email,
                fullname: accounts.value.fullname,
            });
            const response = use_userStore.getState;
            if(response.status){
                resetData();
                Swal.fire("Success",response.msg,"success")
                fetchAccounts()
            }
            else{
                if(response.error == "email"){
                    accounts.value.email_error = response.msg;
                }

                if(response.error == "fullname"){
                    accounts.value.fullname_error = response.msg;
                }
            }
            accounts.value.onSending = false;
            isProcess.value = false;
        }
    })
}

const createAccountDean = ()=>{
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes,Save Account!'
        }).then( async (result) => {
        if (result.isConfirmed) {

            isProcess.value = true;
            accounts.value.onSending = true;

            accounts.value.email_error = '';
            accounts.value.fullname_error = '';
            accounts.value.password_error = '';
            accounts.value.department_error = '';
            accounts.value.globalError = '';

            await use_userStore.createAccountDean({
                email: accounts.value.email,
                fullname: accounts.value.fullname,
                department: accounts.value.department,
                password: accounts.value.password,
            });
            
            const response = use_userStore.getState;
            if(response.status){
                resetData();
                Swal.fire("Success",response.msg,"success")
                fetchAccounts()
            }
            else{
                if(response.error == "email"){
                    accounts.value.email_error = response.msg;
                }

                if(response.error == "fullname"){
                    accounts.value.fullname_error = response.msg;
                }

                if(response.error == "department"){
                    accounts.value.department_error = response.msg;
                }

                if(response.error == "password"){
                    accounts.value.password_error = response.msg;
                }

                if(response.error == "global"){
                    accounts.value.globalError = response.msg;
                }
            }
            accounts.value.onSending = false;
            isProcess.value = false;
        }
    })
}

onMounted(()=>{
    fetchAccounts();
})

const globalDepartmentData = inject("globalDepartmentData");

</script>