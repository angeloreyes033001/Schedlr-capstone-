import { defineStore } from "pinia";
import apiRequest from "./api";

export const specializationStore = defineStore('specializations',{
    state:() => ({
        specialization: null,
        response: null,
    }),

    actions:{

        async create(specialization){
            const formData = new FormData();
            formData.append('specialization',specialization);
            formData.append('tokens',localStorage.getItem('tokens'));

            const response = await apiRequest.post('/api/specialization/create',formData);
            this.response = response;

        },

        async read(){
            const tokens = localStorage.getItem('tokens');
            const response = await apiRequest.get(`/api/specialization/read/${tokens}`);
            this.specialization = response;
        },

        async update(data){
            const formData = new FormData();
            formData.append('tokens',localStorage.getItem('tokens'));
            formData.append('id',data.id);
            formData.append('specialization',data.specialization);

            const response = await apiRequest.post('/api/specialization/update',formData);
            this.response = response;

        },

        async delete(id){
            const formData = new FormData();
            formData.append('id',id);
            formData.append('tokens',localStorage.getItem('tokens'));

            const response = await apiRequest.post('/api/specialization/delete',formData);
            this.response = response;
        },

        async autocreatecommon(){
            const tokens = localStorage.getItem('tokens');
            await apiRequest.get(`/api/specialization/autocreatecommon/${tokens}`);
            //no response this only for create "common" choices
        }
    },

    getters:{
        getSpecialization(state){
            return state.specialization;
        },
        getResponse(state){
            return state.response;
        }
    }


})