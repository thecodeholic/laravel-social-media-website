import axios from 'axios'

const instance = axios.create();
// Add a request interceptor
instance.interceptors.request.use(function (config) {
    // TODO
    return config;
});

export default instance
