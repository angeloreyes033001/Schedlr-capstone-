import { defineStore } from "pinia";
import apiRequest from "./api";

export const generalStore = defineStore( "generals" ,{
    state: (()=>({
        response: '',
        subject: '',
        classroom: '',
        section: '',
        admin: '',
    })),
    actions: {
        async read_subject(){
            const tokens = localStorage.getItem('tokens');
            const response = await apiRequest.get(`/api/generals/total_subject/${tokens}`);
            this.subject = response;
        },

        async read_classroom(){
            const tokens = localStorage.getItem('tokens');
            const response = await apiRequest.get(`/api/generals/total_classroom/${tokens}`);
            this.classroom = response;
        },

        async read_section(){
            const tokens = localStorage.getItem('tokens');
            const response = await apiRequest.get(`/api/generals/total_section/${tokens}`);
            this.section = response;
        },

        async read_admin(){
            const tokens = localStorage.getItem('tokens');
            const response = await apiRequest.get(`/api/generals/total_admin/${tokens}`);
            this.admin = response;
        },
        
        async show_schedule_public(){
            const tokens = localStorage.getItem('tokens');
            const response = await apiRequest.get(`/api/generals/show_public_schedule/${tokens}`);
            this.response = response;
        },

        async hidden_schedule_public(){
            const tokens = localStorage.getItem('tokens');
            const response = await apiRequest.get(`/api/generals/hidden_public_schedule/${tokens}`);
            this.response = response;
        },

        async minor_to_otherDepartment(other_dep){
            const formData = new FormData();
            formData.append('other_dep',other_dep);
            formData.append('tokens',localStorage.getItem('tokens'));

            const response = await apiRequest.post('/api/generals/dean_to_otherDepartment',formData);
            this.response = response;
        },

        async dean_to_otherDepartment(other_dep){
            const formData = new FormData();
            formData.append('other_dep',other_dep);
            formData.append('tokens',localStorage.getItem('tokens'));

            const response = await apiRequest.post('/api/generals/minor_to_otherDepartment',formData);
            this.response = response;
        }
    },
    getters: {
        getResponse(state){
            return state.response;
        },
        getSubject(state){
            return state.subject;
        },
        getClassroom(state){
            return state.classroom;
        },
        getSection(state){
            return state.section;
        },
        getAdmin(state){
            return state.admin;
        },
    }
})