import axios from 'axios';

const bearer_token = localStorage.getItem('auth') || ''

const API = axios.create({
  baseURL: 'http://127.0.0.1:8000/api'
  // baseURL: 'https://be.tesgrant.info/api'
});

API.defaults.headers.common['Authorization'] = `Bearer ${bearer_token}`

export default API;