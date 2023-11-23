import { defineStore } from "pinia";
import apiRequest from "./api";

export const loadStore = defineStore('loads', {
    state: (()=>({
        loads: '',
        response: '',
    })),
    actions: {
        async create(data){
            const formData = new FormData();
            formData.append('professor', data.professor);
            formData.append('subject',data.subject);
            formData.append('tokens', localStorage.getItem('tokens'));

            const response = await apiRequest.post('/api/loads/create', formData);
            this.response = response;
        },
        async read(id){
            const response = await apiRequest.get(`/api/loads/read/${id}`);
            this.loads = response;
        },
        async delete(id){
            const response = await apiRequest.get(`/api/loads/delete/${id}`);
            this.response = response;
        },
        async show_all(semester){
            const formData = new FormData();
            formData.append('semester',semester);
            formData.append('tokens',localStorage.getItem('tokens'));

            const response = await apiRequest.post(`/api/loads/all-load`,formData);
            this.response = response;
            // console.log(semester)
        }
    },
    getters: {
        getLoad(state){
            return state.loads;
        },

        getResponse(state){
            return state.response;
        }
    }
})