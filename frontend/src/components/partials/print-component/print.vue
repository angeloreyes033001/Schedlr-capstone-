<template>
    <section class=" t-h-screen t-w-screen t-bg-white sm:t-bg-orange-200 md:t-bg-white
     md:print:t-p-[20px]
    lg:t-bg-purple-300 lg:t-pl-[180px] lg:t-pr-[180px] lg:t-pt-[80px] lg:t-pb-[80px]" >
        <div class="pape t-shadow print:t-shadow-none t-p-5 print:t-p-0" >
            <div class=" t-h-[100px] t-grid t-grid-cols-[120px,1fr] " >
                <div class="t-h-full t-flex t-items-center t-justify-center" > 
                    <img src="../../../assets/images/icon.jpg" class="t-h-[80px] t-rounded-[50%]"  >
                </div>
                <div class="t-h-full t-flex t-items-center" >
                    <div>
                        <small>Republic of the Philippines</small>
                        <h4 class="t-p-o t-m-0" >NUEVA ECIJA UNIVERSITY OF SCIENCE AND TECHNOLOGY</h4>
                        <small>Cabanatua City,Nueva Ecija, Philippines</small>
                    </div>
                </div>
            </div>
            <hr>
            <h6 class="text-center t-p-0 t-m-0" >1st Semester, Academic Year 2023-2024</h6>
            <div>
                <small>--- BSIT-1A</small>
            </div>
            <div class="" >
                <table class="table" >
                    <thead>
                        <tr>
                            <th>Demo</th>
                        </tr>
                    </thead>
                    <body>
                        <tr v-for="data in 30" >
                            <td>A</td>
                        </tr>
                    </body>
                </table>
            </div>
        </div>
    </section>
</template>

<script setup>
import { ref} from 'vue';
import { useRoute } from 'vue-router';

const route = useRoute();

const id = ref(route.params.id);
const semester = ref(route.params.semester);

const printPage = () => {
print()
};

</script>