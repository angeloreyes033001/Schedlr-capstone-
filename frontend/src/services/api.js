import axios from "axios";
import domain from "./domain";


const BASE_URL = domain;

class apiRequest {

    static async post(url,formData) {
        try{
            const response = await axios.post(BASE_URL+url,formData);
            if(response.status ==200){
                return response.data;
            }
        }
        catch(error){
            console.error(error)
            throw error
        }
    }

    static async get(url){
        try{
            const response = await axios.get(BASE_URL+url);
            if(response.status ==200){
                return response.data;
            }
        }
        catch(error){
            console.error(error)
            throw error
        } 
    }

}

export default apiRequest;