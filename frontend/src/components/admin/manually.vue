<template>
    <div  class="container t-select-none" >
        <h3 class="text-muted t-font-black" >Manually</h3>
        <!-- <pre>{{ schedules }}</pre> -->
        <div class="t-mt-3 t-bg-slate-100 t-p-5 t-rounded-[10px]" >
            <div class="" >
                <div class="t-p-2" >
                    <div class="t-bg-white t-rounded-[10px] t-shadow t-p-5 t-h-full" >
                        <div class="t-w-[200px] t-m-3 t-relative" >
                           <input @focus="focus_function" v-model="isSearch" placeholder="Search Professor" class="form-control" >
                           <div v-show="focusInput" class="t-absolute t-top-[60px] t-w-[400px] t-shadow t-rounded-[10px] t-p-3 t-bg-slate-200" >
                                <div class="t-grid t-grid-cols-[1fr,60px]" >
                                    <span>All Professor</span>
                                    <button @click="blur_function" class="btn btn-danger" >
                                        <fa icon="close" ></fa>
                                    </button>
                                </div>
                                <hr>
                                <div v-for="professor in computedProfessor" @click="selectedProfessor(professor.professorID)" class=" t-cursor-pointer t-relative t-w-full t-mt-2 t-bg-slate-100 t-p-2 t-rounded-[10px]" >
                                    <div class="t-flex" >
                                        <div class="" >
                                            <img src="../../assets/images/profile.png"  class="t-h-[80px]">
                                        </div>
                                        <div class="t-flex t-items-center" >
                                            <div class="" >
                                                <div class="t-grid t-grid-cols-2 t-gap-1" >
                                                    <span>ID:</span>
                                                    <span>{{ professor.professorID }}</span>
                                                </div>
                                                <div class="t-grid t-grid-cols-2 t-gap-1" >
                                                    <span>Fulname:</span>
                                                    <span>{{ professor.fullname }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="t-mt-3 t-bg-white t-p-5 t-shadow t-rounded-[10px]">
                            <div class="t-grid t-grid-cols-4 t-h-[50px] t-bg-slate-300" >
                                <span class="t-h-full t-font-semibold t-flex t-items-center t-pl-[5px]">
                                    <label>Subject</label>
                                </span>
                                <span class="t-h-full t-font-semibold t-flex t-items-center t-pl-[5px]">
                                    <label>Assigned Hour</label>
                                </span>
                                <span class="t-h-full t-font-semibold t-flex t-items-center t-pl-[5px]">
                                    <label>Lec Hour's</label>
                                </span>
                                <span class="t-h-full t-font-semibold t-flex t-items-center t-pl-[5px]">
                                    <label>Lab Hour's</label>
                                </span>
                            </div>
                            <div v-for="loads in loadData"  class="t-grid t-grid-cols-4 t-h-[40px] even:t-bg-white odd:t-bg-slate-100" >
                                <span class="t-h-full t-font-extralight t-flex t-items-center t-pl-[5px]" >
                                    <label class="t-text-[14px]" >{{ loads.code }}</label>
                                </span>
                                <span class="t-h-full t-font-extralight t-flex t-items-center t-pl-[5px]" >
                                    <label class="t-text-[14px]" >{{ loads.givenHour }}</label>
                                </span>
                                <span class="t-h-full t-font-extralight t-flex t-items-center t-pl-[5px]" >
                                    <label class="t-text-[14px]" >{{ loads.lec }}</label>
                                </span>
                                <span class="t-h-full t-font-extralight t-flex t-items-center t-pl-[5px]" >
                                    <label class="t-text-[14px]" >{{ loads.lab }}</label>
                                </span>
                            </div>
                        </div>
                        <div class="t-flex t-justify-between t-mt-3" >
                            <form @submit.prevent="create_schedule" class="t-flex t-flex-auto t-gap-2" >
                                <div class="form-grooup" >
                                    <select class="form-select" :disabled="isProcess || schedules.professor == '' " v-model="schedules.subject"  @change="change_load" >
                                        <option value="" >Subject</option>
                                        <option v-for="loads in loadData" :value="loads.code" >{{loads.code}}</option>
                                    </select>
                                </div>
                                <div class="form-grooup" >
                                    <select @change="change_work" :disabled="isProcess || schedules.professor == '' || schedules.subject == '' "  class="form-select" v-model="schedules.work" >
                                        <option value="" >Time</option>
                                        <option value="lecture" >lecture</option>
                                        <option value="laboratory" >Laboratory</option>
                                    </select>
                                </div>
                                <div class="form-grooup" >
                                    <select @change="getClassroomSchedule" :disabled="isProcess||schedules.professor == ''||schedules.subject == ''||schedules.timeWork == ''" class="form-select t-capitalize" v-model="schedules.classroom" >
                                        <option class="t-capitalize" disabled value="" >Classroom</option>
                                        <option class="t-capitalize" v-for="classroom in classroomData" :value="classroom.id" >{{ classroom.classroom }}</option>
                                    </select>
                                </div>
                                <div class="form-grooup" >
                                    <select @change="getSectionSchedule" :disabled="isProcess||schedules.professor == ''||schedules.subject == ''||schedules.timeWork == '' || schedules.classroom == ''" class="form-select" v-model="schedules.section" >
                                        <option value="" selected disabled >Section</option>
                                        <option v-for="section in sectionData" :value="section.section" >{{ section.section }}</option>
                                        
                                    </select>
                                </div>
                                <div class="form-grooup" >
                                    <select :disabled="isProcess||schedules.professor == ''||schedules.subject == ''||schedules.timeWork == '' || schedules.classroom == '' || schedules.section == '' " class="form-select" v-model="schedules.day" >
                                        <option value="" >Day</option>
                                        <option value="monday" >Monday</option>
                                        <option value="tuesday" >Tuesday</option>
                                        <option value="wednesday" >Wednesday</option>
                                        <option value="thursday" >Thursday</option>
                                        <option value="friday" >Friday</option>
                                        <option value="saturday" >Saturday</option>
                                    </select>
                                </div>
                                <div class="form-grooup" >
                                    <select @change="filterTime" :disabled="isProcess||schedules.professor == ''||schedules.subject == ''||schedules.timeWork == '' || schedules.classroom == '' || schedules.section == '' || schedules.day == '' " class="form-select t-capitalize" v-model="schedules.start" >
                                        <option class="t-capitalize" v-for="time in timers" :key="time.id" :value="time.id" >{{ time.label }}</option>
                                    </select>
                                </div>
                                <div class="form-grooup" >
                                    <select class="form-select" disabled v-model="schedules.end" >
                                        <option class="" value="" >End Time</option>
                                        <option class="t-capitalize" v-for="time in endTime" :key="time.id" :value="time.id" >{{ time.label }}</option>
                                    </select>
                                </div>
                                <div>
                                    <button :disabled="isProcess||schedules.professor == ''||schedules.subject == ''||schedules.timeWork == '' || schedules.classroom == '' || schedules.section == '' || schedules.day == '' " type="submit" class="btn btn-primary " >
                                        <fa icon="save" ></fa>
                                    </button>
                                </div>
                            </form>
                            <div class="" >
                                <button  class="btn btn-danger w-100" >
                                    <fa icon="trash" ></fa>
                                    Remove Schedule
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="t-mt-3 t-bg-white t-rounded-[10px] t-shadow t-p-5" >
                <div class="t-flex t-gap-2" >
                    <button @click="change_tab('professor')" :disabled="isProcess" class="t-w-[200px] btn" :class="{'btn-primary': current_tab === 'professor'}" >
                        <fa icon="users" ></fa>
                        professors
                    </button>
                    <button @click="change_tab('classroom')" :disabled="isProcess" class="t-w-[200px] btn" :class="{'btn-primary': current_tab === 'classroom'}" >
                        <fa icon="people-roof" ></fa>
                        Classroom
                    </button>
                    <button @click="change_tab('section')" :disabled="isProcess" class="t-w-[200px] btn" :class="{'btn-primary': current_tab === 'section'}" >
                        <fa icon="layer-group" ></fa>
                        Section
                    </button>
                </div>
                <div class="" >
                    <!-- professor schedule -->
                    <div v-if="current_tab == 'professor' " class="t-mt-2" >
                        <table class="table" style="border-spacing: 0;border-collapse: collapse" >
                            <thead>
                                <tr>
                                    <th class="bg-primary text-white " >Time</th>
                                    <th class="bg-primary text-white " >Mon</th>
                                    <th class="bg-primary text-white " >Tue</th>
                                    <th class="bg-primary text-white " >Wed</th>
                                    <th class="bg-primary text-white " >Thu</th>
                                    <th class="bg-primary text-white " >Fri</th>
                                    <th class="bg-primary text-white " >Sat</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(schedule,index) in professorScheduleData" > 
                                    <td class="" >{{ schedule.time }}</td>
                                    <td class="t-relative" >
                                        <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0 bg-danger" v-if="schedule.monday === 1" ></div>
                                        <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0" v-else-if="schedule.monday === 0" ></div>
                                        <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0 bg-secondary" v-else >
                                            <div class="t-w-full t-h-full bg-success"   v-if="index === 0 || schedule.monday.id  !== professorScheduleData[index - 1].monday.id" >
                                                <div class="" >
                                                    <h6 class="t-p-1 t-text-white t-truncate text-uppercase t-text-center t-text-[12px] " :title="schedule.monday.subject" >{{ schedule.monday.professor }}</h6>
                                                </div>
                                            </div>
                                            <div class="t-h-full t-w-full t-overflow-hidden t-flex t-items-center" >
                                                <div class="bg-danger t-h-full t-w-full"  v-if="schedule.monday === 1" >
                                                    <br>
                                                </div>
                                                <div class="t-h-full t-w-full" v-else-if="schedule.monday === 0" >
                                                    <br>
                                                </div>
                                                <div class="bg-success t-h-full t-w-full t-relative t-flex t-items-center t-justify-center " v-else >
                                                    <div class="bg-success t-h-full t-w-full"  v-if=" index >= 3  &&  schedule.monday.id === professorScheduleData[index - 3].monday.id &&  schedule.monday.professor === professorScheduleData[index - 1].monday.professor " >
                                                        <br>
                                                    </div>
                                                    <div  class="t-w-full t-h-full bg-success t-flex t-items-center t-justify-center" v-else-if=" index >= 2 &&  schedule.monday.id === professorScheduleData[index - 2].monday.id &&  schedule.monday.professor === professorScheduleData[index - 1].monday.professor " >
                                                        <h6 class=" t-text-white t-truncate text-uppercase t-text-center t-text-[12px]" :title="schedule.monday.section+'-'+schedule.monday.classroom" >{{schedule.monday.section}} , {{ schedule.monday.classroom }}</h6>
                                                    </div>
                                                    <div class="bg-success t-max-h-full t-overflow-hidden" v-else-if=" index >= 1 &&  schedule.monday.id === professorScheduleData[index - 1].monday.id &&  schedule.monday.professor === professorScheduleData[index - 1].monday.professor " >
                                                        <h6 class=" t-text-white t-truncate text-uppercase t-text-center t-text-[12px]" :title="schedule.monday.subject" >{{ schedule.monday.subject }}</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="t-relative" >
                                        <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0 bg-danger" v-if="schedule.tuesday === 1" ></div>
                                        <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0" v-else-if="schedule.tuesday === 0" ></div>
                                        <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0 bg-secondary" v-else >
                                            <div class="t-w-full t-h-full bg-success"   v-if="index === 0 || schedule.tuesday.id  !== professorScheduleData[index - 1].tuesday.id" >
                                                <div class="" >
                                                    <h6 class="t-p-1 t-text-white t-truncate text-uppercase t-text-center t-text-[12px] " :title="schedule.tuesday.subject" >{{ schedule.tuesday.professor }}</h6>
                                                </div>
                                            </div>
                                            <div class="t-h-full t-w-full t-overflow-hidden t-flex t-items-center" >
                                                <div class="bg-danger t-h-full t-w-full"  v-if="schedule.tuesday === 1" >
                                                    <br>
                                                </div>
                                                <div class="t-h-full t-w-full" v-else-if="schedule.tuesday === 0" >
                                                    <br>
                                                </div>
                                                <div class="bg-success t-h-full t-w-full t-relative t-flex t-items-center t-justify-center " v-else >
                                                    <div class="bg-success t-h-full t-w-full"  v-if=" index >= 3  &&  schedule.tuesday.id === professorScheduleData[index - 3].tuesday.id &&  schedule.tuesday.professor === professorScheduleData[index - 1].tuesday.professor " >
                                                        <br>
                                                    </div>
                                                    <div  class="t-w-full t-h-full bg-success t-flex t-items-center t-justify-center" v-else-if=" index >= 2 &&  schedule.tuesday.id === professorScheduleData[index - 2].tuesday.id &&  schedule.tuesday.professor === professorScheduleData[index - 1].tuesday.professor " >
                                                        <h6 class=" t-text-white t-truncate text-uppercase t-text-center t-text-[12px]" :title="schedule.tuesday.section+'-'+schedule.tuesday.classroom" >{{schedule.tuesday.section}} , {{ schedule.tuesday.classroom }}</h6>
                                                    </div>
                                                    <div class="bg-success t-max-h-full t-overflow-hidden" v-else-if=" index >= 1 &&  schedule.tuesday.id === professorScheduleData[index - 1].tuesday.id &&  schedule.tuesday.professor === professorScheduleData[index - 1].tuesday.professor " >
                                                        <h6 class=" t-text-white t-truncate text-uppercase t-text-center t-text-[12px]" :title="schedule.tuesday.subject" >{{ schedule.tuesday.subject }}</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="t-relative" >
                                        <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0 bg-danger" v-if="schedule.wednesday === 1" ></div>
                                        <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0" v-else-if="schedule.wednesday === 0" ></div>
                                        <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0 bg-secondary" v-else >
                                            <div class="t-w-full t-h-full bg-success"   v-if="index === 0 || schedule.wednesday.id  !== professorScheduleData[index - 1].wednesday.id" >
                                                <div class="" >
                                                    <h6 class="t-p-1 t-text-white t-truncate text-uppercase t-text-center t-text-[12px] " :title="schedule.wednesday.subject" >{{ schedule.wednesday.professor }}</h6>
                                                </div>
                                            </div>
                                            <div class="t-h-full t-w-full t-overflow-hidden t-flex t-items-center" >
                                                <div class="bg-danger t-h-full t-w-full"  v-if="schedule.wednesday === 1" >
                                                    <br>
                                                </div>
                                                <div class="t-h-full t-w-full" v-else-if="schedule.wednesday === 0" >
                                                    <br>
                                                </div>
                                                <div class="bg-success t-h-full t-w-full t-relative t-flex t-items-center t-justify-center " v-else >
                                                    <div class="bg-success t-h-full t-w-full"  v-if=" index >= 3  &&  schedule.wednesday.id === professorScheduleData[index - 3].wednesday.id &&  schedule.wednesday.professor === professorScheduleData[index - 1].wednesday.professor " >
                                                        <br>
                                                    </div>
                                                    <div  class="t-w-full t-h-full bg-success t-flex t-items-center t-justify-center" v-else-if=" index >= 2 &&  schedule.wednesday.id === professorScheduleData[index - 2].wednesday.id &&  schedule.wednesday.professor === professorScheduleData[index - 1].wednesday.professor " >
                                                        <h6 class=" t-text-white t-truncate text-uppercase t-text-center t-text-[12px]" :title="schedule.wednesday.section+'-'+schedule.wednesday.classroom" >{{schedule.wednesday.section}} , {{ schedule.wednesday.classroom }}</h6>
                                                    </div>
                                                    <div class="bg-success t-max-h-full t-overflow-hidden" v-else-if=" index >= 1 &&  schedule.wednesday.id === professorScheduleData[index - 1].wednesday.id &&  schedule.wednesday.professor === professorScheduleData[index - 1].wednesday.professor " >
                                                        <h6 class=" t-text-white t-truncate text-uppercase t-text-center t-text-[12px]" :title="schedule.wednesday.subject" >{{ schedule.wednesday.subject }}</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="t-relative" >
                                        <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0 bg-danger" v-if="schedule.thursday === 1" ></div>
                                        <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0" v-else-if="schedule.thursday === 0" ></div>
                                        <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0 bg-secondary" v-else >
                                            <div class="t-w-full t-h-full bg-success"   v-if="index === 0 || schedule.thursday.id  !== professorScheduleData[index - 1].thursday.id" >
                                                <div class="" >
                                                    <h6 class="t-p-1 t-text-white t-truncate text-uppercase t-text-center t-text-[12px] " :title="schedule.thursday.subject" >{{ schedule.thursday.professor }}</h6>
                                                </div>
                                            </div>
                                            <div class="t-h-full t-w-full t-overflow-hidden t-flex t-items-center" >
                                                <div class="bg-danger t-h-full t-w-full"  v-if="schedule.thursday === 1" >
                                                    <br>
                                                </div>
                                                <div class="t-h-full t-w-full" v-else-if="schedule.thursday === 0" >
                                                    <br>
                                                </div>
                                                <div class="bg-success t-h-full t-w-full t-relative t-flex t-items-center t-justify-center " v-else >
                                                    <div class="bg-success t-h-full t-w-full"  v-if=" index >= 3  &&  schedule.thursday.id === professorScheduleData[index - 3].thursday.id &&  schedule.thursday.professor === professorScheduleData[index - 1].thursday.professor " >
                                                        <br>
                                                    </div>
                                                    <div  class="t-w-full t-h-full bg-success t-flex t-items-center t-justify-center" v-else-if=" index >= 2 &&  schedule.thursday.id === professorScheduleData[index - 2].thursday.id &&  schedule.thursday.professor === professorScheduleData[index - 1].thursday.professor " >
                                                        <h6 class=" t-text-white t-truncate text-uppercase t-text-center t-text-[12px]" :title="schedule.thursday.section+'-'+schedule.thursday.classroom" >{{schedule.thursday.section}} , {{ schedule.thursday.classroom }}</h6>
                                                    </div>
                                                    <div class="bg-success t-max-h-full t-overflow-hidden" v-else-if=" index >= 1 &&  schedule.thursday.id === professorScheduleData[index - 1].thursday.id &&  schedule.thursday.professor === professorScheduleData[index - 1].thursday.professor " >
                                                        <h6 class=" t-text-white t-truncate text-uppercase t-text-center t-text-[12px]" :title="schedule.thursday.subject" >{{ schedule.thursday.subject }}</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="t-relative" >
                                        <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0 bg-danger" v-if="schedule.friday === 1" ></div>
                                        <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0" v-else-if="schedule.friday === 0" ></div>
                                        <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0 bg-secondary" v-else >
                                            <div class="t-w-full t-h-full bg-success"   v-if="index === 0 || schedule.friday.id  !== professorScheduleData[index - 1].friday.id" >
                                                <div class="" >
                                                    <h6 class="t-p-1 t-text-white t-truncate text-uppercase t-text-center t-text-[12px] " :title="schedule.friday.subject" >{{ schedule.friday.professor }}</h6>
                                                </div>
                                            </div>
                                            <div class="t-h-full t-w-full t-overflow-hidden t-flex t-items-center" >
                                                <div class="bg-danger t-h-full t-w-full"  v-if="schedule.friday === 1" >
                                                    <br>
                                                </div>
                                                <div class="t-h-full t-w-full" v-else-if="schedule.friday === 0" >
                                                    <br>
                                                </div>
                                                <div class="bg-success t-h-full t-w-full t-relative t-flex t-items-center t-justify-center " v-else >
                                                    <div class="bg-success t-h-full t-w-full"  v-if=" index >= 3  &&  schedule.friday.id === professorScheduleData[index - 3].friday.id &&  schedule.friday.professor === professorScheduleData[index - 1].friday.professor " >
                                                        <br>
                                                    </div>
                                                    <div  class="t-w-full t-h-full bg-success t-flex t-items-center t-justify-center" v-else-if=" index >= 2 &&  schedule.friday.id === professorScheduleData[index - 2].friday.id &&  schedule.friday.professor === professorScheduleData[index - 1].friday.professor " >
                                                        <h6 class=" t-text-white t-truncate text-uppercase t-text-center t-text-[12px]" :title="schedule.friday.section+'-'+schedule.friday.classroom" >{{schedule.friday.section}} , {{ schedule.friday.classroom }}</h6>
                                                    </div>
                                                    <div class="bg-success t-max-h-full t-overflow-hidden" v-else-if=" index >= 1 &&  schedule.friday.id === professorScheduleData[index - 1].friday.id &&  schedule.friday.professor === professorScheduleData[index - 1].friday.professor " >
                                                        <h6 class=" t-text-white t-truncate text-uppercase t-text-center t-text-[12px]" :title="schedule.friday.subject" >{{ schedule.friday.subject }}</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="t-relative" >
                                        <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0 bg-danger" v-if="schedule.saturday === 1" ></div>
                                        <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0" v-else-if="schedule.saturday === 0" ></div>
                                        <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0 bg-secondary" v-else >
                                            <div class="t-w-full t-h-full bg-success"   v-if="index === 0 || schedule.saturday.id  !== professorScheduleData[index - 1].saturday.id" >
                                                <div class="" >
                                                    <h6 class="t-p-1 t-text-white t-truncate text-uppercase t-text-center t-text-[12px] " :title="schedule.saturday.subject" >{{ schedule.saturday.professor }}</h6>
                                                </div>
                                            </div>
                                            <div class="t-h-full t-w-full t-overflow-hidden t-flex t-items-center" >
                                                <div class="bg-danger t-h-full t-w-full"  v-if="schedule.saturday === 1" >
                                                    <br>
                                                </div>
                                                <div class="t-h-full t-w-full" v-else-if="schedule.saturday === 0" >
                                                    <br>
                                                </div>
                                                <div class="bg-success t-h-full t-w-full t-relative t-flex t-items-center t-justify-center " v-else >
                                                    <div class="bg-success t-h-full t-w-full"  v-if=" index >= 3  &&  schedule.saturday.id === professorScheduleData[index - 3].saturday.id &&  schedule.saturday.professor === professorScheduleData[index - 1].saturday.professor " >
                                                        <br>
                                                    </div>
                                                    <div  class="t-w-full t-h-full bg-success t-flex t-items-center t-justify-center" v-else-if=" index >= 2 &&  schedule.saturday.id === professorScheduleData[index - 2].saturday.id &&  schedule.saturday.professor === professorScheduleData[index - 1].saturday.professor " >
                                                        <h6 class=" t-text-white t-truncate text-uppercase t-text-center t-text-[12px]" :title="schedule.saturday.section+'-'+schedule.saturday.classroom" >{{schedule.saturday.section}} , {{ schedule.saturday.classroom }}</h6>
                                                    </div>
                                                    <div class="bg-success t-max-h-full t-overflow-hidden" v-else-if=" index >= 1 &&  schedule.saturday.id === professorScheduleData[index - 1].saturday.id &&  schedule.saturday.professor === professorScheduleData[index - 1].saturday.professor " >
                                                        <h6 class=" t-text-white t-truncate text-uppercase t-text-center t-text-[12px]" :title="schedule.saturday.subject" >{{ schedule.saturday.subject }}</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- classroom schedule -->
                    <div v-if="current_tab == 'classroom' " class="t-mt-2" >
                        <table class="table" style="border-spacing: 0;border-collapse: collapse" >
                            <thead>
                                <tr>
                                    <th class="bg-primary text-white " >Time</th>
                                    <th class="bg-primary text-white " >Mon</th>
                                    <th class="bg-primary text-white " >Tue</th>
                                    <th class="bg-primary text-white " >Wed</th>
                                    <th class="bg-primary text-white " >Thu</th>
                                    <th class="bg-primary text-white " >Fri</th>
                                    <th class="bg-primary text-white " >Sat</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(schedule,index) in classroomScheduleData" > 
                                    <td class="" >{{ schedule.time }}</td>
                                    <td class="t-relative" >
                                        <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0 bg-danger" v-if="schedule.monday === 1" ></div>
                                        <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0" v-else-if="schedule.monday === 0" ></div>
                                        <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0 bg-secondary" v-else >
                                            <div class="t-w-full t-h-full bg-success"   v-if="index === 0 || schedule.monday.id  !== classroomScheduleData[index - 1].monday.id" >
                                                <div class="" >
                                                    <h6 class="t-p-1 t-text-white t-truncate text-uppercase t-text-center t-text-[12px] " :title="schedule.monday.subject" >{{ schedule.monday.professor }}</h6>
                                                </div>
                                            </div>
                                            <div class="t-h-full t-w-full t-overflow-hidden t-flex t-items-center" >
                                                <div class="bg-danger t-h-full t-w-full"  v-if="schedule.monday === 1" >
                                                    <br>
                                                </div>
                                                <div class="t-h-full t-w-full" v-else-if="schedule.monday === 0" >
                                                    <br>
                                                </div>
                                                <div class="bg-success t-h-full t-w-full t-relative t-flex t-items-center t-justify-center " v-else >
                                                    <div class="bg-success t-h-full t-w-full"  v-if=" index >= 3  &&  schedule.monday.id === classroomScheduleData[index - 3].monday.id &&  schedule.monday.professor === classroomScheduleData[index - 1].monday.professor " >
                                                        <br>
                                                    </div>
                                                    <div  class="t-w-full t-h-full bg-success t-flex t-items-center t-justify-center" v-else-if=" index >= 2 &&  schedule.monday.id === classroomScheduleData[index - 2].monday.id &&  schedule.monday.professor === classroomScheduleData[index - 1].monday.professor " >
                                                        <h6 class=" t-text-white t-truncate text-uppercase t-text-center t-text-[12px]" :title="schedule.monday.section+'-'+schedule.monday.classroom" >{{schedule.monday.section}} , {{ schedule.monday.classroom }}</h6>
                                                    </div>
                                                    <div class="bg-success t-max-h-full t-overflow-hidden" v-else-if=" index >= 1 &&  schedule.monday.id === classroomScheduleData[index - 1].monday.id &&  schedule.monday.professor === classroomScheduleData[index - 1].monday.professor " >
                                                        <h6 class=" t-text-white t-truncate text-uppercase t-text-center t-text-[12px]" :title="schedule.monday.subject" >{{ schedule.monday.subject }}</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="t-relative" >
                                        <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0 bg-danger" v-if="schedule.tuesday === 1" ></div>
                                        <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0" v-else-if="schedule.tuesday === 0" ></div>
                                        <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0 bg-secondary" v-else >
                                            <div class="t-w-full t-h-full bg-success"   v-if="index === 0 || schedule.tuesday.id  !== classroomScheduleData[index - 1].tuesday.id" >
                                                <div class="" >
                                                    <h6 class="t-p-1 t-text-white t-truncate text-uppercase t-text-center t-text-[12px] " :title="schedule.tuesday.subject" >{{ schedule.tuesday.professor }}</h6>
                                                </div>
                                            </div>
                                            <div class="t-h-full t-w-full t-overflow-hidden t-flex t-items-center" >
                                                <div class="bg-danger t-h-full t-w-full"  v-if="schedule.tuesday === 1" >
                                                    <br>
                                                </div>
                                                <div class="t-h-full t-w-full" v-else-if="schedule.tuesday === 0" >
                                                    <br>
                                                </div>
                                                <div class="bg-success t-h-full t-w-full t-relative t-flex t-items-center t-justify-center " v-else >
                                                    <div class="bg-success t-h-full t-w-full"  v-if=" index >= 3  &&  schedule.tuesday.id === classroomScheduleData[index - 3].tuesday.id &&  schedule.tuesday.professor === classroomScheduleData[index - 1].tuesday.professor " >
                                                        <br>
                                                    </div>
                                                    <div  class="t-w-full t-h-full bg-success t-flex t-items-center t-justify-center" v-else-if=" index >= 2 &&  schedule.tuesday.id === classroomScheduleData[index - 2].tuesday.id &&  schedule.tuesday.professor === classroomScheduleData[index - 1].tuesday.professor " >
                                                        <h6 class=" t-text-white t-truncate text-uppercase t-text-center t-text-[12px]" :title="schedule.tuesday.section+'-'+schedule.tuesday.classroom" >{{schedule.tuesday.section}} , {{ schedule.tuesday.classroom }}</h6>
                                                    </div>
                                                    <div class="bg-success t-max-h-full t-overflow-hidden" v-else-if=" index >= 1 &&  schedule.tuesday.id === classroomScheduleData[index - 1].tuesday.id &&  schedule.tuesday.professor === classroomScheduleData[index - 1].tuesday.professor " >
                                                        <h6 class=" t-text-white t-truncate text-uppercase t-text-center t-text-[12px]" :title="schedule.tuesday.subject" >{{ schedule.tuesday.subject }}</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="t-relative" >
                                        <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0 bg-danger" v-if="schedule.wednesday === 1" ></div>
                                        <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0" v-else-if="schedule.wednesday === 0" ></div>
                                        <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0 bg-secondary" v-else >
                                            <div class="t-w-full t-h-full bg-success"   v-if="index === 0 || schedule.wednesday.id  !== classroomScheduleData[index - 1].wednesday.id" >
                                                <div class="" >
                                                    <h6 class="t-p-1 t-text-white t-truncate text-uppercase t-text-center t-text-[12px] " :title="schedule.wednesday.subject" >{{ schedule.wednesday.professor }}</h6>
                                                </div>
                                            </div>
                                            <div class="t-h-full t-w-full t-overflow-hidden t-flex t-items-center" >
                                                <div class="bg-danger t-h-full t-w-full"  v-if="schedule.wednesday === 1" >
                                                    <br>
                                                </div>
                                                <div class="t-h-full t-w-full" v-else-if="schedule.wednesday === 0" >
                                                    <br>
                                                </div>
                                                <div class="bg-success t-h-full t-w-full t-relative t-flex t-items-center t-justify-center " v-else >
                                                    <div class="bg-success t-h-full t-w-full"  v-if=" index >= 3  &&  schedule.wednesday.id === classroomScheduleData[index - 3].wednesday.id &&  schedule.wednesday.professor === classroomScheduleData[index - 1].wednesday.professor " >
                                                        <br>
                                                    </div>
                                                    <div  class="t-w-full t-h-full bg-success t-flex t-items-center t-justify-center" v-else-if=" index >= 2 &&  schedule.wednesday.id === classroomScheduleData[index - 2].wednesday.id &&  schedule.wednesday.professor === classroomScheduleData[index - 1].wednesday.professor " >
                                                        <h6 class=" t-text-white t-truncate text-uppercase t-text-center t-text-[12px]" :title="schedule.wednesday.section+'-'+schedule.wednesday.classroom" >{{schedule.wednesday.section}} , {{ schedule.wednesday.classroom }}</h6>
                                                    </div>
                                                    <div class="bg-success t-max-h-full t-overflow-hidden" v-else-if=" index >= 1 &&  schedule.wednesday.id === classroomScheduleData[index - 1].wednesday.id &&  schedule.wednesday.professor === classroomScheduleData[index - 1].wednesday.professor " >
                                                        <h6 class=" t-text-white t-truncate text-uppercase t-text-center t-text-[12px]" :title="schedule.wednesday.subject" >{{ schedule.wednesday.subject }}</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="t-relative" >
                                        <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0 bg-danger" v-if="schedule.thursday === 1" ></div>
                                        <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0" v-else-if="schedule.thursday === 0" ></div>
                                        <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0 bg-secondary" v-else >
                                            <div class="t-w-full t-h-full bg-success"   v-if="index === 0 || schedule.thursday.id  !== classroomScheduleData[index - 1].thursday.id" >
                                                <div class="" >
                                                    <h6 class="t-p-1 t-text-white t-truncate text-uppercase t-text-center t-text-[12px] " :title="schedule.thursday.subject" >{{ schedule.thursday.professor }}</h6>
                                                </div>
                                            </div>
                                            <div class="t-h-full t-w-full t-overflow-hidden t-flex t-items-center" >
                                                <div class="bg-danger t-h-full t-w-full"  v-if="schedule.thursday === 1" >
                                                    <br>
                                                </div>
                                                <div class="t-h-full t-w-full" v-else-if="schedule.thursday === 0" >
                                                    <br>
                                                </div>
                                                <div class="bg-success t-h-full t-w-full t-relative t-flex t-items-center t-justify-center " v-else >
                                                    <div class="bg-success t-h-full t-w-full"  v-if=" index >= 3  &&  schedule.thursday.id === classroomScheduleData[index - 3].thursday.id &&  schedule.thursday.professor === classroomScheduleData[index - 1].thursday.professor " >
                                                        <br>
                                                    </div>
                                                    <div  class="t-w-full t-h-full bg-success t-flex t-items-center t-justify-center" v-else-if=" index >= 2 &&  schedule.thursday.id === classroomScheduleData[index - 2].thursday.id &&  schedule.thursday.professor === classroomScheduleData[index - 1].thursday.professor " >
                                                        <h6 class=" t-text-white t-truncate text-uppercase t-text-center t-text-[12px]" :title="schedule.thursday.section+'-'+schedule.thursday.classroom" >{{schedule.thursday.section}} , {{ schedule.thursday.classroom }}</h6>
                                                    </div>
                                                    <div class="bg-success t-max-h-full t-overflow-hidden" v-else-if=" index >= 1 &&  schedule.thursday.id === classroomScheduleData[index - 1].thursday.id &&  schedule.thursday.professor === classroomScheduleData[index - 1].thursday.professor " >
                                                        <h6 class=" t-text-white t-truncate text-uppercase t-text-center t-text-[12px]" :title="schedule.thursday.subject" >{{ schedule.thursday.subject }}</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="t-relative" >
                                        <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0 bg-danger" v-if="schedule.friday === 1" ></div>
                                        <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0" v-else-if="schedule.friday === 0" ></div>
                                        <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0 bg-secondary" v-else >
                                            <div class="t-w-full t-h-full bg-success"   v-if="index === 0 || schedule.friday.id  !== classroomScheduleData[index - 1].friday.id" >
                                                <div class="" >
                                                    <h6 class="t-p-1 t-text-white t-truncate text-uppercase t-text-center t-text-[12px] " :title="schedule.friday.subject" >{{ schedule.friday.professor }}</h6>
                                                </div>
                                            </div>
                                            <div class="t-h-full t-w-full t-overflow-hidden t-flex t-items-center" >
                                                <div class="bg-danger t-h-full t-w-full"  v-if="schedule.friday === 1" >
                                                    <br>
                                                </div>
                                                <div class="t-h-full t-w-full" v-else-if="schedule.friday === 0" >
                                                    <br>
                                                </div>
                                                <div class="bg-success t-h-full t-w-full t-relative t-flex t-items-center t-justify-center " v-else >
                                                    <div class="bg-success t-h-full t-w-full"  v-if=" index >= 3  &&  schedule.friday.id === classroomScheduleData[index - 3].friday.id &&  schedule.friday.professor === classroomScheduleData[index - 1].friday.professor " >
                                                        <br>
                                                    </div>
                                                    <div  class="t-w-full t-h-full bg-success t-flex t-items-center t-justify-center" v-else-if=" index >= 2 &&  schedule.friday.id === classroomScheduleData[index - 2].friday.id &&  schedule.friday.professor === classroomScheduleData[index - 1].friday.professor " >
                                                        <h6 class=" t-text-white t-truncate text-uppercase t-text-center t-text-[12px]" :title="schedule.friday.section+'-'+schedule.friday.classroom" >{{schedule.friday.section}} , {{ schedule.friday.classroom }}</h6>
                                                    </div>
                                                    <div class="bg-success t-max-h-full t-overflow-hidden" v-else-if=" index >= 1 &&  schedule.friday.id === classroomScheduleData[index - 1].friday.id &&  schedule.friday.professor === classroomScheduleData[index - 1].friday.professor " >
                                                        <h6 class=" t-text-white t-truncate text-uppercase t-text-center t-text-[12px]" :title="schedule.friday.subject" >{{ schedule.friday.subject }}</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="t-relative" >
                                        <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0 bg-danger" v-if="schedule.saturday === 1" ></div>
                                        <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0" v-else-if="schedule.saturday === 0" ></div>
                                        <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0 bg-secondary" v-else >
                                            <div class="t-w-full t-h-full bg-success"   v-if="index === 0 || schedule.saturday.id  !== classroomScheduleData[index - 1].saturday.id" >
                                                <div class="" >
                                                    <h6 class="t-p-1 t-text-white t-truncate text-uppercase t-text-center t-text-[12px] " :title="schedule.saturday.subject" >{{ schedule.saturday.professor }}</h6>
                                                </div>
                                            </div>
                                            <div class="t-h-full t-w-full t-overflow-hidden t-flex t-items-center" >
                                                <div class="bg-danger t-h-full t-w-full"  v-if="schedule.saturday === 1" >
                                                    <br>
                                                </div>
                                                <div class="t-h-full t-w-full" v-else-if="schedule.saturday === 0" >
                                                    <br>
                                                </div>
                                                <div class="bg-success t-h-full t-w-full t-relative t-flex t-items-center t-justify-center " v-else >
                                                    <div class="bg-success t-h-full t-w-full"  v-if=" index >= 3  &&  schedule.saturday.id === classroomScheduleData[index - 3].saturday.id &&  schedule.saturday.professor === classroomScheduleData[index - 1].saturday.professor " >
                                                        <br>
                                                    </div>
                                                    <div  class="t-w-full t-h-full bg-success t-flex t-items-center t-justify-center" v-else-if=" index >= 2 &&  schedule.saturday.id === classroomScheduleData[index - 2].saturday.id &&  schedule.saturday.professor === classroomScheduleData[index - 1].saturday.professor " >
                                                        <h6 class=" t-text-white t-truncate text-uppercase t-text-center t-text-[12px]" :title="schedule.saturday.section+'-'+schedule.saturday.classroom" >{{schedule.saturday.section}} , {{ schedule.saturday.classroom }}</h6>
                                                    </div>
                                                    <div class="bg-success t-max-h-full t-overflow-hidden" v-else-if=" index >= 1 &&  schedule.saturday.id === classroomScheduleData[index - 1].saturday.id &&  schedule.saturday.professor === classroomScheduleData[index - 1].saturday.professor " >
                                                        <h6 class=" t-text-white t-truncate text-uppercase t-text-center t-text-[12px]" :title="schedule.saturday.subject" >{{ schedule.saturday.subject }}</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- section schedule -->
                    <div v-if="current_tab == 'section' " class="t-mt-2" >
                        <table class="table" style="border-spacing: 0;border-collapse: collapse" >
                            <thead>
                                <tr>
                                    <th class="bg-primary text-white " >Time</th>
                                    <th class="bg-primary text-white " >Mon</th>
                                    <th class="bg-primary text-white " >Tue</th>
                                    <th class="bg-primary text-white " >Wed</th>
                                    <th class="bg-primary text-white " >Thu</th>
                                    <th class="bg-primary text-white " >Fri</th>
                                    <th class="bg-primary text-white " >Sat</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(schedule,index) in sectionScheduleData" > 
                                    <td class="" >{{ schedule.time }}</td>
                                    <td class="t-relative" >
                                        <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0 bg-danger" v-if="schedule.monday === 1" ></div>
                                        <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0" v-else-if="schedule.monday === 0" ></div>
                                        <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0 bg-secondary" v-else >
                                            <div class="t-w-full t-h-full bg-success"   v-if="index === 0 || schedule.monday.id  !== sectionScheduleData[index - 1].monday.id" >
                                                <div class="" >
                                                    <h6 class="t-p-1 t-text-white t-truncate text-uppercase t-text-center t-text-[12px] " :title="schedule.monday.subject" >{{ schedule.monday.professor }}</h6>
                                                </div>
                                            </div>
                                            <div class="t-h-full t-w-full t-overflow-hidden t-flex t-items-center" >
                                                <div class="bg-danger t-h-full t-w-full"  v-if="schedule.monday === 1" >
                                                    <br>
                                                </div>
                                                <div class="t-h-full t-w-full" v-else-if="schedule.monday === 0" >
                                                    <br>
                                                </div>
                                                <div class="bg-success t-h-full t-w-full t-relative t-flex t-items-center t-justify-center " v-else >
                                                    <div class="bg-success t-h-full t-w-full"  v-if=" index >= 3  &&  schedule.monday.id === sectionScheduleData[index - 3].monday.id &&  schedule.monday.professor === sectionScheduleData[index - 1].monday.professor " >
                                                        <br>
                                                    </div>
                                                    <div  class="t-w-full t-h-full bg-success t-flex t-items-center t-justify-center" v-else-if=" index >= 2 &&  schedule.monday.id === sectionScheduleData[index - 2].monday.id &&  schedule.monday.professor === sectionScheduleData[index - 1].monday.professor " >
                                                        <h6 class=" t-text-white t-truncate text-uppercase t-text-center t-text-[12px]" :title="schedule.monday.section+'-'+schedule.monday.classroom" >{{schedule.monday.section}} , {{ schedule.monday.classroom }}</h6>
                                                    </div>
                                                    <div class="bg-success t-max-h-full t-overflow-hidden" v-else-if=" index >= 1 &&  schedule.monday.id === sectionScheduleData[index - 1].monday.id &&  schedule.monday.professor === sectionScheduleData[index - 1].monday.professor " >
                                                        <h6 class=" t-text-white t-truncate text-uppercase t-text-center t-text-[12px]" :title="schedule.monday.subject" >{{ schedule.monday.subject }}</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="t-relative" >
                                        <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0 bg-danger" v-if="schedule.tuesday === 1" ></div>
                                        <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0" v-else-if="schedule.tuesday === 0" ></div>
                                        <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0 bg-secondary" v-else >
                                            <div class="t-w-full t-h-full bg-success"   v-if="index === 0 || schedule.tuesday.id  !== sectionScheduleData[index - 1].tuesday.id" >
                                                <div class="" >
                                                    <h6 class="t-p-1 t-text-white t-truncate text-uppercase t-text-center t-text-[12px] " :title="schedule.tuesday.subject" >{{ schedule.tuesday.professor }}</h6>
                                                </div>
                                            </div>
                                            <div class="t-h-full t-w-full t-overflow-hidden t-flex t-items-center" >
                                                <div class="bg-danger t-h-full t-w-full"  v-if="schedule.tuesday === 1" >
                                                    <br>
                                                </div>
                                                <div class="t-h-full t-w-full" v-else-if="schedule.tuesday === 0" >
                                                    <br>
                                                </div>
                                                <div class="bg-success t-h-full t-w-full t-relative t-flex t-items-center t-justify-center " v-else >
                                                    <div class="bg-success t-h-full t-w-full"  v-if=" index >= 3  &&  schedule.tuesday.id === sectionScheduleData[index - 3].tuesday.id &&  schedule.tuesday.professor === sectionScheduleData[index - 1].tuesday.professor " >
                                                        <br>
                                                    </div>
                                                    <div  class="t-w-full t-h-full bg-success t-flex t-items-center t-justify-center" v-else-if=" index >= 2 &&  schedule.tuesday.id === sectionScheduleData[index - 2].tuesday.id &&  schedule.tuesday.professor === sectionScheduleData[index - 1].tuesday.professor " >
                                                        <h6 class=" t-text-white t-truncate text-uppercase t-text-center t-text-[12px]" :title="schedule.tuesday.section+'-'+schedule.tuesday.classroom" >{{schedule.tuesday.section}} , {{ schedule.tuesday.classroom }}</h6>
                                                    </div>
                                                    <div class="bg-success t-max-h-full t-overflow-hidden" v-else-if=" index >= 1 &&  schedule.tuesday.id === sectionScheduleData[index - 1].tuesday.id &&  schedule.tuesday.professor === sectionScheduleData[index - 1].tuesday.professor " >
                                                        <h6 class=" t-text-white t-truncate text-uppercase t-text-center t-text-[12px]" :title="schedule.tuesday.subject" >{{ schedule.tuesday.subject }}</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="t-relative" >
                                        <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0 bg-danger" v-if="schedule.wednesday === 1" ></div>
                                        <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0" v-else-if="schedule.wednesday === 0" ></div>
                                        <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0 bg-secondary" v-else >
                                            <div class="t-w-full t-h-full bg-success"   v-if="index === 0 || schedule.wednesday.id  !== sectionScheduleData[index - 1].wednesday.id" >
                                                <div class="" >
                                                    <h6 class="t-p-1 t-text-white t-truncate text-uppercase t-text-center t-text-[12px] " :title="schedule.wednesday.subject" >{{ schedule.wednesday.professor }}</h6>
                                                </div>
                                            </div>
                                            <div class="t-h-full t-w-full t-overflow-hidden t-flex t-items-center" >
                                                <div class="bg-danger t-h-full t-w-full"  v-if="schedule.wednesday === 1" >
                                                    <br>
                                                </div>
                                                <div class="t-h-full t-w-full" v-else-if="schedule.wednesday === 0" >
                                                    <br>
                                                </div>
                                                <div class="bg-success t-h-full t-w-full t-relative t-flex t-items-center t-justify-center " v-else >
                                                    <div class="bg-success t-h-full t-w-full"  v-if=" index >= 3  &&  schedule.wednesday.id === sectionScheduleData[index - 3].wednesday.id &&  schedule.wednesday.professor === sectionScheduleData[index - 1].wednesday.professor " >
                                                        <br>
                                                    </div>
                                                    <div  class="t-w-full t-h-full bg-success t-flex t-items-center t-justify-center" v-else-if=" index >= 2 &&  schedule.wednesday.id === sectionScheduleData[index - 2].wednesday.id &&  schedule.wednesday.professor === sectionScheduleData[index - 1].wednesday.professor " >
                                                        <h6 class=" t-text-white t-truncate text-uppercase t-text-center t-text-[12px]" :title="schedule.wednesday.section+'-'+schedule.wednesday.classroom" >{{schedule.wednesday.section}} , {{ schedule.wednesday.classroom }}</h6>
                                                    </div>
                                                    <div class="bg-success t-max-h-full t-overflow-hidden" v-else-if=" index >= 1 &&  schedule.wednesday.id === sectionScheduleData[index - 1].wednesday.id &&  schedule.wednesday.professor === sectionScheduleData[index - 1].wednesday.professor " >
                                                        <h6 class=" t-text-white t-truncate text-uppercase t-text-center t-text-[12px]" :title="schedule.wednesday.subject" >{{ schedule.wednesday.subject }}</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="t-relative" >
                                        <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0 bg-danger" v-if="schedule.thursday === 1" ></div>
                                        <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0" v-else-if="schedule.thursday === 0" ></div>
                                        <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0 bg-secondary" v-else >
                                            <div class="t-w-full t-h-full bg-success"   v-if="index === 0 || schedule.thursday.id  !== sectionScheduleData[index - 1].thursday.id" >
                                                <div class="" >
                                                    <h6 class="t-p-1 t-text-white t-truncate text-uppercase t-text-center t-text-[12px] " :title="schedule.thursday.subject" >{{ schedule.thursday.professor }}</h6>
                                                </div>
                                            </div>
                                            <div class="t-h-full t-w-full t-overflow-hidden t-flex t-items-center" >
                                                <div class="bg-danger t-h-full t-w-full"  v-if="schedule.thursday === 1" >
                                                    <br>
                                                </div>
                                                <div class="t-h-full t-w-full" v-else-if="schedule.thursday === 0" >
                                                    <br>
                                                </div>
                                                <div class="bg-success t-h-full t-w-full t-relative t-flex t-items-center t-justify-center " v-else >
                                                    <div class="bg-success t-h-full t-w-full"  v-if=" index >= 3  &&  schedule.thursday.id === sectionScheduleData[index - 3].thursday.id &&  schedule.thursday.professor === sectionScheduleData[index - 1].thursday.professor " >
                                                        <br>
                                                    </div>
                                                    <div  class="t-w-full t-h-full bg-success t-flex t-items-center t-justify-center" v-else-if=" index >= 2 &&  schedule.thursday.id === sectionScheduleData[index - 2].thursday.id &&  schedule.thursday.professor === sectionScheduleData[index - 1].thursday.professor " >
                                                        <h6 class=" t-text-white t-truncate text-uppercase t-text-center t-text-[12px]" :title="schedule.thursday.section+'-'+schedule.thursday.classroom" >{{schedule.thursday.section}} , {{ schedule.thursday.classroom }}</h6>
                                                    </div>
                                                    <div class="bg-success t-max-h-full t-overflow-hidden" v-else-if=" index >= 1 &&  schedule.thursday.id === sectionScheduleData[index - 1].thursday.id &&  schedule.thursday.professor === sectionScheduleData[index - 1].thursday.professor " >
                                                        <h6 class=" t-text-white t-truncate text-uppercase t-text-center t-text-[12px]" :title="schedule.thursday.subject" >{{ schedule.thursday.subject }}</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="t-relative" >
                                        <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0 bg-danger" v-if="schedule.friday === 1" ></div>
                                        <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0" v-else-if="schedule.friday === 0" ></div>
                                        <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0 bg-secondary" v-else >
                                            <div class="t-w-full t-h-full bg-success"   v-if="index === 0 || schedule.friday.id  !== sectionScheduleData[index - 1].friday.id" >
                                                <div class="" >
                                                    <h6 class="t-p-1 t-text-white t-truncate text-uppercase t-text-center t-text-[12px] " :title="schedule.friday.subject" >{{ schedule.friday.professor }}</h6>
                                                </div>
                                            </div>
                                            <div class="t-h-full t-w-full t-overflow-hidden t-flex t-items-center" >
                                                <div class="bg-danger t-h-full t-w-full"  v-if="schedule.friday === 1" >
                                                    <br>
                                                </div>
                                                <div class="t-h-full t-w-full" v-else-if="schedule.friday === 0" >
                                                    <br>
                                                </div>
                                                <div class="bg-success t-h-full t-w-full t-relative t-flex t-items-center t-justify-center " v-else >
                                                    <div class="bg-success t-h-full t-w-full"  v-if=" index >= 3  &&  schedule.friday.id === sectionScheduleData[index - 3].friday.id &&  schedule.friday.professor === sectionScheduleData[index - 1].friday.professor " >
                                                        <br>
                                                    </div>
                                                    <div  class="t-w-full t-h-full bg-success t-flex t-items-center t-justify-center" v-else-if=" index >= 2 &&  schedule.friday.id === sectionScheduleData[index - 2].friday.id &&  schedule.friday.professor === sectionScheduleData[index - 1].friday.professor " >
                                                        <h6 class=" t-text-white t-truncate text-uppercase t-text-center t-text-[12px]" :title="schedule.friday.section+'-'+schedule.friday.classroom" >{{schedule.friday.section}} , {{ schedule.friday.classroom }}</h6>
                                                    </div>
                                                    <div class="bg-success t-max-h-full t-overflow-hidden" v-else-if=" index >= 1 &&  schedule.friday.id === sectionScheduleData[index - 1].friday.id &&  schedule.friday.professor === sectionScheduleData[index - 1].friday.professor " >
                                                        <h6 class=" t-text-white t-truncate text-uppercase t-text-center t-text-[12px]" :title="schedule.friday.subject" >{{ schedule.friday.subject }}</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="t-relative" >
                                        <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0 bg-danger" v-if="schedule.saturday === 1" ></div>
                                        <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0" v-else-if="schedule.saturday === 0" ></div>
                                        <div class="t-absolute t-w-full t-h-full t-top-0 t-left-0 bg-secondary" v-else >
                                            <div class="t-w-full t-h-full bg-success"   v-if="index === 0 || schedule.saturday.id  !== sectionScheduleData[index - 1].saturday.id" >
                                                <div class="" >
                                                    <h6 class="t-p-1 t-text-white t-truncate text-uppercase t-text-center t-text-[12px] " :title="schedule.saturday.subject" >{{ schedule.saturday.professor }}</h6>
                                                </div>
                                            </div>
                                            <div class="t-h-full t-w-full t-overflow-hidden t-flex t-items-center" >
                                                <div class="bg-danger t-h-full t-w-full"  v-if="schedule.saturday === 1" >
                                                    <br>
                                                </div>
                                                <div class="t-h-full t-w-full" v-else-if="schedule.saturday === 0" >
                                                    <br>
                                                </div>
                                                <div class="bg-success t-h-full t-w-full t-relative t-flex t-items-center t-justify-center " v-else >
                                                    <div class="bg-success t-h-full t-w-full"  v-if=" index >= 3  &&  schedule.saturday.id === sectionScheduleData[index - 3].saturday.id &&  schedule.saturday.professor === sectionScheduleData[index - 1].saturday.professor " >
                                                        <br>
                                                    </div>
                                                    <div  class="t-w-full t-h-full bg-success t-flex t-items-center t-justify-center" v-else-if=" index >= 2 &&  schedule.saturday.id === sectionScheduleData[index - 2].saturday.id &&  schedule.saturday.professor === sectionScheduleData[index - 1].saturday.professor " >
                                                        <h6 class=" t-text-white t-truncate text-uppercase t-text-center t-text-[12px]" :title="schedule.saturday.section+'-'+schedule.saturday.classroom" >{{schedule.saturday.section}} , {{ schedule.saturday.classroom }}</h6>
                                                    </div>
                                                    <div class="bg-success t-max-h-full t-overflow-hidden" v-else-if=" index >= 1 &&  schedule.saturday.id === sectionScheduleData[index - 1].saturday.id &&  schedule.saturday.professor === sectionScheduleData[index - 1].saturday.professor " >
                                                        <h6 class=" t-text-white t-truncate text-uppercase t-text-center t-text-[12px]" :title="schedule.saturday.subject" >{{ schedule.saturday.subject }}</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div v-if="current_tab == 'classroom'" >classroom</div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import { ref,inject,computed } from 'vue';
import Swal from 'sweetalert2';

import {scheduleStore} from '../../services/schedule';
const use_scheduleStore = scheduleStore();


const isProcess = ref(false);
const isSearch = ref('');

const focusInput = ref('');

const focus_function = ()=>{
    focusInput.value= true;
}

const blur_function = ()=>{
    focusInput.value = false;
}


const globalProfessorData = inject('globalProfessorData');   
const professorData = ref(globalProfessorData);
const computedProfessor = computed(()=>{
    if(isSearch.value != ""){
        return professorData.value.filter(professor=>{
            return professor.fullname.toLowerCase().includes(isSearch.value.toLowerCase());
        })
    }
    else{
        return professorData.value;
    }
})

const current_tab = ref('professor');
const change_tab = (change)=>{
    current_tab.value = change;
}

const timers = ref([
    {id: 0, label: "7:00 AM"},{id: 1, label: "7:30 AM"},
    {id: 2, label: "8:00 AM"},{id: 3, label: "8:30 AM"},
    {id: 4, label: "9:00 AM"},{id: 5, label: "9:30 AM"},
    {id: 6, label: "10:00 AM"},{id: 7, label: "10:30 AM"},
    {id: 8, label: "11:00 AM"},{id: 9, label: "11:30 AM"},
    {id: 10, label: "12:00 NN"},{id: 11, label: "12:30 PM"},
    {id: 12, label: "1:00 PM"},{id: 12, label: "1:30 PM"},
    {id: 14, label: "2:00 PM"},{id: 15, label: "2:30 PM"},
    {id: 16, label: "3:00 PM"},{id: 17, label: "3:30 PM"},
    {id: 18, label: "4:00 PM"},{id: 19, label: "4:30 PM"},
    {id: 20, label: "5:00 PM"},{id: 21, label: "5:30 PM"},
    {id: 22, label: "6:00 PM"},{id: 23, label: "6:30 PM"},
    {id: 24, label: "7:00 PM"},{id: 25, label: "7:30 PM"},
    {id: 26, label: "8:00 PM"},{id: 27, label: "8:30 PM"},
    {id: 28, label: "9:00 PM"},{id: 29, label: "9:30 PM"},
    {id: 30, label: "10:00 PM"},
]);

const endTime = ref([]);
const filterTime = ()=>{

    let time = 0;
    if(schedules.value.work == "lecture"){
        time = subjectHour.value.lec * 2;
    }else{
        time = subjectHour.value.lab * 2;
    }
    console.log(time)
    let total = (schedules.value.start + time) ;
    let data = timers.value.filter((item)=>item.id >= (schedules.value.start + time) && item.id <= 30 );
    console.log(data)
if(data.length ==0){
    data = timers.value.filter((item)=>item.id>=(30-time)&&item.id<=30)
    schedules.value.end = 30;
    schedules.value.start = data[0].id;
}else{

    schedules.value.end = total;
}
console.log(data)
endTime.value = data;

    

}

const schedules = ref({
    day: '',
    start: 0,
    end: 1,
    work: "",
    timeWork: 0,
    professor: '',
    subject: '',
    classclassroom: '',
    section: '',
    semester: '1st semester',
});

const selectedProfessor = (professor)=>{
    schedules.value.professor = professor;
    getLoads(professor)
    isSearch.value = "";
    getProfessorSchedule()
}

const subjectHour = ref({
    lab: 0,
    lec: 0,
})

const change_load = ()=>{
    selectedSubject()
    getSections()
}
const selectedSubject = ()=>{
    const response = loadData.value.find(item => item.code === schedules.value.subject);
    subjectHour.value.lec  =response.lec;
    subjectHour.value.lab  =response.lab;
}

const change_work = ()=>{
    schedules.value.classclassroom = "";
    selectedHour()
    getClassrooms()
}

const selectedHour  = ()=>{
    
    if(schedules.value.work === "lecture"){
        schedules.value.timeWork = subjectHour.value.lec;
    }
    else{
        schedules.value.timeWork = subjectHour.value.lab;
    }
}

const classroomData = ref([]);
const getClassrooms = async()=>{
    try {
        isProcess.value = true;
        await use_scheduleStore.getClassrooms(schedules.value.work);
        classroomData.value = use_scheduleStore.getResponse;
        isProcess.value= false;
    } catch (error) {
        console.error(error)
    }
}

const sectionData = ref([]);
const getSections = async()=>{
    try {
        isProcess.value = true;
        await use_scheduleStore.getSections(schedules.value.subject);
        sectionData.value = use_scheduleStore.getResponse;
        isProcess.value= false;
    } catch (error) {
        console.error(error)
    }
}

const loadData = ref([]);
const getLoads = async (professor)=>{
    try {
        isProcess.value = true;

        await use_scheduleStore.getLoads(professor);
        loadData.value = use_scheduleStore.getResponse;

        isProcess.value = false;
    } catch (error) {
        console.error(error)
    }
}

const create_schedule = async()=>{
    try {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes,Save it!"
        }).then(async(result) => {
            if (result.isConfirmed) {
                await  use_scheduleStore.create_schedule({
                    day:schedules.value.day,
                    start: schedules.value.start,
                    end: schedules.value.end,
                    professor: schedules.value.professor,
                    subject: schedules.value.subject,
                    classroom: schedules.value.classroom,
                    section: schedules.value.section
                });

                const response = use_scheduleStore.getResponse;
                if(response.status){
                    getProfessorSchedule();
                    getClassroomSchedule();
                    getSectionSchedule();
                    Swal.fire('Success',response.msg,'success');
                }
                else{
                    Swal.fire("Error",response.msg,"error");
                }
            }
        });
    } catch (error) {
        console.error(error)
    }
}

const professorScheduleData = ref([]);
const getProfessorSchedule = async ()=>{
    try {
        await use_scheduleStore.getProfessorSchedule(schedules.value.professor)
        professorScheduleData.value = use_scheduleStore.getSchedule;
        current_tab.value = "professor"
    } catch (error) {
        console.error(error);
    }
}

const classroomScheduleData = ref([]);
const getClassroomSchedule = async ()=>{
    try {
        await use_scheduleStore.getClassroomSchedule(schedules.value.classroom)
        classroomScheduleData.value = use_scheduleStore.getSchedule;
        current_tab.value = "classroom"
    } catch (error) {
        console.error(error);
    }
}

const sectionScheduleData = ref([]);
const getSectionSchedule = async ()=>{
    try {
        await use_scheduleStore.getSectionSchedule(schedules.value.section)
        sectionScheduleData.value = use_scheduleStore.getSchedule;
        current_tab.value = "section"
    } catch (error) {
        console.error(error);
    }
}



</script>