import { defineStore } from "pinia";
import apiRequest from "./api";

export const scheduleStore = defineStore('schedules',{
    state: (()=>({
        schedule: '',
        response: '',
    })),
    actions:{

        async getLoads(professor){
            const response = await apiRequest.get(`/api/schedules/loads/${professor}`);
            this.response = response;
        },
        async getClassrooms(type){
            const formData = new FormData();
            formData.append('type',type);
            formData.append('tokens',localStorage.getItem('tokens'));
            const response = await apiRequest.post('/api/schedules/showClassroom',formData);
            this.response= response;
        },

        async getSections(subject){
            const formData = new FormData();
            formData.append('subject',subject);
            formData.append('tokens',localStorage.getItem('tokens'));

            const response = await apiRequest.post('/api/schedules/showSection',formData);
            this.response = response;
        },

        async getProfessorSchedule(professor){
            const response = await apiRequest.get(`/api/schedules/professorSchedule/${professor}`);
            this.schedule = response
        },

        async getClassroomSchedule(classroom){
            const response = await apiRequest.get(`/api/schedules/classroomSchedule/${classroom}`);
            this.schedule = response
        },
        async create_schedule(data){
            const formData = new FormData();
            formData.append("day",data.day);
            formData.append("start",data.start);
            formData.append("end",data.end);
            formData.append("professor",data.professor);
            formData.append("subject",data.subject);
            formData.append("classroom",data.classroom);
            formData.append("section",data.section);
            
            const response = await apiRequest.post('/api/schedules/create_schedule',formData);
            this.response = response;
        },

        async getSectionSchedule(section){
            const response = await apiRequest.get(`/api/schedules/sectionSchedule/${section}`);
            this.schedule = response
        },
        async getScheduleAvailbleDelete(professor){
            const response = await apiRequest.get(`/api/schedules/showAvailableDelete/${professor}`);
            this.response = response
        },
        async delete_schedule(id){
            const response = await apiRequest.get(`/api/schedules/delete_schedule/${id}`);
            this.response = response;
            
        },

    },
    getters:{
        getSchedule(state){
            return state.schedule;
        },
        getResponse(state){
            return state.response;
        }
    }
})