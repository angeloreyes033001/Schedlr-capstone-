<template>
    <div class="mt-3" >
        <div class="t-bg-logoBlue t-relative t-h-[20px] t-grid t-grid-cols-8 t-text-center" >
            <div class="bg-white shadow t-h-[40px] t-w-[10px]  t-relative t-top-[-20px] " style="border-radius: 5px;" ></div>
            <div class="bg-white shadow t-h-[40px] t-w-[10px]  t-relative t-top-[-20px] " style="border-radius: 5px;" ></div>
            <div class="bg-white shadow t-h-[40px] t-w-[10px]  t-relative t-top-[-20px] " style="border-radius: 5px;" ></div>
            <div class="bg-white shadow t-h-[40px] t-w-[10px]  t-relative t-top-[-20px] " style="border-radius: 5px;" ></div>
            <div class="bg-white shadow t-h-[40px] t-w-[10px]  t-relative t-top-[-20px] " style="border-radius: 5px;" ></div>
            <div class="bg-white shadow t-h-[40px] t-w-[10px]  t-relative t-top-[-20px] " style="border-radius: 5px;" ></div>
            <div class="bg-white shadow t-h-[40px] t-w-[10px]  t-relative t-top-[-20px] " style="border-radius: 5px;" ></div>
            <div class="bg-white shadow t-h-[40px] t-w-[10px]  t-relative t-top-[-20px] " style="border-radius: 5px;" ></div>
            <div class="bg-white shadow t-h-[40px] t-w-[10px]  t-absolute t-top-[-20px]  t-right-0" style="border-radius: 5px;" ></div>
        </div>
        <div class=" t-bg-logoBlue t-grid t-grid-cols-7 t-h-[30px]" >
            <span class="d-flex align-items-end text-uppercase fw-bold text-white text-nowrap p-1 " >Time</span>
            <span class="d-flex align-items-end text-uppercase fw-bold text-white text-nowrap p-1 " >Mon</span>
            <span class="d-flex align-items-end text-uppercase fw-bold text-white text-nowrap p-1 " >Tue</span>
            <span class="d-flex align-items-end text-uppercase fw-bold text-white text-nowrap p-1 " >Wed</span>
            <span class="d-flex align-items-end text-uppercase fw-bold text-white text-nowrap p-1 " >Thu</span>
            <span class="d-flex align-items-end text-uppercase fw-bold text-white text-nowrap p-1 " >Fri</span>
            <span class="d-flex align-items-end text-uppercase fw-bold text-white text-nowrap p-1 " >Sat</span>
        </div>
        <div class="t-border-blue-500" >
            <div class="t-grid t-grid-cols-7  shadow"  v-for="(professor,index) in schedule" :key="index" :class="{'bg-white': index % 2 === 0, 't-bg-slate-100': index % 2 !== 0}" >
                <span class="text-uppercase fw-thin text-nowrap p-1 t-h-[30px] t-text-[#6D6F88]" >{{ professor.time }}</span>
                <span class=" t-relative text-uppercase fw-thin text-nowrap t-h-[30px] t-text-[#6D6F88]" >
                    <div class="bg-danger t-h-full t-w-full" v-if="professor.mon == 1" ></div>
                    <div class="t-h-full t-w-full" v-else-if="professor.mon == 0" ></div>
                    <div class="t-h-full t-w-full text-center d-flex justify-content-center align-items-center" v-else >
                        <div class=" t-w-full t-h-full bg-info mt-3"  v-if="index === 0 || professor.mon.id  !== schedule[index - 1].mon.id" >
                            <div>
                                {{ professor.mon.professor }}
                            </div>
                        </div>
                        <div class=" t-w-full t-h-full bg-info" v-else >
                            <div  v-if=" index >= 3  &&  professor.mon.id === schedule[index - 3].mon.id " >
                                
                             </div>
                            <div  v-else-if=" index >= 2 &&  professor.mon.id === schedule[index - 2].mon.id " >
                                {{ professor.mon.section }}
                             </div>
                            <div  v-else-if=" index >= 1 &&  professor.mon.id === schedule[index - 1].mon.id " >
                                {{ professor.mon.subject }}
                            </div>
                        </div>
                    </div>
                </span>
                <span class=" t-relative text-uppercase fw-thin text-nowrap t-h-[30px] t-text-[#6D6F88]" >
                    <div class="bg-danger t-h-full t-w-full" v-if="professor.tue == 1" ></div>
                    <div class="t-h-full t-w-full" v-else-if="professor.tue == 0" ></div>
                    <div class="t-h-full t-w-full text-center d-flex justify-content-center align-items-center" v-else >
                        <div class=" t-w-full t-h-full bg-info mt-3"  v-if="index === 0 || professor.tue.id  !== schedule[index - 1].tue.id" >
                            <div>
                                {{ professor.tue.professor }}
                            </div>
                        </div>
                        <div class=" t-w-full t-h-full bg-info" v-else >
                            <div  v-if=" index >= 3  &&  professor.tue.id === schedule[index - 3].tue.id " >
                                
                             </div>
                            <div  v-else-if=" index >= 2 &&  professor.tue.id === schedule[index - 2].tue.id " >
                                {{ professor.tue.section }}
                             </div>
                            <div  v-else-if=" index >= 1 &&  professor.tue.id === schedule[index - 1].tue.id " >
                                {{professor.tue.subject}}
                            </div>
                        </div>
                    </div>
                </span>
                <span class=" t-relative text-uppercase fw-thin text-nowrap t-h-[30px] t-text-[#6D6F88]" >
                    <div class="bg-danger t-h-full t-w-full" v-if="professor.wed == 1" ></div>
                    <div class="t-h-full t-w-full" v-else-if="professor.wed == 0" ></div>
                    <div class="t-h-full t-w-full text-center d-flex justify-content-center align-items-center" v-else >
                        <div class=" t-w-full t-h-full bg-info mt-3"  v-if="index === 0 || professor.wed.id  !== schedule[index - 1].wed.id" >
                            <div>
                                {{ professor.wed.professor }}
                            </div>
                        </div>
                        <div class=" t-w-full t-h-full bg-info" v-else >
                            <div  v-if=" index >= 3  &&  professor.wed.id === schedule[index - 3].wed.id " >
                                
                             </div>
                            <div  v-else-if=" index >= 2 &&  professor.wed.id === schedule[index - 2].wed.id " >
                                {{ professor.wed.section }}
                             </div>
                            <div  v-else-if=" index >= 1 &&  professor.wed.id === schedule[index - 1].wed.id " >
                                {{ professor.wed.subject }}
                            </div>
                        </div>
                    </div>
                </span>
                <span class=" t-relative text-uppercase fw-thin text-nowrap t-h-[30px] t-text-[#6D6F88]" >
                    <div class="bg-danger t-h-full t-w-full" v-if="professor.thu == 1" ></div>
                    <div class="t-h-full t-w-full" v-else-if="professor.thu == 0" ></div>
                    <div class="t-h-full t-w-full text-center d-flex justify-content-center align-items-center" v-else >
                        <div class=" t-w-full t-h-full bg-info mt-3"  v-if="index === 0 || professor.thu.id  !== schedule[index - 1].thu.id" >
                            <div>
                                {{ professor.thu.professor }}
                            </div>
                        </div>
                        <div class=" t-w-full t-h-full bg-info" v-else >
                            <div  v-if=" index >= 3  &&  professor.thu.id === schedule[index - 3].thu.id " >
                                
                             </div>
                            <div  v-else-if=" index >= 2 &&  professor.thu.id === schedule[index - 2].thu.id " >
                                {{ professor.thu.section }}
                             </div>
                            <div  v-else-if=" index >= 1 &&  professor.thu.id === schedule[index - 1].thu.id " >
                                {{ professor.thu.subject }}
                            </div>
                        </div>
                    </div>
                </span>
                <span class=" t-relative text-uppercase fw-thin text-nowrap t-h-[30px] t-text-[#6D6F88]" >
                    <div class="bg-danger t-h-full t-w-full" v-if="professor.fri == 1" ></div>
                    <div class="t-h-full t-w-full" v-else-if="professor.fri == 0" ></div>
                    <div class="t-h-full t-w-full text-center d-flex justify-content-center align-items-center" v-else >
                        <div class=" t-w-full t-h-full bg-info mt-3"  v-if="index === 0 || professor.fri.id  !== schedule[index - 1].fri.id" >
                            <div>
                                {{ professor.fri.professor }}
                            </div>
                        </div>
                        <div class=" t-w-full t-h-full bg-info" v-else >
                            <div  v-if=" index >= 3  &&  professor.fri.id === schedule[index - 3].fri.id " >
                                
                             </div>
                            <div  v-else-if=" index >= 2 &&  professor.fri.id === schedule[index - 2].fri.id " >
                                {{ professor.fri.section }}
                             </div>
                            <div  v-else-if=" index >= 1 &&  professor.fri.id === schedule[index - 1].fri.id " >
                                {{ professor.fri.subject }}
                            </div>
                        </div>
                    </div>
                </span>
                <span class=" t-relative text-uppercase fw-thin text-nowrap t-h-[30px] t-text-[#6D6F88]" >
                    <div class="bg-danger t-h-full t-w-full" v-if="professor.sat == 1" ></div>
                    <div class="t-h-full t-w-full" v-else-if="professor.sat == 0" ></div>
                    <div class="t-h-full t-w-full text-center d-flex justify-content-center align-items-center" v-else >
                        <div class=" t-w-full t-h-full bg-info mt-3"  v-if="index === 0 || professor.sat.id  !== schedule[index - 1].sat.id" >
                            <div>
                                {{ professor.sat.professor }}
                            </div>
                        </div>
                        <div class=" t-w-full t-h-full bg-info" v-else >
                            <div  v-if=" index >= 3  &&  professor.sat.id === schedule[index - 3].sat.id " >
                                
                             </div>
                            <div  v-else-if=" index >= 2 &&  professor.sat.id === schedule[index - 2].sat.id " >
                                {{ professor.sat.section }}
                             </div>
                            <div  v-else-if=" index >= 1 &&  professor.sat.id === schedule[index - 1].sat.id " >
                                {{ professor.sat.subject }}
                            </div>
                        </div>
                    </div>
                </span>
            </div>
        </div>
    </div>
</template>
<script setup >
    const props = defineProps({
    schedule: {
        type: Array,
        required: true,
    },
})
</script>