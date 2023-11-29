import { defineStore } from "pinia";
import apiRequest from "./api";

export const publicStore = defineStore( "publics" ,{
    state: (()=>({
        response: '',
        schedule: '',
        dataprint: '',
    })),
    actions: {
        async read_professor(department){
            const response = await apiRequest.get(`/api/public/read_professor/${department}`)
            this.response = response;
        },
        async read_professor_schedule(data){
            const formData = new FormData();
            formData.append('professor',data.professor);
            formData.append('semester',data.semester);
            const response = await apiRequest.post('/api/public/read_professor_schedule',formData);
            this.schedule = response;
        },
        async read_classroom(department){
            const response = await apiRequest.get(`/api/public/read_classroom/${department}`)
            this.response = response;
        },
        async read_classroom_schedule(data){
            const formData = new FormData();
            formData.append('classroom',data.classroom);
            formData.append('semester',data.semester);
            const response = await apiRequest.post('/api/public/read_classroom_schedule',formData);
            this.schedule = response;
        },
        async read_section(department){
            const response = await apiRequest.get(`/api/public/read_section/${department}`)
            this.response = response;
        },
        async read_section_schedule(data){
            const formData = new FormData();
            formData.append('section',data.section);
            formData.append('semester',data.semester);
            const response = await apiRequest.post('/api/public/read_section_schedule',formData);
            this.schedule = response;
        },
        async read_print(data){
            const formData = new FormData();
            formData.append('id',data.id);
            formData.append('semester',data.semester);
            formData.append('identifier',data.identifier);
            const response = await apiRequest.post('/api/public/read_print',formData);
            this.dataprint = response;
        },
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