<template>
    <table class="table" style="border-spacing: 0;border-collapse: collapse" >
        <thead>
            <tr>
                <th class="bg-primary text-white t-w-[14.28%] " >Time</th>
                <th class="bg-primary text-white t-w-[14.28%] " >Mon</th>
                <th class="bg-primary text-white t-w-[14.28%] " >Tue</th>
                <th class="bg-primary text-white t-w-[14.28%] " >Wed</th>
                <th class="bg-primary text-white t-w-[14.28%] " >Thu</th>
                <th class="bg-primary text-white t-w-[14.28%] " >Fri</th>
                <th class="bg-primary text-white t-w-[14.28%] " >Sat</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(schedule,index) in schedules" > 
                <td class="" >{{ schedule.time }}</td>
                <td class="t-relative" >
                    <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0 bg-danger" v-if="schedule.monday === 1" ></div>
                    <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0" v-else-if="schedule.monday === 0" ></div>
                    <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0 bg-success" v-else >
                        <div class="t-w-full t-h-full"   v-if="index === 0 || schedule.monday.id  !== schedules[index - 1].monday.id" >
                            <div class=" t-h-full t-w-full t-relative t-flex t-items-center t-justify-center" >
                                <h6 class="t-p-1 t-text-white t-truncate text-uppercase t-text-center t-text-[12px] " :title="schedule.monday.subject" >{{ schedule.monday.professor }}</h6>
                            </div>
                        </div>
                        <div class="t-h-full t-w-full p-1" >
                            <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0 bg-danger"  v-if="schedule.monday === 1" >
                                <br>
                            </div>
                            <div class="t-h-full t-w-full" v-else-if="schedule.monday === 0" >
                                <br>
                            </div>
                            <div class=" t-h-full t-w-full t-relative t-flex t-items-center t-justify-center " v-else >
                                <div class=" t-h-full t-w-full"  v-if=" index >= 3  &&  schedule.monday.id === schedules[index - 3].monday.id &&  schedule.monday.professor === schedules[index - 1].monday.professor " >
                                    <br>
                                </div>
                                <div  class="t-w-full t-h-full  t-flex t-items-center t-justify-center" v-else-if=" index >= 2 &&  schedule.monday.id === schedules[index - 2].monday.id &&  schedule.monday.professor === schedules[index - 1].monday.professor " >
                                    <h6 class=" t-text-white t-truncate text-uppercase t-text-center t-text-[12px]" :title="schedule.monday.section+'-'+schedule.monday.classroom" >{{schedule.monday.section}} , {{ schedule.monday.classroom }}</h6>
                                </div>
                                <div class=" t-h-full t-w-full t-relative t-flex t-items-center t-justify-center" v-else-if=" index >= 1 &&  schedule.monday.id === schedules[index - 1].monday.id &&  schedule.monday.professor === schedules[index - 1].monday.professor " >
                                    <h6 class=" t-text-white t-truncate text-uppercase t-text-center t-text-[12px]" :title="schedule.monday.subject" >{{ schedule.monday.subject }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
                <td class="t-relative" >
                    <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0 bg-danger" v-if="schedule.tuesday === 1" ></div>
                    <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0" v-else-if="schedule.tuesday === 0" ></div>
                    <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0 bg-success" v-else >
                        <div class="t-w-full t-h-full"   v-if="index === 0 || schedule.tuesday.id  !== schedules[index - 1].tuesday.id" >
                            <div class=" t-h-full t-w-full t-relative t-flex t-items-center t-justify-center" >
                                <h6 class="t-p-1 t-text-white t-truncate text-uppercase t-text-center t-text-[12px] " :title="schedule.tuesday.subject" >{{ schedule.tuesday.professor }}</h6>
                            </div>
                        </div>
                        <div class="t-h-full t-w-full p-1" >
                            <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0 bg-danger"  v-if="schedule.tuesday === 1" >
                                <br>
                            </div>
                            <div class="t-h-full t-w-full" v-else-if="schedule.tuesday === 0" >
                                <br>
                            </div>
                            <div class=" t-h-full t-w-full t-relative t-flex t-items-center t-justify-center " v-else >
                                <div class=" t-h-full t-w-full"  v-if=" index >= 3  &&  schedule.tuesday.id === schedules[index - 3].tuesday.id &&  schedule.tuesday.professor === schedules[index - 1].tuesday.professor " >
                                    <br>
                                </div>
                                <div  class="t-w-full t-h-full  t-flex t-items-center t-justify-center" v-else-if=" index >= 2 &&  schedule.tuesday.id === schedules[index - 2].tuesday.id &&  schedule.tuesday.professor === schedules[index - 1].tuesday.professor " >
                                    <h6 class=" t-text-white t-truncate text-uppercase t-text-center t-text-[12px]" :title="schedule.tuesday.section+'-'+schedule.tuesday.classroom" >{{schedule.tuesday.section}} , {{ schedule.tuesday.classroom }}</h6>
                                </div>
                                <div class=" t-h-full t-w-full t-relative t-flex t-items-center t-justify-center" v-else-if=" index >= 1 &&  schedule.tuesday.id === schedules[index - 1].tuesday.id &&  schedule.tuesday.professor === schedules[index - 1].tuesday.professor " >
                                    <h6 class=" t-text-white t-truncate text-uppercase t-text-center t-text-[12px]" :title="schedule.tuesday.subject" >{{ schedule.tuesday.subject }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
                <td class="t-relative" >
                    <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0 bg-danger" v-if="schedule.wednesday === 1" ></div>
                    <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0" v-else-if="schedule.wednesday === 0" ></div>
                    <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0 bg-success" v-else >
                        <div class="t-w-full t-h-full"   v-if="index === 0 || schedule.wednesday.id  !== schedules[index - 1].wednesday.id" >
                            <div class=" t-h-full t-w-full t-relative t-flex t-items-center t-justify-center" >
                                <h6 class="t-p-1 t-text-white t-truncate text-uppercase t-text-center t-text-[12px] " :title="schedule.wednesday.subject" >{{ schedule.wednesday.professor }}</h6>
                            </div>
                        </div>
                        <div class="t-h-full t-w-full p-1" >
                            <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0 bg-danger"  v-if="schedule.wednesday === 1" >
                                <br>
                            </div>
                            <div class="t-h-full t-w-full" v-else-if="schedule.wednesday === 0" >
                                <br>
                            </div>
                            <div class=" t-h-full t-w-full t-relative t-flex t-items-center t-justify-center " v-else >
                                <div class=" t-h-full t-w-full"  v-if=" index >= 3  &&  schedule.wednesday.id === schedules[index - 3].wednesday.id &&  schedule.wednesday.professor === schedules[index - 1].wednesday.professor " >
                                    <br>
                                </div>
                                <div  class="t-w-full t-h-full  t-flex t-items-center t-justify-center" v-else-if=" index >= 2 &&  schedule.wednesday.id === schedules[index - 2].wednesday.id &&  schedule.wednesday.professor === schedules[index - 1].wednesday.professor " >
                                    <h6 class=" t-text-white t-truncate text-uppercase t-text-center t-text-[12px]" :title="schedule.wednesday.section+'-'+schedule.wednesday.classroom" >{{schedule.wednesday.section}} , {{ schedule.wednesday.classroom }}</h6>
                                </div>
                                <div class=" t-h-full t-w-full t-relative t-flex t-items-center t-justify-center" v-else-if=" index >= 1 &&  schedule.wednesday.id === schedules[index - 1].wednesday.id &&  schedule.wednesday.professor === schedules[index - 1].wednesday.professor " >
                                    <h6 class=" t-text-white t-truncate text-uppercase t-text-center t-text-[12px]" :title="schedule.wednesday.subject" >{{ schedule.wednesday.subject }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
                <td class="t-relative" >
                    <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0 bg-danger" v-if="schedule.thursday === 1" ></div>
                    <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0" v-else-if="schedule.thursday === 0" ></div>
                    <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0 bg-success" v-else >
                        <div class="t-w-full t-h-full"   v-if="index === 0 || schedule.thursday.id  !== schedules[index - 1].thursday.id" >
                            <div class=" t-h-full t-w-full t-relative t-flex t-items-center t-justify-center" >
                                <h6 class="t-p-1 t-text-white t-truncate text-uppercase t-text-center t-text-[12px] " :title="schedule.thursday.subject" >{{ schedule.thursday.professor }}</h6>
                            </div>
                        </div>
                        <div class="t-h-full t-w-full p-1" >
                            <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0 bg-danger"  v-if="schedule.thursday === 1" >
                                <br>
                            </div>
                            <div class="t-h-full t-w-full" v-else-if="schedule.thursday === 0" >
                                <br>
                            </div>
                            <div class=" t-h-full t-w-full t-relative t-flex t-items-center t-justify-center " v-else >
                                <div class=" t-h-full t-w-full"  v-if=" index >= 3  &&  schedule.thursday.id === schedules[index - 3].thursday.id &&  schedule.thursday.professor === schedules[index - 1].thursday.professor " >
                                    <br>
                                </div>
                                <div  class="t-w-full t-h-full  t-flex t-items-center t-justify-center" v-else-if=" index >= 2 &&  schedule.thursday.id === schedules[index - 2].thursday.id &&  schedule.thursday.professor === schedules[index - 1].thursday.professor " >
                                    <h6 class=" t-text-white t-truncate text-uppercase t-text-center t-text-[12px]" :title="schedule.thursday.section+'-'+schedule.thursday.classroom" >{{schedule.thursday.section}} , {{ schedule.thursday.classroom }}</h6>
                                </div>
                                <div class=" t-h-full t-w-full t-relative t-flex t-items-center t-justify-center" v-else-if=" index >= 1 &&  schedule.thursday.id === schedules[index - 1].thursday.id &&  schedule.thursday.professor === schedules[index - 1].thursday.professor " >
                                    <h6 class=" t-text-white t-truncate text-uppercase t-text-center t-text-[12px]" :title="schedule.thursday.subject" >{{ schedule.thursday.subject }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
                <td class="t-relative" >
                    <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0 bg-danger" v-if="schedule.friday === 1" ></div>
                    <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0" v-else-if="schedule.friday === 0" ></div>
                    <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0 bg-success" v-else >
                        <div class="t-w-full t-h-full"   v-if="index === 0 || schedule.friday.id  !== schedules[index - 1].friday.id" >
                            <div class=" t-h-full t-w-full t-relative t-flex t-items-center t-justify-center" >
                                <h6 class="t-p-1 t-text-white t-truncate text-uppercase t-text-center t-text-[12px] " :title="schedule.friday.subject" >{{ schedule.friday.professor }}</h6>
                            </div>
                        </div>
                        <div class="t-h-full t-w-full p-1" >
                            <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0 bg-danger"  v-if="schedule.friday === 1" >
                                <br>
                            </div>
                            <div class="t-h-full t-w-full" v-else-if="schedule.friday === 0" >
                                <br>
                            </div>
                            <div class=" t-h-full t-w-full t-relative t-flex t-items-center t-justify-center " v-else >
                                <div class=" t-h-full t-w-full"  v-if=" index >= 3  &&  schedule.friday.id === schedules[index - 3].friday.id &&  schedule.friday.professor === schedules[index - 1].friday.professor " >
                                    <br>
                                </div>
                                <div  class="t-w-full t-h-full  t-flex t-items-center t-justify-center" v-else-if=" index >= 2 &&  schedule.friday.id === schedules[index - 2].friday.id &&  schedule.friday.professor === schedules[index - 1].friday.professor " >
                                    <h6 class=" t-text-white t-truncate text-uppercase t-text-center t-text-[12px]" :title="schedule.friday.section+'-'+schedule.friday.classroom" >{{schedule.friday.section}} , {{ schedule.friday.classroom }}</h6>
                                </div>
                                <div class=" t-h-full t-w-full t-relative t-flex t-items-center t-justify-center" v-else-if=" index >= 1 &&  schedule.friday.id === schedules[index - 1].friday.id &&  schedule.friday.professor === schedules[index - 1].friday.professor " >
                                    <h6 class=" t-text-white t-truncate text-uppercase t-text-center t-text-[12px]" :title="schedule.friday.subject" >{{ schedule.friday.subject }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
                <td class="t-relative" >
                    <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0 bg-danger" v-if="schedule.saturday === 1" ></div>
                    <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0" v-else-if="schedule.saturday === 0" ></div>
                    <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0 bg-success" v-else >
                        <div class="t-w-full t-h-full"   v-if="index === 0 || schedule.saturday.id  !== schedules[index - 1].saturday.id" >
                            <div class=" t-h-full t-w-full t-relative t-flex t-items-center t-justify-center" >
                                <h6 class="t-p-1 t-text-white t-truncate text-uppercase t-text-center t-text-[12px] " :title="schedule.saturday.subject" >{{ schedule.saturday.professor }}</h6>
                            </div>
                        </div>
                        <div class="t-h-full t-w-full p-1" >
                            <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0 bg-danger"  v-if="schedule.saturday === 1" >
                                <br>
                            </div>
                            <div class="t-h-full t-w-full" v-else-if="schedule.saturday === 0" >
                                <br>
                            </div>
                            <div class=" t-h-full t-w-full t-relative t-flex t-items-center t-justify-center " v-else >
                                <div class=" t-h-full t-w-full"  v-if=" index >= 3  &&  schedule.saturday.id === schedules[index - 3].saturday.id &&  schedule.saturday.professor === schedules[index - 1].saturday.professor " >
                                    <br>
                                </div>
                                <div  class="t-w-full t-h-full  t-flex t-items-center t-justify-center" v-else-if=" index >= 2 &&  schedule.saturday.id === schedules[index - 2].saturday.id &&  schedule.saturday.professor === schedules[index - 1].saturday.professor " >
                                    <h6 class=" t-text-white t-truncate text-uppercase t-text-center t-text-[12px]" :title="schedule.saturday.section+'-'+schedule.saturday.classroom" >{{schedule.saturday.section}} , {{ schedule.saturday.classroom }}</h6>
                                </div>
                                <div class=" t-h-full t-w-full t-relative t-flex t-items-center t-justify-center" v-else-if=" index >= 1 &&  schedule.saturday.id === schedules[index - 1].saturday.id &&  schedule.saturday.professor === schedules[index - 1].saturday.professor " >
                                    <h6 class=" t-text-white t-truncate text-uppercase t-text-center t-text-[12px]" :title="schedule.saturday.subject" >{{ schedule.saturday.subject }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</template>
<script setup >
// import { ref } from 'vue';
    const props = defineProps({
        schedules: {
            type: Array,
            required: true,
        }
    });
</script>