import { defineStore } from "pinia";
import apiRequest from "./api";

export const minorStore = defineStore(('minor'),{
    state: (()=>({
        response: null,
        schedule: null,
    })),
    actions: {
        async departments(){
            const department = localStorage.getItem('tokens');
            const response = await apiRequest.get(`/api/minor/department/${department}`)
            this.response = response;
        },
        async classroomSchedule(data){
            const {department,semester} = data;
            const formData = new FormData();
            formData.append('department',department);
            formData.append('semester',semester);
            formData.append('tokens', localStorage.getItem('tokens'))
            const response = await apiRequest.post('/api/minor/classroom',formData);
            this.schedule = response;
            console.log(response)
        },
        async sectionSchedule(data){
            const {department,semester} = data;
            const formData = new FormData();
            formData.append('department',department);
            formData.append('semester',semester);
            formData.append('tokens', localStorage.getItem('tokens'))
            const response = await apiRequest.post('/api/minor/section',formData);
            this.schedule = response;
        },
        async yearlevel(department){
            const response = await apiRequest.get(`/api/minor/yearlevel/${department}`);
            this.response = response;
        },
        async showsubject(){
            const tokens = localStorage.getItem('tokens')
            const response = await apiRequest.get(`/api/minor/showsubject/${tokens}`)
            this.response =  response;
            console.log(response)
        },
        async showclassroom(department){
            const formData = new FormData();
            formData.append('tokens', localStorage.getItem('tokens'))
            formData.append('department', department)
            const response = await apiRequest.post(`/api/minor/showclassroom`,formData)
            this.response =  response;
            console.log(response)
        },
        async showsection(department){
            const formData = new FormData();
            const response = await apiRequest.get(`/api/minor/showsection/${department}`,formData)
            this.response =  response;
            console.log(response)
        },
        async createSchedule(data){
            const {department,professor,semester,subject,classroom,section,day,start,end} = data;
            const formData = new FormData();
            formData.append("department",department);
            formData.append("professor",professor);
            formData.append("semester",semester);
            formData.append("subject",subject);
            formData.append("classroom",classroom);
            formData.append("section",section);
            formData.append("day",day);
            formData.append("start",start);
            formData.append("end",end);
            
            const response = await apiRequest.post('/api/minor/create-schedule',formData);
            this.response = response;
        },
        async showDelete(data){
            const {professor, semester} = data;
            const formData = new FormData();
            formData.append('professor',professor);
            formData.append('semester',semester);

            const response = await apiRequest.post('/api/minor/showDelete',formData);
            this.response = response;
        },
        async delete(id){
            const response = await apiRequest.get(`/api/minor/delete/${id}`);
            this.response = response;
        }

    },
    getters: {
        getSchedule(state){
            return state.schedule;
        },
        getResponse(state){
            return state.response;
        }
    }
})