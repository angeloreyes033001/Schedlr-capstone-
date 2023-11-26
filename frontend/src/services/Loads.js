import { defineStore } from "pinia";
import apiRequest from "./api";

export const loadStore = defineStore('loads', {
    state: (()=>({
        loads: '',
        response: '',
    })),
    actions: {
        async read_load_professor(){
            const tokens = localStorage.getItem('tokens');
            const response = await apiRequest.get(`/api/loads/read_load_professor/${tokens}`);
            this.response = response;
        },
        async delete_load(id){
            const response = await apiRequest.get(`/api/loads/delete_load/${id}`);
            this.response = response;
        },

        async read_all_load(data){
            const formData = new FormData();
            formData.append('year',data.year);
            formData.append('semester',data.semester);
            formData.append('professor',data.professor);
            formData.append('tokens',localStorage.getItem('tokens'));

            const response = await apiRequest.post('/api/loads/read_all_load',formData);
            this.response = response;
        },

        async create_load(data){
            const formData = new FormData();
            formData.append('year',data.year);
            formData.append('semester',data.semester);
            formData.append('professor',data.professor);
            formData.append('subject',data.subject);
            formData.append('hour',data.hour);
            formData.append('tokens',localStorage.getItem('tokens'));
            const response = await apiRequest.post('/api/loads/create_load',formData);
            this.response = response;
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