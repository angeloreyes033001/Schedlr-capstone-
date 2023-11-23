<template>
    <div class="t-h-full t-pt-[10px] t-overflow-x-auto p-0 m-0 shadow" >
        <table class="table table-borderless " >
            <thead>
                <tr class="text-uppercase text-center" >
                    <th class="bg-primary text-white" >Time</th>
                    <th class="bg-primary text-white" >Monday</th>
                    <th class="bg-primary text-white" >Tuesday</th>
                    <th class="bg-primary text-white" >Wednesday</th>
                    <th class="bg-primary text-white" >Thursday</th>
                    <th class="bg-primary text-white" >Friday</th>
                    <th class="bg-primary text-white" >Saturday</th>
                </tr>
            </thead>
            <tbody>
                <tr class="text-center" v-for="(data,index) in datas" >
                    <td class="text-uppercase" >{{ data.time }}</td>
                    <td :class="{'bg-info': data.mon != 0 }" >
                        <span v-if="data.mon==0" >
                            
                        </span>
                        <span v-else >
                            <span v-if="index === 0 || data.mon.id !== datas[index - 1].mon.id "  >
                                <span v-if="schedules == 'professors'" >
                                    {{ data.mon.classroom }}
                                </span>
                                <span v-else >
                                    {{ data.mon.professor }}
                                </span>
                            </span>
                            <span v-else >
                                <small v-if=" index >= 2 &&  data.mon.id === datas[index - 2].mon.id " >
                                    <br>
                                </small>
                                <small v-else >
                                    <small   >
                                       <span class="text-uppercase" v-if="schedules == 'professors'" >
                                            {{ data.mon.subject }} , {{ data.mon.section }} 
                                       </span>
                                       <span class="text-uppercase" v-else-if="schedules == 'sections'" >
                                            {{ data.mon.subject }} , {{ data.mon.classroom }} 
                                       </span>
                                       <span class="text-uppercase" v-else >
                                            {{ data.mon.subject }} , {{ data.mon.section }} 
                                       </span>
                                    </small>
                                </small>
                            </span>
                        </span>
                    </td>
                    <td :class="{'bg-info': data.tue != 0 }" >
                        <span v-if="data.tue==0" >
                            
                        </span>
                        <span v-else >
                            <span v-if="index === 0 || data.tue.id !== datas[index - 1].tue.id "  >
                                <span v-if="schedules == 'professors'" >
                                    {{ data.tue.classroom }}
                                </span>
                                <span v-else >
                                    {{ data.tue.professor }}
                                </span>
                            </span>
                            <span v-else >
                                <small v-if=" index >= 2 &&  data.tue.id === datas[index - 2].tue.id " >
                                    <br>
                                </small>
                                <small v-else >
                                    <small   >
                                       <span class="text-uppercase" v-if="schedules == 'professors'" >
                                            {{ data.tue.subject }} , {{ data.tue.section }} 
                                       </span>
                                       <span class="text-uppercase" v-else-if="schedules == 'sections'" >
                                            {{ data.tue.subject }} , {{ data.tue.classroom }} 
                                       </span>
                                       <span class="text-uppercase" v-else >
                                            {{ data.tue.subject }} , {{ data.tue.section }} 
                                       </span>
                                    </small>
                                </small>
                            </span>
                        </span>
                    </td>
                    <td :class="{'bg-info': data.wed != 0 }" >
                        <span v-if="data.wed==0" >
                            
                        </span>
                        <span v-else >
                            <span v-if="index === 0 || data.wed.id !== datas[index - 1].wed.id "  >
                                <span v-if="schedules == 'professors'" >
                                    {{ data.wed.classroom }}
                                </span>
                                <span v-else >
                                    {{ data.wed.professor }}
                                </span>
                            </span>
                            <span v-else >
                                <small v-if=" index >= 2 &&  data.wed.id === datas[index - 2].wed.id " >
                                    <br>
                                </small>
                                <small v-else >
                                    <small   >
                                       <span class="text-uppercase" v-if="schedules == 'professors'" >
                                            {{ data.wed.subject }} , {{ data.wed.section }} 
                                       </span>
                                       <span class="text-uppercase" v-else-if="schedules == 'sections'" >
                                            {{ data.wed.subject }} , {{ data.wed.classroom }} 
                                       </span>
                                       <span class="text-uppercase" v-else >
                                            {{ data.wed.subject }} , {{ data.wed.section }} 
                                       </span>
                                    </small>
                                </small>
                            </span>
                        </span>
                    </td>
                    <td :class="{'bg-info': data.thu != 0 }" >
                        <span v-if="data.thu==0" >
                            
                        </span>
                        <span v-else >
                            <span v-if="index === 0 || data.thu.id !== datas[index - 1].thu.id "  >
                                <span v-if="schedules == 'professors'" >
                                    {{ data.thu.classroom }}
                                </span>
                                <span v-else >
                                    {{ data.thu.professor }}
                                </span>
                            </span>
                            <span v-else >
                                <small v-if=" index >= 2 &&  data.thu.id === datas[index - 2].thu.id " >
                                    <br>
                                </small>
                                <small v-else >
                                    <small   >
                                       <span class="text-uppercase" v-if="schedules == 'professors'" >
                                            {{ data.thu.subject }} , {{ data.thu.section }} 
                                       </span>
                                       <span class="text-uppercase" v-else-if="schedules == 'sections'" >
                                            {{ data.thu.subject }} , {{ data.thu.classroom }} 
                                       </span>
                                       <span class="text-uppercase" v-else >
                                            {{ data.thu.subject }} , {{ data.thu.section }} 
                                       </span>
                                    </small>
                                </small>
                            </span>
                        </span>
                    </td>
                    <td :class="{'bg-info': data.fri != 0 }" >
                        <span v-if="data.fri==0" >
                            
                        </span>
                        <span v-else >
                            <span v-if="index === 0 || data.fri.id !== datas[index - 1].fri.id "  >
                                <span v-if="schedules == 'professors'" >
                                    {{ data.fri.classroom }}
                                </span>
                                <span v-else >
                                    {{ data.fri.professor }}
                                </span>
                            </span>
                            <span v-else >
                                <small v-if=" index >= 2 &&  data.fri.id === datas[index - 2].fri.id " >
                                    <br>
                                </small>
                                <small v-else >
                                    <small   >
                                       <span class="text-uppercase" v-if="schedules == 'professors'" >
                                            {{ data.fri.subject }} , {{ data.fri.section }} 
                                       </span>
                                       <span class="text-uppercase" v-else-if="schedules == 'sections'" >
                                            {{ data.fri.subject }} , {{ data.fri.classroom }} 
                                       </span>
                                       <span class="text-uppercase" v-else >
                                            {{ data.fri.subject }} , {{ data.fri.section }} 
                                       </span>
                                    </small>
                                </small>
                            </span>
                        </span>
                    </td>
                    <td :class="{'bg-info': data.sat != 0 }" >
                        <span v-if="data.sat==0" >
                            
                        </span>
                        <span v-else >
                            <span v-if="index === 0 || data.sat.id !== datas[index - 1].sat.id "  >
                                <span v-if="schedules == 'professors'" >
                                    {{ data.sat.classroom }}
                                </span>
                                <span v-else >
                                    {{ data.sat.professor }}
                                </span>
                            </span>
                            <span v-else >
                                <small v-if=" index >= 2 &&  data.sat.id === datas[index - 2].sat.id " >
                                    <br>
                                </small>
                                <small v-else >
                                    <small   >
                                       <span class="text-uppercase" v-if="schedules == 'professors'" >
                                            {{ data.sat.subject }} , {{ data.sat.section }} 
                                       </span>
                                       <span class="text-uppercase" v-else-if="schedules == 'sections'" >
                                            {{ data.sat.subject }} , {{ data.sat.classroom }} 
                                       </span>
                                       <span class="text-uppercase" v-else >
                                            {{ data.sat.subject }} , {{ data.sat.section }} 
                                       </span>
                                    </small>
                                </small>
                            </span>
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
<script setup >
import { ref } from 'vue';

const props = defineProps({
    datas: {
        type: Array,
        required: true,
    },
    schedules: {
        type: String,
        required: true,
    }
})

</script>