import { defineStore } from "pinia";
import apiRequest from "./api";

export const departmentStore = defineStore('departments', {
    state: () => ({
        departments: null,
        response: null,
    }),
    actions: {
        async read(){
            const response = await apiRequest.get('/api/departments/read');
            this.departments = response;
        },

        async create(department){
            const formData = new FormData();
            formData.append("department",department)
            const response = await apiRequest.post(`/api/departments/create`,formData);
            this.response = response;
        },
    },
    getters:{
        getDepartments(state){
            return state.departments;
        },

        getResponse(state){
            return state.response;
        }
    }
})