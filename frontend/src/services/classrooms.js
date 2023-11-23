import { defineStore } from "pinia";
import apiRequest from "./api";

export const classroomStore = defineStore('classrooms',{
    state: (()=>({
        response: '',
        classroom: '',
    })),
    actions: {
        async create(data){
            const formData = new FormData();
            formData.append('room',data.room);
            formData.append('type',data.type);
            formData.append('year', data.year);
            formData.append('overwrite',data.overwrite);
            formData.append('tokens', localStorage.getItem('tokens'));

            const response = await apiRequest.post('/api/classrooms/create', formData);
            this.response = response;
        },
        async read(){
            const tokens = localStorage.getItem('tokens');
            const response = await apiRequest.get(`/api/classrooms/read/${tokens}`);
            this.classroom = response
        },
        async update(data){
            const formData = new FormData();
            formData.append('id', data.id);
            formData.append('room', data.room);
            formData.append('type',data.type);
            formData.append('year',data.year);
            formData.append('overwrite',data.overwrite)
            const response = await apiRequest.post('/api/classrooms/update',formData);
            this.response = response;
        },
        async delete(id){
            const response = await apiRequest.get(`/api/classrooms/delete/${id}`);
            this.response = response;
        }
    },
    getters:{
        getClassrooms(state){
            return state.classroom;
        },
        getResponse(state){
            return state.response;
        }
    }
})