import { useCallback, useRef } from 'react';
import type { ApiResponse } from '@/types';

type HttpMethod = 'GET' | 'POST' | 'PUT' | 'PATCH' | 'DELETE';

type UseApiOptions = {
    onSuccess?: (data: unknown) => void;
    onError?: (message: string, status: number) => void;
};

let csrfInitialised = false;

async function initCsrf(): Promise<void> {
    if (csrfInitialised) return;
    await fetch('/sanctum/csrf-cookie', { credentials: 'include' });
    csrfInitialised = true;
}

function getCsrfToken(): string {
    return (
        document.cookie
            .split('; ')
            .find((c) => c.startsWith('XSRF-TOKEN='))
            ?.split('=')[1]
            .replace(/%3D/g, '=') ?? ''
    );
}

/**
 * Thin typed wrapper over the Fetch API for Task Orbit's REST API.
 *
 * - Automatically initialises the Sanctum CSRF cookie before mutating requests.
 * - Expects `{ status, message, data }` envelopes from the server.
 * - Returns a stable `request` function (memoised with useCallback).
 */
export function useApi(options: UseApiOptions = {}) {
    const optionsRef = useRef(options);
    optionsRef.current = options;

    const request = useCallback(
        async <T = unknown>(
            method: HttpMethod,
            url: string,
            body?: Record<string, unknown> | FormData,
        ): Promise<T | null> => {
            if (method !== 'GET') {
                await initCsrf();
            }

            const isFormData = body instanceof FormData;

            const headers: Record<string, string> = {
                Accept: 'application/json',
                'X-XSRF-TOKEN': getCsrfToken(),
            };

            if (!isFormData) {
                headers['Content-Type'] = 'application/json';
            }

            const res = await fetch(`/api${url}`, {
                method,
                credentials: 'include',
                headers,
                body: body
                    ? isFormData
                        ? body
                        : JSON.stringify(body)
                    : undefined,
            });

            const json = (await res.json()) as ApiResponse<T>;

            if (!res.ok || json.status === 'error') {
                optionsRef.current.onError?.(json.message, res.status);
                return null;
            }

            optionsRef.current.onSuccess?.(json.data);
            return json.data;
        },
        [],
    );

    const get = useCallback(
        <T = unknown>(url: string) => request<T>('GET', url),
        [request],
    );

    const post = useCallback(
        <T = unknown>(url: string, body: Record<string, unknown> | FormData) =>
            request<T>('POST', url, body),
        [request],
    );

    const patch = useCallback(
        <T = unknown>(url: string, body: Record<string, unknown>) =>
            request<T>('PATCH', url, body),
        [request],
    );

    const del = useCallback(
        <T = unknown>(url: string) => request<T>('DELETE', url),
        [request],
    );

    return { get, post, patch, del, request };
}
