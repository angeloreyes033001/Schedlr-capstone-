import { defineStore } from "pinia";
import apiRequest from "./api";

export const sectionStore = defineStore('sections',{
    state: (()=>({
        response: null,
        sections: null,
    })),
    actions:{
        async create(data){
            const formData = new FormData();
            formData.append('section', data.section);
            formData.append('yearlevel',data.yearlevel);
            formData.append('specialization',data.specialization);
            formData.append('tokens', localStorage.getItem('tokens'));

            const response = await apiRequest.post('/api/sections/create',formData);
            this.response = response
        },
        async read(){
            const tokens = localStorage.getItem('tokens');
            const response = await apiRequest.get(`/api/sections/read/${tokens}`);
            this.sections = response;
        },
        async update(data){
            const formData = new FormData();
            formData.append('id',data.id);
            formData.append('section', data.section);
            formData.append('yearlevel', data.yearlevel);
            formData.append('specialization', data.specialization);

            const response = await apiRequest.post('/api/sections/update', formData);
            this.response = response;
        },
        async delete(id){
            const response = await apiRequest.get(`/api/sections/delete/${id}`);
            this.response  = response;
        }
    },
    getters: {
        getSections(state){
            return state.sections;
        },
        getResponse(state){
            return state.response;
        }
    }
});