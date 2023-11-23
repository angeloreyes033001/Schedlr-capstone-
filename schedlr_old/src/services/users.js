import { defineStore } from "pinia";
import apiRequest from "./api";

export const userStore = defineStore('users', {
    state: () => ({
        collection: null,
    }),
    actions: {
        async onLogin(users) {
            const formData = new FormData();
            formData.append("email", users.email)
            formData.append("password", users.password);

            const response = await apiRequest.post(`/api/users/login`,formData);
            if(response.status) {
                localStorage.setItem("tokens", response.tokens);
            }
            this.collection = response;
        },
        async fetchUsers(){
            const department = localStorage.getItem('tokens');
            const response = await apiRequest.get(`/api/users/read/${department}`); 
            this.collection = response;
        },
        async changeStatus(data){
            const formData = new FormData();
            formData.append('id',data.id);
            formData.append('status', data.status);
            const response = await apiRequest.post('/api/users/changeStatus',formData); 
            this.collection = response;
        },
        async createAccountAdmin(data){
            const formData = new FormData();
            formData.append("email",data.email);
            formData.append("fullname",data.fullname);
            formData.append("tokens",  localStorage.getItem('tokens'));

            const response = await apiRequest.post('/api/users/create-accounts',formData);
            this.collection = response;
        },
        async createAccountDean(data){
            
            const formData = new FormData();
            formData.append("email",data.email);
            formData.append("fullname",data.fullname);
            formData.append("department", data.department);
            formData.append("tokens", localStorage.getItem('tokens'));
            formData.append("password", data.password);

            const response = await apiRequest.post('/api/users/create-deans',formData);
            this.collection = response;
            console.log(response);
        },
        async changeEmail(data){
            const formData = new FormData();
            formData.append('email',data.email);
            formData.append('password',data.password);
            formData.append('tokens',localStorage.getItem('tokens'));

            const response = await apiRequest.post('/api/users/update-email',formData);
            this.collection = response;
        },

        async changePassword(data){
            const formData = new FormData();
            formData.append('password',data.current_password);
            formData.append('new_password',data.new_password);
            formData.append('tokens', localStorage.getItem('tokens'));
            const response = await apiRequest.post('/api/users/update-password',formData);
            this.collection = response;
        }

    },
    getters: {       
        getState(state){
            return state.collection;
        },
    }
  });