<template>
    <div class="p-4 t-h-full" >
        <h4 class="text-uppercase t-font-bold">Settings</h4>
        <div class="t-grid t-grid-cols-2 t-gap-3" >
            <div class=" t-bg-slate-200 p-3" >
                <form @submit.prevent="changeEmail" >
                    <h4 class="t-font-bold text-uppercase text-muted" >Change Email Address</h4>
                    <div class="mt-2" >
                        <div class="form-group">
                            <label for="">New Email</label>
                            <input v-model="email_config.new_email" type="text" class="form-control" placeholder="example@gmail.com" >
                        </div>
                        <small class="text-danger" v-if="email_config.new_email_error != ''" >{{ email_config.new_email_error }}</small>
                    </div>
                    <div class="mt-2" >
                        <div class="form-group">
                            <label for="">Confirm New Email</label>
                            <input v-model="email_config.confirm_email" type="text" class="form-control" placeholder="example@gmail.com" >
                        </div>
                        <small class="text-danger" v-if="email_config.confirm_email_error != ''" >{{ email_config.confirm_email_error }}</small>
                    </div>
                    <div class="mt-2" >
                        <div class="form-group">
                            <label for="">Password</label>
                            <input v-model="email_config.password" type="password" class="form-control" placeholder="Enter Your Password" >
                        </div>
                        <small class="text-danger" v-if="email_config.password_error != ''" >{{ email_config.password_error }}</small>
                    </div>
                    <div class="t-flex t-justify-end mt-2" >
                        <button :disabled="email_config.new_email == '' || email_config.confirm_email =='' || email_config.password == '' " class="btn btn-primary w-50" >
                            <fa icon="save" ></fa>
                            Save
                        </button>
                    </div>
                </form>
                <hr>
                <form @submit.prevent="changePassword" >
                    <h4 class="t-font-bold text-uppercase text-muted" >Change Password</h4>
                    <div class="mt-2" >
                        <div class="form-group">
                            <label for="">Current Password</label>
                            <input v-model="password_config.current_password" type="password" class="form-control" placeholder="Current Password" >
                        </div>
                        <small class="text-danger" v-if="password_config.current_password_error != ''" >{{ password_config.current_password_error }}</small>
                    </div>
                    <div class="mt-2" >
                        <div class="form-group">
                            <label for="">New Password</label>
                            <input v-model="password_config.new_password" type="password" class="form-control" placeholder="New Password" >
                        </div>
                        <small class="text-danger" v-if="password_config.new_password_error != ''" >{{ password_config.new_password_error }}</small>
                    </div>
                    <div class="mt-2" >
                        <div class="form-group">
                            <label for="">Confirm New Password</label>
                            <input v-model="password_config.confirm_password" type="password" class="form-control" placeholder="Confirm New Password" >
                        </div>
                        <small class="text-danger" v-if="password_config.confirm_password_error != ''" >{{ password_config.confirm_password_error }}</small>
                    </div>
                    <div class="t-flex t-justify-end mt-2" >
                        <button :disabled="password_config.current_password == '' || password_config.new_password =='' || password_config.confirm_password == '' " class="btn btn-primary w-50" >
                            <fa icon="save" ></fa>
                            Save
                        </button>
                    </div>
                </form>
                <hr>
                <h4 class="t-font-bold text-uppercase text-muted" >Reset All Schedule</h4>
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                    <fa icon="trash" class="t-text-[20px]" ></fa>
                    <!-- <br> -->
                    Reset Schedule
                </button>
            </div>
            <div class=" " >
                <!-- <h4 class="t-font-bold text-uppercase text-muted" >Reset All Schedule</h4>
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                    <fa icon="trash" class="t-text-[20px]" ></fa>
                    
                    Reset Schedule
                </button> -->
            </div>
        </div>
    </div>
    <!-- modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form @submit.prevent="resetSchedule" >
                    <div class="modal-header">
                        <h5 class="modal-title t-uppercase" id="staticBackdropLabel">Reset Schedule</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group" >
                            <label for="">Password</label>
                            <input v-model="reset_schedule.password" type="password" placeholder="Passsword" class="form-control" >
                        </div>
                        <small class="text-danger" v-if="reset_schedule.password_error != ''" >{{ reset_schedule.password_error }}</small>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button :disabled="reset_schedule.password == '' || reset_schedule.password.length < 8 " type="submit" class="btn btn-danger">
                            <fa icon="trash" ></fa>
                            Delete
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script setup>
    import { ref , onMounted , computed } from 'vue';
    import { userStore } from '../../../services/users';
    import { scheduleStore } from '../../../services/schedule';
    import Swal from 'sweetalert2';

    const use_userStore = userStore();
    const use_scheduleStore = scheduleStore();

    const email_config = ref({
        new_email: '',
        new_email_error: '',
        confirm_email: '',
        confirm_email_error: '',
        password: '',
        password_error: ''
    });

    const password_config = ref({
        current_password: '',
        current_password_error: '',
        new_password: '',
        new_password_error: '',
        confirm_password: '',
        confirm_password_error: ''
    });

    const reset_schedule = ref({
        password: '',
        password_error: ''
    });

    const changeEmail = ()=>{
        try {
            email_config.value.new_email_error = "";
            email_config.value.confirm_email_error = '';
            email_config.value.password_error = "";
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes,Update it!'
            }).then( async (result) => {
                if (result.isConfirmed) {
                    if( email_config.value.new_email === email_config.value.confirm_email ){
                        await use_userStore.changeEmail(
                            {
                                email:email_config.value.new_email,
                                password: email_config.value.password
                            }
                        );

                        const response = use_userStore.getState;
                        if(response.status){
                            email_config.value.new_email = '';
                            email_config.value.confirm_email ='';
                            email_config.value.password ='';
                            Swal.fire("Success",response.msg,'success');
                        }
                        else{
                            if(response.error == "email"){
                                email_config.value.new_email_error = response.msg;
                            }

                            if(response.error == "password"){
                                email_config.value.password_error = response.msg;
                            }
                        }
                    }
                    else{
                        email_config.value.confirm_email_error = "Email not matched!";
                    }
                }
            })
        } catch (error) {
            console.error(error);
        }
    }

    const changePassword = ()=>{
        try {
            password_config.value.current_password_error = '';
            password_config.value.new_password_error = '';
            password_config.value.confirm_password_error = '';

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes,Update it!'
                }).then( async (result) => {
                if (result.isConfirmed) {
                    if(password_config.value.confirm_password === password_config.value.new_password ){
                        await use_userStore.changePassword(
                            { 
                                new_password: password_config.value.new_password, 
                                current_password: password_config.value.current_password, 
                            }
                        )

                        const response = use_userStore.getState;
                        if(response.status){
                            password_config.value.current_password = "";
                            password_config.value.new_password = "";
                            password_config.value.confirm_password = "";
                            Swal.fire('Success',response.msg,'success');
                        }
                        else{
                            if(response.error == "password"){
                                password_config.value.current_password_error = response.msg;
                            }

                            if(response.error == "new_password"){
                                password_config.value.new_password_error = response.msg;
                            }
                        }
                    }
                    else{
                        password_config.value.confirm_password_error = "Password not matched!";
                    }
                }
            })

        } catch (error) {
            console.error(error);
        }
    }

    const resetSchedule = () =>{
        try {
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
                    
                    await use_scheduleStore.resetSchedule(reset_schedule.value.password);
                    const response = use_scheduleStore.getResponse;

                    console.log(response)
                    if(response.status){
                        Swal.fire("Success",response.msg,"success");
                    }
                    else{
                        if(response.error == "password"){
                            reset_schedule.value.password_error = response.msg;
                        }
                    }
                }
            })
        } catch (error) {
            console.error(error);
        }
    }

</script>