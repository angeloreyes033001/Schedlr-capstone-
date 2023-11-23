import { defineStore } from "pinia";
import apiRequest from "./api";

export const designationStore = defineStore("designations", {
    state: ()=>({
        designation: '',
        response: '',
    }),
    actions: {
        async create(data){
            const formData = new FormData();
            formData.append('position',data.position);
            formData.append('hour',data.hour);
            const response = await apiRequest.post('/api/designations/create',formData);
            this.response = response;
        },
        async read(){
            const response = await apiRequest.get('/api/designations/read');
            this.designation = response;
        },
        async update(data){
            const formData = new FormData();
            formData.append('position',data.position);
            formData.append('hour',data.hour);
            const response = await apiRequest.post('/api/designations/update',formData);
            this.response = response;
        },
        async delete(position){
            const response = await apiRequest.get(`/api/designations/delete/${position}`);
            this.response = response;
        }
    },
    getters:{
        getDesignation(state){
            return state.designation;
        },
        getResponse(state){
            return state.response;
        }
    }
})