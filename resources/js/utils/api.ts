/* eslint-disable @typescript-eslint/no-explicit-any */
import axios, { type AxiosRequestConfig } from 'axios'

function url(path: string) {
    return (import.meta.env.VITE_HOST_URL ?? '') + '/api' + path
}

export function get(uri: string, config?: AxiosRequestConfig): Promise<any> {
    return axios.get(url(uri), config)
}

export function post(
    uri: string,
    data?: any,
    config?: AxiosRequestConfig
): Promise<any> {
    return axios.post(url(uri), data, config)
}

export function put(
    uri: string,
    data?: any,
    config?: AxiosRequestConfig
): Promise<any> {
    return axios.put(url(uri), data, config)
}

export function del(uri: string, config?: AxiosRequestConfig): Promise<any> {
    return axios.delete(url(uri), config)
}

export function request(
    method: string,
    uri: string,
    data?: object,
    config?: AxiosRequestConfig
): Promise<any> {
    return axios.request({ method, url: url(uri), data, ...config })
}
