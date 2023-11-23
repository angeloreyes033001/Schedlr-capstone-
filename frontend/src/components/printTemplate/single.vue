<template>
    <div class="  t-h-full t-w-full my-space t-grid print-template " >
        <!-- <div class="print-trigger text-end mb-3" >
            <button class="btn btn-primary w-25" @click="printNow" ><fa icon="print" ></fa> Print</button>
        </div> -->
        <div class="custom-shadow p-2 mt-3" v-for="data in schedules" >
            <div id="print-data" >
                <div class="t-grid t-grid-cols-[120px,1fr]" >
                    <div class="t-grid t-justify-center t-items-center " >
                        <img src="../../assets/images/icon.jpg" class="rounded-circle" alt="">
                    </div>
                    <span class="t-grid" >
                        <h6 class="p-0 m-0" >Republic of the Philippines</h6>
                        <h6 class="p-0 m-0 fw-bolder" >Nueva Ecija University of Science and Technology</h6>
                        <h6 class="p-0 m-0" >Cabanatuan City,Nueva Ecija, Philippines</h6>
                    </span>
                </div>
                <div class="bg-warning t-border-b-2 t-border-black text-center " >
                    <h6 class="text-uppercase pt-2" >{{ data.department }}</h6>
                </div>
                <div class="text-center" >
                    <small class="text-capitalize" >{{ data.semester }}</small>
                </div>
                <div class="" >
                    <small class="text-capitalize"  >data:  <b>{{ data.name }}</b></small>
                    <div class="t-overflow-auto" >
                    <div class="t-grid t-grid-cols-7 t-gap-2 t-bg-logoBlue t-h-[40px] p-2" >
                        <div class="t-overflow-hidden t-flex t-items-center" >
                            <h6 class="t-truncate t-font-semibold t-text-white " title="Time" >Time</h6>
                        </div>
                        <div class="t-overflow-hidden t-flex t-items-center" >
                            <h6 class="t-truncate t-font-semibold t-text-white" title="Monday" >Monday</h6>
                        </div>
                        <div class="t-overflow-hidden t-flex t-items-center" >
                            <h6 class="t-truncate t-font-semibold t-text-white" title="Tuesday" >Tuesday</h6>
                        </div>
                        <div class="t-overflow-hidden t-flex t-items-center" >
                            <h6 class="t-truncate t-font-semibold t-text-white" title="Wednesday" >Wednesday</h6>
                        </div>
                        <div class="t-overflow-hidden t-flex t-items-center" >
                            <h6 class="t-truncate t-font-semibold t-text-white" title="Thursday" >Thursday</h6>
                        </div>
                        <div class="t-overflow-hidden t-flex t-items-center" >
                            <h6 class="t-truncate t-font-semibold t-text-white" title="Friday" >Friday</h6>
                        </div>
                        <div class="t-overflow-hidden t-flex t-items-center" >
                            <h6 class="t-truncate t-font-semibold t-text-white" title="Saturday" >Saturday</h6>
                        </div>
                    </div>
                </div>
                <div class="" >
                    <div v-for="(schedule,index) in data.schedule" :key="index" class="t-grid t-grid-cols-7 odd:t-bg-white even:t-bg-slate-100"  >
                        <div class="t-overflow-hidden  t-w-full t-flex t-items-center" >
                            <h6 class="t-truncate t-font-extralight t-text-black" :title="schedule.time" >{{ schedule.time }}</h6>
                        </div>
                        <div class=" t-h-full t-w-full" >
                            <div class="bg-danger t-h-full t-w-full" v-if="schedule.mon === 1" ></div>
                            <div class="t-h-full t-w-full" v-else-if="schedule.mon === 0" ></div>
                            <div class="t-h-full t-w-full" v-else >
                                <div class=" t-w-full t-h-full bg-success"  v-if="index === 0 || schedule.mon.id  !== data.schedule[index - 1].mon.id" >
                                    <div>
                                        <h6 class="t-p-1 t-text-white t-truncate text-uppercase t-text-center" :title="schedule.mon.professor" >{{ schedule.mon.professor }}</h6>
                                    </div>
                                </div>
                                <div class="t-h-full t-w-full t-overflow-hidden t-flex t-items-center" >
                                    <div class="bg-danger t-h-full t-w-full" v-if="schedule.mon === 1" >
                                        <br>
                                    </div>
                                    <div class="t-h-full t-w-full" v-else-if="schedule.mon === 0" >
                                        <br>
                                    </div>
                                    <div class="bg-success t-h-full t-w-full t-relative" v-else >
                                        <div class="bg-success t-h-full t-w-full"  v-if=" index >= 3  &&  schedule.mon.id === data.schedule[index - 3].mon.id &&  schedule.mon.professor === data.schedule[index - 3].mon.professor " >
                                            <br>
                                        </div>
                                        <div  class="t-w-full t-h-full bg-success" v-else-if=" index >= 2 &&  schedule.mon.id === data.schedule[index - 2].mon.id &&  schedule.mon.professor === data.schedule[index - 2].mon.professor " >
                                            <h6 class=" t-text-white t-truncate text-uppercase t-text-center" :title="schedule.mon.section" >{{schedule.mon.section}} , {{ schedule.mon.room }}</h6>
                                        </div>
                                        <div class="bg-success t-max-h-full t-overflow-hidden" v-else-if=" index >= 1 &&  schedule.mon.id === data.schedule[index - 1].mon.id &&  schedule.mon.professor === data.schedule[index - 1].mon.professor " >
                                            <h6 class=" t-text-white t-truncate text-uppercase t-text-center" :title="schedule.mon.subject" >{{ schedule.mon.subject }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" t-h-full t-w-full" >
                            <div class="bg-danger t-h-full t-w-full" v-if="schedule.tue === 1" ></div>
                            <div class="t-h-full t-w-full" v-else-if="schedule.tue === 0" ></div>
                            <div class="t-h-full t-w-full" v-else >
                                <div class=" t-w-full t-h-full bg-success"  v-if="index === 0 || schedule.tue.id  !== data.schedule[index - 1].tue.id" >
                                    <div>
                                        <h6 class="t-p-1 t-text-white t-truncate text-uppercase t-text-center" :title="schedule.tue.professor" >{{ schedule.tue.professor }}</h6>
                                    </div>
                                </div>
                                <div class="t-h-full t-w-full t-overflow-hidden t-flex t-items-center" >
                                    <div class="bg-danger t-h-full t-w-full" v-if="schedule.tue === 1" >
                                        <br>
                                    </div>
                                    <div class="t-h-full t-w-full" v-else-if="schedule.tue === 0" >
                                        <br>
                                    </div>
                                    <div class="bg-success t-h-full t-w-full t-relative" v-else >
                                        <div class="bg-success t-h-full t-w-full"  v-if=" index >= 3  &&  schedule.tue.id === data.schedule[index - 3].tue.id &&  schedule.tue.professor === data.schedule[index - 3].tue.professor " >
                                            <br>
                                        </div>
                                        <div  class="t-w-full t-h-full bg-success" v-else-if=" index >= 2 &&  schedule.tue.id === data.schedule[index - 2].tue.id &&  schedule.tue.professor === data.schedule[index - 2].tue.professor " >
                                            <h6 class=" t-text-white t-truncate text-uppercase t-text-center" :title="schedule.tue.section" >{{schedule.tue.section}} , {{ schedule.tue.room }}</h6>
                                        </div>
                                        <div class="bg-success t-max-h-full t-overflow-hidden" v-else-if=" index >= 1 &&  schedule.tue.id === data.schedule[index - 1].tue.id &&  schedule.tue.professor === data.schedule[index - 1].tue.professor " >
                                            <h6 class=" t-text-white t-truncate text-uppercase t-text-center" :title="schedule.tue.subject" >{{ schedule.tue.subject }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" t-h-full t-w-full" >
                            <div class="bg-danger t-h-full t-w-full" v-if="schedule.wed === 1" ></div>
                            <div class="t-h-full t-w-full" v-else-if="schedule.wed === 0" ></div>
                            <div class="t-h-full t-w-full" v-else >
                                <div class=" t-w-full t-h-full bg-success"  v-if="index === 0 || schedule.wed.id  !== data.schedule[index - 1].wed.id" >
                                    <div>
                                        <h6 class="t-p-1 t-text-white t-truncate text-uppercase t-text-center" :title="schedule.wed.professor" >{{ schedule.wed.professor }}</h6>
                                    </div>
                                </div>
                                <div class="t-h-full t-w-full t-overflow-hidden t-flex t-items-center" >
                                    <div class="bg-danger t-h-full t-w-full" v-if="schedule.wed === 1" >
                                        <br>
                                    </div>
                                    <div class="t-h-full t-w-full" v-else-if="schedule.wed === 0" >
                                        <br>
                                    </div>
                                    <div class="bg-success t-h-full t-w-full t-relative" v-else >
                                        <div class="bg-success t-h-full t-w-full"  v-if=" index >= 3  &&  schedule.wed.id === data.schedule[index - 3].wed.id &&  schedule.wed.professor === data.schedule[index - 3].wed.professor " >
                                            <br>
                                        </div>
                                        <div  class="t-w-full t-h-full bg-success" v-else-if=" index >= 2 &&  schedule.wed.id === data.schedule[index - 2].wed.id &&  schedule.wed.professor === data.schedule[index - 2].wed.professor " >
                                            <h6 class=" t-text-white t-truncate text-uppercase t-text-center" :title="schedule.wed.section" >{{schedule.wed.section}} , {{ schedule.wed.room }}</h6>
                                        </div>
                                        <div class="bg-success t-max-h-full t-overflow-hidden" v-else-if=" index >= 1 &&  schedule.wed.id === data.schedule[index - 1].wed.id &&  schedule.wed.professor === data.schedule[index - 1].wed.professor " >
                                            <h6 class=" t-text-white t-truncate text-uppercase t-text-center" :title="schedule.wed.subject" >{{ schedule.wed.subject }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" t-h-full t-w-full" >
                            <div class="bg-danger t-h-full t-w-full" v-if="schedule.thu === 1" ></div>
                            <div class="t-h-full t-w-full" v-else-if="schedule.thu === 0" ></div>
                            <div class="t-h-full t-w-full" v-else >
                                <div class=" t-w-full t-h-full bg-success"  v-if="index === 0 || schedule.thu.id  !== data.schedule[index - 1].thu.id" >
                                    <div>
                                        <h6 class="t-p-1 t-text-white t-truncate text-uppercase t-text-center" :title="schedule.thu.professor" >{{ schedule.thu.professor }}</h6>
                                    </div>
                                </div>
                                <div class="t-h-full t-w-full t-overflow-hidden t-flex t-items-center" >
                                    <div class="bg-danger t-h-full t-w-full" v-if="schedule.thu === 1" >
                                        <br>
                                    </div>
                                    <div class="t-h-full t-w-full" v-else-if="schedule.thu === 0" >
                                        <br>
                                    </div>
                                    <div class="bg-success t-h-full t-w-full t-relative" v-else >
                                        <div class="bg-success t-h-full t-w-full"  v-if=" index >= 3  &&  schedule.thu.id === data.schedule[index - 3].thu.id &&  schedule.thu.professor === data.schedule[index - 3].thu.professor " >
                                            <br>
                                        </div>
                                        <div  class="t-w-full t-h-full bg-success" v-else-if=" index >= 2 &&  schedule.thu.id === data.schedule[index - 2].thu.id &&  schedule.thu.professor === data.schedule[index - 2].thu.professor " >
                                            <h6 class=" t-text-white t-truncate text-uppercase t-text-center" :title="schedule.thu.section" >{{schedule.thu.section}} , {{ schedule.thu.room }}</h6>
                                        </div>
                                        <div class="bg-success t-max-h-full t-overflow-hidden" v-else-if=" index >= 1 &&  schedule.thu.id === data.schedule[index - 1].thu.id &&  schedule.thu.professor === data.schedule[index - 1].thu.professor " >
                                            <h6 class=" t-text-white t-truncate text-uppercase t-text-center" :title="schedule.thu.subject" >{{ schedule.thu.subject }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" t-h-full t-w-full" >
                            <div class="bg-danger t-h-full t-w-full" v-if="schedule.fri === 1" ></div>
                            <div class="t-h-full t-w-full" v-else-if="schedule.fri === 0" ></div>
                            <div class="t-h-full t-w-full" v-else >
                                <div class=" t-w-full t-h-full bg-success"  v-if="index === 0 || schedule.fri.id  !== data.schedule[index - 1].fri.id" >
                                    <div>
                                        <h6 class="t-p-1 t-text-white t-truncate text-uppercase t-text-center" :title="schedule.fri.professor" >{{ schedule.fri.professor }}</h6>
                                    </div>
                                </div>
                                <div class="t-h-full t-w-full t-overflow-hidden t-flex t-items-center" >
                                    <div class="bg-danger t-h-full t-w-full" v-if="schedule.fri === 1" >
                                        <br>
                                    </div>
                                    <div class="t-h-full t-w-full" v-else-if="schedule.fri === 0" >
                                        <br>
                                    </div>
                                    <div class="bg-success t-h-full t-w-full t-relative" v-else >
                                        <div class="bg-success t-h-full t-w-full"  v-if=" index >= 3  &&  schedule.fri.id === data.schedule[index - 3].fri.id &&  schedule.fri.professor === data.schedule[index - 3].fri.professor " >
                                            <br>
                                        </div>
                                        <div  class="t-w-full t-h-full bg-success" v-else-if=" index >= 2 &&  schedule.fri.id === data.schedule[index - 2].fri.id &&  schedule.fri.professor === data.schedule[index - 2].fri.professor " >
                                            <h6 class=" t-text-white t-truncate text-uppercase t-text-center" :title="schedule.fri.section" >{{schedule.fri.section}} , {{ schedule.fri.room }}</h6>
                                        </div>
                                        <div class="bg-success t-max-h-full t-overflow-hidden" v-else-if=" index >= 1 &&  schedule.fri.id === data.schedule[index - 1].fri.id &&  schedule.fri.professor === data.schedule[index - 1].fri.professor " >
                                            <h6 class=" t-text-white t-truncate text-uppercase t-text-center" :title="schedule.fri.subject" >{{ schedule.fri.subject }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" t-h-full t-w-full" >
                            <div class="bg-danger t-h-full t-w-full" v-if="schedule.sat === 1" ></div>
                            <div class="t-h-full t-w-full" v-else-if="schedule.sat === 0" ></div>
                            <div class="t-h-full t-w-full" v-else >
                                <div class=" t-w-full t-h-full bg-success"  v-if="index === 0 || schedule.sat.id  !== data.schedule[index - 1].sat.id" >
                                    <div>
                                        <h6 class="t-p-1 t-text-white t-truncate text-uppercase t-text-center" :title="schedule.sat.professor" >{{ schedule.sat.professor }}</h6>
                                    </div>
                                </div>
                                <div class="t-h-full t-w-full t-overflow-hidden t-flex t-items-center" >
                                    <div class="bg-danger t-h-full t-w-full" v-if="schedule.sat === 1" >
                                        <br>
                                    </div>
                                    <div class="t-h-full t-w-full" v-else-if="schedule.sat === 0" >
                                        <br>
                                    </div>
                                    <div class="bg-success t-h-full t-w-full t-relative" v-else >
                                        <div class="bg-success t-h-full t-w-full"  v-if=" index >= 3  &&  schedule.sat.id === data.schedule[index - 3].sat.id &&  schedule.sat.professor === data.schedule[index - 3].sat.professor " >
                                            <br>
                                        </div>
                                        <div  class="t-w-full t-h-full bg-success" v-else-if=" index >= 2 &&  schedule.sat.id === data.schedule[index - 2].sat.id &&  schedule.sat.professor === data.schedule[index - 2].sat.professor " >
                                            <h6 class=" t-text-white t-truncate text-uppercase t-text-center" :title="schedule.sat.section" >{{schedule.sat.section}} , {{ schedule.sat.room }}</h6>
                                        </div>
                                        <div class="bg-success t-max-h-full t-overflow-hidden" v-else-if=" index >= 1 &&  schedule.sat.id === data.schedule[index - 1].sat.id &&  schedule.sat.professor === data.schedule[index - 1].sat.professor " >
                                            <h6 class=" t-text-white t-truncate text-uppercase t-text-center" :title="schedule.sat.subject" >{{ schedule.sat.subject }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="" >
                    <div  v-for="(schedule,index) in schedules" :key="index" class="t-grid t-grid-cols-7 t-h-[30px] odd:t-bg-white even:t-bg-slate-100" >
                        <div class="t-overflow-hidden  t-w-full t-flex t-items-center" >
                            <h6 class="t-truncate t-font-extralight t-text-black" :title="schedule.time" >{{ schedule.time }}</h6>
                        </div>
                        <div class=" t-h-full t-w-full" >
                            <div class="bg-danger t-h-full t-w-full" v-if="schedule.mon === 1" ></div>
                            <div class="t-h-full t-w-full" v-else-if="schedule.mon === 0" ></div>
                            <div class="t-h-full t-w-full" v-else >
                                <div class=" t-w-full t-h-full bg-success"  v-if="index === 0 || schedule.mon.id  !== schedules[index - 1].mon.id" >
                                    <div>
                                        <h6 class="t-p-1 t-text-white t-truncate text-uppercase t-text-center" :title="schedule.mon.professor" >{{ schedule.mon.professor }}</h6>
                                    </div>
                                </div>
                                <div class="t-h-full t-w-full t-overflow-hidden t-flex t-items-center" >
                                    <div class="bg-danger t-h-full t-w-full" v-if="schedule.mon === 1" >
                                        <br>
                                    </div>
                                    <div class="t-h-full t-w-full" v-else-if="schedule.mon === 0" >
                                        <br>
                                    </div>
                                    <div class="bg-success t-h-full t-w-full t-relative" v-else >
                                        <div class="bg-success t-h-full t-w-full"  v-if=" index >= 3  &&  schedule.mon.id === schedules[index - 3].mon.id &&  schedule.mon.professor === schedules[index - 3].mon.professor " >
                                            <br>
                                        </div>
                                        <div  class="t-w-full t-h-full bg-success" v-else-if=" index >= 2 &&  schedule.mon.id === schedules[index - 2].mon.id &&  schedule.mon.professor === schedules[index - 2].mon.professor " >
                                            <h6 class=" t-text-white t-truncate text-uppercase t-text-center" :title="schedule.mon.section" >{{schedule.mon.section}} , {{ schedule.mon.room }}</h6>
                                        </div>
                                        <div class="bg-success t-max-h-full t-overflow-hidden" v-else-if=" index >= 1 &&  schedule.mon.id === schedules[index - 1].mon.id &&  schedule.mon.professor === schedules[index - 1].mon.professor " >
                                            <h6 class=" t-text-white t-truncate text-uppercase t-text-center" :title="schedule.mon.subject" >{{ schedule.mon.subject }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" t-h-full t-w-full" >
                            <div class="bg-danger t-h-full t-w-full" v-if="schedule.tue === 1" ></div>
                            <div class="t-h-full t-w-full" v-else-if="schedule.tue === 0" ></div>
                            <div class="t-h-full t-w-full" v-else >
                                <div class=" t-w-full t-h-full bg-success"  v-if="index === 0 || schedule.tue.id  !== schedules[index - 1].tue.id" >
                                    <div>
                                        <h6 class="t-p-1 t-text-white t-truncate text-uppercase t-text-center" :title="schedule.tue.professor" >{{ schedule.tue.professor }}</h6>
                                    </div>
                                </div>
                                <div class="t-h-full t-w-full t-overflow-hidden t-flex t-items-center" >
                                    <div class="bg-danger t-h-full t-w-full" v-if="schedule.tue === 1" >
                                        <br>
                                    </div>
                                    <div class="t-h-full t-w-full" v-else-if="schedule.tue === 0" >
                                        <br>
                                    </div>
                                    <div class="bg-success t-h-full t-w-full t-relative" v-else >
                                        <div class="bg-success t-h-full t-w-full"  v-if=" index >= 3  &&  schedule.tue.id === schedules[index - 3].tue.id &&  schedule.tue.professor === schedules[index - 3].tue.professor " >
                                            <br>
                                        </div>
                                        <div  class="t-w-full t-h-full bg-success" v-else-if=" index >= 2 &&  schedule.tue.id === schedules[index - 2].tue.id &&  schedule.tue.professor === schedules[index - 2].tue.professor " >
                                            <h6 class=" t-text-white t-truncate text-uppercase t-text-center" :title="schedule.tue.section" >{{schedule.tue.section}} , {{ schedule.tue.room }}</h6>
                                        </div>
                                        <div class="bg-success t-max-h-full t-overflow-hidden" v-else-if=" index >= 1 &&  schedule.tue.id === schedules[index - 1].tue.id &&  schedule.tue.professor === schedules[index - 1].tue.professor " >
                                            <h6 class=" t-text-white t-truncate text-uppercase t-text-center" :title="schedule.tue.subject" >{{ schedule.tue.subject }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" t-h-full t-w-full" >
                            <div class="bg-danger t-h-full t-w-full" v-if="schedule.wed === 1" ></div>
                            <div class="t-h-full t-w-full" v-else-if="schedule.wed === 0" ></div>
                            <div class="t-h-full t-w-full" v-else >
                                <div class=" t-w-full t-h-full bg-success"  v-if="index === 0 || schedule.wed.id  !== schedules[index - 1].wed.id" >
                                    <div>
                                        <h6 class="t-p-1 t-text-white t-truncate text-uppercase t-text-center" :title="schedule.wed.professor" >{{ schedule.wed.professor }}</h6>
                                    </div>
                                </div>
                                <div class="t-h-full t-w-full t-overflow-hidden t-flex t-items-center" >
                                    <div class="bg-danger t-h-full t-w-full" v-if="schedule.wed === 1" >
                                        <br>
                                    </div>
                                    <div class="t-h-full t-w-full" v-else-if="schedule.wed === 0" >
                                        <br>
                                    </div>
                                    <div class="bg-success t-h-full t-w-full t-relative" v-else >
                                        <div class="bg-success t-h-full t-w-full"  v-if=" index >= 3  &&  schedule.wed.id === schedules[index - 3].wed.id &&  schedule.wed.professor === schedules[index - 3].wed.professor " >
                                            <br>
                                        </div>
                                        <div  class="t-w-full t-h-full bg-success" v-else-if=" index >= 2 &&  schedule.wed.id === schedules[index - 2].wed.id &&  schedule.wed.professor === schedules[index - 2].wed.professor " >
                                            <h6 class=" t-text-white t-truncate text-uppercase t-text-center" :title="schedule.wed.section" >{{schedule.wed.section}} , {{ schedule.wed.room }}</h6>
                                        </div>
                                        <div class="bg-success t-max-h-full t-overflow-hidden" v-else-if=" index >= 1 &&  schedule.wed.id === schedules[index - 1].wed.id &&  schedule.wed.professor === schedules[index - 1].wed.professor " >
                                            <h6 class=" t-text-white t-truncate text-uppercase t-text-center" :title="schedule.wed.subject" >{{ schedule.wed.subject }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" t-h-full t-w-full" >
                            <div class="bg-danger t-h-full t-w-full" v-if="schedule.thu === 1" ></div>
                            <div class="t-h-full t-w-full" v-else-if="schedule.thu === 0" ></div>
                            <div class="t-h-full t-w-full" v-else >
                                <div class=" t-w-full t-h-full bg-success"  v-if="index === 0 || schedule.thu.id  !== schedules[index - 1].thu.id" >
                                    <div>
                                        <h6 class="t-p-1 t-text-white t-truncate text-uppercase t-text-center" :title="schedule.thu.professor" >{{ schedule.thu.professor }}</h6>
                                    </div>
                                </div>
                                <div class="t-h-full t-w-full t-overflow-hidden t-flex t-items-center" >
                                    <div class="bg-danger t-h-full t-w-full" v-if="schedule.thu === 1" >
                                        <br>
                                    </div>
                                    <div class="t-h-full t-w-full" v-else-if="schedule.thu === 0" >
                                        <br>
                                    </div>
                                    <div class="bg-success t-h-full t-w-full t-relative" v-else >
                                        <div class="bg-success t-h-full t-w-full"  v-if=" index >= 3  &&  schedule.thu.id === schedules[index - 3].thu.id &&  schedule.thu.professor === schedules[index - 3].thu.professor " >
                                            <br>
                                        </div>
                                        <div  class="t-w-full t-h-full bg-success" v-else-if=" index >= 2 &&  schedule.thu.id === schedules[index - 2].thu.id &&  schedule.thu.professor === schedules[index - 2].thu.professor " >
                                            <h6 class=" t-text-white t-truncate text-uppercase t-text-center" :title="schedule.thu.section" >{{schedule.thu.section}} , {{ schedule.thu.room }}</h6>
                                        </div>
                                        <div class="bg-success t-max-h-full t-overflow-hidden" v-else-if=" index >= 1 &&  schedule.thu.id === schedules[index - 1].thu.id &&  schedule.thu.professor === schedules[index - 1].thu.professor " >
                                            <h6 class=" t-text-white t-truncate text-uppercase t-text-center" :title="schedule.thu.subject" >{{ schedule.thu.subject }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" t-h-full t-w-full" >
                            <div class="bg-danger t-h-full t-w-full" v-if="schedule.fri === 1" ></div>
                            <div class="t-h-full t-w-full" v-else-if="schedule.fri === 0" ></div>
                            <div class="t-h-full t-w-full" v-else >
                                <div class=" t-w-full t-h-full bg-success"  v-if="index === 0 || schedule.fri.id  !== schedules[index - 1].fri.id" >
                                    <div>
                                        <h6 class="t-p-1 t-text-white t-truncate text-uppercase t-text-center" :title="schedule.fri.professor" >{{ schedule.fri.professor }}</h6>
                                    </div>
                                </div>
                                <div class="t-h-full t-w-full t-overflow-hidden t-flex t-items-center" >
                                    <div class="bg-danger t-h-full t-w-full" v-if="schedule.fri === 1" >
                                        <br>
                                    </div>
                                    <div class="t-h-full t-w-full" v-else-if="schedule.fri === 0" >
                                        <br>
                                    </div>
                                    <div class="bg-success t-h-full t-w-full t-relative" v-else >
                                        <div class="bg-success t-h-full t-w-full"  v-if=" index >= 3  &&  schedule.fri.id === schedules[index - 3].fri.id &&  schedule.fri.professor === schedules[index - 3].fri.professor " >
                                            <br>
                                        </div>
                                        <div  class="t-w-full t-h-full bg-success" v-else-if=" index >= 2 &&  schedule.fri.id === schedules[index - 2].fri.id &&  schedule.fri.professor === schedules[index - 2].fri.professor " >
                                            <h6 class=" t-text-white t-truncate text-uppercase t-text-center" :title="schedule.fri.section" >{{schedule.fri.section}} , {{ schedule.fri.room }}</h6>
                                        </div>
                                        <div class="bg-success t-max-h-full t-overflow-hidden" v-else-if=" index >= 1 &&  schedule.fri.id === schedules[index - 1].fri.id &&  schedule.fri.professor === schedules[index - 1].fri.professor " >
                                            <h6 class=" t-text-white t-truncate text-uppercase t-text-center" :title="schedule.fri.subject" >{{ schedule.fri.subject }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" t-h-full t-w-full" >
                            <div class="bg-danger t-h-full t-w-full" v-if="schedule.sat === 1" ></div>
                            <div class="t-h-full t-w-full" v-else-if="schedule.sat === 0" ></div>
                            <div class="t-h-full t-w-full" v-else >
                                <div class=" t-w-full t-h-full bg-success"  v-if="index === 0 || schedule.sat.id  !== schedules[index - 1].sat.id" >
                                    <div>
                                        <h6 class="t-p-1 t-text-white t-truncate text-uppercase t-text-center" :title="schedule.sat.professor" >{{ schedule.sat.professor }}</h6>
                                    </div>
                                </div>
                                <div class="t-h-full t-w-full t-overflow-hidden t-flex t-items-center" >
                                    <div class="bg-danger t-h-full t-w-full" v-if="schedule.sat === 1" >
                                        <br>
                                    </div>
                                    <div class="t-h-full t-w-full" v-else-if="schedule.sat === 0" >
                                        <br>
                                    </div>
                                    <div class="bg-success t-h-full t-w-full t-relative" v-else >
                                        <div class="bg-success t-h-full t-w-full"  v-if=" index >= 3  &&  schedule.sat.id === schedules[index - 3].sat.id &&  schedule.sat.professor === schedules[index - 3].sat.professor " >
                                            <br>
                                        </div>
                                        <div  class="t-w-full t-h-full bg-success" v-else-if=" index >= 2 &&  schedule.sat.id === schedules[index - 2].sat.id &&  schedule.sat.professor === schedules[index - 2].sat.professor " >
                                            <h6 class=" t-text-white t-truncate text-uppercase t-text-center" :title="schedule.sat.section" >{{schedule.sat.section}} , {{ schedule.sat.room }}</h6>
                                        </div>
                                        <div class="bg-success t-max-h-full t-overflow-hidden" v-else-if=" index >= 1 &&  schedule.sat.id === schedules[index - 1].sat.id &&  schedule.sat.professor === schedules[index - 1].sat.professor " >
                                            <h6 class=" t-text-white t-truncate text-uppercase t-text-center" :title="schedule.sat.subject" >{{ schedule.sat.subject }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->
                    <div class="mt-3 t-grid t-grid-cols-2 text-center" >
                        <span class="text-text" >
                            <small>Prepared by:<br>
                                <b>Dr. Arnold P. Dela CruzDean</b><br>
                                Date Signed:
                            </small>
                        </span>
                        <span class="text-text" >
                            <small>Recommending Approval:<br>
                                <b>Dr. Melissa Belinda P. Faronilo</b><br>
                                Director, Office of Admission and Registration Date Signed:
                            </small>
                        </span>
                    </div>
                    <div class="text-center mt-1" >
                        <span class="text-text" >
                            <small>Approved by:<br>
                                <b>Dr. Rhodora R. Jugo</b><br>
                                Vice President for Academic Affairs Date Signed:
                            </small>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import { ref } from "vue";



const props = defineProps({
    schedules: {
        type: Array,
        required: true,
    },
})

const printNow = ()=>{
    print();
}

</script>
<style scoped >

.my-space{
    padding: 20px 100px;
}

img{
    height: 80px;
    width: 80px;
}

.custom-shadow{
    box-shadow: 0px 0px 10px rgba(0,0,0,0.3);
}

.print-template{
    display: none;
}

@media print{
    .print-template{
        display: block;
    }
    img{
        height: 80px;
        width: 80px;
    } 

    .custom-shadow{
        box-shadow: none;
    }

    .print-trigger{
        display: none;
    }

    .my-space{
    padding: 0 20px;
}
}

</style>