import { defineStore } from "pinia";
import apiRequest from "./api";

export const scheduleStore = defineStore('schedules',{
    state: (()=>({
        schedule: '',
        response: '',
    })),
    actions:{

        async automation(){
            const formData = new FormData();
            formData.append('tokens', localStorage.getItem('tokens'));

            const response = await apiRequest.post('/api/schedule/automated',formData)
            this.response = response;

        },

        async create(data){
            const {professor,semester,subject,room,section,day,end,start} = data;
            const formData = new FormData();
            formData.append('tokens', localStorage.getItem('tokens'));
            formData.append('professor',professor);
            formData.append('semester',semester);
            formData.append('subject',subject);
            formData.append('classroom',room);
            formData.append('section',section);
            formData.append('day',day);
            formData.append('start',start);
            formData.append('end',end);
            
            const response = await apiRequest.post('/api/schedule/create',formData);
            this.response = response;

        },

        async professor(data){
            const {professor,semester} = data;
            const formData = new FormData();
            formData.append('professor',professor);
            formData.append('semester',semester);
            const response = await apiRequest.post('/api/schedule/professor',formData);
            this.schedule = response;
        },
        async classroom(data){
            const {classroom,semester} = data;
            const formData = new FormData();
            formData.append('classroom',classroom);
            formData.append('semester',semester);
            const response = await apiRequest.post('/api/schedule/classroom',formData);
            this.schedule = response;
        },
        async section(data){
            const {section,semester} = data;
            const formData = new FormData();
            formData.append('section',section);
            formData.append('semester',semester);
            const response = await apiRequest.post('/api/schedule/section',formData);
            this.schedule = response;
        },
        async showDelete(data){
            const {professor,semester} = data;
            const formData = new FormData();
            formData.append('professor',professor);
            formData.append('semester',semester);
            const response = await apiRequest.post('/api/schedule/showDelete',formData);
            this.response = response;
        },
        async delete(id){
            const response =  await apiRequest.get(`/api/schedule/delete/${id}`);
            this.response = response;
        },
        async submitSchedule(){
            const response = await apiRequest.get('/api/schedule/submitSchedule');
            this.response = response;
        },
        async myHour(){
            const {professor,semester} = data;
            const formData = new FormData();
            formData.append('professor',professor);
            formData.append('semester',semester);
            const response = await apiRequest.post('/api/schedule/showDelete',formData);
            this.response = response;
        },
        async showLoad(data){
            const {professor,semester} = data;
            const formData = new FormData();
            formData.append('professor',professor);
            formData.append('semester',semester);
            formData.append("tokens", localStorage.getItem('tokens'));
            const response = await apiRequest.post('/api/schedule/professorLoad',formData);
            console.log(response)
            this.response = response;
        },
        async resetSchedule(password){
            const formData = new FormData();
            formData.append('password',password);
            formData.append('tokens',localStorage.getItem('tokens'));
            const response = await apiRequest.post('/api/schedule/reset',formData);
            this.response = response;
        },
        async notifyOtherDepartment(department){
            
            const formData = new FormData();
            formData.append('other_dep',department);
            formData.append('tokens',localStorage.getItem('tokens'));

            const response = await apiRequest.post('/api/schedule/notify-other-department',formData);
            this.response = response;

        },
        async notifyMinorToDean(department){
            const formData = new FormData();
            formData.append('other_dep',department);
            formData.append('tokens',localStorage.getItem('tokens'));

            const response = await apiRequest.post('/api/schedule/notify-minor-to-dean',formData);
            console.log(response);
            this.response = response;
            console.log(department)
        }

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