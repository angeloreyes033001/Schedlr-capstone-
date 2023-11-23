<template  >
    <div class="p-2" >
        <div class="t-flex t-justify-end t-gap-2" >
            <select v-model="semester" @change="updateSemester"  class="form-select w-25" >
                    <option value="1st semester">1st Semester</option>
                    <option value="2nd semester">2nd Semester</option>
                </select>
                <input v-model="isSearch" type="text" placeholder="Search Professor" class="form-control w-25" >
        </div>
        <div v-if="schedules.length > 0" >
            <div class="bg-white shadow p-3 mt-3" v-for="professor in computedSchedules" >
            <div class="" >
                <h6 class="text-capitalize" >Fullname: {{ professor.owner }}</h6>
                <h6 class="text-capitalize" >Rank: {{ professor.rank }}</h6>
                <h6 class="text-capitalize" >Designated: {{ professor.designated }}</h6>
            </div>
            <schedule :schedules="professor.schedule" ></schedule>
        </div>
        </div>
        <div class="d-flex t-justify-center t-items-center t-h-full t-w-full p-5"  v-else>
            <div>
                <div class="t-flex t-justify-center t-items-center" >
                    <img src="../../assets/images/table.png" class="t-h-[200px]" alt="">
                </div>
                <h6 class="text-center" >No Professor record</h6>
            </div>
        </div>
    </div>
</template>
<script setup >
    import {computed, onMounted, ref} from 'vue'
    import schedule from '../partials/schedule-component/singleSchedule.vue'
    import {publicStore} from '../../services/public';
    const use_publicStore = publicStore();

    const isSearch = ref('');
    const semester = ref('1st semester');

    const computedSchedules = computed(()=>{
        if(isSearch.value != ""){
            return schedules.value.filter(professor=>{
                return professor.owner.toLowerCase().includes(isSearch.value.toLowerCase());
            })
        }
        else{
            return schedules.value;
        }
    })

    const schedules = ref([]);


    const updateSemester = ()=>{
        readProfessorSchedule()
    }

    const readProfessorSchedule = async () =>{
        try {
            await use_publicStore.readDeanProfessorScheudle(semester.value);
            schedules.value = use_publicStore.getSchedule;
        } catch (error) {
            console.error(error);
        }
    }

    onMounted(()=>{
        readProfessorSchedule();
    })

</script>