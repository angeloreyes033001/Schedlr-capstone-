import { defineStore } from "pinia";
import apiRequest from "./api";

export const sectionStore = defineStore('sections',{
    state: (()=>({
        response: '',
        sections: '',
        slot: '',
    })),
    actions:{

        async auto_generated_slot(){
            const tokens = localStorage.getItem('tokens');
            await apiRequest.get(`/api/sections/auto_generated_slot/${tokens}`);
        },

        async read_slot(data){
            const formData = new FormData();
            formData.append('year', data.year),
            formData.append('semester',data.semester);
            
            const response = await apiRequest.post('/api/sections/read_slot',formData);
            this.slot = response;
        },

        async update_slot(data){
            const formData = new FormData();
            formData.append('slot',data.slot);
            formData.append('year', data.year),
            formData.append('semester',data.semester);
            
            const response = await apiRequest.post('/api/sections/update_slot',formData);
            this.response = response;
        },

        async create_section(data){
            const formData = new FormData();
            formData.append("year",data.year);
            formData.append("semester",data.semester);
            formData.append("section",data.section);
            formData.append("specialization",data.specialization);

            const response = await apiRequest.post('/api/sections/create_section',formData);
            this.response = response;
        },

        async read_section(data){
            const formData = new FormData();
            formData.append('year',data.year);
            formData.append('semester',data.semester);

            const response = await apiRequest.post('/api/sections/read_section',formData);
            this.sections = response;
        },

        async update_section(data){
            const formData = new FormData();
            formData.append("id",data.id);
            formData.append("section",data.section);
            formData.append("semester",data.semester);
            formData.append("specialization",data.specialization);
            const response = await apiRequest.post('/api/sections/update_section',formData);
            this.response = response;
        },

        async delete_section(section){
            const response = await apiRequest.get(`/api/sections/delete_section/${section}`);
            this.response = response
        }

        // async create(data){
        //     const formData = new FormData();
        //     formData.append('section', data.sectionName);
        //     formData.append('yearlevel',data.year);
        //     formData.append('specialization',data.specialization);
        //     formData.append('tokens', localStorage.getItem('tokens'));

        //     const response = await apiRequest.post('/api/sections/create',formData);
        //     this.response = response
        // },

        // async read(){
        //     const tokens = localStorage.getItem('tokens');
        //     const response = await apiRequest.get(`/api/sections/read/${tokens}`);
        //     this.sections = response;
        // },

        // async update(data){
        //     const formData = new FormData();
        //     formData.append('id',data.section);
        //     formData.append('section', data.sectionName);
        //     formData.append('yearlevel', data.year);
        //     formData.append('specialization', data.specialization);

        //     const response = await apiRequest.post('/api/sections/update', formData);
        //     this.response = response;
        // },

        // async delete(id){
        //     const response = await apiRequest.get(`/api/sections/delete/${id}`);
        //     this.response  = response;
        // }
    },
    getters: {
        getSections(state){
            return state.sections;
        },
        getSlot(state){
            return state.slot;
        },
        getResponse(state){
            return state.response;
        }
    }
});