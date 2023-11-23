<template>
   <section class=" t-select-none t-h-screen t-w-screen sm:t-grid sm:t-justify-center sm:t-items-center t-bg-[url('./assets/images/login_background.png')] t-bg-center t-bg-cover t-bg-no-repeat" >
        <div class=" t-grid t-grid-cols-1  t-h-full sm:t-h-[50%] sm:t-w-[500px] md:t-w-[700px] lg:t-w-[900px] lg:t-grid-cols-2 t-rounded-lg">
            <div class="left t-hidden lg:t-block t-relative">
                <span class="t-bg-logoYellow t-absolute t-bottom-0 t-w-full t-h-full t-rounded-tl-lg" ></span>
                <span class="t-bg-logoOrange t-absolute t-bottom-0 t-w-full t-h-[93%]" ></span>
                <span class="t-bg-logoBlue t-absolute t-bottom-0 t-w-full t-h-[86%] t-justify-center t-items-center t-flex" ></span>
            </div>
            <div class="right t-grid t-justify-center t-items-center t-relative t-bg-white t-rounded-tr-lg t-rounded-br-lg">
                <form @submit.prevent="loginAccount" class=" t-w-full t-p-5  " >
                    <div class=" t-justify-center t-items-center t-grid">
                        <img class="t-rounded-full t-w-[120px]" src="../assets/images/icon.jpg" alt="">
                    </div>
                    <h6 class="text-center fw-bold text-uppercase ">Administrator</h6>
                    <hr>
                    <div class="mt-3">
                        <div class="input-group">
                            <input v-model="loginData.email" type="text" class="form-control t-w-full" placeholder="Email">
                            <span class="input-group-text" id="basic-addon2">
                                <fa icon="envelope" ></fa>
                            </span>
                        </div>
                        <small class="t-text-red-400" v-if="loginData.email_error" >{{ loginData.email_error }}</small>
                    </div>
                    <div class="mt-3">
                        <div class="input-group">
                            <input v-model="loginData.password" :type="showFieldPassword" class="form-control t-w-full" placeholder="Password">
                            <span @click="showEyeFunction" class="t-cursor-pointer input-group-text" id="basic-addon2">
                                <span v-if="!showEyeComputed" ><fa icon="eye" ></fa></span>
                                <span v-else ><fa icon="eye-slash" ></fa></span>
                            </span>
                        </div>
                        <small class="t-text-red-400" v-if="loginData.password_error" >{{ loginData.password_error }}</small>
                    </div>
                    <div class="form-group mt-3">
                        <button type="submit" class="btn btn-primary t-w-full" >Login</button>
                    </div>
                </form>
            </div>
        </div>
   </section>
</template>
<script setup >
import { computed, ref } from 'vue';
import { userStore } from '../services/users';
import { useRouter } from 'vue-router';

const use_userStore = userStore();
const router = useRouter();

const loginData = ref({
    email: "",
    email_error: null,
    password: "",
    password_error: null,
});

const showEye = ref(false);
const showFieldPassword = ref('password')
const showEyeComputed = computed(()=>{
    return showEye.value;
})

const showEyeFunction = ()=>{
    if(showEye.value){
        showFieldPassword.value = "password";
        showEye.value = false;
    }
    else{
        showFieldPassword.value = "text";
        showEye.value = true;
    }
}

const loginAccount = async  ()=>{
    await use_userStore.onLogin(loginData.value);
    const response = use_userStore.getState;
    if(response.status){
        router.push('/dean/dashboard');
    }
    else{
        loginData.value.email_error = "";
        loginData.value.password_error = "";
        if(response.error == "email"){
            loginData.value.email_error = response.msg;
        }
        else{
            loginData.value.password_error = response.msg;
        }
    }

}

// const

</script>