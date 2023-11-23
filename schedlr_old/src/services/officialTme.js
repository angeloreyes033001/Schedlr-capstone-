import { defineStore } from "pinia";
import apiRequest from "./api";

export const officialStore = defineStore('official_time',{
    state: (()=>({
        official: null,
        response: null,
    })),
    actions:{
        async create(data){
            const formData = new FormData();
            formData.append("professor",data.professor);
            formData.append("day",data.day);
            formData.append("start",data.start);
            formData.append("end",data.end);
            formData.append("semester",data.semester);
            const response = await apiRequest.post("/api/officialtime/create",formData);
            this.response = response;
        },
        async read(data){
            const formData = new FormData();
            formData.append("professor",data.professor);
            formData.append("semester",data.semester);
            const response = await apiRequest.post('/api/officialtime/read',formData);
            this.official = response;
            
        },

        async delete(id){
            const response = await apiRequest.get(`/api/officialtime/delete/${id}`);
            this.response = response;
        },

        async showDelete(data){
            const formData = new FormData();
            formData.append("professor",data.professor);
            formData.append("semester",data.semester);
            const response = await apiRequest.post('/api/officialtime/showDelete',formData);
            this.response = response;
        }
    },
    getters:{
        getOfficial(state){
            return state.official;
        },
        getResponse(state){
            return state.response;
        }
    }
})