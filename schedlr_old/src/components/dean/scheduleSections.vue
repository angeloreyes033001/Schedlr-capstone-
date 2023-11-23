<template  >
    <div class="p-2" >
        <div class="t-flex t-justify-end t-gap-2" >
            <select @change="updateSemester" v-model="semester" name="" id="" class="w-25 form-select t-inline-block text-capitalize" >
                <option class="text-capitalize" value="1st semester">1st Semester</option>
                <option class="text-capitalize" value="2nd semester">2nd Semester</option>
            </select>
            <select v-model="yearlevelID" name="" id="" class="w-25 form-select t-inline-block text-capitalize" >
                <option class="text-capitalize" value="">All Level</option>
                <option class="text-capitalize" v-for="yearlevel in yearlevelData" :value="yearlevel.year">{{ yearlevel.yearName }}</option>
            </select>
        </div>
        <div v-if="schedules.length > 0" >
            <div class="bg-white shadow p-3 mt-3" v-for="section in computedSchedules" >
                <div class="" >
                    <h6 class="text-capitalize" >Section: <span class="text-uppercase" >{{ section.owner }}</span> </h6>
                    <h6 class="text-capitalize" >Yearlevel: {{ section.yearlevel }}</h6>
                </div>
                <schedule :schedules="section.schedule" ></schedule>
            </div>
        </div>
        <div class="d-flex t-justify-center t-items-center t-h-full t-w-full p-5"  v-else>
            <div>
                <div class="t-flex t-justify-center t-items-center" >
                    <img src="../../assets/images/table.png" class="t-h-[200px]" alt="">
                </div>
                <h6 class="text-center" >No Section record</h6>
            </div>
        </div>
    </div>
</template>
<script setup >
    import {computed, ref,onMounted,inject} from 'vue'
    import schedule from '../partials/schedule-component/singleSchedule.vue'
    import {publicStore} from '../../services/public';
    const use_publicStore = publicStore();

    const globalYearlevelData = inject('globalYearLevelData');
    const yearlevelData = ref(globalYearlevelData);

    const semester = ref('1st semester');
    const yearlevelID = ref('');

    const computedSchedules = computed(()=>{
        if(yearlevelID.value != ""){
            return schedules.value.filter(section=>{
                return section.yearlevelID == yearlevelID.value;
            })
        }
        else{
            return schedules.value;
        }
    })

    const schedules = ref([])

    const updateSemester = ()=>{
        readSectionSchedule()
    }

    const readSectionSchedule = async () =>{
        try {
            await use_publicStore.readDeanSectionScheudle(semester.value);
            schedules.value = use_publicStore.getSchedule;
        } catch (error) {
            console.error(error);
        }
    }

    onMounted(()=>{
        readSectionSchedule();
    })


</script>