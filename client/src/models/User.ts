import axios from 'axios';

export interface IUser {
    id: number;
    username: string,
    email: string,
}

const baseUrl = "http://localhost:8080/api/users"

const UserModel = {
    getAllUsers: () => {
        return axios.get(baseUrl)
            .then((result) => {
                return result;
            }).catch((err) => {
                return err;
            });
    },
}

export default UserModel;