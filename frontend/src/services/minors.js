import { defineStore } from "pinia";
import apiRequest from "./api";

export const minorStore = defineStore('minor', {
    state: (()=>({
        response: '',
    })),
    actions: {
        async getProfessor(){
            const tokens = localStorage.getItem('tokens');
            const response = await apiRequest.get(`/api/minors/read_professor/${tokens}`);
            this.response = response;
        },
        async getClassrooms(data){
            const formData = new FormData();
            formData.append('type',data.type);
            formData.append('department',data.department);
            formData.append('tokens',localStorage.getItem('tokens'));
            const response = await apiRequest.post("/api/minors/read_classroom",formData);
            this.response = response;
        },
        async getSections(data){
            const formData = new FormData();
            formData.append('subject',data.subject);
            formData.append('department',data.department);//other department
            const response = await apiRequest.post("/api/minors/read_section",formData);
            this.response = response;
        }
    },
    getters: {
        getResponse(state){
            return state.response;
        }
    }
})