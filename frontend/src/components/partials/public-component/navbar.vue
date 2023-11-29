<template >
    <nav class="t-absolute p-2 t-h-[50px] t-w-full t-z-10 t-flex t-justify-between" :class="{'t-bg-transparent': isColor == false, 't-bg-logoBlue': isColor == true}"  >
        <h4 class="t-font-extrabold text-white " >Sche<strong class="t-text-logoYellow" >dlr</strong> </h4>
        <div >
            <span>
                <fa @click="navbarFunction" icon="bars" class="h3 mt-2 text-white t-cursor-pointer t-flex md:t-hidden"  ></fa>
                <div v-show="navbarTrigger" class="t-p-[20px t-absolute t-h-screen t-bg-slate-200 t-top-0 t-left-0 t-w-screen md:t-p-0 md:t-flex md:t-bg-transparent md:t-h-auto md:t-w-auto md:t-relative md:t-gap-2" >
                    <div class="t-flex t-justify-end">
                        <h5 @click="navbarFunction" class="mt-3 t-mr-[10px] t-cursor-pointer t-flex text-danger md:t-hidden" >X</h5>
                    </div>
                    <ul class=" p-2  md:t-flex md:t-bg-transparent md:t-h-auto md:t-w-auto md:t-relative md:t-gap-5" >
                        <li v-for="nav in navigators" class="t-h-[50px]" >
                            <router-link :to="nav.link" class="text-white hover:t-text-logoBlue text-decoration-none t-text-black t-font-extralight"  >
                                <fa :icon="nav.icon" ></fa>
                                {{ nav.label }}
                            </router-link>
                        </li>
                    </ul>
                </div>
            </span>
        </div>
    </nav>
</template>
<script setup >
    import {ref, onMounted} from 'vue';
    const navbarTrigger = ref(false);
    const navbarFunction =()=>{
        navbarTrigger.value = navbarTrigger.value ? false : true;
    }

    const navigators = ref([
        {
            label: 'Home',
            link: '/',
            icon: 'home'
        },
        {
            label: 'Schedule',
            link: '/schedule',
            icon: 'calendar-days'
        },
        {
            label: 'Login',
            link: '/login',
            icon: 'sign-in'
        },
        
    ])

    const props = defineProps({
        isColor: {
            type: Boolean,
            required: true,
        },
    })

    onMounted(()=>{
        navbarTrigger.value = window.innerWidth > 760 ? true: false;
    })
</script>