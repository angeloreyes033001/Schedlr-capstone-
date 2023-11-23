<template>
    <div class="t-h-screen t-overflow-hidden" >
        <div class="t-h-full t-w-full t-overflow-auto" >
            <div class="t-h-full t-w-full t-relative" >
                <div class="t-z-10 t-absolute t-h-[0px] t-w-full" >
                    <navbar :isColor="false" ></navbar>
                </div>
                <div class="t-overflow-hidden t-absolute   bg-center t-h-full t-w-full t-top-0 t-left-0 t-bg-[url('./assets/images/login_background.png')] t-bg-cover t-bg-center t-bg-[../assets/images.login_background.png]" >
                    <!-- <div class=" t-opacity-[70%] t-absolute t-h-[900px] t-rotate-[-10deg] p-4 t-w-[5000px] t-top-[-500px] t-left-[-500px]  t-bg-logoBlue" ></div> -->
                    <div class=" t-opacity-[70%] t-absolute t-h-full p-4 t-w-full  t-bg-logoBlue" ></div>
                </div>
                <div class="t-bg-cover t-bg-center t-grid t-grid-rows-1 t-grid-cols-1 md:t-grid-rows-1 md:t-grid-cols-2 t-overflow-hidden t-absolute  t-h-full t-w-full t-top-0 t-left-0" >
                    <div class="t-h-full t-w-full t-flex t-justify-center t-items-center" >
                        <div class="p-5" >
                            <div class=" t-flex t-justify-center  md:t-hidden" >
                                <img src="../assets/images/icon.jpg" class="t-h-[150px] t-w-[150px] rounded-circle" alt="">
                            </div>
                            <h2 class="mt-3 t-text-white t-font-extrabold  lg:t-text-[60px]" >Welcome, NEUSTians</h2>
                            <p class="t-text-white t-font-light" >Online Schedule</p>
                            <router-link to="/professor-schedule" class="btn btn-warning text-white" >
                                <strong>View Schedule</strong>
                                &nbsp;
                                <fa icon="arrow-right" ></fa>
                            </router-link>
                        </div>
                    </div>
                    <div class="t-h-full t-w-full t-hidden md:t-flex t-justify-center t-items-center" >
                        <div class="t-flex t-justify-center t-items-center" >
                            <img src="../assets/images/testing.png" class="" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup >
import navbar from './navbar.vue'
</script>