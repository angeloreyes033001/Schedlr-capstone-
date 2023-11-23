import { defineStore } from "pinia";
import apiRequest from "./api";

export const rankStore = defineStore('ranks',{
   state: (()=>({
    ranks: null,
    response: null,
   })),
   actions:{

        async create(data){
            const formData = new FormData();
            formData.append('rank',data.rank);
            formData.append('hour',data.hour);
            formData.append('tokens',localStorage.getItem('tokens'));
            const response = await apiRequest.post('/api/ranks/create',formData);
            this.response = response; 
        },

        async read(){
            const tokens = localStorage.getItem('tokens');
            const response = await apiRequest.get(`/api/ranks/read/${tokens}`);
            this.ranks = response;  
        },
        
        async update(data){
            const formData = new FormData();
            formData.append('id',data.id);
            formData.append('rank',data.rank);
            formData.append('hour', data.hour);
            const response = await apiRequest.post('/api/ranks/update',formData);
            this.response = response;
        },

        async delete(id){
            const response = await apiRequest.get(`/api/ranks/delete/${id}`);
            this.response = response;
        }

   },
   getters:{
        getRanks(state){
            return state.ranks;
        },
        getResponse(state){
            return state.response;
        }
   } 
});