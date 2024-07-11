import axios from 'axios';

export interface IPost {
    id: number;
    user: {
        id: number,
        username: string,
        email: string,
    };
    title: string;
    body: string;
}

const baseUrl = "http://localhost:8080/api/posts"

const PostModel = {
    getAllPosts: () => {
        return axios.get(baseUrl)
            .then((result) => {
                return result;
            }).catch((err) => {
                return err;
            });
    },

    getPostById: (id: number) => {
        return axios.get(`${baseUrl}/${id}`)
            .then((result) => {
                return result;
            }).catch((err) => {
                return err;
            });
    },

    createPost: (value: object) => {
        return axios.post(`${baseUrl}`, value)
            .then((result) => {
                return result;
            }).catch((err) => {
                return err;
            });
    },

    deletePostById: (id: number) => {
        return axios.delete(`${baseUrl}/${id}`)
            .then((result) => {
                return result;
            }).catch((err) => {
                return err;
            });
    },
}

export default PostModel;