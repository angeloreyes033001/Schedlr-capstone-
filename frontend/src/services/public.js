import { defineStore } from "pinia";
import apiRequest from "./api";

export const publicStore = defineStore( "publics" ,{
    state: (()=>({
        response: '',
        schedule: '',
        dataprint: '',
    })),
    actions: {
        async readProfessors(department){
            const response = await apiRequest.get(`/api/public/showProfessor/${department}`);
            this.response = response;
        },
        async readProfessorSchedule(data){
            const {professor,semester} = data;
            const formData = new FormData();
            formData.append("professor",professor);
            formData.append("semester",semester);

            const response = await apiRequest.post('/api/public/scheduleProfessor',formData);
            this.schedule = response;
        },
        async readprintSchedule(data){
            const {professor,semester} = data;
            const formData = new FormData();
            formData.append("professor",professor);
            formData.append("semester",semester);

            const response = await apiRequest.post('/api/public/printProfessor',formData);
            this.dataprint = response;
        },

        async readClassrooms(department){
            const response = await apiRequest.get(`/api/public/showClassroom/${department}`);
            this.response = response;
        },
        async readClassroomSchedule(data){
            const {classroom,semester} = data;
            const formData = new FormData();
            formData.append("classroom",classroom);
            formData.append("semester",semester);

            const response = await apiRequest.post('/api/public/scheduleClassroom',formData);
            this.schedule = response;
        },
        async readprintClassroom(data){
            const {classroom,semester} = data;
            const formData = new FormData();
            formData.append("classroom",classroom);
            formData.append("semester",semester);

            const response = await apiRequest.post('/api/public/printClassroom',formData);
            this.dataprint = response;
        },

        async readSections(department){
            const response = await apiRequest.get(`/api/public/showSection/${department}`);
            this.response = response;
        },
        async readSectionSchedule(data){
            const {section,semester} = data;
            const formData = new FormData();
            formData.append("section",section);
            formData.append("semester",semester);

            const response = await apiRequest.post('/api/public/scheduleSection',formData);
            this.schedule = response;
        },
        async readprintSection(data){
            const {section,semester} = data;
            const formData = new FormData();
            formData.append("section",section);
            formData.append("semester",semester);

            const response = await apiRequest.post('/api/public/printSection',formData);
            this.dataprint = response;
        },

        async readDeanProfessorScheudle(semester){
            const formData = new FormData();
            formData.append("semester", semester);
            formData.append("tokens", localStorage.getItem('tokens'));

            const response = await apiRequest.post('/api/public/deanProfessor',formData);
            this.schedule = response;
        },
        async readDeanSectionScheudle(semester){
            const formData = new FormData();
            formData.append("semester", semester);
            formData.append("tokens", localStorage.getItem('tokens'));

            const response = await apiRequest.post('/api/public/deanSection',formData);
            this.schedule = response;
        },
        async readDeanClassroomScheudle(semester){
            const formData = new FormData();
            formData.append("semester", semester);
            formData.append("tokens", localStorage.getItem('tokens'));

            const response = await apiRequest.post('/api/public/deanClassroom',formData);
            this.schedule = response;
        },

        async last_added(){
            const tokens =  localStorage.getItem('tokens')
            const response = await apiRequest.get(`/api/public/deanlast/${tokens}`);
            this.response = response;
        },

        async last_added_department(){
            const tokens =  localStorage.getItem('tokens')
            const response = await apiRequest.get(`/api/public/deanlastDepartment/${tokens}`);
            this.response = response;
        }
    },
    getters: {
        getResponse(state){
            return state.response
        },
        getSchedule(state){
            return state.schedule
        },
        getDataPrint(state){
            return state.dataprint;
        }
    }
})