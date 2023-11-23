import { defineStore } from "pinia";
import apiRequest from "./api";

export const professorStore = defineStore("professors",{
    state: (()=>({
        professor: null,
        response: null,
    })),
    actions: {
        async create(data){
            const formData  = new FormData();
            formData.append("id",data.professorID);
            formData.append("fullname",data.fullname);
            formData.append("status",data.status);
            formData.append("rank",data.rank);
            formData.append("designation",data.designation)
            formData.append("tokens", localStorage.getItem('tokens'));
            
            const response = await apiRequest.post('/api/professors/create',formData);
            this.response = response;

        },

        async read(){
            const tokens = localStorage.getItem('tokens');
            const response = await apiRequest.get(`/api/professors/read/${tokens}`);
            this.professor = response;
        },

        async update(data){
            const formData  = new FormData();
            formData.append("id",data.professorID);
            formData.append("fullname",data.fullname);
            formData.append("status",data.status);
            formData.append("rank",data.rank);
            formData.append("designation",data.designation)

            const response = await apiRequest.post('/api/professors/update',formData);
            this.response = response;
        },

        async delete(id){
            const response = await apiRequest.get(`/api/professors/delete/${id}`);
            this.response = response;
        }
    },
    getters:{
        getProfessor(state){
            return state.professor;
        },

        getResponse(state){
            return state.response;
        }
    }
});