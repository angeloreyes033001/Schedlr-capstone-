import { defineStore } from "pinia";
import apiRequest from "./api";

export const yearlevelStore = defineStore(('yearlevels'),{
    state: (()=>({
        response: null,
        yearlevels: null,
    })),
    actions: {
        async read(){
            const tokens = localStorage.getItem('tokens');
            const response = await apiRequest.get(`/api/yearlevels/read/${tokens}`);
            this.yearlevels = response;
        },

        async create(year){
            const formData = new FormData();
            formData.append('yearlevel', year);
            formData.append('tokens',localStorage.getItem('tokens'));

            const response = await apiRequest.post('/api/yearlevels/create',formData);
            this.response =  response;
        },

        async update(data){
            const formData = new  FormData();
            formData.append('id', data.id );
            formData.append('yearlevel', data.year);
            formData.append('tokens', localStorage.getItem('tokens'));

            const response = await apiRequest.post('/api/yearlevels/update',formData);
            this.response = response;

        },

        async delete(id){
            const response = await apiRequest.get(`/api/yearlevels/delete/${id}`);
            this.response = response;
        }

    },
    getters: {
        getYearLevel(state){
            return state.yearlevels;
        },
        getResponse(state){
            return state.response;
        }
    }
})