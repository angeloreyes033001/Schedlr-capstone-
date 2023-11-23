import { defineStore } from "pinia";
import apiRequest from "./api";

export const subjectStore = defineStore('subjects', {
    state: (()=>({
        subjects: null,
        response: null,
    })),
    actions: {
        async create(data){
            const formData = new FormData();
            formData.append('code', data.code);
            formData.append('subject', data.subject );
            formData.append('semester', data.semester);
            formData.append('lecture', data.lecture);
            formData.append('laboratory', data.laboratory);
            formData.append('specialization',data.specialization);
            formData.append('year', data.year);
            formData.append('tokens',localStorage.getItem('tokens'));
            
            const response = await apiRequest.post('/api/subjects/create',formData);
            this.response = response;
        },

        async read(){
            const tokens = localStorage.getItem('tokens');
            const response = await apiRequest.get(`/api/subjects/read/${tokens}`);
            this.subjects = response;
        },
        async update(data){
            const formData = new FormData();
            formData.append('code', data.code);
            formData.append('subject', data.subject );
            formData.append('semester', data.semester);
            formData.append('lecture', data.lecture);
            formData.append('laboratory', data.laboratory);
            formData.append('specialization',data.specialization);
            formData.append('year', data.year);
            const response = await apiRequest.post('/api/subjects/update', formData);
            this.response = response;
        },
        async delete(code){
            const response = await apiRequest.get(`/api/subjects/delete/${code}`);
            this.response = response;
        }

    },
    getters:{
        getSubjects(state){
            return state.subjects;
        },
        getResponse(state){
            return state.response;
        }
    }
})