import axios, { AxiosInstance, AxiosResponse } from 'axios';
import { ref } from 'vue';

export interface ApiResponse<T = any> {
    data: T | null;
    loading: boolean;
    error: string | null;
    get: <R = any>(url: string) => Promise<R>;
    post: <R = any>(url: string, payload: any) => Promise<R>;
    put: <R = any>(url: string, payload: any) => Promise<R>;
    del: <R = any>(url: string) => Promise<R>;
    patch: <R = any>(url: string, payload: any) => Promise<R>;
}

type MethodsType = 'GET' | 'POST' | 'PUT' | 'DELETE' | 'PATCH';

enum Method {
    GET = 'GET',
    POST = 'POST',
    PUT = 'PUT',
    DELETE = 'DELETE',
    PATCH = 'PATCH',
}

const useApi = (): ApiResponse => {
    const data = ref<any | null>(null);
    const loading = ref<boolean>(false);
    const error = ref<string | null>(null);

    const api: AxiosInstance = axios.create({
        baseURL: '/',
        headers: {
            Accept: 'application/json',
            'Content-Type': 'application/json',
        },
    });

    const request = async <T = any>(url: string, method: MethodsType, payload?: any): Promise<T> => {
        loading.value = true;
        error.value = null;
        data.value = null;

        try {
            const response: AxiosResponse<T> = await api.request({
                url,
                method,
                data: payload,
            });
            data.value = response.data;
            return response.data;
        } catch (err: any) {
            error.value = err.response?.data?.message || err.message || 'An unknown error occurred';
            throw err;
        } finally {
            loading.value = false;
        }
    };

    const get = <T = any>(url: string): Promise<T> => request<T>(url, Method.GET);
    const post = <T = any>(url: string, payload: any): Promise<T> => request<T>(url, Method.POST, payload);
    const put = <T = any>(url: string, payload: any): Promise<T> => request<T>(url, Method.PUT, payload);
    const del = <T = any>(url: string): Promise<T> => request<T>(url, Method.DELETE);
    const patch = <T = any>(url: string, payload: any): Promise<T> => request<T>(url, Method.PATCH, payload);

    return {
        data,
        loading: loading.value,
        error: error.value,
        get,
        post,
        put,
        del,
        patch,
    };
};

export default useApi;
