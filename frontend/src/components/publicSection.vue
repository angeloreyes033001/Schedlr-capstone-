<!-- <template>
    <section class="wrapper t-select-none t-h-screen t-relative t-overflow-hidden t-bg-slate-100" >
        <navbar :isColor="true" ></navbar>
        <div class="t-absolute t-h-full t-w-full t-overflow-hidden t-pt-[60px] t-pb-[60px]" >
            <div class="container t-h-full t-grid t-grid-rows-[80px,1fr,50px]" >
                <div class="" >
                    <h5 class="t t-font-extrabold text-uppercase" >Section Schedule</h5>
                    <div class="t-flex t-justify-between" >
                        <div class="form-group">
                            <select  @change="changeDepartment" v-model="departments" class="form-select text-uppercase" >
                                <option class="text-uppercase" value="">Select Department</option>
                                <option class="text-uppercase" v-for="department in globalDepartmentData" :value="department.department" >{{ department.department }}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <input v-model="isSearch" type="text" class="form-control" placeholder="Search Section" >
                        </div>
                    </div>
                </div>
                <div class=" t-h-full t-w-full t-overflow-auto" >
                    <div class="col t-h-full t-text-center " >
                        <div v-if="sectionDatas.length > 0"  v-for="section in computedSection" @click="showSchedule(section.id)" data-bs-toggle="modal" data-bs-target="#sectionModal" class="t-inline-block t-w-[290px] t-h-[80px] t-bg-white m-3 t-rounded-[10px] hover:t-shadow hover:t-rotate-2 t-p-[10px]" >
                            <div class="t-grid t-grid-cols-[80px,1fr] t-h-full" >
                                <div class="t-flex t-justify-center t-items-center t-h-full" >
                                    <fa class="t-text-[30px]" icon="chalkboard-user" ></fa>
                                </div>
                                <div class="t-flex t-items-center t-h-full t-overflow-hidden" >
                                    <div class="t-overflow-hidden t-text-left" >
                                        <h6 class="m-0 p-0  t-truncate t-uppercase" >{{ section.section }}</h6>
                                        <small class="m-0 p-0 t-truncate t-capitalize" >{{ section.yearlevel }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-else-if="departments === ''" class="t-h-full t-flex t-justify-center t-items-center" >
                            <div>
                                <div class="t-flex t-justify-center t-items-center" >
                                    <img src="../assets/images/table.png" class="t-h-[200px]" alt="">
                                </div>
                                <h6 class="text-center" >Please select department first!</h6>
                            </div>
                        </div>
                        <div  v-else class="t-h-full t-flex t-justify-center t-items-center" >
                            <div>
                                <div class="t-flex t-justify-center t-items-center" >
                                    <img src="../assets/images/table.png" class="t-h-[200px]" alt="">
                                </div>
                                <h6 class="text-center" >No section record</h6>
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="t-flex t-justify-end t-pt-[10px]" >
                    <div class="t-flex t-gap-2" >
                        <div  class="" >
                            <button @click="previousPage" :disabled="currentPage === 1 || isSearch != ''" class="btn btn-outline-primary" >
                                <fa icon="arrow-left" ></fa>
                            </button>
                        </div>
                        <div class="t-h-[40px] t-w-[40px] t-bg-slate-200 t-rounded-[50%] t-flex t-justify-center t-items-center" >
                            {{ pageCountNow}}/{{ totalpage }}
                        </div>
                        <div  class="" >
                            <button @click="nextPage()" :disabled="currentPage * itemPerPage > sectionDatas.length || isSearch != '' " class="btn btn-outline-primary" >
                                <fa icon="arrow-right" ></fa>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <div class="modal fade" id="sectionModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="sectionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Section</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="t-flex t-justify-between t-gap-2 t-relative" >
                    <div class="form-group">
                        <button @click="printNow" data-bs-dismiss="modal" class="btn btn-primary t-truncate" >
                            <fa icon="print" ></fa>
                            &nbsp;
                            <span class="" >Print Schedule</span>
                        </button>
                    </div>
                    <div class="form-group">
                        <select  v-model="schedules.semester" @change="changeSemester" name="" class="form-select text-capitalize">
                            <option value="1st semester" selected class="text-capitalize" >1st semester</option>
                            <option value="2nd semester" class="text-capitalize" >2nd semester</option>
                        </select>
                    </div>
                </div>
                <div>
                    <schedule colors="green" :schedules="scheduleSection" ></schedule>
                </div>
            </div>
            </div>
        </div>
    </div>
    <professorPrint :schedules="scheduleDatas" ></professorPrint>
</template>
<script setup >
    import navbar from './navbar.vue';
    import schedule from './partials/schedule-component/singleSchedule.vue';
    import professorPrint from './printTemplate/single.vue';
    
    import { departmentStore } from '../services/departments';
    import { publicStore } from '../services/public';
    import { onMounted, ref, computed } from 'vue';

    const use_departmentStore = departmentStore();
    const use_publicStore = publicStore();

    const  globalDepartmentData = ref([]);
    const globalFetchDepartments = async ()=>{
        await use_departmentStore.read();
        const response = use_departmentStore.getDepartments;
        globalDepartmentData.value = response;
    }
    onMounted(()=>{
        globalFetchDepartments();
    })

    const departments = ref('');
    const isSearch = ref('');

    const schedules = ref([{
        section: '',
        semester: "1st semester",
    }])

    const currentPage = ref(1);
    const itemPerPage = ref(28);//28

    const pageCountNow = ref(0);
    const totalpage = ref(0);

    const changeDepartment = ()=>{
        schedules.value.semester = "1st semester";
        readSection();
    }

    const sectionDatas = ref([])
    const readSection = async()=>{
        try{
            await use_publicStore.readSections(departments.value);
            sectionDatas.value = use_publicStore.getResponse;
        }
        catch(error){
            console.error(error);
        }
    }

    const computedSection = computed(()=>{
        const c = Math.ceil(sectionDatas.value.length / itemPerPage.value);

        if(sectionDatas.value.length >  itemPerPage.value){
            totalpage.value = c;
        }
        else if(sectionDatas.value.length > 1){
            totalpage.value = 1;
        }
        else{
            totalpage.value = 0;
        }

        pageCountNow.value = sectionDatas.value.length > 0 ? pageCountNow.value = currentPage : pageCountNow.value = 0;

        if(isSearch.value != ""){
            return sectionDatas.value.filter(section=>{
                return section.section.toLowerCase().includes(isSearch.value.toLowerCase());
            })
        }
        else{
            const startIndex = (currentPage.value - 1) * itemPerPage.value;
            const endIndex = startIndex + itemPerPage.value;
            return sectionDatas.value.slice(startIndex,endIndex);
        }
    });

    const nextPage = ()=>{
        const allpage = Math.ceil(sectionDatas.value.length / itemPerPage.value)
        if(currentPage.value < allpage){
            currentPage.value++;
        }
    }

    const previousPage = ()=>{
        if(currentPage.value > 1){
            currentPage.value--;
        }
    }

    const scheduleSection = ref([])
    const readSchedule = async (data)=>{
        try {
            await use_publicStore.readSectionSchedule(data);
            scheduleSection.value = use_publicStore.getSchedule;
            console.log(use_publicStore.getSchedule)
            
        } catch (error) {
            console.error(error)
        }
    }

    const changeSemester = ()=>{
        readSchedule({section: schedules.value.section, semester: schedules.value.semester });
        readprintSchedule({section: schedules.value.section, semester: schedules.value.semester });
    }

    const showSchedule = (section)=>{
        schedules.value.section = section;
        readSchedule({section: schedules.value.section, semester: schedules.value.semester });
        readprintSchedule({section: schedules.value.section, semester: schedules.value.semester });
    }

    const scheduleDatas = ref([]);
    const readprintSchedule = async (data)=>{
        try {
            await use_publicStore.readprintSection(data);
            scheduleDatas.value = use_publicStore.getDataPrint;
            
        } catch (error) {
            console.error(error)
        }
    }

    const printNow = ()=>{
    setTimeout(()=>{
        print();
    },500);
}

</script> -->
<template>
    public section
</template>