<template>
    <div class="t-bg-[url('./assets/images/bg.png')] t-bg-cover t-h-[80px]" >
        <nav class="navbar ">
            <div class="container-fluid">
                <h1 class="fw-bolder text-capitalize text-white" >Sche<span class=" t-text-logoBlue" >dlr</span></h1>
                <span>
                    <ul class="d-flex t-gap-5 mt-3 " >
                        <li>
                            <router-link class="text-decoration-none t-text-white hover:t-text-logoBlue fw-bold t-text-[18px]" to="/" >Home</router-link>
                        </li>
                        <li>
                            <router-link class="text-decoration-none t-text-white hover:t-text-logoBlue fw-bold t-text-[18px]" to="/professor-schedule" >Professors</router-link>
                        </li>
                        <li>
                            <router-link class="text-decoration-none t-text-white hover:t-text-logoBlue fw-bold t-text-[18px]" to="/classroom-schedule" >Classroom</router-link>
                        </li>
                        <li>
                            <router-link class="text-decoration-none t-text-white hover:t-text-logoBlue fw-bold t-text-[18px]" to="/section-schedule" >Sections</router-link>
                        </li>
                        <li>
                            <router-link class="text-decoration-none t-text-white hover:t-text-logoBlue fw-bold t-text-[18px]" to="/login" >
                               Login <fa icon="sign-in"></fa>
                            </router-link>
                        </li>
                    </ul>
                </span>
            </div>
        </nav>
    </div>
    
</template>